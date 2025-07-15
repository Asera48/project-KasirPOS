<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class SalesReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        // PERBAIKAN: Menggunakan JOIN untuk mengambil data relasi dan menambahkan orderBy
        return Transaction::query()
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->leftJoin('members', 'transactions.member_id', '=', 'members.id')
            ->select(
                'transactions.id',
                'transactions.invoice_number',
                'transactions.transaction_date',
                'users.name as user_name',
                'members.name as member_name',
                'transactions.payment_method',
                'transactions.subtotal',
                'transactions.tax_amount',
                'transactions.total'
            )
            ->whereBetween('transactions.transaction_date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->orderBy('transactions.transaction_date', 'asc'); // Menambahkan orderBy
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Transaksi',
            'No. Invoice',
            'Tanggal',
            'Kasir',
            'Pelanggan',
            'Metode Pembayaran',
            'Subtotal',
            'Pajak',
            'Total',
        ];
    }

    /**
     * @param mixed $transaction
     * @return array
     */
    public function map($transaction): array
    {
        // Karena kita menggunakan join, kita akses propertinya langsung
        return [
            $transaction->id,
            $transaction->invoice_number,
            Carbon::parse($transaction->transaction_date)->format('d-m-Y H:i:s'),
            $transaction->user_name,
            $transaction->member_name ?? 'Umum',
            ucwords($transaction->payment_method),
            $transaction->subtotal,
            $transaction->tax_amount,
            $transaction->total,
        ];
    }
}

