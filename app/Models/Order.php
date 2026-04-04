<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    /**
     * Kolom-kolom yang boleh diisi secara massal
     */
    protected $fillable = [
        'user_id',
        'technician_id', // Dibiarkan saja untuk jaga-jaga data lama
        'booking_date',
        'booking_time',
        'address_detail',
        'latitude',
        'longitude',
        'notes',
        'total_price',
        'dp_amount',
        'remaining_balance',
        'status',
        'payment_status',
        'payment_step',
        'snap_token',
        'cancel_notes',
    ];

    /**
     * Relasi ke Pelanggan (Customer)
     * Satu pesanan dimiliki oleh satu pelanggan
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * ✅ INI OBAT ERRORNYA: Relasi ke Tim Teknisi (Many-to-Many)
     * Satu pesanan bisa dikerjakan oleh banyak teknisi
     */
    public function technicians(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'order_user');
    }

    /**
     * Relasi ke Layanan (Services)
     * Satu pesanan bisa memiliki banyak jenis layanan (Cuci AC, Freon, dll)
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'order_service'); // Pastikan nama tabel pivot servicenya benar (biasanya order_service)
    }
}