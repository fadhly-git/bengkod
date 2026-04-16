<?php

namespace Database\Seeders;

use App\Models\Poli;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $polis = Poli::query()
            ->whereIn('nama_poli', ['Umum', 'Gigi', 'Anak', 'Mata', 'THT', 'Kulit', 'Jantung'])
            ->get()
            ->keyBy('nama_poli');

        User::updateOrCreate(
            ['email' => 'admin@mail.to'],
            [
                'nama' => 'Admin',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'alamat' => 'Jl. Administrasi No. 1',
                'no_ktp' => '1234567890123456',
                'no_hp' => '081234567890',
                'no_rm' => null,
                'id_poli' => null,
            ]
        );

        $dokterSeed = [
            [
                'email' => 'dokter.umum@mail.to',
                'nama' => 'dr. Andi Pratama',
                'alamat' => 'Jl. Kesehatan No. 45',
                'no_ktp' => '4410000000000001',
                'no_hp' => '081234567891',
                'poli' => 'Umum',
            ],
            [
                'email' => 'dokter.gigi@mail.to',
                'nama' => 'drg. Citra Lestari',
                'alamat' => 'Jl. Melati No. 8',
                'no_ktp' => '4410000000000002',
                'no_hp' => '081234567892',
                'poli' => 'Gigi',
            ],
            [
                'email' => 'dokter.anak@mail.to',
                'nama' => 'dr. Budi Santoso, Sp.A',
                'alamat' => 'Jl. Kenanga No. 9',
                'no_ktp' => '4410000000000003',
                'no_hp' => '081234567893',
                'poli' => 'Anak',
            ],
            [
                'email' => 'dokter.mata@mail.to',
                'nama' => 'dr. Rina Maharani, Sp.M',
                'alamat' => 'Jl. Sudirman No. 10',
                'no_ktp' => '4410000000000004',
                'no_hp' => '081234567894',
                'poli' => 'Mata',
            ],
            [
                'email' => 'dokter.tht@mail.to',
                'nama' => 'dr. Fajar Nugroho, Sp.THT',
                'alamat' => 'Jl. Ahmad Yani No. 11',
                'no_ktp' => '4410000000000005',
                'no_hp' => '081234567895',
                'poli' => 'THT',
            ],
            [
                'email' => 'dokter.kulit@mail.to',
                'nama' => 'dr. Maya Putri, Sp.KK',
                'alamat' => 'Jl. Diponegoro No. 12',
                'no_ktp' => '4410000000000006',
                'no_hp' => '081234567896',
                'poli' => 'Kulit',
            ],
            [
                'email' => 'dokter.jantung@mail.to',
                'nama' => 'dr. Rizky Hidayat, Sp.JP',
                'alamat' => 'Jl. Gatot Subroto No. 13',
                'no_ktp' => '4410000000000007',
                'no_hp' => '081234567897',
                'poli' => 'Jantung',
            ],
        ];

        foreach ($dokterSeed as $dokter) {
            User::updateOrCreate(
                ['email' => $dokter['email']],
                [
                    'nama' => $dokter['nama'],
                    'password' => Hash::make('dokter'),
                    'role' => 'dokter',
                    'alamat' => $dokter['alamat'],
                    'no_ktp' => $dokter['no_ktp'],
                    'no_hp' => $dokter['no_hp'],
                    'no_rm' => null,
                    'id_poli' => $polis->get($dokter['poli'])?->id,
                ]
            );
        }

        User::updateOrCreate(
            ['email' => 'pasien@mail.to'],
            [
                'nama' => 'Pasien',
                'password' => Hash::make('pasien'),
                'role' => 'pasien',
                'alamat' => 'Jl. Mawar Merah No. 12',
                'no_ktp' => '3456789012345678',
                'no_hp' => '081234567892',
                'no_rm' => 'RM-000001',
                'id_poli' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'pasien.demo@mail.to'],
            [
                'nama' => 'Pasien Demo',
                'password' => Hash::make('pasien'),
                'role' => 'pasien',
                'alamat' => 'Jl. Mawar Merah No. 13',
                'no_ktp' => '3456789012345679',
                'no_hp' => '081234567893',
                'no_rm' => 'RM-000002',
                'id_poli' => null,
            ]
        );
    }
}
