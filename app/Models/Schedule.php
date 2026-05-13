<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'technician_id',
        'work_date',
        'start_time',
        'status',
    ];

    // Relasi ke tabel orders
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke tabel users (teknisi)
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}