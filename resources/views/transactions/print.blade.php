<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - {{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
            color: #000;
            background-color: #fff;
        }
        .receipt-container {
            width: 280px; /* Lebar umum untuk printer thermal 80mm */
            margin: 0 auto;
        }
        .header { text-align: center; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 14pt; }
        .header p { margin: 0; font-size: 9pt; }
        .transaction-details, .item-details, .payment-details { margin-bottom: 10px; }
        .transaction-details p, .payment-details p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 2px 0; }
        .item-row td { vertical-align: top; }
        .align-right { text-align: right; }
        .dashed-line { border-top: 1px dashed #000; margin: 5px 0; }
        .footer { text-align: center; margin-top: 10px; font-size: 9pt; }
        .discount-info { font-size: 8pt; padding-left: 10px; }
        
        .print-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .print-button {
            padding: 8px 16px;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        @media print {
            body { margin: 0; }
            .receipt-container { margin: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="print-header no-print">
        <button onclick="window.print()" class="print-button">Cetak Struk</button>
    </div>

    <div class="receipt-container">
        <div class="header">
            <h1>{{ setting('app_name', 'AppKasir') }}</h1>
            <p>{{ setting('store_address', 'Jl. Jenderal Sudirman No. 1, Jakarta') }}</p>
            <p>Telp: {{ setting('store_phone', '(021) 123-4567') }}</p>
        </div>

        <div class="dashed-line"></div>
        
        <div class="transaction-details">
            <p>No: {{ $transaction->invoice_number }}</p>
            <p>Tgl: {{ $transaction->transaction_date->format('d/m/y H:i') }}</p>
            <p>Kasir: {{ $transaction->user->name ?? 'N/A' }}</p>
        </div>

        <div class="dashed-line"></div>

        <div class="item-details">
            <table>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr class="item-row">
                        <td colspan="3">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                    </tr>
                    <tr>
                        <td>{{ $item->quantity }}x</td>
                        <td class="align-right">{{ format_rupiah($item->price) }}</td>
                        <td class="align-right">{{ format_rupiah($item->subtotal) }}</td>
                    </tr>
                    @if($item->discount_amount > 0)
                    <tr class="discount-info">
                        <td colspan="3">
                            Diskon: 
                            @if($item->discount_type == 'percentage')
                                ({{ $item->discount_value }}%)
                            @endif
                            -{{ format_rupiah($item->discount_amount * $item->quantity) }}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="dashed-line"></div>

        <div class="payment-details">
            <table>
                <tbody>
                    <tr>
                        {{-- PERBAIKAN: Menggunakan subtotal dari database --}}
                        <td>Subtotal</td>
                        <td class="align-right">{{ format_rupiah($transaction->subtotal) }}</td>
                    </tr>
                    @if($transaction->tax_amount > 0)
                    <tr>
                        <td>Pajak</td>
                        <td class="align-right">{{ format_rupiah($transaction->tax_amount) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Total</td>
                        <td class="align-right">{{ format_rupiah($transaction->total) }}</td>
                    </tr>
                    <tr>
                        <td>{{ ucwords($transaction->payment_method) }}</td>
                        <td class="align-right">{{ format_rupiah($transaction->paid) }}</td>
                    </tr>
                    <tr>
                        <td>Kembalian</td>
                        <td class="align-right">{{ format_rupiah($transaction->change) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="dashed-line"></div>

        <div class="footer">
            <p>Terima kasih telah berbelanja!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.</p>
        </div>
    </div>
</body>
</html>
