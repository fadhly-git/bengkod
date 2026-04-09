<x-layouts.app title="Tambah Poli">

    <div class="ui-page-head">
        <div class="flex items-center gap-3">
            <a href="{{ route('polis.index') }}" class="ui-btn-soft !w-10 !h-10 !p-0">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>

            <div>
                <h2 class="ui-title !text-[1.55rem] md:!text-[1.7rem]">Tambah Poli</h2>
                <p class="ui-subtitle">Lengkapi informasi poli baru yang akan ditampilkan pada sistem.</p>
            </div>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-body">

            <form action="{{ route('polis.store') }}" method="POST">
                @csrf

                <div class="ui-field mb-5">
                    <label class="ui-label">
                        Nama Poli <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="nama_poli" value="{{ old('nama_poli') }}"
                        placeholder="Masukkan nama poli..."
                        class="ui-input @error('nama_poli') is-error @enderror"
                        required>

                    @error('nama_poli')
                    <p class="ui-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="ui-field mb-6">
                    <label class="ui-label">
                        Keterangan <span class="text-red-500">*</span>
                    </label>

                    <textarea name="keterangan" rows="4" placeholder="Masukkan keterangan poli..."
                        class="ui-textarea resize-y min-h-[120px] @error('keterangan') is-error @enderror"
                        required>{{ old('keterangan') }}</textarea>
                    <p class="ui-help">Contoh: Poli ini menangani pemeriksaan dan konsultasi kesehatan jantung.</p>

                    @error('keterangan')
                    <p class="ui-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="ui-btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>

                    <a href="{{ route('polis.index') }}" class="ui-btn-soft">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-layouts.app>
