<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Unified Dashboard for Owner, Admin, and Staff
     * Contains Overview, Financial Report, Master Data, and Transactions all in one place.
     */
    public function index(Request $request)
    {
        // 1. Basic Counts
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalCustomers = Customer::count();
        $totalUsers = User::count();

        // 2. Financial Overview
        // Total Sales (Revenue)
        $totalRevenue = Sale::where('status', 'completed')->sum('total');
        $todayRevenue = Sale::whereDate('sold_at', today())->where('status', 'completed')->sum('total');
        $monthlyRevenue = Sale::whereMonth('sold_at', now()->month)->whereYear('sold_at', now()->year)->where('status', 'completed')->sum('total');

        // Total Purchases (Expense)
        $totalExpense = Purchase::where('status', 'completed')->sum('total');
        $todayExpense = Purchase::whereDate('purchased_at', today())->where('status', 'completed')->sum('total');
        $monthlyExpense = Purchase::whereMonth('purchased_at', now()->month)->whereYear('purchased_at', now()->year)->where('status', 'completed')->sum('total');

        // Profit estimation = Revenue - Cost of Goods Sold (or Revenue - Purchases)
        $totalProfit = $totalRevenue - $totalExpense;
        $monthlyProfit = $monthlyRevenue - $monthlyExpense;

        // Inventory Value
        $inventoryAssetValue = Product::select(DB::raw('SUM(stock * purchase_price) as total_asset'))->value('total_asset') ?? 0;
        $inventoryPotentialValue = Product::select(DB::raw('SUM(stock * selling_price) as total_potential'))->value('total_potential') ?? 0;

        // Today & Monthly Transaction Counts
        $todaySalesCount = Sale::whereDate('sold_at', today())->where('status', 'completed')->count();
        $todayPurchasesCount = Purchase::whereDate('purchased_at', today())->where('status', 'completed')->count();

        // 3. Lists for tabs
        // Low Stock Products
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->get();

        // All Products for quick table in dashboard
        $productsList = Product::with('category')->latest()->take(10)->get();

        // Recent Sales
        $recentSales = Sale::with(['customer', 'user', 'saleItems.product'])
            ->latest('sold_at')
            ->take(10)
            ->get();

        // Recent Purchases
        $recentPurchases = Purchase::with(['supplier', 'user', 'purchaseItems.product'])
            ->latest('purchased_at')
            ->take(10)
            ->get();

        // Filter date for financial report if provided
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Financial Report Period
        $periodSales = Sale::with(['customer', 'user'])
            ->where('status', 'completed')
            ->whereDate('sold_at', '>=', $startDate)
            ->whereDate('sold_at', '<=', $endDate)
            ->latest('sold_at')
            ->get();

        $periodPurchases = Purchase::with(['supplier', 'user'])
            ->where('status', 'completed')
            ->whereDate('purchased_at', '>=', $startDate)
            ->whereDate('purchased_at', '<=', $endDate)
            ->latest('purchased_at')
            ->get();

        $periodRevenue = $periodSales->sum('total');
        $periodExpense = $periodPurchases->sum('total');
        $periodProfit = $periodRevenue - $periodExpense;

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalCustomers',
            'totalUsers',
            'totalRevenue',
            'todayRevenue',
            'monthlyRevenue',
            'totalExpense',
            'todayExpense',
            'monthlyExpense',
            'totalProfit',
            'monthlyProfit',
            'inventoryAssetValue',
            'inventoryPotentialValue',
            'todaySalesCount',
            'todayPurchasesCount',
            'lowStockProducts',
            'productsList',
            'recentSales',
            'recentPurchases',
            'startDate',
            'endDate',
            'periodSales',
            'periodPurchases',
            'periodRevenue',
            'periodExpense',
            'periodProfit'
        ));
    }
}
