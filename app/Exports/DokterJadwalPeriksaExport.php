<?php

namespace App\Exports;

use App\Models\JadwalPeriksa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DokterJadwalPeriksaExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function __construct(private readonly int $dokterId) {}

    public function collection(): Collection
    {
        return JadwalPeriksa::query()
            ->where('id_dokter', $this->dokterId)
            ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->orderBy('jam_mulai')
            ->get()
            ->map(fn (JadwalPeriksa $jadwal) => [
                'hari' => ucfirst($jadwal->hari),
                'jam_mulai' => substr((string) $jadwal->jam_mulai, 0, 5),
                'jam_selesai' => substr((string) $jadwal->jam_selesai, 0, 5),
            ]);
    }

    public function headings(): array
    {
        return ['Hari', 'Jam Mulai', 'Jam Selesai'];
    }
}
