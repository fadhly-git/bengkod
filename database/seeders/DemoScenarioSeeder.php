<?php

namespace Database\Seeders;

use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Pembayaran;
use App\Models\Periksa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DemoScenarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->resetTransactionalData();

            $users = User::query()->whereIn('email', [
                'admin@mail.to',
                'dokter.umum@mail.to',
                'dokter.gigi@mail.to',
                'dokter.mata@mail.to',
                'dokter.anak@mail.to',
                'pasien@mail.to',
                'pasien.demo@mail.to',
            ])->get()->keyBy('email');

            $obats = Obat::query()->get()->keyBy('nama_obat');

            $jadwalUmum = $this->findJadwalForDokter((int) $users['dokter.umum@mail.to']->id, 'senin');
            $jadwalGigi = $this->findJadwalForDokter((int) $users['dokter.gigi@mail.to']->id, 'senin');
            $jadwalMata = $this->findJadwalForDokter((int) $users['dokter.mata@mail.to']->id, 'kamis');
            $jadwalAnak = $this->findJadwalForDokter((int) $users['dokter.anak@mail.to']->id, 'kamis');

            $pasienUtamaId = (int) $users['pasien@mail.to']->id;
            $pasienDemoId = (int) $users['pasien.demo@mail.to']->id;
            $adminId = (int) $users['admin@mail.to']->id;

            // Jadwal Umum: nomor dilayani sudah berjalan, plus satu antrean aktif.
            $umumSelesai1 = $this->createDaftarPoli($pasienDemoId, (int) $jadwalUmum->id, 'Demam ringan', 1, now()->subDays(4));
            $this->createPeriksaWithObat($umumSelesai1, 'Perbanyak istirahat dan hidrasi.', now()->subDays(4), [
                (int) $obats['Paracetamol']->id,
            ]);

            $umumSelesai2 = $this->createDaftarPoli($pasienDemoId, (int) $jadwalUmum->id, 'Sakit kepala', 2, now()->subDays(2));
            $this->createPeriksaWithObat($umumSelesai2, 'Observasi 3 hari.', now()->subDays(2), [
                (int) $obats['Paracetamol']->id,
                (int) $obats['Ibuprofen']->id,
            ]);

            $this->createDaftarPoli($pasienUtamaId, (int) $jadwalUmum->id, 'Batuk belum reda', 3, now()->subHours(5));

            // Jadwal Gigi: pasien utama sudah diperiksa dan pembayaran lunas.
            $gigiSelesai = $this->createDaftarPoli($pasienUtamaId, (int) $jadwalGigi->id, 'Gusi nyeri', 1, now()->subDays(3));
            $periksaGigi = $this->createPeriksaWithObat($gigiSelesai, 'Peradangan ringan pada gusi.', now()->subDays(3), [
                (int) $obats['Amoxicillin']->id,
                (int) $obats['Paracetamol']->id,
            ]);

            $this->createPembayaranLunas($gigiSelesai, (int) $periksaGigi->biaya_periksa, $adminId, now()->subDays(3));

            // Jadwal Mata: pasien utama sudah diperiksa, pembayaran pending.
            $mataSelesai = $this->createDaftarPoli($pasienUtamaId, (int) $jadwalMata->id, 'Mata terasa kering', 1, now()->subDay());
            $periksaMata = $this->createPeriksaWithObat($mataSelesai, 'Gunakan tetes mata 3x sehari.', now()->subDay(), [
                (int) $obats['Loratadine']->id,
            ]);

            $this->createPembayaranPending($mataSelesai, (int) $periksaMata->biaya_periksa, now()->subDay());

            // Jadwal Anak: antrean aktif lain untuk pasien demo.
            $this->createDaftarPoli($pasienDemoId, (int) $jadwalAnak->id, 'Kontrol pasca demam', 1, now()->subHours(3));

            $this->setDemoStokObat();
        });
    }

    private function resetTransactionalData(): void
    {
        DetailPeriksa::query()->delete();
        Periksa::query()->delete();
        Pembayaran::query()->delete();
        DaftarPoli::query()->delete();
    }

    private function findJadwalForDokter(int $dokterId, string $hari): JadwalPeriksa
    {
        return JadwalPeriksa::query()
            ->where('id_dokter', $dokterId)
            ->where('hari', $hari)
            ->orderBy('jam_mulai')
            ->firstOrFail();
    }

    private function createDaftarPoli(int $pasienId, int $jadwalId, string $keluhan, int $noAntrian, $createdAt): DaftarPoli
    {
        return DaftarPoli::query()->create([
            'id_pasien' => $pasienId,
            'id_jadwal' => $jadwalId,
            'keluhan' => $keluhan,
            'no_antrian' => $noAntrian,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    /**
     * @param list<int> $obatIds
     */
    private function createPeriksaWithObat(DaftarPoli $daftarPoli, string $catatan, $tglPeriksa, array $obatIds): Periksa
    {
        $biayaObat = Obat::query()->whereIn('id', $obatIds)->sum('harga');

        $periksa = Periksa::query()->create([
            'id_daftar_poli' => $daftarPoli->id,
            'tgl_periksa' => $tglPeriksa,
            'catatan' => $catatan,
            'biaya_periksa' => 20000 + (int) $biayaObat,
            'created_at' => $tglPeriksa,
            'updated_at' => $tglPeriksa,
        ]);

        foreach (array_unique($obatIds) as $obatId) {
            DetailPeriksa::query()->create([
                'id_periksa' => $periksa->id,
                'id_obat' => $obatId,
                'created_at' => $tglPeriksa,
                'updated_at' => $tglPeriksa,
            ]);
        }

        return $periksa;
    }

    private function createPembayaranLunas(DaftarPoli $daftarPoli, int $tagihan, int $verifiedBy, $paidAt): void
    {
        Pembayaran::query()->create([
            'id_daftar_poli' => $daftarPoli->id,
            'jumlah_tagihan' => $tagihan,
            'status' => 'lunas',
            'bukti_file' => $this->createPlaceholderBukti('lunas-'.$daftarPoli->id),
            'tanggal_pembayaran' => $paidAt,
            'tanggal_verifikasi' => $paidAt->copy()->addHours(2),
            'verified_by' => $verifiedBy,
            'created_at' => $paidAt,
            'updated_at' => $paidAt,
        ]);
    }

    private function createPembayaranPending(DaftarPoli $daftarPoli, int $tagihan, $paidAt): void
    {
        Pembayaran::query()->create([
            'id_daftar_poli' => $daftarPoli->id,
            'jumlah_tagihan' => $tagihan,
            'status' => 'pending',
            'bukti_file' => $this->createPlaceholderBukti('pending-'.$daftarPoli->id),
            'tanggal_pembayaran' => $paidAt,
            'tanggal_verifikasi' => null,
            'verified_by' => null,
            'created_at' => $paidAt,
            'updated_at' => $paidAt,
        ]);
    }

    private function createPlaceholderBukti(string $name): string
    {
        $path = 'pembayaran/'.$name.'.png';

        // 1x1 transparent PNG for demo links in payment verification pages.
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9sXn0i8AAAAASUVORK5CYII=');

        Storage::disk('public')->put($path, $png ?: '');

        return $path;
    }

    private function setDemoStokObat(): void
    {
        $stokByNama = [
            'Amoxicillin' => 24,
            'Paracetamol' => 4,
            'Ibuprofen' => 12,
            'Cetirizine' => 7,
            'Amlodipine' => 16,
            'Omeprazole' => 9,
            'Loratadine' => 2,
            'Salbutamol' => 0,
            'Diclofenac' => 6,
        ];

        foreach ($stokByNama as $namaObat => $stok) {
            Obat::query()->where('nama_obat', $namaObat)->update(['stok' => $stok]);
        }
    }
}
