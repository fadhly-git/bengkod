<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_daftar_poli')->unique();
            $table->integer('jumlah_tagihan');
            $table->string('status')->default('pending');
            $table->string('bukti_file')->nullable();
            $table->dateTime('tanggal_pembayaran')->nullable();
            $table->dateTime('tanggal_verifikasi')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamps();

            $table->foreign('id_daftar_poli')
                ->references('id')->on('daftar_poli')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('verified_by')
                ->references('id')->on('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
