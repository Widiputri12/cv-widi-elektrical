<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_service', function (Blueprint $table) {
            // Menambahkan kolom quantity ke tabel pivot
            $table->integer('quantity')->default(1)->after('service_id');
        });
    }

    public function down(): void
    {
        Schema::table('order_service', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
