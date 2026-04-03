<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_periksa', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_periksa');
            $table->unsignedBigInteger('id_obat');

            $table->timestamps();

            $table->foreign('id_periksa')
                ->references('id')->on('periksa')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_obat')
                ->references('id')->on('obat')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // opsional: cegah obat dobel dalam 1 periksa
            $table->unique(['id_periksa', 'id_obat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_periksa');
    }
};
