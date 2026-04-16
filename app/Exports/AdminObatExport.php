<?php

namespace App\Exports;

use App\Models\Obat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminObatExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function collection(): Collection
    {
        return Obat::query()
            ->orderBy('nama_obat')
            ->get()
            ->map(fn (Obat $obat) => [
                'nama_obat' => $obat->nama_obat,
                'kemasan' => $obat->kemasan,
                'harga' => $obat->harga,
                'stok' => $obat->stok,
            ]);
    }

    public function headings(): array
    {
        return ['Nama Obat', 'Kemasan', 'Harga', 'Stok'];
    }
}
