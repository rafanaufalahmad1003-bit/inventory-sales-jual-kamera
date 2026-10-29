<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers (All internal roles can view).
     */
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchases');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->latest()->paginate(10)->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier (Owner only).
     */
    public function create()
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat menambah pemasok (supplier).');
        }

        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier (Owner only).
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat menambah pemasok (supplier).');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Pemasok baru (' . $validated['name'] . ') berhasil ditambahkan.');
    }

    /**
     * Display the specified supplier (All internal roles).
     */
    public function show(Supplier $supplier)
    {
        $supplier->load(['purchases.user', 'purchases.purchaseItems.product']);
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier (Owner only).
     */
    public function edit(Supplier $supplier)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat mengedit pemasok.');
        }

        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier (Owner only).
     */
    public function update(Request $request, Supplier $supplier)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat mengedit pemasok.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Data pemasok (' . $supplier->name . ') berhasil diperbarui.');
    }

    /**
     * Remove the specified supplier (Owner only).
     */
    public function destroy(Supplier $supplier)
    {
        if (!auth()->user()->isOwner()) {
            abort(403, 'Akses ditolak: Hanya Owner yang dapat menghapus pemasok.');
        }

        if ($supplier->purchases()->count() > 0) {
            $supplier->delete(); // Soft delete
            return redirect()->route('suppliers.index')
                ->with('success', 'Pemasok telah diarsipkan (soft delete) karena memiliki riwayat pembelian.');
        }

        $supplier->forceDelete();
        return redirect()->route('suppliers.index')
            ->with('success', 'Pemasok berhasil dihapus secara permanen.');
    }
}
