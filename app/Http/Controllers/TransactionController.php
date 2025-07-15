<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Member;
use App\Models\PointHistory;
use App\Models\ActivityLog;
use App\Http\Requests\TransactionStoreRequest;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transactions = Transaction::with(['user', 'member'])
            ->when($startDate, fn($q) => $q->whereDate('transaction_date', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('transaction_date', '<=', $endDate))
            ->when($search, function ($query, $search) {
                return $query->where('id', 'like', "%{$search}%")
                             ->orWhereHas('member', fn($q) => $q->where('name', 'like', "%{$search}%"))
                             ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('transactions.index', compact('transactions', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionStoreRequest $request)
    {
        $validated = $request->validated();
        $taxRate = (float) setting('tax_rate', 11);
        $pointValue = (int) setting('point_value', 100);
        $pointsPerAmount = (int) setting('points_per_amount', 10000);
        $redeemedPoints = (int) ($validated['redeemed_points'] ?? 0);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            // Loop untuk kalkulasi awal subtotal (harga setelah diskon produk)
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }
                
                $finalPrice = $product->price;
                if ($product->discount && $product->discount->isActive()) {
                    $discountValue = $product->discount->type == 'percentage' ? ($finalPrice * ($product->discount->value / 100)) : $product->discount->value;
                    $finalPrice -= $discountValue;
                }
                $subtotal += $finalPrice * $item['quantity'];
            }

            // Validasi dan kalkulasi diskon dari penukaran poin
            $pointDiscountValue = 0;
            $member = null;
            if ($redeemedPoints > 0) {
                if (empty($validated['member_id'])) {
                    throw new \Exception("Pilih member terlebih dahulu untuk menukar poin.");
                }
                $member = Member::find($validated['member_id']);
                if ($member->points < $redeemedPoints) {
                    throw new \Exception("Poin member tidak mencukupi.");
                }
                $pointDiscountValue = $redeemedPoints * $pointValue;
            }

            // Kalkulasi pajak dan total 
            $subtotalAfterPointDiscount = $subtotal - $pointDiscountValue;
            $taxAmount = round($subtotalAfterPointDiscount * ($taxRate / 100));
            $unroundedTotal = $subtotalAfterPointDiscount + $taxAmount;
            $total = $unroundedTotal;
            $roundingAmount = 0;

            // Pembulatan hanya berlaku untuk pembayaran tunai
            if (($validated['payment_method'] ?? 'cash') === 'cash') {
                // Bulatkan ke 100 terdekat
                $total = round($unroundedTotal / 100) * 100;
                $roundingAmount = $total - $unroundedTotal;
            }
            // --- Akhir Logika Pembulatan ---

            if (($validated['paid'] ?? 0) < $total && $validated['payment_method'] == 'cash') {
                throw new \Exception("Jumlah bayar kurang dari total belanja.");
            }

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'member_id' => $validated['member_id'] ?? null,
                'transaction_date' => now(),
                'subtotal' => $subtotal, // Menyimpan subtotal sebelum diskon poin dan pajak
                'tax_amount' => $taxAmount, // Menyimpan jumlah pajak
                'rounding_amount' => $roundingAmount, // Menyimpan nilai pembulatan
                'total' => $total, // Menyimpan total akhir yang sudah dibulatkan
                'paid' => $validated['paid'] ?? $total,
                'change' => ($validated['paid'] ?? $total) - $total,
                'payment_method' => $validated['payment_method'] ?? 'cash',
            ]);

            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . $transaction->id;
            $transaction->invoice_number = $invoiceNumber;
            $transaction->save();

            // Loop untuk membuat item transaksi dan mengurangi stok
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                
                $finalPrice = $product->price;
                $discountAmount = 0;
                $discountType = null;
                $discountValue = null;

                if ($product->discount && $product->discount->isActive()) {
                    $discountType = $product->discount->type;
                    $discountValue = $product->discount->value;
                    if ($discountType == 'percentage') {
                        $discountAmount = $product->price * ($discountValue / 100);
                    } else {
                        $discountAmount = $discountValue;
                    }
                    $finalPrice -= $discountAmount;
                }

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'cost_price' => $product->cost_price,
                    'subtotal' => $finalPrice * $item['quantity'],
                    'discount_type' => $discountType,
                    'discount_value' => $discountValue,
                    'discount_amount' => $discountAmount,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            // --- Poin Member ---
            if ($transaction->member_id) {
                if (!$member) {
                    $member = Member::find($transaction->member_id);
                }

                if ($redeemedPoints > 0) {
                    $member->decrement('points', $redeemedPoints);
                    // Catat riwayat penukaran poin
                    PointHistory::create([
                        'member_id' => $member->id,
                        'transaction_id' => $transaction->id,
                        'type' => 'redeem',
                        'points' => -$redeemedPoints, // Simpan sebagai nilai negatif
                        'description' => "Penukaran poin pada transaksi {$transaction->invoice_number}",
                    ]);
                }

                if ($pointsPerAmount > 0) {
                    $pointsEarned = floor($transaction->total / $pointsPerAmount);
                    if ($pointsEarned > 0) {
                        $member->increment('points', $pointsEarned);
                        // Catat riwayat perolehan poin
                        PointHistory::create([
                            'member_id' => $member->id,
                            'transaction_id' => $transaction->id,
                            'type' => 'earn',
                            'points' => $pointsEarned,
                            'description' => "Perolehan poin dari transaksi {$transaction->invoice_number}",
                        ]);
                    }
                }
            }
            // --- Akhir Logika Poin ---

            DB::commit();
            ActivityLog::addLog('CREATE_TRANSACTION', "Membuat transaksi baru: {$transaction->invoice_number}");

            return redirect()->route('kasir.transactions.show', $transaction)->with('success', 'Transaksi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        // Otorisasi
        // Memastikan kasir hanya bisa melihat detail transaksinya sendiri. Admin bisa melihat semua.
        if (Auth::user()->isKasir() && $transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat transaksi ini.');
        }

        $transaction->load(['items.product', 'user', 'member']);
        
        // view 'transactions.show' untuk admin dan kasir
        return view('transactions.show', compact('transaction'));
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

    public function history(Request $request)
    {
        $filterDate = $request->input('filter_date');

        $transactions = Transaction::where('user_id', Auth::id())
            ->when($filterDate, function ($query, $date) {
                return $query->whereDate('transaction_date', $date);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('kasir.transactions.history', compact('transactions', 'filterDate'));
    }

    public function printReceipt(Transaction $transaction)
    {
        if (Auth::user()->isKasir() && $transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mencetak struk ini.');
        }

        $transaction->load(['items.product', 'user', 'member']);
        
        return view('transactions.print', compact('transaction'));
    }
}
