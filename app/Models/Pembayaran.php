<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'id_daftar_poli',
        'jumlah_tagihan',
        'status',
        'bukti_file',
        'tanggal_pembayaran',
        'tanggal_verifikasi',
        'verified_by',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

    public function daftarPoli()
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
