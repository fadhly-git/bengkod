<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('no_ktp')->unique()->nullable();
            $table->string('no_hp')->nullable();
            $table->string('no_rm')->unique()->nullable();

            $table->enum('role', ['admin', 'dokter', 'pasien']); // sesuaikan kebutuhan

            $table->unsignedBigInteger('id_poli')->nullable(); // dokter bisa punya poli
            $table->string('email')->unique();
            $table->string('password');

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_poli')
                ->references('id')->on('poli')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
