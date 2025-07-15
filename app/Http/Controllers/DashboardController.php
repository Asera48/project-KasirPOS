<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Member;
use App\Models\PaymentMethod;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            $productsCount = Product::count();
            $transactionsCount = Transaction::count();
            $usersCount = User::count();
            
            // Mengambil 5 produk terlaris berdasarkan jumlah yang terjual
            $bestsellingProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->with('product:id,name') // Hanya ambil id dan nama produk
                ->groupBy('product_id')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->get();

            // Mengambil 5 produk dengan stok terendah
            $lowStockProducts = Product::orderBy('stock', 'asc')
                ->take(5)
                ->get();

            return view('dashboard.admin', [
                'productsCount' => $productsCount,
                'transactionsCount' => $transactionsCount,
                'usersCount' => $usersCount,
                'bestsellingProducts' => $bestsellingProducts,
                'lowStockProducts' => $lowStockProducts,
            ]);
        }
        
        // Dashboard kasir
        $now = now();
        $initialProducts = Product::with(['discount' => function ($query) use ($now) {
            $query->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now->startOfDay()); // Cukup periksa tanggalnya
        }])->latest()->take(12)->get();

        return view('dashboard.kasir', [
            'products' => $initialProducts, // Mengirim produk awal
            'members' => Member::orderBy('name')->get(),
            'paymentMethods' => PaymentMethod::where('is_active', true)->get(),
        ]);
    }
}
