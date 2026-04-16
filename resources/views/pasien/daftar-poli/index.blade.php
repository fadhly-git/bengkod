<x-layouts.app title="Daftar Poli Saya">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Daftar Poli Saya</h2>
            <p class="ui-subtitle">Riwayat pendaftaran Anda ke poli dan jadwal dokter.</p>
        </div>

        <a href="{{ route('pasien.daftar-poli.create') }}" class="ui-btn-primary">
            <i class="fas fa-plus"></i>
            Daftar Poli
        </a>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Data Pendaftaran</h3>
                <p class="text-sm text-slate-500 mt-1">Total pendaftaran: <span class="font-semibold text-slate-700">{{ $daftars->count() }}</span></p>
            </div>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>No. Antrean</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                        <th>Hari / Jam</th>
                        <th>Keluhan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($daftars as $daftar)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $daftar->no_antrian }}</td>
                            <td>{{ $daftar->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-' }}</td>
                            <td>{{ $daftar->jadwalPeriksa?->dokter?->nama ?? '-' }}</td>
                            <td>
                                {{ ucfirst($daftar->jadwalPeriksa?->hari ?? '-') }}
                                @if($daftar->jadwalPeriksa)
                                    ({{ substr($daftar->jadwalPeriksa->jam_mulai, 0, 5) }} - {{ substr($daftar->jadwalPeriksa->jam_selesai, 0, 5) }})
                                @endif
                            </td>
                            <td>{{ $daftar->keluhan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="ui-empty">
                                    <i class="fas fa-notes-medical"></i>
                                    <p class="text-sm font-medium">Belum ada pendaftaran poli.</p>
                                    <p class="text-xs text-slate-400 mt-1">Buat pendaftaran baru untuk mendapatkan nomor antrean.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
