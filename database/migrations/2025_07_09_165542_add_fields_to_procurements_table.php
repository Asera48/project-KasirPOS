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
        Schema::table('procurements', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->after('user_id')->constrained('suppliers')->onDelete('set null');
            $table->integer('total_cost')->default(0)->after('procurement_date');
            $table->string('reference_number')->nullable()->after('total_cost');
            $table->enum('status', ['ordered', 'completed', 'cancelled'])->default('completed')->after('reference_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procurements', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['supplier_id', 'total_cost', 'reference_number', 'status']);
        });
    }
};
