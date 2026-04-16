<?php

namespace App\Policies;

use App\Models\JadwalPeriksa;
use App\Models\User;

class JadwalPeriksaPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'dokter';
    }

    public function update(User $user, JadwalPeriksa $jadwalPeriksa): bool
    {
        return $user->role === 'dokter' && $jadwalPeriksa->id_dokter === $user->id;
    }

    public function delete(User $user, JadwalPeriksa $jadwalPeriksa): bool
    {
        return $this->update($user, $jadwalPeriksa);
    }
}
