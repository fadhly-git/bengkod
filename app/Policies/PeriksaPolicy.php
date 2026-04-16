<?php

namespace App\Policies;

use App\Models\Periksa;
use App\Models\User;

class PeriksaPolicy
{
    public function view(User $user, Periksa $periksa): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        $daftarPoli = $periksa->daftarPoli;

        if (! $daftarPoli) {
            return false;
        }

        if ($user->role === 'pasien') {
            return $daftarPoli->id_pasien === $user->id;
        }

        if ($user->role === 'dokter') {
            return (int) $daftarPoli->jadwalPeriksa?->id_dokter === $user->id;
        }

        return false;
    }
}
