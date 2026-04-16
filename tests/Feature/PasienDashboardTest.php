<?php

namespace Tests\Feature;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasienDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_menampilkan_banner_antrean_aktif_jika_pasien_belum_diperiksa(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Umum',
            'keterangan' => 'Poli umum',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Demam',
            'no_antrian' => 5,
        ]);

        $response = $this->actingAs($pasien)
            ->get(route('pasien.dashboard'));

        $response->assertOk();
        $response->assertSee('Antrean Aktif Anda');
        $response->assertSee('No. Antrean Anda');
        $response->assertSee('5');
    }

    public function test_dashboard_menampilkan_pesan_kosong_jika_tidak_ada_antrean_aktif(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Gigi',
            'keterangan' => 'Poli gigi',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'selasa',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
        ]);

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Sakit gigi',
            'no_antrian' => 2,
        ]);

        Periksa::create([
            'id_daftar_poli' => $daftarPoli->id,
            'tgl_periksa' => now(),
            'catatan' => 'Sudah diperiksa',
            'biaya_periksa' => 150000,
        ]);

        $response = $this->actingAs($pasien)
            ->get(route('pasien.dashboard'));

        $response->assertOk();
        $response->assertDontSee('Antrean Aktif Anda');
        $response->assertSee('belum memiliki antrean aktif', false);
    }

    private function makeUser(string $role, ?int $idPoli = null): User
    {
        static $counter = 1;

        $index = $counter++;

        return User::create([
            'nama' => ucfirst($role).' '.$index,
            'alamat' => 'Alamat '.$index,
            'no_ktp' => str_pad((string) (3500000000000000 + $index), 16, '0', STR_PAD_LEFT),
            'no_hp' => '08567'.str_pad((string) $index, 6, '0', STR_PAD_LEFT),
            'no_rm' => $role === 'pasien' ? 'RM-'.str_pad((string) $index, 6, '0', STR_PAD_LEFT) : null,
            'role' => $role,
            'id_poli' => $idPoli,
            'email' => $role.$index.'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}
