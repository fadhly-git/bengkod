<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daftar_poli', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_jadwal');

            $table->text('keluhan');
            $table->integer('no_antrian');

            $table->timestamps();

            $table->foreign('id_pasien')
                ->references('id')->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_jadwal')
                ->references('id')->on('jadwal_periksa')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // opsional tapi umum: no_antrian unik per jadwal per tanggal (kalau ada tanggal daftar)
            // kalau tidak ada tanggal daftar, minimal unik per id_jadwal:
            $table->unique(['id_jadwal', 'no_antrian']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daftar_poli');
    }
};
