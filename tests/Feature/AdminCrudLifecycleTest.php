<?php

namespace Tests\Feature;

use App\Models\Obat;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCrudLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_update_dan_hapus_poli(): void
    {
        $admin = $this->makeAdmin();

        $poli = Poli::create([
            'nama_poli' => 'Mata',
            'keterangan' => 'Poli mata',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.polis.update', $poli->id), [
                'nama_poli' => 'Mata Lanjutan',
                'keterangan' => 'Poli mata lanjutan',
            ])
            ->assertRedirect(route('admin.polis.index'));

        $this->assertDatabaseHas('poli', [
            'id' => $poli->id,
            'nama_poli' => 'Mata Lanjutan',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.polis.destroy', $poli->id))
            ->assertRedirect(route('admin.polis.index'));

        $this->assertDatabaseMissing('poli', [
            'id' => $poli->id,
        ]);
    }

    public function test_admin_dapat_update_dan_hapus_obat(): void
    {
        $admin = $this->makeAdmin();

        $obat = Obat::create([
            'nama_obat' => 'Ibuprofen',
            'kemasan' => 'Tablet',
            'harga' => 12000,
            'stok' => 10,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.obat.update', $obat->id), [
                'nama_obat' => 'Ibuprofen Forte',
                'kemasan' => 'Tablet 400mg',
                'harga' => 15000,
                'stok' => 8,
            ])
            ->assertRedirect(route('admin.obat.index'));

        $this->assertDatabaseHas('obat', [
            'id' => $obat->id,
            'nama_obat' => 'Ibuprofen Forte',
            'harga' => 15000,
            'stok' => 8,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.obat.destroy', $obat->id))
            ->assertRedirect(route('admin.obat.index'));

        $this->assertDatabaseMissing('obat', [
            'id' => $obat->id,
        ]);
    }

    private function makeAdmin(): User
    {
        static $counter = 1;
        $index = $counter++;

        return User::create([
            'nama' => 'Admin '.$index,
            'alamat' => 'Alamat admin '.$index,
            'no_ktp' => str_pad((string) (3400000000000000 + $index), 16, '0', STR_PAD_LEFT),
            'no_hp' => '08456'.str_pad((string) $index, 6, '0', STR_PAD_LEFT),
            'no_rm' => null,
            'role' => 'admin',
            'id_poli' => null,
            'email' => 'admin'.$index.'@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}
