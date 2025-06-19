<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Key Stats Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <!-- Total Products -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Total Products</h3>
                                <p class="mt-1 text-3xl font-semibold text-blue-600">{{ $stats['total_products'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">Low stock items: {{ $stats['low_stock_products'] }}</p>
                            <a href="{{ route('products.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-900">View all →</a>
                        </div>
                    </div>
                </div>

                <!-- Today's Sales -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Today's Sales</h3>
                                <p class="mt-1 text-3xl font-semibold text-green-600">${{ number_format($stats['total_sales_today'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">Monthly: ${{ number_format($stats['total_sales_month'], 2) }}</p>
                            <a href="{{ route('sales.index') }}" class="text-sm font-medium text-green-600 hover:text-green-900">View all →</a>
                        </div>
                    </div>
                </div>

                <!-- Pending Purchase Orders -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Pending Orders</h3>
                                <p class="mt-1 text-3xl font-semibold text-yellow-600">{{ $stats['pending_purchase_orders'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <a href="{{ route('purchase-orders.index') }}" class="text-sm font-medium text-yellow-600 hover:text-yellow-900">View all orders →</a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('products.create') }}" class="flex items-center text-sm text-blue-600 hover:text-blue-900">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add New Product
                            </a>
                            <a href="{{ route('purchase-orders.create') }}" class="flex items-center text-sm text-blue-600 hover:text-blue-900">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                                New Purchase Order
                            </a>
                            <a href="{{ route('sales.create') }}" class="flex items-center text-sm text-blue-600 hover:text-blue-900">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Record Sale
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Products & Recent Activities -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Low Stock Products -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Low Stock Products</h3>
                        <div class="space-y-4">
                            @foreach($lowStockProducts as $product)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $product->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-red-600">Stock: {{ $product->current_stock }}</p>
                                        <p class="text-xs text-gray-500">Min: {{ $product->minimum_stock }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Stock Movements -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Stock Movements</h3>
                        <div class="space-y-4">
                            @foreach($recentMovements as $movement)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $movement->product->name }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $movement->type }} by {{ $movement->user->name }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium {{ $movement->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $movement->type == 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $movement->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Sales</h3>
                        <div class="space-y-4">
                            @foreach($recentSales as $sale)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Sale #{{ $sale->id }}</h4>
                                        <p class="text-sm text-gray-500">{{ $sale->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">${{ number_format($sale->total_amount, 2) }}</p>
                                        <p class="text-xs text-gray-500">{{ $sale->items_count }} items</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <a href="{{ route('sales.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-900">View all sales →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
