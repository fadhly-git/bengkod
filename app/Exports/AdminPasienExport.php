<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminPasienExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function collection(): Collection
    {
        return User::query()
            ->where('role', 'pasien')
            ->orderBy('nama')
            ->get()
            ->map(fn (User $pasien) => [
                'nama' => $pasien->nama,
                'email' => $pasien->email,
                'no_hp' => $pasien->no_hp,
                'no_ktp' => $pasien->no_ktp,
                'no_rm' => $pasien->no_rm,
                'alamat' => $pasien->alamat,
            ]);
    }

    public function headings(): array
    {
        return ['Nama', 'Email', 'No. HP', 'No. KTP', 'No. RM', 'Alamat'];
    }
}
