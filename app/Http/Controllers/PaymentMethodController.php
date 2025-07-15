<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Requests\PaymentMethodStoreRequest;
use App\Http\Requests\PaymentMethodUpdateRequest;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $paymentMethods = PaymentMethod::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('code', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10);
        return view('payment_methods.index', compact('paymentMethods', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payment_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentMethodStoreRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('qr_code_image')) {
            $validated['qr_code_image'] = $request->file('qr_code_image')->store('qrcodes', 'public');
        }

        $paymentMethod = PaymentMethod::create($validated);
        ActivityLog::addLog('CREATE_PAYMENT_METHOD', "Membuat metode pembayaran baru: {$paymentMethod->name}");
        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode pembayaran berhasil ditambahkan.');
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
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentMethodUpdateRequest $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validated();

        // Menangani penghapusan gambar QR
        if ($request->boolean('delete_qr_code') && $paymentMethod->qr_code_image) {
            Storage::disk('public')->delete($paymentMethod->qr_code_image);
            $validated['qr_code_image'] = null;
        }

        // Menangani upload gambar QR baru
        if ($request->hasFile('qr_code_image')) {
            if ($paymentMethod->qr_code_image) {
                Storage::disk('public')->delete($paymentMethod->qr_code_image);
            }
            $validated['qr_code_image'] = $request->file('qr_code_image')->store('qrcodes', 'public');
        }

        $paymentMethod->update($validated);
        ActivityLog::addLog('UPDATE_PAYMENT_METHOD', "Memperbarui metode pembayaran: {$paymentMethod->name}");
        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Hapus gambar terkait saat metode pembayaran dihapus
        if ($paymentMethod->qr_code_image) {
            Storage::disk('public')->delete($paymentMethod->qr_code_image);
        }

        $paymentMethodName = $paymentMethod->name;
        $paymentMethod->delete();
        ActivityLog::addLog('DELETE_PAYMENT_METHOD', "Menghapus metode pembayaran: {$paymentMethodName}");
        return redirect()->route('admin.payment-methods.index')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
