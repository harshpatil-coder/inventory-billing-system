<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'low_stock_products' => Product::whereRaw('current_stock <= minimum_stock')->count(),
            'total_sales_today' => Sale::whereDate('sale_date', Carbon::today())->sum('total_amount'),
            'total_sales_month' => Sale::whereMonth('sale_date', Carbon::now()->month)->sum('total_amount'),
            'pending_purchase_orders' => PurchaseOrder::where('status', 'pending')->count(),
        ];

        $lowStockProducts = Product::with(['category', 'supplier'])
            ->whereRaw('current_stock <= minimum_stock')
            ->take(10)
            ->get();

        $recentMovements = StockMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recentSales = Sale::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'lowStockProducts', 'recentMovements', 'recentSales'));
    }
}