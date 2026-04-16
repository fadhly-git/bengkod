<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAndAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_diarahkan_ke_login_saat_akses_dashboard_admin(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_pasien_ditolak_saat_akses_dashboard_admin(): void
    {
        $pasien = $this->makeUser('pasien');

        $this->actingAs($pasien)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_bisa_menambah_data_poli(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)
            ->post(route('admin.polis.store'), [
                'nama_poli' => 'Paru',
                'keterangan' => 'Poli paru dan pernapasan',
            ])
            ->assertRedirect(route('admin.polis.index'));

        $this->assertDatabaseHas('poli', [
            'nama_poli' => 'Paru',
        ]);
    }

    private function makeUser(string $role): User
    {
        static $counter = 1;

        $index = $counter++;

        return User::create([
            'nama' => ucfirst($role) . ' ' . $index,
            'alamat' => 'Alamat ' . $index,
            'no_ktp' => str_pad((string) (3200000000000000 + $index), 16, '0', STR_PAD_LEFT),
            'no_hp' => '08234' . str_pad((string) $index, 6, '0', STR_PAD_LEFT),
            'no_rm' => $role === 'pasien' ? 'RM-' . str_pad((string) $index, 6, '0', STR_PAD_LEFT) : null,
            'role' => $role,
            'id_poli' => null,
            'email' => $role . $index . '@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}
