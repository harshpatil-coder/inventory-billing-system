<?php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('user');

        // Search by customer name or invoice number
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('invoice_number', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('sale_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('sale_date', '<=', $request->date_to);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->where('current_stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'sale_date' => 'required|date',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,partial',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request) {
            // Create sale
            $sale = Sale::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'sale_date' => $request->sale_date,
                'tax_amount' => $request->tax_amount ?? 0,
                'discount_amount' => $request->discount_amount ?? 0,
                'payment_status' => $request->payment_status,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
                'subtotal' => 0,
                'total_amount' => 0
            ]);

            // Create sale items and update stock
            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['id']);
                
                // Check stock availability
                if ($product->current_stock < $productData['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $productData['unit_price'],
                    'total_price' => $productData['quantity'] * $productData['unit_price']
                ]);

                // Update stock
                StockMovement::createMovement(
                    $product->id,
                    'out',
                    $productData['quantity'],
                    'sale',
                    $sale->id,
                    "Sale #{$sale->sale_number}"
                );
            }

            // Calculate totals
            $sale->calculateTotal();
        });

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'user']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('items.product');
        $products = Product::where('is_active', true)->get();
        return view('sales.edit', compact('sale', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_status' => 'required|in:pending,paid,partial',
            'notes' => 'nullable|string'
        ]);

        $sale->update($request->only([
            'customer_name',
            'customer_email',
            'customer_phone',
            'payment_status',
            'notes'
        ]));

        return redirect()->route('sales.show', $sale)->with('success', 'Sale updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            // Restore stock for each item
            foreach ($sale->items as $item) {
                StockMovement::createMovement(
                    $item->product_id,
                    'in',
                    $item->quantity,
                    'sale_reversal',
                    $sale->id,
                    "Sale #{$sale->sale_number} reversal"
                );
            }

            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Sale deleted and stock restored.');
    }
}