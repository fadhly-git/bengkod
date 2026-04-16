<x-layouts.app title="Jadwal Periksa Dokter">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Jadwal Periksa</h2>
            <p class="ui-subtitle">Kelola jadwal praktik Anda untuk pendaftaran pasien.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('dokter.jadwal-periksa.export') }}" class="ui-btn-soft">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>

            <a href="{{ route('dokter.jadwal-periksa.create') }}" class="ui-btn-primary">
                <i class="fas fa-plus"></i>
                Tambah Jadwal
            </a>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Jadwal</h3>
                <p class="text-sm text-slate-500 mt-1">Total jadwal aktif: <span class="font-semibold text-slate-700">{{ $jadwals->count() }}</span></p>
            </div>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jadwals as $jadwal)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ ucfirst($jadwal->hari) }}</td>
                            <td>{{ substr($jadwal->jam_mulai, 0, 5) }}</td>
                            <td>{{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('dokter.jadwal-periksa.edit', $jadwal->id) }}" class="ui-btn-edit !px-3.5 !py-2 !text-xs">
                                        <i class="fas fa-pen-to-square"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('dokter.jadwal-periksa.destroy', $jadwal->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus jadwal ini?')" class="ui-btn-danger !px-3.5 !py-2 !text-xs">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="ui-empty">
                                    <i class="fas fa-calendar-days"></i>
                                    <p class="text-sm font-medium">Belum ada jadwal periksa.</p>
                                    <p class="text-xs text-slate-400 mt-1">Tambahkan jadwal pertama agar pasien dapat mendaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
