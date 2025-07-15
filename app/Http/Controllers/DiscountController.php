<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Http\Requests\DiscountStoreRequest;
use App\Http\Requests\DiscountUpdateRequest;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'created_at'); // Default pengurutan
        $direction = $request->input('direction', 'desc'); // Default arah

        // Daftar kolom yang diizinkan untuk diurutkan
        $sortableColumns = ['type', 'value', 'start_date', 'end_date', 'product_name'];
        if (!in_array($sort, $sortableColumns)) {
            $sort = 'created_at';
        }

        $discountsQuery = Discount::with('product')
            ->when($search, function ($query, $search) {
                return $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });

        // Logika khusus untuk mengurutkan berdasarkan nama produk (dari tabel relasi)
        if ($sort === 'product_name') {
            $discountsQuery->select('discounts.*')
                         ->join('products', 'discounts.product_id', '=', 'products.id')
                         ->orderBy('products.name', $direction);
        } else {
            $discountsQuery->orderBy($sort, $direction);
        }

        // withQueryString() memastikan parameter sort & direction tetap ada di link paginasi
        $discounts = $discountsQuery->paginate(10)->withQueryString();

        return view('discounts.index', compact('discounts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::whereDoesntHave('discount')->orderBy('name')->get();
        return view('discounts.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiscountStoreRequest $request)
    {
        $discount = Discount::create($request->validated());
        ActivityLog::addLog('CREATE_DISCOUNT', "Membuat diskon untuk produk: {$discount->product->name}");

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil ditambahkan.');
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
    public function edit(Discount $discount)
    {
        $products = Product::whereDoesntHave('discount')
            ->orWhere('id', $discount->product_id)
            ->orderBy('name')
            ->get();
            
        return view('discounts.edit', compact('discount', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiscountUpdateRequest $request, Discount $discount) // <-- Diperbarui
    {
        $discount->update($request->validated());
        ActivityLog::addLog('UPDATE_DISCOUNT', "Memperbarui diskon untuk produk: {$discount->product->name}");

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        $productName = $discount->product->name;
        $discount->delete();
        ActivityLog::addLog('DELETE_DISCOUNT', "Menghapus diskon dari produk: {$productName}");

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil dihapus.');
    }
}
