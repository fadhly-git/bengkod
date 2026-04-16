<x-layouts.app title="Edit Jadwal Periksa">

    <div class="ui-page-head">
        <div class="flex items-center gap-3">
            <a href="{{ route('dokter.jadwal-periksa.index') }}" class="ui-btn-soft !w-10 !h-10 !p-0">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>

            <div>
                <h2 class="ui-title !text-[1.55rem] md:!text-[1.7rem]">Edit Jadwal Periksa</h2>
                <p class="ui-subtitle">Perbarui hari dan jam praktik Anda.</p>
            </div>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-body">
            <form action="{{ route('dokter.jadwal-periksa.update', $jadwal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="ui-field mb-5">
                    <label class="ui-label">Hari <span class="text-red-500">*</span></label>
                    <select name="hari" class="ui-input @error('hari') is-error @enderror" required>
                        <option value="">Pilih hari</option>
                        @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $hari)
                            <option value="{{ $hari }}" @selected(old('hari', $jadwal->hari) === $hari)>{{ ucfirst($hari) }}</option>
                        @endforeach
                    </select>
                    @error('hari')<p class="ui-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div class="ui-field">
                        <label class="ui-label">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}" class="ui-input @error('jam_mulai') is-error @enderror" required>
                        @error('jam_mulai')<p class="ui-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="ui-field">
                        <label class="ui-label">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_selesai" value="{{ old('jam_selesai', substr($jadwal->jam_selesai, 0, 5)) }}" class="ui-input @error('jam_selesai') is-error @enderror" required>
                        @error('jam_selesai')<p class="ui-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="ui-btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('dokter.jadwal-periksa.index') }}" class="ui-btn-soft">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
