<?php

namespace Tests\Feature;

use App\Events\AntrianDilayaniUpdated;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OperationalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();
    }

    public function test_pasien_dapat_mendaftar_poli_dan_mendapat_nomor_antrian(): void
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

        $this->actingAs($pasien)
            ->post(route('pasien.daftar-poli.store'), [
                'id_jadwal' => $jadwal->id,
                'keluhan' => 'Demam dan batuk',
            ])
            ->assertRedirect(route('pasien.daftar-poli.index'));

        $this->assertDatabaseHas('daftar_poli', [
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'no_antrian' => 1,
            'keluhan' => 'Demam dan batuk',
        ]);
    }

    public function test_pasien_tidak_bisa_mendaftar_dua_kali_pada_jadwal_yang_sama(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Umum',
            'keterangan' => 'Poli umum',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $this->actingAs($pasien)
            ->post(route('pasien.daftar-poli.store'), [
                'id_jadwal' => $jadwal->id,
                'keluhan' => 'Batuk',
            ])
            ->assertRedirect(route('pasien.daftar-poli.index'));

        $this->actingAs($pasien)
            ->post(route('pasien.daftar-poli.store'), [
                'id_jadwal' => $jadwal->id,
                'keluhan' => 'Batuk lagi',
            ])
            ->assertSessionHasErrors(['id_jadwal']);

        $this->assertDatabaseCount('daftar_poli', 1);
    }

    public function test_pasien_tidak_bisa_mendaftar_ke_jadwal_lain_selama_masih_aktif(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Umum',
            'keterangan' => 'Poli umum',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwalPertama = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $jadwalKedua = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'selasa',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        $this->actingAs($pasien)
            ->post(route('pasien.daftar-poli.store'), [
                'id_jadwal' => $jadwalPertama->id,
                'keluhan' => 'Demam',
            ])
            ->assertRedirect(route('pasien.daftar-poli.index'));

        $this->actingAs($pasien)
            ->post(route('pasien.daftar-poli.store'), [
                'id_jadwal' => $jadwalKedua->id,
                'keluhan' => 'Pusing',
            ])
            ->assertSessionHasErrors(['id_jadwal']);

        $this->assertDatabaseCount('daftar_poli', 1);
    }

    public function test_pasien_bisa_daftar_lagi_setelah_pemeriksaan_sebelumnya_selesai(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Umum',
            'keterangan' => 'Poli umum',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwalPertama = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $jadwalKedua = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'kamis',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        $this->actingAs($pasien)
            ->post(route('pasien.daftar-poli.store'), [
                'id_jadwal' => $jadwalPertama->id,
                'keluhan' => 'Batuk',
            ])
            ->assertRedirect(route('pasien.daftar-poli.index'));

        $daftarPertama = DaftarPoli::query()->where('id_jadwal', $jadwalPertama->id)->firstOrFail();

        Periksa::create([
            'id_daftar_poli' => $daftarPertama->id,
            'tgl_periksa' => now(),
            'catatan' => 'Sudah diperiksa',
            'biaya_periksa' => 150000,
        ]);

        $this->actingAs($pasien)
            ->post(route('pasien.daftar-poli.store'), [
                'id_jadwal' => $jadwalKedua->id,
                'keluhan' => 'Kontrol lanjutan',
            ])
            ->assertRedirect(route('pasien.daftar-poli.index'));

        $this->assertDatabaseCount('daftar_poli', 2);
        $this->assertDatabaseHas('daftar_poli', [
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwalKedua->id,
        ]);
    }

    public function test_pasien_bisa_mendaftar_ulang_pada_jadwal_yang_sama_setelah_selesai_diperiksa(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Umum',
            'keterangan' => 'Poli umum',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'jumat',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $this->actingAs($pasien)
            ->post(route('pasien.daftar-poli.store'), [
                'id_jadwal' => $jadwal->id,
                'keluhan' => 'Kontrol pertama',
            ])
            ->assertRedirect(route('pasien.daftar-poli.index'));

        $daftarPertama = DaftarPoli::query()->where('id_pasien', $pasien->id)->latest('id')->firstOrFail();

        Periksa::create([
            'id_daftar_poli' => $daftarPertama->id,
            'tgl_periksa' => now(),
            'catatan' => 'Selesai diperiksa',
            'biaya_periksa' => 150000,
        ]);

        $this->actingAs($pasien)
            ->post(route('pasien.daftar-poli.store'), [
                'id_jadwal' => $jadwal->id,
                'keluhan' => 'Kontrol kedua',
            ])
            ->assertRedirect(route('pasien.daftar-poli.index'));

        $this->assertDatabaseCount('daftar_poli', 2);
    }

    public function test_dokter_dapat_menyimpan_pemeriksaan_dan_resep_obat(): void
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
            'no_antrian' => 1,
        ]);

        $obat1 = Obat::create([
            'nama_obat' => 'Amoxicillin',
            'kemasan' => 'Tablet',
            'harga' => 25000,
            'stok' => 10,
        ]);

        $obat2 = Obat::create([
            'nama_obat' => 'Paracetamol',
            'kemasan' => 'Tablet',
            'harga' => 15000,
            'stok' => 8,
        ]);

        $this->actingAs($dokter)
            ->post(route('dokter.pemeriksaan.store', $daftarPoli->id), [
                'catatan' => 'Ada peradangan ringan',
                'obat_ids' => [$obat1->id, $obat2->id],
            ])
            ->assertRedirect(route('dokter.pemeriksaan.show', $daftarPoli->id));

        $periksa = Periksa::query()->where('id_daftar_poli', $daftarPoli->id)->firstOrFail();

        $this->assertSame('Ada peradangan ringan', $periksa->catatan);
        $this->assertSame(60000, $periksa->biaya_periksa);
        $this->assertDatabaseHas('detail_periksa', [
            'id_periksa' => $periksa->id,
            'id_obat' => $obat1->id,
        ]);
        $this->assertDatabaseHas('detail_periksa', [
            'id_periksa' => $periksa->id,
            'id_obat' => $obat2->id,
        ]);
        $this->assertDatabaseHas('obat', [
            'id' => $obat1->id,
            'stok' => 9,
        ]);
        $this->assertDatabaseHas('obat', [
            'id' => $obat2->id,
            'stok' => 7,
        ]);
    }

    public function test_dokter_dapat_menyimpan_pemeriksaan_tanpa_resep_obat(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'THT',
            'keterangan' => 'Poli THT',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'kamis',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Sakit tenggorokan',
            'no_antrian' => 1,
        ]);

        $this->actingAs($dokter)
            ->post(route('dokter.pemeriksaan.store', $daftarPoli->id), [
                'catatan' => 'Cukup istirahat dan minum air hangat',
                'obat_ids' => [],
            ])
            ->assertRedirect(route('dokter.pemeriksaan.show', $daftarPoli->id));

        $periksa = Periksa::query()->where('id_daftar_poli', $daftarPoli->id)->firstOrFail();

        $this->assertSame(20000, $periksa->biaya_periksa);
        $this->assertDatabaseMissing('detail_periksa', [
            'id_periksa' => $periksa->id,
        ]);
    }

    public function test_simpan_pemeriksaan_dibatalkan_jika_stok_obat_tidak_cukup(): void
    {
        $poli = Poli::create([
            'nama_poli' => 'Mata',
            'keterangan' => 'Poli mata',
        ]);

        $dokter = $this->makeUser('dokter', $poli->id);
        $pasien = $this->makeUser('pasien');

        $jadwal = JadwalPeriksa::create([
            'id_dokter' => $dokter->id,
            'hari' => 'jumat',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
        ]);

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Mata perih',
            'no_antrian' => 1,
        ]);

        $obatHabis = Obat::create([
            'nama_obat' => 'Tetes Mata A',
            'kemasan' => 'Botol',
            'harga' => 18000,
            'stok' => 0,
        ]);

        $this->from(route('dokter.pemeriksaan.show', $daftarPoli->id))
            ->actingAs($dokter)
            ->post(route('dokter.pemeriksaan.store', $daftarPoli->id), [
                'catatan' => 'Iritasi ringan',
                'obat_ids' => [$obatHabis->id],
            ])
            ->assertRedirect(route('dokter.pemeriksaan.show', $daftarPoli->id))
            ->assertSessionHasErrors(['obat_ids']);

        $this->assertDatabaseMissing('periksa', [
            'id_daftar_poli' => $daftarPoli->id,
        ]);
        $this->assertDatabaseMissing('detail_periksa', [
            'id_obat' => $obatHabis->id,
        ]);
        $this->assertDatabaseHas('obat', [
            'id' => $obatHabis->id,
            'stok' => 0,
        ]);
    }

    public function test_dokter_menyiarkan_update_antrian_setelah_simpan_pemeriksaan(): void
    {
        Event::fake([AntrianDilayaniUpdated::class]);

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

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Pusing',
            'no_antrian' => 1,
        ]);

        $this->actingAs($dokter)
            ->post(route('dokter.pemeriksaan.store', $daftarPoli->id), [
                'catatan' => 'Rawat jalan',
                'obat_ids' => [],
            ])
            ->assertRedirect(route('dokter.pemeriksaan.show', $daftarPoli->id));

        Event::assertDispatched(AntrianDilayaniUpdated::class, function (AntrianDilayaniUpdated $event) use ($jadwal) {
            return $event->idJadwal === $jadwal->id
                && $event->nomorDilayani === 1;
        });
    }

    private function makeUser(string $role, ?int $idPoli = null): User
    {
        static $counter = 1;

        $index = $counter++;

        return User::create([
            'nama' => ucfirst($role).' '.$index,
            'alamat' => 'Alamat '.$index,
            'no_ktp' => str_pad((string) (3300000000000000 + $index), 16, '0', STR_PAD_LEFT),
            'no_hp' => '08345'.str_pad((string) $index, 6, '0', STR_PAD_LEFT),
            'no_rm' => $role === 'pasien' ? 'RM-'.str_pad((string) $index, 6, '0', STR_PAD_LEFT) : null,
            'role' => $role,
            'id_poli' => $idPoli,
            'email' => $role.$index.'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}
