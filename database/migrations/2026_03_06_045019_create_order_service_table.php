<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_service', function (Blueprint $blueprint) {
            $blueprint->id();
            // Menghubungkan ke tabel orders
            $blueprint->foreignId('order_id')->constrained()->onDelete('cascade');
            // Menghubungkan ke tabel services
            $blueprint->foreignId('service_id')->constrained()->onDelete('cascade');
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_service');
    }
};