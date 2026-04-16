<?php

namespace Database\Seeders;

use App\Models\Poli;
use Illuminate\Database\Seeder;

class PoliSeeder extends Seeder
{
    public function run(): void
    {
        $polis = [
            [
                'nama_poli' => 'Umum',
                'keterangan' => 'Poli layanan umum untuk pemeriksaan dasar.',
            ],
            [
                'nama_poli' => 'Gigi',
                'keterangan' => 'Poli untuk pemeriksaan dan perawatan gigi.',
            ],
            [
                'nama_poli' => 'Anak',
                'keterangan' => 'Poli layanan kesehatan anak.',
            ],
            [
                'nama_poli' => 'Mata',
                'keterangan' => 'Poli pemeriksaan mata dan gangguan penglihatan.',
            ],
            [
                'nama_poli' => 'THT',
                'keterangan' => 'Poli telinga, hidung, dan tenggorokan.',
            ],
            [
                'nama_poli' => 'Kulit',
                'keterangan' => 'Poli untuk keluhan kulit dan alergi.',
            ],
            [
                'nama_poli' => 'Jantung',
                'keterangan' => 'Poli spesialis jantung dan pembuluh darah.',
            ],
        ];

        foreach ($polis as $poli) {
            Poli::updateOrCreate(
                ['nama_poli' => $poli['nama_poli']],
                $poli
            );
        }
    }
}
