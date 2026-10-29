<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the main admin/staff/owner dashboard.
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalCustomers = Customer::count();

        // Today's sales
        $todaySales = Sale::whereDate('sold_at', today())
            ->where('status', 'completed')
            ->count();

        $todaySalesAmount = Sale::whereDate('sold_at', today())
            ->where('status', 'completed')
            ->sum('total');

        // Monthly sales
        $monthlySales = Sale::whereMonth('sold_at', now()->month)
            ->whereYear('sold_at', now()->year)
            ->where('status', 'completed')
            ->sum('total');

        // Total sales all time
        $totalSalesAllTime = Sale::where('status', 'completed')->sum('total');
        $totalSalesCount = Sale::where('status', 'completed')->count();

        // Low stock products (stock <= minimum_stock)
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->get();

        // Recent sales
        $recentSales = Sale::with(['customer', 'user', 'saleItems.product'])
            ->latest('sold_at')
            ->take(5)
            ->get();

        // Recent purchases (stock-in)
        $recentPurchases = Purchase::with(['supplier', 'user', 'purchaseItems.product'])
            ->latest('purchased_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalCustomers',
            'todaySales',
            'todaySalesAmount',
            'monthlySales',
            'totalSalesAllTime',
            'totalSalesCount',
            'lowStockProducts',
            'recentSales',
            'recentPurchases'
        ));
    }
}
