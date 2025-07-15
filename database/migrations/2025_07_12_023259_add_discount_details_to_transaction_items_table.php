<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            // Menyimpan tipe diskon (e.g., 'percentage', 'fixed')
            $table->string('discount_type')->nullable()->after('cost_price');
            // Menyimpan nilai asli dari diskon (e.g., 10 untuk 10% atau 5000 untuk Rp 5.000)
            $table->integer('discount_value')->nullable()->after('discount_type');
            // Menyimpan jumlah potongan rupiah yang sebenarnya didapat item ini
            $table->integer('discount_amount')->default(0)->after('discount_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'discount_amount']);
        });
    }
};
