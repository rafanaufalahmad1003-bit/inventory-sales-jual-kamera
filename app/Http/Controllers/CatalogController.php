<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CatalogController extends Controller
{
    /**
     * Display public/customer camera store catalog.
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $query = Product::with('category')->where('stock', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(9)->withQueryString();

        return view('catalog.index', compact('products', 'categories'));
    }

    /**
     * Display product details for customer.
     */
    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->take(3)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show order/checkout form for a specific product.
     */
    public function order(Product $product)
    {
        if ($product->stock < 1) {
            return redirect()->route('catalog.index')
                ->with('error', 'Maaf, stok kamera ' . $product->name . ' sedang habis.');
        }

        $customer = null;
        if (Auth::check()) {
            $customer = Customer::where('email', Auth::user()->email)->first();
        }

        return view('catalog.order', compact('product', 'customer'));
    }

    /**
     * Process customer camera order (creates sale & deducts stock).
     */
    public function processOrder(Request $request, Product $product)
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['required', 'string', 'max:1000'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $sale = DB::transaction(function () use ($request, $product) {
            // Find or create customer
            $customer = null;
            if ($request->filled('customer_email')) {
                $customer = Customer::where('email', $request->customer_email)->first();
            }
            if (!$customer) {
                $customer = Customer::where('phone', $request->customer_phone)->first();
            }
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'email' => $request->customer_email,
                    'address' => $request->customer_address,
                ]);
            } else {
                $customer->update([
                    'name' => $request->customer_name,
                    'address' => $request->customer_address,
                ]);
            }

            // Lock product to check stock
            $prod = Product::lockForUpdate()->findOrFail($product->id);
            $qty = (int) $request->quantity;

            if ($prod->stock < $qty) {
                throw ValidationException::withMessages([
                    'quantity' => "Stok kamera tidak mencukupi! Tersedia hanya {$prod->stock} unit.",
                ]);
            }

            // Assign sales user_id: current user or admin/staff
            $userId = Auth::check() ? Auth::id() : User::whereIn('role', ['admin', 'owner'])->first()->id ?? 1;

            $unitPrice = $prod->selling_price;
            $subtotal = $unitPrice * $qty;

            $sale = Sale::create([
                'customer_id' => $customer->id,
                'user_id' => $userId,
                'sale_number' => Sale::generateSaleNumber(),
                'total' => $subtotal,
                'status' => 'completed',
                'notes' => 'Pemesanan Online: ' . ($request->notes ?? '-'),
                'sold_at' => now(),
            ]);

            $sale->saleItems()->create([
                'product_id' => $prod->id,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);

            // Decrement stock
            $prod->decrement('stock', $qty);

            return $sale;
        });

        return redirect()->route('catalog.invoice', $sale)
            ->with('success', 'Pesanan kamera Anda berhasil diproses! Nomor Faktur: ' . $sale->sale_number);
    }

    /**
     * Show order invoice / receipt for customer.
     */
    public function invoice(Sale $sale)
    {
        $sale->load(['customer', 'saleItems.product']);
        return view('catalog.invoice', compact('sale'));
    }

    /**
     * Display customer order history (if logged in).
     */
    public function myOrders()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $customer = Customer::where('email', $user->email)->orWhere('phone', $user->phone)->first();

        $sales = collect();
        if ($customer) {
            $sales = Sale::with(['saleItems.product'])
                ->where('customer_id', $customer->id)
                ->latest('sold_at')
                ->paginate(10);
        }

        return view('catalog.my_orders', compact('sales'));
    }
}
