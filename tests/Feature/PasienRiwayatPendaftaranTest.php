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

class PasienRiwayatPendaftaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_riwayat_menampilkan_semua_pendaftaran_dan_status_pemeriksaan(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Umum',
            'keterangan' => 'Poli umum',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwalA = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $jadwalB = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'selasa',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        $daftarSelesai = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwalA->id,
            'keluhan' => 'Batuk',
            'no_antrian' => 1,
        ]);

        $daftarMenunggu = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwalB->id,
            'keluhan' => 'Pusing',
            'no_antrian' => 2,
        ]);

        $periksa = Periksa::create([
            'id_daftar_poli' => $daftarSelesai->id,
            'tgl_periksa' => now(),
            'catatan' => 'Rawat jalan',
            'biaya_periksa' => 150000,
        ]);

        $response = $this->actingAs($pasien)->get(route('pasien.riwayat.index'));

        $response->assertOk();
        $response->assertSee('Riwayat Pendaftaran Poli');
        $response->assertSee('Sudah diperiksa');
        $response->assertSee('Menunggu pemeriksaan');
        $response->assertSee((string) $daftarSelesai->no_antrian);
        $response->assertSee((string) $daftarMenunggu->no_antrian);
        $response->assertSee(route('pasien.riwayat.show', $periksa->id), false);
    }

    private function makeUser(string $role, ?int $idPoli = null): User
    {
        static $counter = 1;

        $index = $counter++;

        return User::create([
            'nama' => ucfirst($role).' '.$index,
            'alamat' => 'Alamat '.$index,
            'no_ktp' => str_pad((string) (3800000000000000 + $index), 16, '0', STR_PAD_LEFT),
            'no_hp' => '08890'.str_pad((string) $index, 6, '0', STR_PAD_LEFT),
            'no_rm' => $role === 'pasien' ? 'RM-'.str_pad((string) $index, 6, '0', STR_PAD_LEFT) : null,
            'role' => $role,
            'id_poli' => $idPoli,
            'email' => $role.$index.'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}
