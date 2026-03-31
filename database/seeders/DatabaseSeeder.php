<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Widi Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), 
            'role' => 'admin',
            'phone' => '085536949348',
            'address' => 'Kantor Pusat CV Widi',
        ]);

        User::create([
            'name' => 'Budi Teknisi',
            'email' => 'teknisibudi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'technician',
            'phone' => '087842949212',
            'address' => 'Mess Teknisi',
        ]);

        User::create([
            'name' => 'Andi Teknisi',
            'email' => 'teknisiandi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'technician',
            'phone' => '089876543211',
            'address' => 'Mess Teknisi 2',
        ]);

        // User::create([
        //     'name' => 'Andi Pelanggan',
        //     'email' => 'andi@gmail.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'customer',
        //     'phone' => '085555555555',
        //     'address' => 'Jl. Merdeka No. 45',
        // ]);

        //Data LAYANAN 
        $services = [
            [
                'name' => 'Cuci AC Split (0.5 - 1 PK)',
                'price' => 75000,
                'description' => 'Pembersihan unit indoor dan outdoor, pengecekan freon.',
                'duration' => 60
            ],
            [
                'name' => 'Cuci AC Split (1.5 - 2 PK)',
                'price' => 85000,
                'description' => 'Pembersihan unit indoor dan outdoor kapasitas besar.',
                'duration' => 75
            ],
            [
                'name' => 'Isi Freon R32/R410',
                'price' => 150000,
                'description' => 'Penambahan tekanan freon untuk AC Inverter/Standard.',
                'duration' => 30
            ],
            [
                'name' => 'Bongkar Pasang AC',
                'price' => 350000,
                'description' => 'Jasa pindah unit AC ke lokasi baru (belum termasuk material pipa).',
                'duration' => 120
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}