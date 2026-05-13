<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery; 
use App\Models\Service; 

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Layanan
        $services = Service::all(); 

        // 2. Ambil Data Galeri
        $galleries = Gallery::where('status', 'approved')->latest()->take(9)->get();

        return view('welcome', compact('services', 'galleries'));
    }
}