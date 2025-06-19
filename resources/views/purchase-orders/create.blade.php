@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Create Purchase Order</h2>

                <form method="POST" action="{{ route('purchase-orders.store') }}">
                    @csrf
                    
                    <!-- Supplier Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                        <select name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Select a supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Order Items -->
                    <div id="order-items">
                        <div class="mb-4 order-item">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                                    <select name="items[0][product_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Select a product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->cost_price }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                    <input type="number" name="items[0][quantity]" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost</label>
                                    <input type="number" step="0.01" name="items[0][cost]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
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

                    <div class="border-t border-gray-200 mt-6 pt-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Total Amount</h3>
                            <p class="text-2xl font-bold text-gray-900" id="total-amount">$0.00</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Create Purchase Order
                        </button>
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
        const itemTemplate = document.querySelector('.order-item').cloneNode(true);
        const inputs = itemTemplate.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.name = input.name.replace('[0]', `[${itemCount}]`);
            input.value = '';
        });
        document.getElementById('order-items').appendChild(itemTemplate);
        itemCount++;
    });

    document.addEventListener('change', function(e) {
        if (e.target.matches('select[name*="product_id"]')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const costInput = e.target.closest('.order-item').querySelector('input[name*="cost"]');
            costInput.value = selectedOption.dataset.price || '';
        }
        updateTotal();
    });

    document.addEventListener('input', function(e) {
        if (e.target.matches('input[name*="quantity"], input[name*="cost"]')) {
            updateTotal();
        }
    });

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.order-item').forEach(item => {
            const quantity = parseFloat(item.querySelector('input[name*="quantity"]').value) || 0;
            const cost = parseFloat(item.querySelector('input[name*="cost"]').value) || 0;
            total += quantity * cost;
        });
        document.getElementById('total-amount').textContent = `$${total.toFixed(2)}`;
    }
</script>
@endpush
@endsection
