<?php

namespace App\Policies;

use App\Models\DaftarPoli;
use App\Models\User;

class DaftarPoliPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'pasien';
    }

    public function view(User $user, DaftarPoli $daftarPoli): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'pasien') {
            return $daftarPoli->id_pasien === $user->id;
        }

        if ($user->role === 'dokter') {
            return (int) $daftarPoli->jadwalPeriksa?->id_dokter === $user->id;
        }

        return false;
    }

    public function periksa(User $user, DaftarPoli $daftarPoli): bool
    {
        return $user->role === 'dokter'
            && (int) $daftarPoli->jadwalPeriksa?->id_dokter === $user->id;
    }
}
