<?php

namespace Tests\Unit;

use App\Models\Obat;
use PHPUnit\Framework\TestCase;

class ObatStockStatusTest extends TestCase
{
    public function test_obat_out_of_stock_when_stok_zero_or_less(): void
    {
        $obatKosong = new Obat(['stok' => 0]);
        $obatNegatif = new Obat(['stok' => -1]);
        $obatAda = new Obat(['stok' => 2]);

        $this->assertTrue($obatKosong->isOutOfStock());
        $this->assertTrue($obatNegatif->isOutOfStock());
        $this->assertFalse($obatAda->isOutOfStock());
    }

    public function test_obat_low_stock_uses_threshold_constant(): void
    {
        $threshold = Obat::LOW_STOCK_THRESHOLD;

        $obatKosong = new Obat(['stok' => 0]);
        $obatRendah = new Obat(['stok' => $threshold]);
        $obatAman = new Obat(['stok' => $threshold + 1]);

        $this->assertFalse($obatKosong->isLowStock());
        $this->assertTrue($obatRendah->isLowStock());
        $this->assertFalse($obatAman->isLowStock());
    }
}
