<?php

namespace App\Actions\Periksa;

use App\Models\DetailPeriksa;

class SinkronResepObat
{
    public function execute(int $idPeriksa, array $obatIds): void
    {
        $uniqueObatIds = array_values(array_unique(array_map('intval', $obatIds)));

        DetailPeriksa::query()->where('id_periksa', $idPeriksa)->delete();

        if ($uniqueObatIds === []) {
            return;
        }

        $rows = array_map(function (int $obatId) use ($idPeriksa): array {
            return [
                'id_periksa' => $idPeriksa,
                'id_obat' => $obatId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $uniqueObatIds);

        DetailPeriksa::insert($rows);
    }
}
