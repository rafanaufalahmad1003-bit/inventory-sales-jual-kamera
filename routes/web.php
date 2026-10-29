<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to catalog or login
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isPelanggan()
            ? redirect()->route('catalog.index')
            : redirect()->route('dashboard');
    }
    return redirect()->route('catalog.index');
});

// =========================================================================
// 1. PUBLIC & CUSTOMER CATALOG / ORDERING
// =========================================================================
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/catalog/{product}/order', [CatalogController::class, 'order'])->name('catalog.order');
Route::post('/catalog/{product}/order', [CatalogController::class, 'processOrder'])->name('catalog.processOrder');
Route::get('/invoice/{sale}', [CatalogController::class, 'invoice'])->name('catalog.invoice');

// Customer's order history
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [CatalogController::class, 'myOrders'])->name('catalog.myOrders');
});

// =========================================================================
// 2. AUTHENTICATION ROUTES
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// =========================================================================
// 3. INTERNAL MANAGEMENT (Owner, Admin, Staff)
// =========================================================================
Route::middleware(['auth', 'internal'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---------------------------------------------------------------------
    // PRODUCTS:
    // Staff & Admin: View only
    // Owner: Full control (create, edit, delete)
    // ---------------------------------------------------------------------
    Route::middleware('owner')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // ---------------------------------------------------------------------
    // SUPPLIERS:
    // Staff & Admin: View only
    // Owner: Full control (create, edit, delete)
    // ---------------------------------------------------------------------
    Route::middleware('owner')->group(function () {
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');

    // ---------------------------------------------------------------------
    // CATEGORIES:
    // Viewable by all internal staff; modification only by Owner
    // ---------------------------------------------------------------------
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::middleware('owner')->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // ---------------------------------------------------------------------
    // CUSTOMERS (Pelanggan management in admin panel):
    // Available to Staff, Admin, and Owner
    // ---------------------------------------------------------------------
    Route::resource('customers', CustomerController::class);

    // ---------------------------------------------------------------------
    // PURCHASES (Stock-in):
    // Staff, Admin, Owner can all create purchases to ADD stock
    // Only Owner can cancel completed purchases
    // ---------------------------------------------------------------------
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::post('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel')->middleware('owner');

    // ---------------------------------------------------------------------
    // SALES (Penjualan / Stock-out):
    // Staff, Admin, Owner can all create sales
    // Only Owner can cancel completed sales
    // ---------------------------------------------------------------------
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel')->middleware('owner');

    // ---------------------------------------------------------------------
    // USER MANAGEMENT:
    // Strictly Owner Only
    // ---------------------------------------------------------------------
    Route::middleware('owner')->group(function () {
        Route::resource('users', UserController::class);
    });
});
