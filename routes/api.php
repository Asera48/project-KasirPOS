<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan API route untuk aplikasi Anda. Route-route
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditetapkan ke grup middleware "api".
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route untuk pencarian produk
// 'auth:sanctum' memastikan hanya pengguna yang terotentikasi yang bisa mengakses API ini
Route::middleware('auth:sanctum')->get('/products/search', [ProductController::class, 'searchApi'])->name('api.products.search');

// Route untuk Data Grafik
Route::middleware('auth:sanctum')->get('/reports/sales-chart', [ReportController::class, 'salesChartData'])->name('api.reports.sales-chart');