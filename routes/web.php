<?php

use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PemeriksaanController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\Pasien\DaftarPoliController;
use App\Http\Controllers\Pasien\DashboardController;
use App\Http\Controllers\Pasien\PembayaranController;
use App\Http\Controllers\Pasien\RiwayatPeriksaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('polis', PoliController::class)->except('show');
    Route::resource('obat', ObatController::class)->except('show');
    Route::resource('dokter', DokterController::class)->except('show');

    Route::get('dokter/export', [DokterController::class, 'export'])->name('dokter.export');
    Route::get('pasien/export', [PasienController::class, 'export'])->name('pasien.export');
    Route::get('obat/export', [ObatController::class, 'export'])->name('obat.export');

    Route::resource('pasien', PasienController::class)->only(['index', 'show']);

    Route::get('pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/{pembayaran}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
    Route::patch('pembayaran/{pembayaran}/konfirmasi', [AdminPembayaranController::class, 'konfirmasi'])->name('pembayaran.konfirmasi');
});

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dashboard');

    Route::resource('jadwal-periksa', JadwalPeriksaController::class)->except('show');
    Route::get('jadwal-periksa/export', [JadwalPeriksaController::class, 'export'])->name('jadwal-periksa.export');
    Route::get('pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
    Route::get('pemeriksaan/{id}', [PemeriksaanController::class, 'show'])->name('pemeriksaan.show');
    Route::post('pemeriksaan/{id}', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');

    Route::get('riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('riwayat-pasien/export', [RiwayatPasienController::class, 'export'])->name('riwayat-pasien.export');
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('daftar-poli', DaftarPoliController::class)->only(['index', 'create', 'store']);
    Route::get('riwayat-periksa', [RiwayatPeriksaController::class, 'index'])->name('riwayat.index');
    Route::get('riwayat-periksa/{id}', [RiwayatPeriksaController::class, 'show'])->name('riwayat.show');

    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('pembayaran/{daftarPoli}/upload', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('pembayaran/{daftarPoli}', [PembayaranController::class, 'store'])->name('pembayaran.store');
});
