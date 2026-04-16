<x-layouts.app title="Verifikasi Pembayaran">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Verifikasi Pembayaran</h2>
            <p class="ui-subtitle">Tinjau bukti pembayaran pasien dan konfirmasi tagihan lunas.</p>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-bold text-slate-800">Daftar Pembayaran</h3>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.pembayaran.index') }}" class="{{ $activeStatus === 'semua' ? 'ui-btn-primary' : 'ui-btn-soft' }} !px-3.5 !py-2 !text-xs">
                    Semua
                </a>
                <a href="{{ route('admin.pembayaran.index', ['status' => 'pending']) }}" class="{{ $activeStatus === 'pending' ? 'ui-btn-primary' : 'ui-btn-soft' }} !px-3.5 !py-2 !text-xs">
                    Pending
                </a>
                <a href="{{ route('admin.pembayaran.index', ['status' => 'lunas']) }}" class="{{ $activeStatus === 'lunas' ? 'ui-btn-primary' : 'ui-btn-soft' }} !px-3.5 !py-2 !text-xs">
                    Lunas
                </a>
            </div>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Pasien</th>
                        <th>Poli / Dokter</th>
                        <th>Tgl Bayar</th>
                        <th>Tagihan</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pembayarans as $pembayaran)
                        <tr>
                            <td>
                                <p class="font-semibold text-slate-800">{{ $pembayaran->daftarPoli?->pasien?->nama ?? '-' }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $pembayaran->daftarPoli?->pasien?->no_rm ?? '-' }}</p>
                            </td>
                            <td>
                                <p class="font-semibold text-slate-800">{{ $pembayaran->daftarPoli?->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-' }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $pembayaran->daftarPoli?->jadwalPeriksa?->dokter?->nama ?? '-' }}</p>
                            </td>
                            <td>{{ optional($pembayaran->tanggal_pembayaran)->format('d-m-Y H:i') ?? '-' }}</td>
                            <td>Rp {{ number_format((int) $pembayaran->jumlah_tagihan, 0, ',', '.') }}</td>
                            <td>
                                @if($pembayaran->status === 'lunas')
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">Lunas</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    <a href="{{ route('admin.pembayaran.show', $pembayaran->id) }}" class="ui-btn-soft !px-3.5 !py-2 !text-xs">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="ui-empty">
                                    <i class="fas fa-wallet"></i>
                                    <p class="text-sm font-medium">Belum ada data pembayaran.</p>
                                    <p class="text-xs text-slate-400 mt-1">Data akan muncul ketika pasien mengunggah bukti pembayaran.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
