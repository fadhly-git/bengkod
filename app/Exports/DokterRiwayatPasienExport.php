<?php

namespace App\Exports;

use App\Models\Periksa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DokterRiwayatPasienExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function __construct(private readonly int $dokterId) {}

    public function collection(): Collection
    {
        return Periksa::query()
            ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa'])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($query) {
                $query->where('id_dokter', $this->dokterId);
            })
            ->latest('tgl_periksa')
            ->get()
            ->map(fn (Periksa $periksa) => [
                'tanggal_periksa' => optional($periksa->tgl_periksa)->format('d-m-Y H:i'),
                'nama_pasien' => $periksa->daftarPoli?->pasien?->nama ?? '-',
                'no_rm' => $periksa->daftarPoli?->pasien?->no_rm ?? '-',
                'no_antrian' => $periksa->daftarPoli?->no_antrian ?? '-',
                'catatan' => $periksa->catatan ?? '-',
                'biaya_periksa' => $periksa->biaya_periksa,
            ]);
    }

    public function headings(): array
    {
        return ['Tanggal Periksa', 'Nama Pasien', 'No. RM', 'No. Antrean', 'Catatan', 'Biaya Periksa'];
    }
}
