<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    public const LOW_STOCK_THRESHOLD = 5;

    protected $table = 'obat';

    protected $fillable = [
        'nama_obat',
        'kemasan',
        'harga',
        'stok',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
    ];

    public function detailPeriksas()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }

    public function isOutOfStock(): bool
    {
        return (int) $this->stok <= 0;
    }

    public function isLowStock(): bool
    {
        $stok = (int) $this->stok;

        return $stok > 0 && $stok <= self::LOW_STOCK_THRESHOLD;
    }
}
