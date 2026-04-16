<x-layouts.app title="Data Obat">

    <div class="ui-page-head">
        <div>
            <h2 class="ui-title">Data Obat</h2>
            <p class="ui-subtitle">Kelola daftar obat untuk kebutuhan resep pemeriksaan.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.obat.export') }}" class="ui-btn-soft">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>

            <a href="{{ route('admin.obat.create') }}" class="ui-btn-primary">
                <i class="fas fa-plus"></i>
                Tambah Obat
            </a>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Obat</h3>
                <p class="text-sm text-slate-500 mt-1">Total saat ini: <span class="font-semibold text-slate-700">{{ $obats->count() }}</span> obat</p>
            </div>
        </div>

        <div class="ui-table-wrap">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Kemasan</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="items-center flex justify-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($obats as $obat)
                        <tr>
                            <td class="font-semibold text-slate-800">{{ $obat->nama_obat }}</td>
                            <td>{{ $obat->kemasan }}</td>
                            <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-700">{{ $obat->stok }}</span>
                                    @if($obat->isOutOfStock())
                                        <span class="inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-semibold text-rose-700">Habis</span>
                                    @elseif($obat->isLowStock())
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Stok rendah</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.obat.edit', $obat->id) }}" class="ui-btn-edit !px-3.5 !py-2 !text-xs">
                                        <i class="fas fa-pen-to-square"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.obat.destroy', $obat->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus obat ini?')" class="ui-btn-danger !px-3.5 !py-2 !text-xs">
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
                                    <i class="fas fa-capsules"></i>
                                    <p class="text-sm font-medium">Belum ada data obat.</p>
                                    <p class="text-xs text-slate-400 mt-1">Tambahkan obat pertama agar resep dapat digunakan di fitur pemeriksaan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
