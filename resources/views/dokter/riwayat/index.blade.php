<x-layouts.app title="Riwayat Pasien">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Riwayat Pasien</h2>
            <p class="ui-subtitle">Riwayat pemeriksaan pasien yang telah Anda tangani.</p>
        </div>

        <a href="{{ route('dokter.riwayat-pasien.export') }}" class="ui-btn-soft">
            <i class="fas fa-file-excel"></i>
            Export Excel
        </a>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Data Riwayat Pemeriksaan</h3>
                <p class="text-sm text-slate-500 mt-1">Total riwayat: <span class="font-semibold text-slate-700">{{ $periksas->count() }}</span></p>
            </div>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Tanggal Periksa</th>
                        <th>Nama Pasien</th>
                        <th>No. RM</th>
                        <th>No. Antrean</th>
                        <th>Biaya</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($periksas as $periksa)
                        <tr>
                            <td>{{ optional($periksa->tgl_periksa)->format('d-m-Y H:i') }}</td>
                            <td class="font-semibold text-slate-800">{{ $periksa->daftarPoli?->pasien?->nama ?? '-' }}</td>
                            <td>{{ $periksa->daftarPoli?->pasien?->no_rm ?? '-' }}</td>
                            <td>{{ $periksa->daftarPoli?->no_antrian ?? '-' }}</td>
                            <td>Rp {{ number_format((int) $periksa->biaya_periksa, 0, ',', '.') }}</td>
                            <td>{{ $periksa->catatan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="ui-empty">
                                    <i class="fas fa-file-medical"></i>
                                    <p class="text-sm font-medium">Belum ada riwayat pasien.</p>
                                    <p class="text-xs text-slate-400 mt-1">Riwayat akan muncul setelah Anda menyimpan data pemeriksaan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
