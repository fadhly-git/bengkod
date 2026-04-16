<x-layouts.app title="Antrean Pemeriksaan">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Antrean Pemeriksaan</h2>
            <p class="ui-subtitle">Daftar pasien yang terdaftar pada jadwal praktik Anda.</p>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Antrean</h3>
                <p class="text-sm text-slate-500 mt-1">Total antrean: <span class="font-semibold text-slate-700">{{ $antrians->count() }}</span></p>
            </div>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>No. Antrean</th>
                        <th>Pasien</th>
                        <th>Keluhan</th>
                        <th>Hari / Jam</th>
                        <th>Tanggal Daftar</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($antrians as $antrian)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $antrian->no_antrian }}</td>
                            <td>{{ $antrian->pasien?->nama ?? '-' }}</td>
                            <td>{{ $antrian->keluhan }}</td>
                            <td>
                                {{ ucfirst($antrian->jadwalPeriksa?->hari ?? '-') }}
                                @if($antrian->jadwalPeriksa)
                                    ({{ substr($antrian->jadwalPeriksa->jam_mulai, 0, 5) }} - {{ substr($antrian->jadwalPeriksa->jam_selesai, 0, 5) }})
                                @endif
                            </td>
                            <td>{{ $antrian->created_at?->format('d-m-Y H:i') }}</td>
                            <td>
                                <div class="flex justify-end">
                                    <a href="{{ route('dokter.pemeriksaan.show', $antrian->id) }}" class="ui-btn-soft !px-3.5 !py-2 !text-xs">
                                        <i class="fas fa-stethoscope"></i>
                                        Periksa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="ui-empty">
                                    <i class="fas fa-stethoscope"></i>
                                    <p class="text-sm font-medium">Belum ada antrean pasien.</p>
                                    <p class="text-xs text-slate-400 mt-1">Antrean akan muncul ketika pasien mendaftar pada jadwal Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
