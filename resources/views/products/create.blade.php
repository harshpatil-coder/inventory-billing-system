@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Add New Product</h1>

<form method="POST" action="{{ route('products.store') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block font-semibold">Name:</label>
        <input name="name" class="w-full border p-2" required>
    </div>
    <div>
        <label class="block font-semibold">SKU:</label>
        <input name="sku" class="w-full border p-2" required>
    </div>
    <div>
        <label class="block font-semibold">Price:</label>
        <input name="price" type="number" step="0.01" class="w-full border p-2" required>
    </div>
    <div>
        <label class="block font-semibold">Stock Quantity:</label>
        <input name="stock_qty" type="number" class="w-full border p-2" required>
    </div>
    <div>
        <label class="block font-semibold">Unit:</label>
        <input name="unit" class="w-full border p-2" required>
    </div>
    <div>
        <label class="block font-semibold">Category:</label>
        <select name="category_id" class="w-full border p-2" required>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Save Product</button>
</form>
@endsection
