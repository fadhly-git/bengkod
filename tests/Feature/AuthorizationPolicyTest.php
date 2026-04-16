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

class AuthorizationPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_dokter_tidak_bisa_edit_jadwal_milik_dokter_lain(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Penyakit Dalam',
            'keterangan' => 'Poli umum penyakit dalam',
        ]);

        $dokterLogin = $this->makeUser('dokter', $poli->id);
        $dokterPemilikJadwal = $this->makeUser('dokter', $poli->id);

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokterPemilikJadwal->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $this->actingAs($dokterLogin)
            ->get(route('dokter.jadwal-periksa.edit', $jadwal->id))
            ->assertForbidden();
    }

    public function test_dokter_tidak_bisa_akses_pemeriksaan_milik_dokter_lain(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Jantung',
            'keterangan' => 'Poli spesialis jantung',
        ]);

        $dokterLogin = $this->makeUser('dokter', $poli->id);
        $dokterPemilikJadwal = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokterPemilikJadwal->id,
            'hari' => 'selasa',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
        ]);

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Nyeri dada',
            'no_antrian' => 1,
        ]);

        $this->actingAs($dokterLogin)
            ->get(route('dokter.pemeriksaan.show', $daftarPoli->id))
            ->assertForbidden();

        $this->actingAs($dokterLogin)
            ->post(route('dokter.pemeriksaan.store', $daftarPoli->id), [
                'catatan' => 'Pemeriksaan awal',
            ])
            ->assertForbidden();
    }

    public function test_pasien_tidak_bisa_melihat_riwayat_pasien_lain(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Anak',
            'keterangan' => 'Poli anak',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasienPemilik = $this->makeUser('pasien');
        $pasienLain = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'rabu',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasienPemilik->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Demam',
            'no_antrian' => 1,
        ]);

        $periksa = Periksa::create([
            'id_daftar_poli' => $daftarPoli->id,
            'tgl_periksa' => now(),
            'catatan' => 'Istirahat dan hidrasi',
            'biaya_periksa' => 150000,
        ]);

        $this->actingAs($pasienLain)
            ->get(route('pasien.riwayat.show', $periksa->id))
            ->assertForbidden();
    }

    public function test_dokter_pemilik_bisa_membuka_halaman_pemeriksaan(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Saraf',
            'keterangan' => 'Poli saraf',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'kamis',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '09:00:00',
        ]);

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Sakit kepala',
            'no_antrian' => 1,
        ]);

        $this->actingAs($dokter)
            ->get(route('dokter.pemeriksaan.show', $daftarPoli->id))
            ->assertOk();
    }

    public function test_pasien_pemilik_bisa_melihat_riwayat_sendiri(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Kulit',
            'keterangan' => 'Poli kulit',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'jumat',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
        ]);

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Gatal',
            'no_antrian' => 1,
        ]);

        $periksa = Periksa::create([
            'id_daftar_poli' => $daftarPoli->id,
            'tgl_periksa' => now(),
            'catatan' => 'Gunakan salep',
            'biaya_periksa' => 120000,
        ]);

        $this->actingAs($pasien)
            ->get(route('pasien.riwayat.show', $periksa->id))
            ->assertOk();
    }

    private function makeUser(string $role, ?int $idPoli = null): User
    {
        static $counter = 1;

        $index = $counter++;

        return User::create([
            'nama' => ucfirst($role) . ' ' . $index,
            'alamat' => 'Alamat ' . $index,
            'no_ktp' => str_pad((string) (3100000000000000 + $index), 16, '0', STR_PAD_LEFT),
            'no_hp' => '08123' . str_pad((string) $index, 6, '0', STR_PAD_LEFT),
            'no_rm' => $role === 'pasien' ? 'RM-' . str_pad((string) $index, 6, '0', STR_PAD_LEFT) : null,
            'role' => $role,
            'id_poli' => $idPoli,
            'email' => $role . $index . '@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}
