<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    // DAFTARKAN SEMUA KOLOM YANG BOLEH DIISI
    protected $fillable = [
        'title',
        'category', 
        'description',
        'image_path',
        'status', 
    ];
}