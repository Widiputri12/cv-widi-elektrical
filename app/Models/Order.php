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
        // 'service_id', 
        'quantity',
        // 'technician_id', 
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technicians(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'order_user');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'order_service'); 
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}