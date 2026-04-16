<x-layouts.app title="Detail Pembayaran">

    <div class="ui-page-head">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pembayaran.index') }}" class="ui-btn-soft !w-10 !h-10 !p-0">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>

            <div>
                <h2 class="ui-title !text-[1.55rem] md:!text-[1.7rem]">Detail Pembayaran Pasien</h2>
                <p class="ui-subtitle">Validasi bukti pembayaran dan konfirmasi status pelunasan.</p>
            </div>
        </div>
    </div>

    @if($errors->has('pembayaran'))
        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ $errors->first('pembayaran') }}
        </div>
    @endif

    <div class="ui-surface mb-6">
        <div class="ui-panel-body grid md:grid-cols-2 gap-4">
            <div class="ui-field">
                <label class="ui-label">Pasien</label>
                <p class="ui-input">{{ $pembayaran->daftarPoli?->pasien?->nama ?? '-' }} ({{ $pembayaran->daftarPoli?->pasien?->no_rm ?? '-' }})</p>
            </div>

            <div class="ui-field">
                <label class="ui-label">Poli / Dokter</label>
                <p class="ui-input">{{ $pembayaran->daftarPoli?->jadwalPeriksa?->dokter?->poli?->nama_poli ?? '-' }} - {{ $pembayaran->daftarPoli?->jadwalPeriksa?->dokter?->nama ?? '-' }}</p>
            </div>

            <div class="ui-field">
                <label class="ui-label">Tanggal Periksa</label>
                <p class="ui-input">{{ optional($periksa?->tgl_periksa)->format('d-m-Y H:i') ?? '-' }}</p>
            </div>

            <div class="ui-field">
                <label class="ui-label">Jumlah Tagihan</label>
                <p class="ui-input">Rp {{ number_format((int) $pembayaran->jumlah_tagihan, 0, ',', '.') }}</p>
            </div>

            <div class="ui-field">
                <label class="ui-label">Tanggal Pembayaran</label>
                <p class="ui-input">{{ optional($pembayaran->tanggal_pembayaran)->format('d-m-Y H:i') ?? '-' }}</p>
            </div>

            <div class="ui-field">
                <label class="ui-label">Status</label>
                <p class="ui-input">{{ strtoupper($pembayaran->status) }}</p>
            </div>

            <div class="ui-field md:col-span-2">
                <label class="ui-label">Bukti Pembayaran</label>
                @if($pembayaran->bukti_file)
                    <a href="{{ asset('storage/' . $pembayaran->bukti_file) }}" target="_blank" class="ui-btn-soft">
                        <i class="fas fa-image"></i>
                        Lihat Bukti Pembayaran
                    </a>
                @else
                    <p class="ui-input">Belum ada bukti pembayaran diunggah.</p>
                @endif
            </div>

            @if($pembayaran->status === 'lunas')
                <div class="ui-field md:col-span-2">
                    <label class="ui-label">Verifikasi</label>
                    <p class="ui-input">
                        Dikonfirmasi pada {{ optional($pembayaran->tanggal_verifikasi)->format('d-m-Y H:i') ?? '-' }}
                        oleh {{ $pembayaran->verifier?->nama ?? '-' }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    @if($pembayaran->status !== 'lunas')
        <div class="ui-surface">
            <div class="ui-panel-body">
                <form method="POST" action="{{ route('admin.pembayaran.konfirmasi', $pembayaran->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="ui-btn-primary" onclick="return confirm('Konfirmasi pembayaran ini sebagai lunas?')">
                        <i class="fas fa-check"></i>
                        Konfirmasi Lunas
                    </button>
                </form>
            </div>
        </div>
    @endif

</x-layouts.app>
