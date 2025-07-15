<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $sortableColumns = ['name', 'price', 'cost_price', 'stock', 'barcode', 'category_name'];
        if (!in_array($sort, $sortableColumns)) {
            $sort = 'created_at';
        }

        $productsQuery = Product::with('category');

        if ($sort === 'category_name') {
            $productsQuery->select('products.*')
                         ->join
                         ('categories', 'products.category_id', '=', 'categories.id')
                         ->orderBy('categories.name', $direction);
        } else {
            $productsQuery->orderBy($sort, $direction);
        }

        $products = $productsQuery->paginate(10)->withQueryString();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product = Product::create($validated);
        ActivityLog::addLog('CREATE_PRODUCT', "Membuat produk baru: {$product->name}");
        return redirect()->route('admin.products.index')
        ->with('success', 'Produk berhasil ditambahkan.');
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
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Simpan gambar baru
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);
        ActivityLog::addLog('UPDATE_PRODUCT', "Memperbarui produk: {$product->name}");
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $productName = $product->name;
        $product->delete();
        ActivityLog::addLog('DELETE_PRODUCT', "Menghapus produk: {$productName}");
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
    /**
     * Metode API untuk mencari produk.
     * Mengembalikan data dalam format JSON.
     */
    public function searchApi(Request $request)
    {
        try {
            $query = $request->input('q', '');
            $now = now();

            if (empty($query)) {
                return response()->json([]);
            }

            $searchQuery = strtolower($query);

            //Memuat relasi diskon yang sedang aktif
            $products = Product::with(['discount' => function ($q) use ($now) {
                $q->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now->startOfDay()); 
            }])
            ->where(function ($q) use ($searchQuery) {
                $q->where(DB::raw('LOWER(name)'), 'like', '%' . $searchQuery . '%')
                  ->orWhere('barcode', 'like', '%' . $searchQuery . '%');
            })
            ->take(20)
            ->get();

            return response()->json($products);
            
        } catch (\Exception $e) {
            Log::error('API Product Search Error (with discount): ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan pada server.'], 500);
        }
    }
}