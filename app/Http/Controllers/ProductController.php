<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products (Viewable by Owner, Admin, and Staff).
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

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

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('stock', '<=', 'minimum_stock');
            } elseif ($request->stock_status === 'out') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_status === 'available') {
                $query->where('stock', '>', 0);
            }
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product (Owner only).
     */
    public function create()
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat menambah produk.');
        }

        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product (Owner only).
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat menambah produk.');
        }

        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'string', 'max:500'],
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk kamera baru (' . $validated['name'] . ') berhasil ditambahkan.');
    }

    /**
     * Display the specified product detail (All roles).
     */
    public function show(Product $product)
    {
        $product->load([
            'category',
            'purchaseItems.purchase.supplier',
            'purchaseItems.purchase.user',
            'saleItems.sale.customer',
            'saleItems.sale.user',
        ]);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product (Owner only).
     */
    public function edit(Product $product)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat mengedit produk.');
        }

        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product (Owner only).
     */
    public function update(Request $request, Product $product)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat mengubah data produk.');
        }

        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku,' . $product->id],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'string', 'max:500'],
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Data produk kamera (' . $product->name . ') berhasil diperbarui.');
    }

    /**
     * Remove the specified product (Owner only).
     */
    public function destroy(Product $product)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat menghapus produk.');
        }

        // Check if product is referenced in transactions
        $hasPurchases = $product->purchaseItems()->count() > 0;
        $hasSales = $product->saleItems()->count() > 0;

        if ($hasPurchases || $hasSales) {
            // Soft delete to protect transaction integrity
            $product->delete();
            return redirect()->route('products.index')
                ->with('success', 'Produk kamera telah diarsipkan (soft delete) untuk menjaga integritas riwayat transaksi.');
        }

        $product->forceDelete();
        return redirect()->route('products.index')
            ->with('success', 'Produk kamera berhasil dihapus secara permanen.');
    }
}
