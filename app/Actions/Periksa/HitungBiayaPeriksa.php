<?php

namespace App\Actions\Periksa;

use App\Models\Obat;

class HitungBiayaPeriksa
{
    public function execute(array $obatIds): int
    {
        $baseFee = 20000;

        $obatCost = Obat::query()
            ->whereIn('id', $obatIds)
            ->sum('harga');

        return $baseFee + (int) $obatCost;
    }
}
