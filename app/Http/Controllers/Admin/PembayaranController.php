<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $status = (string) $request->query('status', 'pending');
        $allowedStatus = ['pending', 'lunas'];

        $pembayaransQuery = Pembayaran::query()
            ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter'])
            ->latest('tanggal_pembayaran')
            ->latest('created_at');

        if (in_array($status, $allowedStatus, true)) {
            $pembayaransQuery->where('status', $status);
        }

        $pembayarans = $pembayaransQuery->get();

        return view('admin.pembayaran.index', [
            'pembayarans' => $pembayarans,
            'activeStatus' => $status,
        ]);
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter.poli',
            'daftarPoli.periksas' => fn ($query) => $query->latest('tgl_periksa'),
            'verifier',
        ]);

        $periksa = $pembayaran->daftarPoli?->periksas?->first();

        return view('admin.pembayaran.verify', compact('pembayaran', 'periksa'));
    }

    public function konfirmasi(Pembayaran $pembayaran)
    {
        if ($pembayaran->status === 'lunas') {
            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran ini sudah berstatus lunas.');
        }

        if (! $pembayaran->bukti_file) {
            return redirect()->route('admin.pembayaran.show', $pembayaran->id)
                ->withErrors(['pembayaran' => 'Bukti pembayaran belum diunggah oleh pasien.']);
        }

        $pembayaran->update([
            'status' => 'lunas',
            'tanggal_verifikasi' => now(),
            'verified_by' => auth()->id(),
        ]);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi lunas.');
    }
}
