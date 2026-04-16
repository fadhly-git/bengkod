<x-layouts.app title="Tambah Dokter">

    <div class="ui-page-head">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dokter.index') }}" class="ui-btn-soft !w-10 !h-10 !p-0">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>

            <div>
                <h2 class="ui-title !text-[1.55rem] md:!text-[1.7rem]">Tambah Dokter</h2>
                <p class="ui-subtitle">Isi data akun dokter beserta poli penugasannya.</p>
            </div>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-body">
            <form action="{{ route('admin.dokter.store') }}" method="POST">
                @csrf

                <div class="grid md:grid-cols-2 gap-4 mb-5">
                    <div class="ui-field">
                        <label class="ui-label">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="ui-input @error('nama') is-error @enderror" required>
                        @error('nama')<p class="ui-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="ui-field">
                        <label class="ui-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="ui-input @error('email') is-error @enderror" required>
                        @error('email')<p class="ui-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-5">
                    <div class="ui-field">
                        <label class="ui-label">No. KTP <span class="text-red-500">*</span></label>
                        <input type="text" name="no_ktp" value="{{ old('no_ktp') }}" class="ui-input @error('no_ktp') is-error @enderror" required>
                        @error('no_ktp')<p class="ui-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="ui-field">
                        <label class="ui-label">No. HP <span class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="ui-input @error('no_hp') is-error @enderror" required>
                        @error('no_hp')<p class="ui-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="ui-field mb-5">
                    <label class="ui-label">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="3" class="ui-textarea @error('alamat') is-error @enderror" required>{{ old('alamat') }}</textarea>
                    @error('alamat')<p class="ui-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div class="ui-field">
                        <label class="ui-label">Poli <span class="text-red-500">*</span></label>
                        <select name="id_poli" class="ui-input @error('id_poli') is-error @enderror" required>
                            <option value="">Pilih poli</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id }}" @selected(old('id_poli') == $poli->id)>{{ $poli->nama_poli }}</option>
                            @endforeach
                        </select>
                        @error('id_poli')<p class="ui-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="ui-field">
                        <label class="ui-label">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="ui-input @error('password') is-error @enderror" required>
                        @error('password')<p class="ui-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="ui-field mb-6">
                    <label class="ui-label">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" class="ui-input" required>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="ui-btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('admin.dokter.index') }}" class="ui-btn-soft">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
