<?php

namespace App\Http\Controllers\Dokter;

use App\Exports\DokterJadwalPeriksaExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dokter\StoreJadwalPeriksaRequest;
use App\Http\Requests\Dokter\UpdateJadwalPeriksaRequest;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class JadwalPeriksaController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPeriksa::where('id_dokter', Auth::id())
            ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('dokter.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        return view('dokter.jadwal.create');
    }

    public function store(StoreJadwalPeriksaRequest $request)
    {
        $this->authorize('create', JadwalPeriksa::class);

        JadwalPeriksa::create([
            'id_dokter' => Auth::id(),
            ...$request->validated(),
        ]);

        return redirect()->route('dokter.jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);
        $this->authorize('update', $jadwal);

        return view('dokter.jadwal.edit', compact('jadwal'));
    }

    public function update(UpdateJadwalPeriksaRequest $request, string $id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);
        $this->authorize('update', $jadwal);
        $jadwal->update($request->validated());

        return redirect()->route('dokter.jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $jadwal = JadwalPeriksa::findOrFail($id);
        $this->authorize('delete', $jadwal);
        $jadwal->delete();

        return redirect()->route('dokter.jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new DokterJadwalPeriksaExport((int) Auth::id()), 'jadwal-periksa.xlsx');
    }
}
