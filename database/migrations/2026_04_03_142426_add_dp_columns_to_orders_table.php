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
            Schema::table('orders', function (Blueprint $table) {
                // Menambahkan kolom yang dibutuhkan sesuai error tadi
                $table->decimal('dp_amount', 10, 2)->default(0)->after('total_price');
                $table->decimal('remaining_balance', 10, 2)->default(0)->after('dp_amount');
                $table->enum('payment_step', ['dp', 'full'])->default('dp')->after('payment_status');
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
        {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['dp_amount', 'remaining_balance', 'payment_step']);
            });
        }
};
