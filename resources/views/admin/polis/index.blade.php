<x-layouts.app title="Data Poli">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Data Poli</h2>
            <p class="ui-subtitle">Kelola daftar poli untuk kebutuhan pendaftaran dan jadwal pemeriksaan.</p>
        </div>

        <a href="{{ route('polis.create') }}" class="ui-btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Poli
        </a>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Poli</h3>
                <p class="text-sm text-slate-500 mt-1">Total saat ini: <span class="font-semibold text-slate-700">{{ $polis->count() }}</span> poli</p>
            </div>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Nama Poli</th>
                        <th>Keterangan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($polis as $poli)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $poli->nama_poli }}</td>

                        <td>{{ $poli->keterangan }}</td>

                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('polis.edit', $poli->id) }}" class="ui-btn-edit !px-3.5 !py-2 !text-xs">
                                    <i class="fas fa-pen-to-square"></i>
                                    Edit
                                </a>

                                <form action="{{ route('polis.destroy', $poli->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus poli ini?')" class="ui-btn-danger !px-3.5 !py-2 !text-xs">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">
                            <div class="ui-empty">
                                <i class="fas fa-inbox"></i>
                                <p class="text-sm font-medium">Belum ada data poli.</p>
                                <p class="text-xs text-slate-400 mt-1">Tambahkan poli pertama agar data bisa digunakan pada jadwal pemeriksaan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</x-layouts.app>
