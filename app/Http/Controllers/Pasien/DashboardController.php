<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $activeDaftarPoli = DaftarPoli::query()
            ->with(['jadwalPeriksa.dokter.poli'])
            ->where('id_pasien', Auth::id())
            ->whereDoesntHave('periksas')
            ->latest('created_at')
            ->first();

        $jadwals = JadwalPeriksa::query()
            ->with(['dokter.poli'])
            ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->orderBy('jam_mulai')
            ->get();

        $nomorDilayaniByJadwal = DaftarPoli::query()
            ->whereHas('periksas')
            ->selectRaw('id_jadwal, MAX(no_antrian) as nomor_dilayani')
            ->groupBy('id_jadwal')
            ->pluck('nomor_dilayani', 'id_jadwal');

        $nomorDilayaniAktif = null;

        if ($activeDaftarPoli) {
            $nomorDilayaniAktif = $nomorDilayaniByJadwal->get($activeDaftarPoli->id_jadwal);
        }

        return view('pasien.dashboard', [
            'activeDaftarPoli' => $activeDaftarPoli,
            'jadwals' => $jadwals,
            'nomorDilayaniByJadwal' => $nomorDilayaniByJadwal,
            'nomorDilayaniAktif' => $nomorDilayaniAktif,
        ]);
    }
}
