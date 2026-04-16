<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminDokterExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function collection(): Collection
    {
        return User::query()
            ->with('poli')
            ->where('role', 'dokter')
            ->orderBy('nama')
            ->get()
            ->map(fn (User $dokter) => [
                'nama' => $dokter->nama,
                'email' => $dokter->email,
                'no_hp' => $dokter->no_hp,
                'no_ktp' => $dokter->no_ktp,
                'poli' => $dokter->poli?->nama_poli ?? '-',
                'alamat' => $dokter->alamat,
            ]);
    }

    public function headings(): array
    {
        return ['Nama', 'Email', 'No. HP', 'No. KTP', 'Poli', 'Alamat'];
    }
}
