<x-layouts.app title="Edit Obat">

    <div class="ui-page-head">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.obat.index') }}" class="ui-btn-soft !w-10 !h-10 !p-0">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>

            <div>
                <h2 class="ui-title !text-[1.55rem] md:!text-[1.7rem]">Edit Obat</h2>
                <p class="ui-subtitle">Perbarui detail obat agar data resep tetap valid.</p>
            </div>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-body">
            <form action="{{ route('admin.obat.update', $obat->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="ui-field mb-5">
                    <label class="ui-label">Nama Obat <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" class="ui-input @error('nama_obat') is-error @enderror" required>
                    @error('nama_obat')<p class="ui-error">{{ $message }}</p>@enderror
                </div>

                <div class="ui-field mb-5">
                    <label class="ui-label">Kemasan <span class="text-red-500">*</span></label>
                    <input type="text" name="kemasan" value="{{ old('kemasan', $obat->kemasan) }}" class="ui-input @error('kemasan') is-error @enderror" required>
                    @error('kemasan')<p class="ui-error">{{ $message }}</p>@enderror
                </div>

                <div class="ui-field mb-6">
                    <label class="ui-label">Harga <span class="text-red-500">*</span></label>
                    <input type="number" min="0" name="harga" value="{{ old('harga', $obat->harga) }}" class="ui-input @error('harga') is-error @enderror" required>
                    @error('harga')<p class="ui-error">{{ $message }}</p>@enderror
                </div>

                <div class="ui-field mb-6">
                    <label class="ui-label">Stok <span class="text-red-500">*</span></label>
                    <input type="number" min="0" name="stok" value="{{ old('stok', $obat->stok) }}" class="ui-input @error('stok') is-error @enderror" required>
                    @error('stok')<p class="ui-error">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="ui-btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('admin.obat.index') }}" class="ui-btn-soft">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
