<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Procurement;
use App\Models\ProcurementItem;
use App\Http\Requests\ProcurementStoreRequest;
use App\Http\Requests\ProcurementUpdateRequest; 
use App\Http\Requests\ProcurementImportRequest;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use App\Imports\ProcurementImport;
use App\Exports\ProcurementTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ProcurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $procurements = Procurement::with(['user', 'supplier'])
            ->when($search, function ($query, $search) {
                return $query->where('id', 'like', "%{$search}%")
                             ->orWhere('reference_number', 'like', "%{$search}%")
                             ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                             ->orWhereHas('supplier', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10);

        return view('procurements.index', compact('procurements', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get(); 
        return view('procurements.create', compact('products', 'suppliers')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProcurementStoreRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $totalCost = 0;
            foreach ($validated['items'] as $item) {
                $totalCost += $item['quantity'] * $item['cost_price'];
            }

            $procurement = Procurement::create([
                'user_id' => Auth::id(),
                'supplier_id' => $validated['supplier_id'], 
                'procurement_date' => $validated['procurement_date'],
                'total_cost' => $totalCost, 
                'reference_number' => $validated['reference_number'], 
                'status' => 'completed',
            ]);

            foreach ($validated['items'] as $item) {
                ProcurementItem::create([
                    'procurement_id' => $procurement->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                ]);

                $product = Product::find($item['product_id']);
                
                // --- HITUNG HARGA POKOK RATA-RATA ---
                $oldStock = $product->stock;
                $oldCostPrice = $product->cost_price;
                $newQuantity = $item['quantity'];
                $newBuyPrice = $item['cost_price'];

                $totalOldValue = $oldStock * $oldCostPrice;
                $totalNewValue = $newQuantity * $newBuyPrice;
                $totalStock = $oldStock + $newQuantity;

                $newAverageCost = ($totalStock > 0) ? (($totalOldValue + $totalNewValue) / $totalStock) : $newBuyPrice;

                // Update harga pokok dan stok produk
                $product->update([
                    'cost_price' => round($newAverageCost),
                    'stock' => $totalStock
                ]);
            }

            DB::commit();
            ActivityLog::addLog('CREATE_PROCUREMENT', "Mencatat pengadaan baru dengan ID: {$procurement->id}");

            return redirect()->route('admin.procurements.index')->with('success', 'Data pengadaan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data pengadaan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Procurement $procurement)
    {
        $procurement->load(['items.product', 'user', 'supplier']); 
        return view('procurements.show', compact('procurement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Procurement $procurement)
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $procurement->load('items'); // Memuat item yang sudah ada
        return view('procurements.edit', compact('procurement', 'products', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProcurementUpdateRequest $request, Procurement $procurement)
    {
        // Fitur update pengadaan cukup kompleks dan berisiko jika tidak hati-hati.
        // Untuk proyek belajar, seringkali lebih aman untuk menghapus dan membuat ulang.
        // Namun, jika diperlukan, logika yang benar harus mengembalikan stok lama,
        // menghitung ulang harga pokok, lalu menerapkan perubahan baru.
        // Untuk saat ini, kita akan fokus pada fungsionalitas store dan destroy.
        return redirect()->route('admin.procurements.index')->withErrors(['error' => 'Fitur update pengadaan belum diimplementasikan.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Procurement $procurement)
    {
        // Logika penghapusan yang benar harus mengembalikan stok.
        DB::beginTransaction();
        try {
            foreach ($procurement->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    // Logika ini mengasumsikan kita mengurangi stok.
                    // Perhitungan ulang harga pokok saat hapus sangat kompleks,
                    // jadi kita sederhanakan dengan hanya mengurangi stok.
                    $product->decrement('stock', $item->quantity);
                }
            }
            
            $procurementId = $procurement->id;
            $procurement->delete();
            
            DB::commit();
            ActivityLog::addLog('DELETE_PROCUREMENT', "Menghapus pengadaan dengan ID: {$procurementId}");

            return redirect()->route('admin.procurements.index')->with('success', 'Data pengadaan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus data pengadaan: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan form untuk impor data pengadaan.
     */
    public function showImportForm()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('procurements.import', compact('suppliers'));
    }

    /**
     * Menangani proses impor data dari file Excel.
     */
    public function handleImport(ProcurementImportRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $procurement = Procurement::create([
                'user_id' => Auth::id(),
                'supplier_id' => $validated['supplier_id'],
                'procurement_date' => $validated['procurement_date'],
                'reference_number' => $validated['reference_number'],
                'status' => 'completed',
            ]);

            Excel::import(new ProcurementImport($procurement), $request->file('import_file'));

            $totalCost = $procurement->items()->sum(DB::raw('quantity * cost_price'));
            $procurement->update(['total_cost' => $totalCost]);

            DB::commit();
            ActivityLog::addLog('IMPORT_PROCUREMENT', "Mengimpor data pengadaan dari file untuk ID: {$procurement->id}");

            return redirect()->route('admin.procurements.index')->with('success', 'Data pengadaan berhasil diimpor.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return back()->withErrors(['import_errors' => $errorMessages])->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengimpor data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Menyediakan file template Excel untuk diunduh.
     */
    /**
     * --- METODE DIPERBARUI ---
     * Menyediakan file template Excel yang dinamis.
     */
    public function downloadTemplate()
    {
        $date = Carbon::now()->format('Y-m-d');
        $fileName = "template-pengadaan-{$date}.xlsx";
        
        return Excel::download(new ProcurementTemplateExport, $fileName);
    }
}
