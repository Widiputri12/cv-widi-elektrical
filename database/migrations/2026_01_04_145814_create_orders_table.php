<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete(); 
            
            $table->date('booking_date');
            $table->time('booking_time');
            $table->text('notes')->nullable();
            
            $table->text('address_detail');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->enum('status', ['pending', 'confirmed', 'otw', 'working', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'expired'])->default('unpaid');
            $table->string('snap_token')->nullable(); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};