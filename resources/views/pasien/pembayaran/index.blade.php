<x-layouts.app title="Pembayaran">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Pembayaran</h2>
            <p class="ui-subtitle">Lihat tagihan hasil pemeriksaan dan unggah bukti pembayaran Anda.</p>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head">
            <h3 class="text-lg font-bold text-slate-800">Daftar Tagihan Pemeriksaan</h3>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Tanggal Periksa</th>
                        <th>Poli / Dokter</th>
                        <th>No. Antrean</th>
                        <th>Tagihan</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($daftarPolis as $daftarPoli)
                        @php
                            $periksa = $daftarPoli->periksas->first();
                            $pembayaran = $daftarPoli->pembayaran;
                            $status = $pembayaran?->status ?? 'belum_upload';
                            $jumlahTagihan = $pembayaran?->jumlah_tagihan ?? $periksa?->biaya_periksa;
                        @endphp
                        <tr>
                            <td>{{ $periksa?->tgl_periksa }}</td>
                            <td>
                                <p class="font-semibold text-slate-800">{{ $daftarPoli->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-' }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $daftarPoli->jadwalPeriksa?->dokter?->nama ?? '-' }}</p>
                            </td>
                            <td>{{ $daftarPoli->no_antrian }}</td>
                            <td>Rp {{ number_format((int) $jumlahTagihan, 0, ',', '.') }}</td>
                            <td>
                                @if($status === 'lunas')
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">Lunas</span>
                                @elseif($status === 'pending')
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Menunggu Verifikasi</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">Belum Upload</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @if($status === 'lunas')
                                        <span class="ui-btn-soft !px-3.5 !py-2 !text-xs opacity-80">Selesai</span>
                                    @else
                                        <a href="{{ route('pasien.pembayaran.create', $daftarPoli->id) }}" class="ui-btn-primary !px-3.5 !py-2 !text-xs">
                                            <i class="fas fa-upload"></i>
                                            {{ $status === 'pending' ? 'Upload Ulang' : 'Upload Bukti' }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="ui-empty">
                                    <i class="fas fa-receipt"></i>
                                    <p class="text-sm font-medium">Belum ada tagihan pemeriksaan.</p>
                                    <p class="text-xs text-slate-400 mt-1">Tagihan akan tersedia setelah Anda menyelesaikan pemeriksaan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
