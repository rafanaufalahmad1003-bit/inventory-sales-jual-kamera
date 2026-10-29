<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchases (Stock-in).
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user', 'purchaseItems.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('purchased_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('purchased_at', '<=', $request->to_date);
        }

        $purchases = $query->latest('purchased_at')->paginate(10)->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new purchase (Stock In - Available to Staff, Admin, Owner).
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created purchase transaction (Atomic DB::transaction).
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ], [
            'items.required' => 'Minimal tambahkan 1 item barang dalam transaksi pembelian stok.',
            'items.min' => 'Minimal tambahkan 1 item barang dalam transaksi pembelian stok.',
            'items.*.product_id.required' => 'Pilih produk kamera untuk setiap baris barang.',
            'items.*.quantity.min' => 'Jumlah barang (kuantitas) harus minimal 1.',
            'items.*.unit_cost.min' => 'Harga beli barang tidak boleh negatif.',
        ]);

        $purchase = DB::transaction(function () use ($request) {
            $purchaseNumber = Purchase::generatePurchaseNumber();

            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'purchase_number' => $purchaseNumber,
                'total' => 0,
                'status' => 'completed',
                'notes' => $request->notes,
                'purchased_at' => $request->purchased_at,
            ]);

            $total = 0;

            foreach ($request->items as $itemData) {
                $qty = (int) $itemData['quantity'];
                $unitCost = (float) $itemData['unit_cost'];
                $subtotal = $qty * $unitCost;

                $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);

                // Create purchase item
                $purchase->purchaseItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                ]);

                // Increment product stock
                $product->increment('stock', $qty);

                $total += $subtotal;
            }

            // Update header total calculated securely on server
            $purchase->update(['total' => $total]);

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Transaksi pembelian/stock-in (' . $purchase->purchase_number . ') berhasil disimpan dan stok produk telah ditambahkan!');
    }

    /**
     * Display the specified purchase details.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'purchaseItems.product.category']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Cancel purchase transaction and restore stock (Owner only).
     */
    public function cancel(Purchase $purchase)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat membatalkan transaksi.');
        }

        if ($purchase->status === 'cancelled') {
            return redirect()->back()
                ->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($purchase) {
            // Decrement stock that was added by this purchase
            foreach ($purchase->purchaseItems as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }

            $purchase->update(['status' => 'cancelled']);
        });

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Transaksi pembelian ' . $purchase->purchase_number . ' telah dibatalkan dan stok dikembalikan secara konsisten.');
    }
}
