<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        return response()->json([
            'products' => $query->paginate($request->per_page ?? 15)
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'is_active' => 'boolean'
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product->load(['category', 'supplier'])
        ], 201);
    }

    public function show(Product $product)
    {
        return response()->json([
            'product' => $product->load(['category', 'supplier'])
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'is_active' => 'boolean'
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load(['category', 'supplier'])
        ]);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    public function adjustStock(Request $request, Product $product)
    {
        $this->authorize('adjustStock', $product);
        
        $validated = $request->validate([
            'adjustment' => 'required|integer',
            'reason' => 'required|string|max:255'
        ]);

        $movement = StockMovement::createMovement(
            $product->id,
            $validated['adjustment'] > 0 ? 'in' : 'out',
            abs($validated['adjustment']),
            'manual',
            null,
            $validated['reason']
        );

        return response()->json([
            'message' => 'Stock adjusted successfully',
            'product' => $product->fresh(),
            'movement' => $movement
        ]);
    }

    public function lowStock()
    {
        $products = Product::whereRaw('current_stock <= minimum_stock')
            ->with(['category', 'supplier'])
            ->get();

        return response()->json([
            'products' => $products
        ]);
    }
}
