<x-layouts.app title="Dashboard Pasien">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Dashboard Pasien</h2>
            <p class="ui-subtitle">Pantau antrean aktif Anda dan lihat status layanan setiap jadwal poli.</p>
        </div>
    </div>

    @if($activeDaftarPoli)
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 via-white to-cyan-50 p-5 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">Antrean Aktif Anda</p>
                    <h3 class="mt-1 text-xl font-extrabold text-slate-800">
                        {{ $activeDaftarPoli->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-' }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-600">
                        Dokter {{ $activeDaftarPoli->jadwalPeriksa?->dokter?->nama ?? '-' }}
                        • {{ ucfirst($activeDaftarPoli->jadwalPeriksa?->hari ?? '-') }}
                        ({{ substr((string) $activeDaftarPoli->jadwalPeriksa?->jam_mulai, 0, 5) }} - {{ substr((string) $activeDaftarPoli->jadwalPeriksa?->jam_selesai, 0, 5) }})
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl border border-emerald-200 bg-white/80 px-4 py-3 text-center">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">No. Antrean Anda</p>
                        <p class="mt-1 text-2xl font-black text-emerald-700">{{ $activeDaftarPoli->no_antrian }}</p>
                    </div>
                    <div class="rounded-xl border border-cyan-200 bg-white/80 px-4 py-3 text-center">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Sedang Dilayani</p>
                        <p id="nomor-dilayani-aktif" data-active-jadwal-id="{{ $activeDaftarPoli->id_jadwal }}" class="mt-1 text-2xl font-black text-cyan-700">{{ $nomorDilayaniAktif ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mb-6 rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <p class="text-sm font-semibold text-slate-700">Saat ini Anda belum memiliki antrean aktif.</p>
            <p class="mt-1 text-sm text-slate-500">Silakan daftar poli untuk mendapatkan nomor antrean baru.</p>
        </div>
    @endif

    <div class="ui-surface">
        <div class="ui-panel-head flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Jadwal Poliklinik</h3>
                <p class="mt-1 text-sm text-slate-500">Nomor dilayani dihitung dari antrean yang sudah selesai diperiksa.</p>
            </div>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                        <th>Hari</th>
                        <th>Jam Periksa</th>
                        <th>No. Dilayani</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jadwals as $index => $jadwal)
                        <tr>
                            <td class="font-semibold text-slate-700">{{ $index + 1 }}</td>
                            <td>{{ $jadwal->dokter?->poli?->nama_poli ?? '-' }}</td>
                            <td>{{ $jadwal->dokter?->nama ?? '-' }}</td>
                            <td>{{ ucfirst($jadwal->hari) }}</td>
                            <td>{{ substr((string) $jadwal->jam_mulai, 0, 5) }} - {{ substr((string) $jadwal->jam_selesai, 0, 5) }}</td>
                            <td>
                                <span data-jadwal-id="{{ $jadwal->id }}" class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700">
                                    {{ $nomorDilayaniByJadwal->get($jadwal->id) ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="ui-empty">
                                    <i class="fas fa-calendar-days"></i>
                                    <p class="text-sm font-medium">Belum ada jadwal poliklinik tersedia.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (!window.Echo) {
                    return;
                }

                window.Echo.channel('antrian.poli')
                    .listen('.antrian.diperbarui', function (payload) {
                        const jadwalId = String(payload.id_jadwal);
                        const nomorDilayani = payload.nomor_dilayani ?? '-';

                        document.querySelectorAll('[data-jadwal-id="' + jadwalId + '"]').forEach(function (element) {
                            element.textContent = nomorDilayani;
                        });

                        const activeElement = document.getElementById('nomor-dilayani-aktif');

                        if (activeElement && activeElement.dataset.activeJadwalId === jadwalId) {
                            activeElement.textContent = nomorDilayani;
                        }
                    });
            });
        </script>
    @endpush

</x-layouts.app>
