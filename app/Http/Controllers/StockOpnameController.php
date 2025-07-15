<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StockOpname;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Http\Requests\StockOpnameStoreRequest;

class StockOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $stockOpnames = StockOpname::with(['product', 'user'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('stock_opnames.index', compact('stockOpnames', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('stock_opnames.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockOpnameStoreRequest $request)
    {
        $validated = $request->validated();
        
        $product = Product::findOrFail($validated['product_id']);
        
        // Simpan data stok opname
        $stockOpname = StockOpname::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'system_stock' => $product->stock, // Ambil stok sistem saat ini
            'physical_stock' => $validated['physical_stock'],
            'note' => $validated['note'],
        ]);

        // Sesuaikan stok produk berdasarkan hasil opname
        $product->update(['stock' => $validated['physical_stock']]);

        ActivityLog::addLog(
            'STOCK_OPNAME', 
            "Melakukan stok opname untuk produk: {$product->name}. Stok sistem: {$stockOpname->system_stock}, Stok fisik: {$stockOpname->physical_stock}."
        );

        return redirect()->route('admin.stock-opnames.index')->with('success', 'Stok opname berhasil disimpan.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
