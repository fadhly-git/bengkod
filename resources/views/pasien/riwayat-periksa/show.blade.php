<x-layouts.app title="Detail Riwayat Pemeriksaan">

    <div class="ui-page-head">
        <div class="flex items-center gap-3">
            <a href="{{ route('pasien.riwayat.index') }}" class="ui-btn-soft !w-10 !h-10 !p-0">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>

            <div>
                <h2 class="ui-title !text-[1.55rem] md:!text-[1.7rem]">Detail Pemeriksaan</h2>
                <p class="ui-subtitle">Rincian hasil pemeriksaan dan obat.</p>
            </div>
        </div>
    </div>

    <div class="ui-surface mb-6">
        <div class="ui-panel-body grid md:grid-cols-2 gap-4">
            <div class="ui-field">
                <label class="ui-label">Tanggal Periksa</label>
                <p class="ui-input">{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d-m-Y H:i') }}</p>
            </div>

            <div class="ui-field">
                <label class="ui-label">Biaya Periksa</label>
                <p class="ui-input">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</p>
            </div>

            <div class="ui-field md:col-span-2">
                <label class="ui-label">Catatan Dokter</label>
                <p class="ui-input">{{ $periksa->catatan ?: '-' }}</p>
            </div>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head">
            <h3 class="text-lg font-bold text-slate-800">Resep Obat</h3>
        </div>

        <div class="ui-panel-body">
            @if($periksa->detailPeriksas->isEmpty())
                <div class="ui-empty">
                    <i class="fas fa-capsules"></i>
                    <p class="text-sm font-medium">Tidak ada resep obat pada pemeriksaan ini.</p>
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-3">
                    @foreach($periksa->detailPeriksas as $detail)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="font-semibold text-slate-800">{{ $detail->obat?->nama_obat ?? '-' }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $detail->obat?->kemasan ?? '-' }} • Rp {{ number_format((int) ($detail->obat?->harga ?? 0), 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-layouts.app>
