<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Http\Requests\SupplierStoreRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Models\ActivityLog;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $suppliers = Supplier::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('contact_person', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('suppliers.index', compact('suppliers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierStoreRequest $request)
    {
        $supplier = Supplier::create($request->validated());
        ActivityLog::addLog('CREATE_SUPPLIER', "Membuat supplier baru: {$supplier->name}");

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierUpdateRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());
        ActivityLog::addLog('UPDATE_SUPPLIER', "Memperbarui supplier: {$supplier->name}");

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        // Mencegah penghapusan jika supplier masih terkait dengan pengadaan
        if ($supplier->procurements()->exists()) {
            return back()->withErrors(['error' => 'Supplier tidak dapat dihapus karena masih memiliki data pengadaan.']);
        }

        $supplierName = $supplier->name;
        $supplier->delete();
        ActivityLog::addLog('DELETE_SUPPLIER', "Menghapus supplier: {$supplierName}");

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
