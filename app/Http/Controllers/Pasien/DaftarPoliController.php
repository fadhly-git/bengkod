<?php

namespace App\Http\Controllers\Pasien;

use App\Actions\DaftarPoli\GenerateNomorAntrian;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pasien\StoreDaftarPoliRequest;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DaftarPoliController extends Controller
{
    public function index()
    {
        $daftars = DaftarPoli::with(['jadwalPeriksa.dokter.poli'])
            ->where('id_pasien', Auth::id())
            ->latest()
            ->get();

        return view('pasien.daftar-poli.index', compact('daftars'));
    }

    public function create()
    {
        $this->authorize('create', DaftarPoli::class);

        $polis = Poli::query()->orderBy('nama_poli')->get();

        $dokters = User::query()
            ->with('poli')
            ->where('role', 'dokter')
            ->orderBy('nama')
            ->get();

        $jadwals = JadwalPeriksa::query()
            ->with('dokter.poli')
            ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('pasien.daftar-poli.create', compact('polis', 'dokters', 'jadwals'));
    }

    public function store(StoreDaftarPoliRequest $request, GenerateNomorAntrian $generateNomorAntrian)
    {
        $this->authorize('create', DaftarPoli::class);

        $validated = $request->validated();

        $daftar = DB::transaction(function () use ($validated, $generateNomorAntrian) {
            $queueNumber = $generateNomorAntrian->execute((int) $validated['id_jadwal']);

            return DaftarPoli::create([
                'id_pasien' => Auth::id(),
                'id_jadwal' => $validated['id_jadwal'],
                'keluhan' => $validated['keluhan'],
                'no_antrian' => $queueNumber,
            ]);
        });

        return redirect()
            ->route('pasien.daftar-poli.index')
            ->with('success', 'Pendaftaran berhasil. Nomor antrean Anda: ' . $daftar->no_antrian);
    }
}
