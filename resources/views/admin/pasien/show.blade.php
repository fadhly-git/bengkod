<x-layouts.app title="Detail Pasien">

    <div class="ui-page-head">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pasien.index') }}" class="ui-btn-soft !w-10 !h-10 !p-0">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>

            <div>
                <h2 class="ui-title !text-[1.55rem] md:!text-[1.7rem]">Detail Pasien</h2>
                <p class="ui-subtitle">Informasi ringkas data pasien.</p>
            </div>
        </div>
    </div>

    <div class="ui-surface">
        <div class="ui-panel-body">
            <div class="grid md:grid-cols-2 gap-5">
                <div class="ui-field">
                    <label class="ui-label">Nama</label>
                    <p class="ui-input">{{ $pasien->nama }}</p>
                </div>

                <div class="ui-field">
                    <label class="ui-label">Email</label>
                    <p class="ui-input">{{ $pasien->email }}</p>
                </div>

                <div class="ui-field">
                    <label class="ui-label">No. KTP</label>
                    <p class="ui-input">{{ $pasien->no_ktp ?? '-' }}</p>
                </div>

                <div class="ui-field">
                    <label class="ui-label">No. HP</label>
                    <p class="ui-input">{{ $pasien->no_hp ?? '-' }}</p>
                </div>

                <div class="ui-field md:col-span-2">
                    <label class="ui-label">Alamat</label>
                    <p class="ui-input">{{ $pasien->alamat ?? '-' }}</p>
                </div>

                <div class="ui-field md:col-span-2">
                    <label class="ui-label">No. Rekam Medis</label>
                    <p class="ui-input">{{ $pasien->no_rm ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
