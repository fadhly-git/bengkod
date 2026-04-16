<x-layouts.app title="Data Dokter">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Data Dokter</h2>
            <p class="ui-subtitle">Kelola akun dokter dan penempatan poli.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dokter.export') }}" class="ui-btn-soft">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>

            <a href="{{ route('admin.dokter.create') }}" class="ui-btn-primary">
                <i class="fas fa-user-doctor"></i>
                Tambah Dokter
            </a>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Dokter</h3>
                <p class="text-sm text-slate-500 mt-1">Total saat ini: <span class="font-semibold text-slate-700">{{ $dokters->count() }}</span> dokter</p>
            </div>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Poli</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($dokters as $dokter)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $dokter->nama }}</td>
                            <td>{{ $dokter->email }}</td>
                            <td>{{ $dokter->no_hp }}</td>
                            <td>{{ $dokter->poli?->nama_poli ?? '-' }}</td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.dokter.edit', $dokter->id) }}" class="ui-btn-edit !px-3.5 !py-2 !text-xs">
                                        <i class="fas fa-pen-to-square"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.dokter.destroy', $dokter->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus dokter ini?')" class="ui-btn-danger !px-3.5 !py-2 !text-xs">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="ui-empty">
                                    <i class="fas fa-user-doctor"></i>
                                    <p class="text-sm font-medium">Belum ada data dokter.</p>
                                    <p class="text-xs text-slate-400 mt-1">Tambahkan dokter untuk mulai menyusun jadwal periksa.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
