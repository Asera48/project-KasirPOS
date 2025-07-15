<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

// Route::get('/', function () {
//     return view('welcome');
// });

//halaman utama ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route dasbor utama yang akan mengarahkan ke dasbor admin atau kasir
// Ini adalah satu-satunya route 'dashboard'
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===============================================
    // GRUP ROUTE KHUSUS ADMIN
    // ===============================================
    // Hanya pengguna dengan peran 'admin' yang bisa mengakses route di dalam grup ini.
    Route::middleware(['role:admin'])->name('admin.')->prefix('admin')->group(function () {
        
        // Laporan
        Route::get('reports/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
        Route::get('reports/sales/export', [ReportController::class, 'exportSalesReport'])->name('reports.sales.export');
        Route::get('reports/sales/print', [ReportController::class, 'printSalesReport'])->name('reports.sales.print');
        
        // Manajemen Produk
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('discounts', DiscountController::class)->except(['show']);

        // Manajemen Stok
        Route::resource('procurements', ProcurementController::class);
        Route::get('procurements-import', [ProcurementController::class, 'showImportForm'])->name('procurements.import.form');
        Route::post('procurements-import', [ProcurementController::class, 'handleImport'])->name('procurements.import.handle');
        Route::get('procurements-template', [ProcurementController::class, 'downloadTemplate'])->name('procurements.template.download');

        Route::resource('stock-opnames', StockOpnameController::class)->only(['index', 'create', 'store']);

        // Manajemen Umum
        Route::resource('transactions', TransactionController::class)->only(['index', 'show']); // Admin hanya bisa melihat
        Route::get('transactions/{transaction}/print', [TransactionController::class, 'printReceipt'])->name('transactions.print');
        Route::resource('members', MemberController::class); // Untuk mengelola Member
        Route::get('members/{member}/point-history', [MemberController::class, 'pointHistory'])->name('members.point-history'); // point member
        Route::resource('users', UserController::class); // Untuk mengelola akun admin & kasir
        Route::resource('suppliers', SupplierController::class); // Untuk mengelola Supplier

        // Sistem
        Route::resource('payment-methods', PaymentMethodController::class)->except(['show']);
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // ===============================================
    // GRUP ROUTE KHUSUS KASIR
    // ===============================================
    // Pengguna dengan peran 'kasir' bisa mengakses route ini.
    Route::middleware(['role:kasir'])->name('kasir.')->prefix('kasir')->group(function () {
        
        // Transaksi
        Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('transactions/history', [TransactionController::class, 'history'])->name('transactions.history');
        Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('transactions/{transaction}/print', [TransactionController::class, 'printReceipt'])->name('transactions.print');

        // Member
        Route::resource('members', MemberController::class)->only(['index', 'create', 'store']);
    });
});

// Memuat route otentikasi (login, logout, dll.)
require __DIR__.'/auth.php';


