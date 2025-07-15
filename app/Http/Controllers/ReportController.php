<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Menampilkan laporan penjualan dengan filter tanggal.
     */
    public function salesReport(Request $request)
    {
        // Set tanggal default ke bulan ini jika tidak ada input
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Query untuk mengambil data transaksi dalam rentang tanggal
        $transactions = Transaction::with('user')
            ->whereBetween('transaction_date', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Query untuk menghitung ringkasan/summary
        $summary = Transaction::whereBetween('transaction_date', 
        [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->select(
                DB::raw('COUNT(id) as total_transactions'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->first();
        
        // Menghitung total biaya (COGS) dari transaction_items
        $totalCost = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.transaction_date', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->sum(DB::raw('transaction_items.cost_price * transaction_items.quantity'));
        
        $summary->total_profit = $summary->total_revenue - $totalCost;

        return view('reports.sales', compact('transactions', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Menyediakan data untuk grafik penjualan 7 hari terakhir.
     */
    public function salesChartData()
    {
        // PERBAIKAN: Menggunakan Carbon untuk memastikan konsistensi timezone
        $endDate = Carbon::today()->endOfDay(); // Akhir hari ini
        $startDate = Carbon::today()->subDays(6)->startOfDay(); // Awal hari 7 hari yang lalu

        // Mengambil total penjualan yang dikelompokkan per hari
        $salesData = Transaction::select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(total) as total_sales')
            )
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // Menyiapkan array untuk label (tanggal) dan data (penjualan)
        $labels = [];
        $data = [];
        
        // Loop selama 7 hari untuk memastikan semua tanggal ada, bahkan jika penjualannya 0
        for ($i = 0; $i < 7; $i++) {
            $currentDate = Carbon::today()->subDays(6 - $i);
            $dateString = $currentDate->format('Y-m-d');
            
            $labels[] = $currentDate->translatedFormat('d M'); // Format tanggal (e.g., 13 Jul)
            
            // Cari data penjualan untuk tanggal ini, jika tidak ada, nilainya 0
            $data[] = $salesData->get($dateString)->total_sales ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    public function stockReport(Request $request)
    {
        $stockThreshold = $request->input('stock_less_than');

        $products = Product::with('category')
            // Terapkan filter hanya jika ada input
            ->when($stockThreshold !== null, function ($query) use ($stockThreshold) {
                return $query->where('stock', '<=', $stockThreshold);
            })
            ->orderBy('stock', 'asc') // Urutkan dari stok terendah
            ->paginate(20)
            ->withQueryString();

        return view('reports.stock', compact('products', 'stockThreshold'));
    }

    /**
     * Mengekspor laporan penjualan ke Excel.
     */
    public function exportSalesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        $fileName = 'laporan-penjualan-' . $startDate . '-sampai-' . $endDate . '.xlsx';

        return Excel::download(new SalesReportExport($startDate, $endDate), $fileName);
    }

    /**
     * Menampilkan halaman cetak untuk laporan penjualan.
     */
    public function printSalesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = Transaction::with(['user', 'member'])
            ->whereBetween('transaction_date', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->latest()
            ->get(); // Ambil semua data tanpa paginasi untuk dicetak

        return view('reports.sales_print', compact('transactions', 'startDate', 'endDate'));
    }
}
