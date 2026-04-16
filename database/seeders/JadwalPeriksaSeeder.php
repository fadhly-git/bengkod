<?php

namespace Database\Seeders;

use App\Models\JadwalPeriksa;
use App\Models\User;
use Illuminate\Database\Seeder;

class JadwalPeriksaSeeder extends Seeder
{
    public function run(): void
    {
        $dokters = User::query()
            ->where('role', 'dokter')
            ->get();

        $slots = [
            ['hari' => 'senin', 'jam_mulai' => '08:00:00', 'jam_selesai' => '11:00:00'],
            ['hari' => 'kamis', 'jam_mulai' => '13:00:00', 'jam_selesai' => '16:00:00'],
        ];

        foreach ($dokters as $dokter) {
            foreach ($slots as $slot) {
                JadwalPeriksa::updateOrCreate(
                    [
                        'id_dokter' => $dokter->id,
                        'hari' => $slot['hari'],
                        'jam_mulai' => $slot['jam_mulai'],
                    ],
                    [
                        'jam_selesai' => $slot['jam_selesai'],
                    ]
                );
            }
        }
    }
}
