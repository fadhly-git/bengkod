<x-layouts.app title="Riwayat Pendaftaran Poli">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Riwayat Pendaftaran Poli</h2>
            <p class="ui-subtitle">Semua pendaftaran poli Anda, urut dari yang terbaru.</p>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head">
            <h3 class="text-lg font-bold text-slate-800">Data Pendaftaran</h3>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Tanggal Daftar</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                        <th>Jadwal</th>
                        <th>No. Antrean</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($daftarPolis as $daftarPoli)
                        @php
                            $periksa = $daftarPoli->periksas->first();
                        @endphp
                        <tr>
                            <td>{{ $daftarPoli->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td>{{ $daftarPoli->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-' }}</td>
                            <td>{{ $daftarPoli->jadwalPeriksa?->dokter?->nama ?? '-' }}</td>
                            <td>
                                {{ ucfirst($daftarPoli->jadwalPeriksa?->hari ?? '-') }}
                                ({{ substr((string) $daftarPoli->jadwalPeriksa?->jam_mulai, 0, 5) }} - {{ substr((string) $daftarPoli->jadwalPeriksa?->jam_selesai, 0, 5) }})
                            </td>
                            <td>{{ $daftarPoli->no_antrian }}</td>
                            <td>
                                @if($periksa)
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">
                                        Sudah diperiksa
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">
                                        Menunggu pemeriksaan
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @if($periksa)
                                        <a href="{{ route('pasien.riwayat.show', $periksa->id) }}" class="ui-btn-soft !px-3.5 !py-2 !text-xs">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </a>
                                    @else
                                        <span class="ui-btn-soft !px-3.5 !py-2 !text-xs opacity-70">Belum tersedia</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="ui-empty">
                                    <i class="fas fa-file-medical"></i>
                                    <p class="text-sm font-medium">Belum ada riwayat pendaftaran poli.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
