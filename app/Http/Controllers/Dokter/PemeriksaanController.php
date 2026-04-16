<?php

namespace App\Http\Controllers\Dokter;

use App\Actions\Periksa\HitungBiayaPeriksa;
use App\Actions\Periksa\SinkronResepObat;
use App\Events\AntrianDilayaniUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dokter\StorePeriksaRequest;
use App\Models\DaftarPoli;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $antrians = DaftarPoli::query()
            ->with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) {
                $query->where('id_dokter', Auth::id());
            })
            ->orderByDesc('created_at')
            ->get();

        return view('dokter.periksa.index', compact('antrians'));
    }

    public function show(string $id)
    {
        $antrian = DaftarPoli::query()
            ->with(['pasien', 'jadwalPeriksa', 'periksas.detailPeriksas'])
            ->findOrFail($id);

        $this->authorize('view', $antrian);

        $periksa = $antrian->periksas()->latest('tgl_periksa')->first();
        $selectedObatIds = $periksa
            ? $periksa->detailPeriksas()->pluck('id_obat')->all()
            : [];

        $obats = Obat::query()->orderBy('nama_obat')->get();

        return view('dokter.periksa.show', compact('antrian', 'periksa', 'obats', 'selectedObatIds'));
    }

    public function store(
        StorePeriksaRequest $request,
        string $id,
        HitungBiayaPeriksa $hitungBiayaPeriksa,
        SinkronResepObat $sinkronResepObat
    ) {
        $antrian = DaftarPoli::query()
            ->with('jadwalPeriksa')
            ->findOrFail($id);

        $this->authorize('periksa', $antrian);

        $validated = $request->validated();
        $obatIds = array_values(array_unique(array_map('intval', $validated['obat_ids'] ?? [])));

        $nomorDilayaniTerbaru = DB::transaction(function () use ($antrian, $validated, $obatIds, $hitungBiayaPeriksa, $sinkronResepObat) {
            $periksa = Periksa::query()->firstOrNew([
                'id_daftar_poli' => $antrian->id,
            ]);

            $existingObatIds = $periksa->exists
                ? $periksa->detailPeriksas()->pluck('id_obat')->map(fn ($id): int => (int) $id)->all()
                : [];

            $allObatIds = array_values(array_unique(array_merge($existingObatIds, $obatIds)));
            $obats = Obat::query()
                ->whereIn('id', $allObatIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $deltaByObatId = [];

            foreach ($obatIds as $obatId) {
                $deltaByObatId[$obatId] = ($deltaByObatId[$obatId] ?? 0) + 1;
            }

            foreach ($existingObatIds as $obatId) {
                $deltaByObatId[$obatId] = ($deltaByObatId[$obatId] ?? 0) - 1;
            }

            foreach ($deltaByObatId as $obatId => $delta) {
                if ($delta <= 0) {
                    continue;
                }

                $obat = $obats->get($obatId);
                $stokSaatIni = (int) Arr::get($obat, 'stok', 0);

                if ($stokSaatIni < $delta) {
                    $namaObat = Arr::get($obat, 'nama_obat', 'Obat');

                    throw ValidationException::withMessages([
                        'obat_ids' => "Stok {$namaObat} tidak mencukupi. Tersisa {$stokSaatIni}.",
                    ]);
                }
            }

            foreach ($deltaByObatId as $obatId => $delta) {
                if ($delta > 0) {
                    Obat::query()->whereKey($obatId)->decrement('stok', $delta);
                }

                if ($delta < 0) {
                    Obat::query()->whereKey($obatId)->increment('stok', abs($delta));
                }
            }

            $biayaPeriksa = $hitungBiayaPeriksa->execute($obatIds);

            $periksa->tgl_periksa = now();
            $periksa->catatan = $validated['catatan'] ?? null;
            $periksa->biaya_periksa = $biayaPeriksa;
            $periksa->save();

            $sinkronResepObat->execute($periksa->id, $obatIds);

            return (int) DaftarPoli::query()
                ->where('id_jadwal', $antrian->id_jadwal)
                ->whereHas('periksas')
                ->max('no_antrian');
        });

        event(new AntrianDilayaniUpdated((int) $antrian->id_jadwal, $nomorDilayaniTerbaru));

        return redirect()->route('dokter.pemeriksaan.show', $antrian->id)
            ->with('success', 'Data pemeriksaan berhasil disimpan');
    }
}
