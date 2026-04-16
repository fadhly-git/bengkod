<?php

namespace Tests\Feature;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Pembayaran;
use App\Models\Periksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PembayaranWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_pasien_dapat_upload_bukti_pembayaran_dari_tagihan(): void
    {
        Storage::fake('public');

        [$pasien, $daftarPoli] = $this->seedPeriksaData();

        $response = $this->actingAs($pasien)
            ->post(route('pasien.pembayaran.store', $daftarPoli->id), [
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti.png', 800, 600),
            ]);

        $response->assertRedirect(route('pasien.pembayaran.index'));

        $pembayaran = Pembayaran::query()->where('id_daftar_poli', $daftarPoli->id)->first();

        $this->assertNotNull($pembayaran);
        $this->assertSame('pending', $pembayaran->status);
        $this->assertSame(150000, $pembayaran->jumlah_tagihan);
        Storage::disk('public')->assertExists($pembayaran->bukti_file);
    }

    public function test_admin_dapat_konfirmasi_pembayaran_menjadi_lunas(): void
    {
        Storage::fake('public');

        [$pasien, $daftarPoli, $admin] = $this->seedPeriksaData(withAdmin: true);

        $uploadResponse = $this->actingAs($pasien)
            ->post(route('pasien.pembayaran.store', $daftarPoli->id), [
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti.jpg', 900, 700),
            ]);

        $uploadResponse->assertRedirect(route('pasien.pembayaran.index'));

        $pembayaran = Pembayaran::query()->where('id_daftar_poli', $daftarPoli->id)->firstOrFail();

        $this->actingAs($admin)
            ->patch(route('admin.pembayaran.konfirmasi', $pembayaran->id))
            ->assertRedirect(route('admin.pembayaran.index'));

        $this->assertDatabaseHas('pembayaran', [
            'id' => $pembayaran->id,
            'status' => 'lunas',
            'verified_by' => $admin->id,
        ]);
    }

    public function test_pasien_tidak_bisa_upload_bukti_untuk_tagihan_pasien_lain(): void
    {
        Storage::fake('public');

        [, $daftarPoli] = $this->seedPeriksaData();
        $pasienLain = $this->makeUser('pasien');

        $this->actingAs($pasienLain)
            ->post(route('pasien.pembayaran.store', $daftarPoli->id), [
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti.png', 800, 600),
            ])
            ->assertForbidden();
    }

    public function test_admin_dapat_filter_pembayaran_berdasarkan_status(): void
    {
        Storage::fake('public');

        [$pasienA, $daftarPoliA, $admin] = $this->seedPeriksaData(withAdmin: true);
        [$pasienB, $daftarPoliB] = $this->seedPeriksaData();

        $this->actingAs($pasienA)
            ->post(route('pasien.pembayaran.store', $daftarPoliA->id), [
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti-a.jpg', 900, 700),
            ])
            ->assertRedirect(route('pasien.pembayaran.index'));

        $this->actingAs($pasienB)
            ->post(route('pasien.pembayaran.store', $daftarPoliB->id), [
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti-b.jpg', 900, 700),
            ])
            ->assertRedirect(route('pasien.pembayaran.index'));

        $pembayaranLunas = Pembayaran::query()->where('id_daftar_poli', $daftarPoliA->id)->firstOrFail();
        $pembayaranPending = Pembayaran::query()->where('id_daftar_poli', $daftarPoliB->id)->firstOrFail();

        $this->actingAs($admin)
            ->patch(route('admin.pembayaran.konfirmasi', $pembayaranLunas->id))
            ->assertRedirect(route('admin.pembayaran.index'));

        $this->actingAs($admin)
            ->get(route('admin.pembayaran.index', ['status' => 'pending']))
            ->assertOk()
            ->assertSee($pasienB->nama)
            ->assertDontSee($pasienA->nama);

        $this->actingAs($admin)
            ->get(route('admin.pembayaran.index', ['status' => 'lunas']))
            ->assertOk()
            ->assertSee($pasienA->nama)
            ->assertDontSee($pasienB->nama);
    }

    public function test_admin_index_default_menampilkan_pembayaran_pending(): void
    {
        Storage::fake('public');

        [$pasienPending, $daftarPending, $admin] = $this->seedPeriksaData(withAdmin: true);
        [$pasienLunas, $daftarLunas] = $this->seedPeriksaData();

        $this->actingAs($pasienPending)
            ->post(route('pasien.pembayaran.store', $daftarPending->id), [
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti-pending.jpg', 900, 700),
            ])
            ->assertRedirect(route('pasien.pembayaran.index'));

        $this->actingAs($pasienLunas)
            ->post(route('pasien.pembayaran.store', $daftarLunas->id), [
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti-lunas.jpg', 900, 700),
            ])
            ->assertRedirect(route('pasien.pembayaran.index'));

        $pembayaranLunas = Pembayaran::query()->where('id_daftar_poli', $daftarLunas->id)->firstOrFail();

        $this->actingAs($admin)
            ->patch(route('admin.pembayaran.konfirmasi', $pembayaranLunas->id))
            ->assertRedirect(route('admin.pembayaran.index'));

        $this->actingAs($admin)
            ->get(route('admin.pembayaran.index'))
            ->assertOk()
            ->assertSee($pasienPending->nama)
            ->assertDontSee($pasienLunas->nama);
    }

    private function seedPeriksaData(bool $withAdmin = false): array
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

        $daftarPoli = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => 'Pusing',
            'no_antrian' => 1,
        ]);

        Periksa::create([
            'id_daftar_poli' => $daftarPoli->id,
            'tgl_periksa' => now(),
            'catatan' => 'Butuh istirahat',
            'biaya_periksa' => 150000,
        ]);

        if ($withAdmin) {
            return [$pasien, $daftarPoli, $this->makeUser('admin')];
        }

        return [$pasien, $daftarPoli];
    }

    private function makeUser(string $role, ?int $idPoli = null): User
    {
        static $counter = 1;

        $index = $counter++;

        return User::create([
            'nama' => ucfirst($role).' '.$index,
            'alamat' => 'Alamat '.$index,
            'no_ktp' => str_pad((string) (3700000000000000 + $index), 16, '0', STR_PAD_LEFT),
            'no_hp' => '08789'.str_pad((string) $index, 6, '0', STR_PAD_LEFT),
            'no_rm' => $role === 'pasien' ? 'RM-'.str_pad((string) $index, 6, '0', STR_PAD_LEFT) : null,
            'role' => $role,
            'id_poli' => $idPoli,
            'email' => $role.$index.'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}
