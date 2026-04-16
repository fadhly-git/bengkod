<x-layouts.app title="Data Pasien">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Data Pasien</h2>
            <p class="ui-subtitle">Monitoring akun pasien yang terdaftar pada sistem.</p>
        </div>

        <a href="{{ route('admin.pasien.export') }}" class="ui-btn-soft">
            <i class="fas fa-file-excel"></i>
            Export Excel
        </a>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Pasien</h3>
                <p class="text-sm text-slate-500 mt-1">Total saat ini: <span class="font-semibold text-slate-700">{{ $pasiens->count() }}</span> pasien</p>
            </div>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. KTP</th>
                        <th>No. HP</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pasiens as $pasien)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $pasien->nama }}</td>
                            <td>{{ $pasien->email }}</td>
                            <td>{{ $pasien->no_ktp ?? '-' }}</td>
                            <td>{{ $pasien->no_hp ?? '-' }}</td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.pasien.show', $pasien->id) }}" class="ui-btn-soft !px-3.5 !py-2 !text-xs">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="ui-empty">
                                    <i class="fas fa-users"></i>
                                    <p class="text-sm font-medium">Belum ada data pasien.</p>
                                    <p class="text-xs text-slate-400 mt-1">Data pasien akan muncul setelah registrasi berhasil.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
