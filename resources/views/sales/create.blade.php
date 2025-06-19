@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Create New Sale</h2>

                <form method="POST" action="{{ route('sales.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <!-- Sale Items -->
                        <div id="sale-items">
                            <div class="mb-4 sale-item">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                                        <select name="items[0][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <option value="">Select a product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->current_stock }}">
                                                    {{ $product->name }} (Stock: {{ $product->current_stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                        <input type="number" name="items[0][quantity]" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                                        <input type="number" step="0.01" name="items[0][price]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-item" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Another Item
                        </button>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">Total Amount</h3>
                                <p class="text-2xl font-bold text-gray-900" id="total-amount">$0.00</p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Create Sale
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemCount = 1;

    document.getElementById('add-item').addEventListener('click', function() {
        const itemTemplate = document.querySelector('.sale-item').cloneNode(true);
        const inputs = itemTemplate.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.name = input.name.replace('[0]', `[${itemCount}]`);
            input.value = '';
        });
        document.getElementById('sale-items').appendChild(itemTemplate);
        itemCount++;
    });

    document.addEventListener('change', function(e) {
        if (e.target.matches('select[name*="product_id"]')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const priceInput = e.target.closest('.sale-item').querySelector('input[name*="price"]');
            priceInput.value = selectedOption.dataset.price || '';
        }
        updateTotal();
    });

    document.addEventListener('input', function(e) {
        if (e.target.matches('input[name*="quantity"], input[name*="price"]')) {
            updateTotal();
        }
    });

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.sale-item').forEach(item => {
            const quantity = parseFloat(item.querySelector('input[name*="quantity"]').value) || 0;
            const price = parseFloat(item.querySelector('input[name*="price"]').value) || 0;
            total += quantity * price;
        });
        document.getElementById('total-amount').textContent = `$${total.toFixed(2)}`;
    }
</script>
@endpush
@endsection
