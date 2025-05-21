{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">All Products</h1>
    <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add New Product</a>

    <table class="table-auto w-full mt-4 border">
        
        <thead class="bg-gray-100">
            <tr>
                <th>Name</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Unit</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr class="border-t">
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>₹{{ $product->price }}</td>
                <td>{{ $product->stock_qty }}</td>
                <td>{{ $product->unit }}</td>
                <td>{{ $product->category->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
