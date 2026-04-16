<x-layouts.app title="Input Pemeriksaan">

    <div class="ui-page-head">
        <div class="flex items-center gap-3">
            <a href="{{ route('dokter.pemeriksaan.index') }}" class="ui-btn-soft !w-10 !h-10 !p-0">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>

            <div>
                <h2 class="ui-title !text-[1.55rem] md:!text-[1.7rem]">Input Pemeriksaan</h2>
                <p class="ui-subtitle">Lengkapi catatan pemeriksaan dan resep obat pasien.</p>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-[1.15fr_0.85fr] gap-6">
        <div class="ui-surface">
            <div class="ui-panel-head">
                <h3 class="text-lg font-bold text-slate-800">Data Pasien</h3>
            </div>
            <div class="ui-panel-body grid md:grid-cols-2 gap-4">
                <div class="ui-field">
                    <label class="ui-label">Nama Pasien</label>
                    <p class="ui-input">{{ $antrian->pasien?->nama ?? '-' }}</p>
                </div>
                <div class="ui-field">
                    <label class="ui-label">No. Antrean</label>
                    <p class="ui-input">{{ $antrian->no_antrian }}</p>
                </div>
                <div class="ui-field md:col-span-2">
                    <label class="ui-label">Keluhan</label>
                    <p class="ui-input">{{ $antrian->keluhan }}</p>
                </div>
            </div>
        </div>

        <div class="ui-surface">
            <div class="ui-panel-head">
                <h3 class="text-lg font-bold text-slate-800">Ringkasan Terakhir</h3>
            </div>
            <div class="ui-panel-body space-y-3 text-sm text-slate-600">
                <p>Tanggal periksa: <span class="font-semibold text-slate-800">{{ $periksa?->tgl_periksa ? \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d-m-Y H:i') : '-' }}</span></p>
                <p>Biaya periksa: <span class="font-semibold text-slate-800">{{ $periksa?->biaya_periksa ? 'Rp ' . number_format($periksa->biaya_periksa, 0, ',', '.') : '-' }}</span></p>
            </div>
        </div>
    </div>

    <div class="ui-surface mt-6">
        <div class="ui-panel-body">
            <form action="{{ route('dokter.pemeriksaan.store', $antrian->id) }}" method="POST">
                @csrf

                <div class="ui-field mb-5">
                    <label class="ui-label">Catatan Pemeriksaan</label>
                    <textarea name="catatan" rows="4" class="ui-textarea @error('catatan') is-error @enderror" placeholder="Tulis hasil pemeriksaan...">{{ old('catatan', $periksa?->catatan) }}</textarea>
                    @error('catatan')<p class="ui-error">{{ $message }}</p>@enderror
                </div>

                <div class="ui-field mb-6">
                    <label class="ui-label">Resep Obat</label>
                    <div class="grid md:grid-cols-2 gap-3 mt-2">
                        @foreach($obats as $obat)
                            @php
                                $isChecked = in_array($obat->id, old('obat_ids', $selectedObatIds), true);
                                $canSelect = $isChecked || ! $obat->isOutOfStock();
                            @endphp
                            <label class="flex items-start gap-3 rounded-xl border p-3 {{ $canSelect ? 'border-slate-200 bg-slate-50/60' : 'border-rose-200 bg-rose-50/60 opacity-80' }}">
                                <input type="checkbox" name="obat_ids[]" value="{{ $obat->id }}" class="mt-1" @checked($isChecked) @disabled(!$canSelect)>
                                <span>
                                    <span class="block font-semibold text-slate-800">{{ $obat->nama_obat }}</span>
                                    <span class="block text-xs text-slate-500">{{ $obat->kemasan }} • Rp {{ number_format($obat->harga, 0, ',', '.') }} • Stok: {{ $obat->stok }}</span>
                                    @if($obat->isOutOfStock())
                                        <span class="mt-1 inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-semibold text-rose-700">Stok habis</span>
                                    @elseif($obat->isLowStock())
                                        <span class="mt-1 inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Stok rendah</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('obat_ids')<p class="ui-error mt-2">{{ $message }}</p>@enderror
                    @error('obat_ids.*')<p class="ui-error mt-2">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="ui-btn-primary"><i class="fas fa-save"></i> Simpan Pemeriksaan</button>
                    <a href="{{ route('dokter.pemeriksaan.index') }}" class="ui-btn-soft">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
