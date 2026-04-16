<?php

namespace App\Http\Controllers\Dokter;

use App\Exports\DokterRiwayatPasienExport;
use App\Http\Controllers\Controller;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RiwayatPasienController extends Controller
{
    public function index()
    {
        $periksas = Periksa::query()
            ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa'])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($query) {
                $query->where('id_dokter', Auth::id());
            })
            ->latest('tgl_periksa')
            ->get();

        return view('dokter.riwayat.index', compact('periksas'));
    }

    public function export()
    {
        return Excel::download(new DokterRiwayatPasienExport((int) Auth::id()), 'riwayat-pasien.xlsx');
    }
}
