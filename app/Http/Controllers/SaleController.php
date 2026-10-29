<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    /**
     * Display a listing of sales transactions.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'saleItems.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('sold_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('sold_at', '<=', $request->to_date);
        }

        $sales = $query->latest('sold_at')->paginate(10)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new sale (Available to Staff, Admin, Owner).
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        // Only products with positive stock are available for sale
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();

        return view('sales.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created sale transaction (Atomic with stock validation).
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'sold_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ], [
            'items.required' => 'Minimal tambahkan 1 item produk kamera yang dibeli.',
            'items.min' => 'Minimal tambahkan 1 item produk kamera yang dibeli.',
            'items.*.product_id.required' => 'Pilih produk kamera untuk setiap baris.',
            'items.*.quantity.min' => 'Jumlah barang yang dibeli minimal 1.',
        ]);

        $sale = DB::transaction(function () use ($request) {
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'user_id' => auth()->id(),
                'sale_number' => Sale::generateSaleNumber(),
                'total' => 0,
                'status' => 'completed',
                'notes' => $request->notes,
                'sold_at' => $request->sold_at,
            ]);

            $total = 0;

            foreach ($request->items as $itemData) {
                // Lock row for concurrent update protection
                $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);
                $qty = (int) $itemData['quantity'];

                // Business Rule: Ensure stock is sufficient
                if ($qty < 1 || $product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'items' => "Stok produk '{$product->name}' tidak mencukupi! Stok tersedia: {$product->stock}, permintaan: {$qty}.",
                    ]);
                }

                // Price is taken securely from Database, NOT trusted from browser
                $unitPrice = $product->selling_price;
                $subtotal = $unitPrice * $qty;

                // Create sale item
                $sale->saleItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                // Atomically decrement stock
                $product->decrement('stock', $qty);

                $total += $subtotal;
            }

            // Update total in database
            $sale->update(['total' => $total]);

            return $sale;
        });

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Transaksi penjualan (' . $sale->sale_number . ') berhasil diproses dan stok produk telah diperbarui!');
    }

    /**
     * Display the specified sale transaction detail.
     */
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'saleItems.product.category']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Cancel sale transaction and restore stock (Owner only).
     */
    public function cancel(Sale $sale)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat membatalkan transaksi.');
        }

        if ($sale->status === 'cancelled') {
            return redirect()->back()
                ->with('error', 'Transaksi penjualan ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($sale) {
            // Restore stock back to products
            foreach ($sale->saleItems as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            $sale->update(['status' => 'cancelled']);
        });

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Transaksi penjualan ' . $sale->sale_number . ' telah dibatalkan dan stok dikembalikan.');
    }
}
