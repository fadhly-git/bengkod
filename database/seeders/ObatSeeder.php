<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        $obats = [
            [
                'nama_obat' => 'Amoxicillin',
                'kemasan' => 'Tablet 500mg',
                'harga' => 25000,
                'stok' => 50,
            ],
            [
                'nama_obat' => 'Paracetamol',
                'kemasan' => 'Tablet 500mg',
                'harga' => 15000,
                'stok' => 80,
            ],
            [
                'nama_obat' => 'Ibuprofen',
                'kemasan' => 'Tablet 400mg',
                'harga' => 12000,
                'stok' => 40,
            ],
            [
                'nama_obat' => 'Cetirizine',
                'kemasan' => 'Tablet 10mg',
                'harga' => 18000,
                'stok' => 35,
            ],
            [
                'nama_obat' => 'Amlodipine',
                'kemasan' => 'Tablet 5mg',
                'harga' => 22000,
                'stok' => 30,
            ],
            [
                'nama_obat' => 'Omeprazole',
                'kemasan' => 'Kapsul 20mg',
                'harga' => 27000,
                'stok' => 45,
            ],
            [
                'nama_obat' => 'Loratadine',
                'kemasan' => 'Tablet 10mg',
                'harga' => 16000,
                'stok' => 55,
            ],
            [
                'nama_obat' => 'Salbutamol',
                'kemasan' => 'Inhaler',
                'harga' => 45000,
                'stok' => 20,
            ],
            [
                'nama_obat' => 'Diclofenac',
                'kemasan' => 'Tablet 50mg',
                'harga' => 20000,
                'stok' => 28,
            ],
        ];

        foreach ($obats as $obat) {
            Obat::updateOrCreate(
                ['nama_obat' => $obat['nama_obat']],
                $obat
            );
        }
    }
}
