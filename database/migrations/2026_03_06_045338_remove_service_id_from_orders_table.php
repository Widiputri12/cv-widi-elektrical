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
            // 1. Hapus Foreign Key dulu agar tidak error
            $table->dropForeign(['service_id']);
            
            // 2. Baru hapus kolomnya
            $table->dropColumn('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Jika rollback, kembalikan kolomnya
            $table->unsignedBigInteger('service_id')->after('user_id');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }
};