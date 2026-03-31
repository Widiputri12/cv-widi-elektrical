<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'technician_id', 'booking_date', 'booking_time', 
        'notes', 'address_detail', 'latitude', 'longitude', 
        'status', 'total_price', 'payment_status', 'snap_token'
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_service');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}