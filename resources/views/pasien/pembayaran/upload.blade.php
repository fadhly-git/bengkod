<x-layouts.app title="Upload Bukti Pembayaran">

    <div class="ui-page-head">
        <div class="flex items-center gap-3">
            <a href="{{ route('pasien.pembayaran.index') }}" class="ui-btn-soft !w-10 !h-10 !p-0">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>

            <div>
                <h2 class="ui-title !text-[1.55rem] md:!text-[1.7rem]">Upload Bukti Pembayaran</h2>
                <p class="ui-subtitle">Unggah bukti transfer untuk diproses admin.</p>
            </div>
        </div>
    </div>

    <div class="ui-surface mb-6">
        <div class="ui-panel-body grid md:grid-cols-2 gap-4">
            <div class="ui-field">
                <label class="ui-label">Poli / Dokter</label>
                <p class="ui-input">{{ $daftarPoli->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-' }} - {{ $daftarPoli->jadwalPeriksa?->dokter?->nama ?? '-' }}</p>
            </div>

            <div class="ui-field">
                <label class="ui-label">Tanggal Periksa</label>
                <p class="ui-input">{{ $periksa->tgl_periksa }}</p>
            </div>

            <div class="ui-field">
                <label class="ui-label">No. Antrean</label>
                <p class="ui-input">{{ $daftarPoli->no_antrian }}</p>
            </div>

            <div class="ui-field">
                <label class="ui-label">Jumlah Tagihan</label>
                <p class="ui-input">Rp {{ number_format((int) ($daftarPoli->pembayaran?->jumlah_tagihan ?? $periksa->biaya_periksa), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-head">
            <h3 class="text-lg font-bold text-slate-800">Form Upload</h3>
        </div>

        <div class="ui-panel-body">
            <form action="{{ route('pasien.pembayaran.store', $daftarPoli->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5 max-w-xl">
                @csrf

                <div class="ui-field">
                    <label for="bukti_pembayaran" class="ui-label">Bukti Pembayaran</label>
                    <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" class="ui-input @error('bukti_pembayaran') is-error @enderror">
                    <p class="ui-help">Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.</p>
                    @error('bukti_pembayaran')
                        <p class="ui-error">{{ $message }}</p>
                    @enderror
                </div>

                @if($daftarPoli->pembayaran?->bukti_file)
                    <div class="ui-field">
                        <label class="ui-label">Bukti Terakhir</label>
                        <a href="{{ asset('storage/' . $daftarPoli->pembayaran->bukti_file) }}" target="_blank" class="ui-btn-soft">
                            <i class="fas fa-image"></i>
                            Lihat Bukti Sebelumnya
                        </a>
                    </div>
                @endif

                <div class="flex items-center gap-3">
                    <button type="submit" class="ui-btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Bukti Pembayaran
                    </button>
                    <a href="{{ route('pasien.pembayaran.index') }}" class="ui-btn-soft">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
