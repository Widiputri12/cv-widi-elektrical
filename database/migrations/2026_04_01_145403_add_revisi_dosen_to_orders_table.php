<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function run(): void
        {
            Schema::table('orders', function (Blueprint $table) {
                // 1. Kolom untuk nominal DP (50%)
                $table->decimal('dp_amount', 15, 2)->default(0)->after('total_price');
                
                // 2. Kolom untuk sisa yang harus dibayar
                $table->decimal('remaining_balance', 15, 2)->default(0)->after('dp_amount');
                
                // 3. Alasan pembatalan dari Admin atau Pelanggan
                $table->text('cancel_notes')->nullable()->after('payment_status');
                
                // 4. Penanda tahap pembayaran: 'dp' (Uang Muka) atau 'full' (Lunas)
                $table->enum('payment_step', ['dp', 'full'])->default('dp')->after('payment_status');
                

            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
        {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['dp_amount', 'remaining_balance', 'cancel_notes', 'payment_step']);
            });
        }
};
