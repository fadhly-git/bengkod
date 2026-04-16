<?php

namespace App\Actions\DaftarPoli;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;

class GenerateNomorAntrian
{
    public function execute(int $idJadwal): int
    {
        // Lock jadwal row so concurrent registrations for the same schedule are serialized.
        JadwalPeriksa::query()->whereKey($idJadwal)->lockForUpdate()->firstOrFail();

        $lastQueueNumber = DaftarPoli::query()
            ->where('id_jadwal', $idJadwal)
            ->max('no_antrian');

        return ((int) $lastQueueNumber) + 1;
    }
}
