<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;

class RiwayatPeriksaController extends Controller
{
    public function index()
    {
        $daftarPolis = DaftarPoli::query()
            ->with([
                'jadwalPeriksa.dokter.poli',
                'periksas' => function ($query) {
                    $query->latest('tgl_periksa');
                },
            ])
            ->where('id_pasien', auth()->id())
            ->latest('created_at')
            ->get();

        return view('pasien.riwayat-periksa.index', compact('daftarPolis'));
    }

    public function show(string $id)
    {
        $periksa = Periksa::query()
            ->with(['daftarPoli.jadwalPeriksa.dokter.poli', 'detailPeriksas.obat'])
            ->findOrFail($id);

        $this->authorize('view', $periksa);

        return view('pasien.riwayat-periksa.show', compact('periksa'));
    }
}
