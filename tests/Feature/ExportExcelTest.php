<?php

namespace Tests\Feature;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExportExcelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_export_data_dokter_pasien_dan_obat(): void
    {
        $admin = $this->makeUser('admin');

        $poli = Poli::create([
            'nama_poli' => 'Umum',
            'keterangan' => 'Poli umum',
        ]);

        $this->makeUser('dokter', $poli->id);
        $this->makeUser('pasien');

        Obat::create([
            'nama_obat' => 'Paracetamol',
            'kemasan' => 'Tablet',
            'harga' => 15000,
            'stok' => 20,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dokter.export'))
            ->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename=data-dokter.xlsx');

        $this->actingAs($admin)
            ->get(route('admin.pasien.export'))
            ->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename=data-pasien.xlsx');

        $this->actingAs($admin)
            ->get(route('admin.obat.export'))
            ->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename=data-obat.xlsx');
    }

    public function test_dokter_dapat_export_jadwal_dan_riwayat_pasien_miliknya(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Mata',
            'keterangan' => 'Poli mata',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Mata lelah',
            'no_antrian' => 1,
        ]);

        Periksa::create([
            'id_daftar_poli' => $daftarPoli->id,
            'tgl_periksa' => now(),
            'catatan' => 'Istirahat cukup',
            'biaya_periksa' => 150000,
        ]);

        $this->actingAs($dokter)
            ->get(route('dokter.jadwal-periksa.export'))
            ->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename=jadwal-periksa.xlsx');

        $this->actingAs($dokter)
            ->get(route('dokter.riwayat-pasien.export'))
            ->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename=riwayat-pasien.xlsx');
    }

    private function makeUser(string $role, ?int $idPoli = null): User
    {
        static $counter = 1;

        $index = $counter++;

        return User::create([
            'nama' => ucfirst($role).' '.$index,
            'alamat' => 'Alamat '.$index,
            'no_ktp' => str_pad((string) (3600000000000000 + $index), 16, '0', STR_PAD_LEFT),
            'no_hp' => '08678'.str_pad((string) $index, 6, '0', STR_PAD_LEFT),
            'no_rm' => $role === 'pasien' ? 'RM-'.str_pad((string) $index, 6, '0', STR_PAD_LEFT) : null,
            'role' => $role,
            'id_poli' => $idPoli,
            'email' => $role.$index.'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}
