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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('invoice_number')->unique()->after('id')->nullable();
            $table->string('payment_method')->default('cash')->after('member_id');
            $table->enum('status', ['completed', 'pending', 'cancelled', 'refunded'])->default('completed')->after('change');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'payment_method', 'status']);
        });
    }
};
