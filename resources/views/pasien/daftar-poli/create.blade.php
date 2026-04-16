<x-layouts.app title="Daftar Poli">

    <div class="ui-page-head">
        <div class="flex items-center gap-3">
            <a href="{{ route('pasien.daftar-poli.index') }}" class="ui-btn-soft !w-10 !h-10 !p-0">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>

            <div>
                <h2 class="ui-title !text-[1.55rem] md:!text-[1.7rem]">Daftar Poli</h2>
                <p class="ui-subtitle">Pilih poli, dokter, dan jadwal yang tersedia.</p>
            </div>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-body">
            <form action="{{ route('pasien.daftar-poli.store') }}" method="POST" id="daftarPoliForm">
                @csrf

                <div class="grid md:grid-cols-2 gap-4 mb-5">
                    <div class="ui-field">
                        <label class="ui-label">Pilih Poli <span class="text-red-500">*</span></label>
                        <select id="poliSelect" class="ui-input" required>
                            <option value="">Pilih poli</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                            @endforeach
                        </select>
                        <p class="ui-help">Langkah 1: pilih poli terlebih dahulu.</p>
                    </div>

                    <div class="ui-field">
                        <label class="ui-label">Pilih Dokter <span class="text-red-500">*</span></label>
                        <select id="dokterSelect" class="ui-input" required disabled>
                            <option value="">Pilih dokter</option>
                            @foreach($dokters as $dokter)
                                <option value="{{ $dokter->id }}" data-poli="{{ $dokter->id_poli }}">{{ $dokter->nama }} - {{ $dokter->poli?->nama_poli ?? '-' }}</option>
                            @endforeach
                        </select>
                        <p class="ui-help">Langkah 2: pilih dokter sesuai poli.</p>
                    </div>
                </div>

                <div class="ui-field mb-5">
                    <label class="ui-label">Pilih Jadwal <span class="text-red-500">*</span></label>
                    <select name="id_jadwal" id="jadwalSelect" class="ui-input @error('id_jadwal') is-error @enderror" required disabled>
                        <option value="">Pilih jadwal</option>
                        @foreach($jadwals as $jadwal)
                            <option value="{{ $jadwal->id }}" data-dokter="{{ $jadwal->id_dokter }}" @selected(old('id_jadwal') == $jadwal->id)>
                                {{ ucfirst($jadwal->hari) }} ({{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_jadwal')<p class="ui-error">{{ $message }}</p>@enderror
                </div>

                <div class="ui-field mb-6">
                    <label class="ui-label">Keluhan <span class="text-red-500">*</span></label>
                    <textarea name="keluhan" rows="4" class="ui-textarea @error('keluhan') is-error @enderror" placeholder="Tuliskan keluhan Anda..." required>{{ old('keluhan') }}</textarea>
                    @error('keluhan')<p class="ui-error">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="ui-btn-primary"><i class="fas fa-save"></i> Simpan Pendaftaran</button>
                    <a href="{{ route('pasien.daftar-poli.index') }}" class="ui-btn-soft">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const poliSelect = document.getElementById('poliSelect');
            const dokterSelect = document.getElementById('dokterSelect');
            const jadwalSelect = document.getElementById('jadwalSelect');

            function resetSelect(select, placeholder) {
                select.value = '';
                Array.from(select.options).forEach((opt, idx) => {
                    if (idx === 0) {
                        opt.textContent = placeholder;
                        opt.hidden = false;
                        return;
                    }
                    opt.hidden = false;
                });
            }

            poliSelect.addEventListener('change', () => {
                const poliId = poliSelect.value;
                dokterSelect.disabled = !poliId;
                resetSelect(dokterSelect, 'Pilih dokter');
                resetSelect(jadwalSelect, 'Pilih jadwal');
                jadwalSelect.disabled = true;

                Array.from(dokterSelect.options).forEach((opt, idx) => {
                    if (idx === 0) return;
                    opt.hidden = opt.dataset.poli !== poliId;
                });
            });

            dokterSelect.addEventListener('change', () => {
                const dokterId = dokterSelect.value;
                jadwalSelect.disabled = !dokterId;
                resetSelect(jadwalSelect, 'Pilih jadwal');

                Array.from(jadwalSelect.options).forEach((opt, idx) => {
                    if (idx === 0) return;
                    opt.hidden = opt.dataset.dokter !== dokterId;
                });
            });
        </script>
    @endpush

</x-layouts.app>
