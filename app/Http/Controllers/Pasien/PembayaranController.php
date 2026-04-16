<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pasien\StorePembayaranRequest;
use App\Models\DaftarPoli;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        $daftarPolis = DaftarPoli::query()
            ->with([
                'jadwalPeriksa.dokter.poli',
                'pembayaran',
                'periksas' => fn ($query) => $query->latest('tgl_periksa'),
            ])
            ->where('id_pasien', auth()->id())
            ->whereHas('periksas')
            ->latest('created_at')
            ->get();

        return view('pasien.pembayaran.index', compact('daftarPolis'));
    }

    public function create(DaftarPoli $daftarPoli)
    {
        abort_unless((int) $daftarPoli->id_pasien === (int) auth()->id(), 403);

        $daftarPoli->load(['jadwalPeriksa.dokter.poli', 'periksas' => fn ($query) => $query->latest('tgl_periksa'), 'pembayaran']);

        $periksa = $daftarPoli->periksas->first();

        abort_unless($periksa !== null, 404);

        if ($daftarPoli->pembayaran?->status === 'lunas') {
            return redirect()->route('pasien.pembayaran.index')
                ->with('success', 'Tagihan ini sudah dikonfirmasi lunas.');
        }

        return view('pasien.pembayaran.upload', compact('daftarPoli', 'periksa'));
    }

    public function store(StorePembayaranRequest $request, DaftarPoli $daftarPoli)
    {
        abort_unless((int) $daftarPoli->id_pasien === (int) auth()->id(), 403);

        $periksa = $daftarPoli->periksas()->latest('tgl_periksa')->firstOrFail();

        $pembayaran = Pembayaran::query()->firstOrNew([
            'id_daftar_poli' => $daftarPoli->id,
        ]);

        if ($pembayaran->status === 'lunas') {
            return redirect()->route('pasien.pembayaran.index')
                ->with('success', 'Tagihan ini sudah dikonfirmasi lunas.');
        }

        if ($pembayaran->bukti_file) {
            Storage::disk('public')->delete($pembayaran->bukti_file);
        }

        $buktiPath = $request->file('bukti_pembayaran')->store('pembayaran', 'public');

        $pembayaran->jumlah_tagihan = $pembayaran->jumlah_tagihan ?: (int) $periksa->biaya_periksa;
        $pembayaran->status = 'pending';
        $pembayaran->bukti_file = $buktiPath;
        $pembayaran->tanggal_pembayaran = now();
        $pembayaran->tanggal_verifikasi = null;
        $pembayaran->verified_by = null;
        $pembayaran->save();

        return redirect()->route('pasien.pembayaran.index')
            ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }
}
