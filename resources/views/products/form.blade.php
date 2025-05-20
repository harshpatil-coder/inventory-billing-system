{{-- resources/views/products/form.blade.php --}}
<div class="mb-4">
    <label class="block text-gray-700">Product Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full border rounded p-2">
</div>

<div class="mb-4">
    <label class="block text-gray-700">Category</label>
    <select name="category_id" class="w-full border rounded p-2">
        <option value="">-- Select Category --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" 
                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="block text-gray-700">Price</label>
    <input type="number" name="price" step="0.01" value="{{ old('price', $product->price ?? '') }}" class="w-full border rounded p-2">
</div>

<div class="mb-4">
    <label class="block text-gray-700">Stock</label>
    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? '') }}" class="w-full border rounded p-2">
</div>
