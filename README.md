# Poliklinik App

Aplikasi manajemen klinik berbasis Laravel 11 untuk mengelola data poli, jadwal dokter, pendaftaran pasien, pemeriksaan, dan obat.

## Tech Stack

- PHP 8.2
- Laravel 11
- SQLite/MySQL
- Vite
- Tailwind CSS
- PHPUnit

## Fitur Inti

- Manajemen data poli
- Manajemen jadwal periksa dokter
- Pendaftaran pasien ke poli dan nomor antrian
- Pencatatan pemeriksaan
- Pengelolaan detail pemeriksaan dan resep obat

## Struktur Domain

Entitas utama:

- User (Dokter dan Pasien)
- Poli
- JadwalPeriksa
- DaftarPoli
- Periksa
- DetailPeriksa
- Obat

Relasi penting:

- Dokter memiliki banyak jadwal periksa
- Pasien mendaftar ke poli melalui jadwal periksa
- Setiap pendaftaran menghasilkan data pemeriksaan
- Pemeriksaan memiliki detail tindakan/hasil dan obat

## Instalasi

1. Install dependency backend.

```bash
composer install
```

2. Install dependency frontend.

```bash
npm install
```

3. Siapkan file environment.

```bash
cp .env.example .env
php artisan key:generate
```

4. Inisialisasi database.

```bash
php artisan migrate
php artisan db:seed
```

## Menjalankan Aplikasi

Jalankan backend:

```bash
php artisan serve
```

Jalankan frontend (mode development):

```bash
npm run dev
```

Build aset production:

```bash
npm run build
```

## Testing dan Quality

Jalankan test:

```bash
php artisan test
```

Atau dengan phpunit:

```bash
./vendor/bin/phpunit
```

Cek/format style code (PSR-12):

```bash
./vendor/bin/pint
```

## Struktur Direktori Singkat

```text
app/
	Http/Controllers/
	Models/
database/
	migrations/
	seeders/
resources/
	views/
	css/
	js/
routes/
	web.php
```

## Status Pengembangan

Sudah tersedia:

- Skema database utama
- Model dan relasi inti
- Integrasi Vite + Tailwind
- Setup pengujian dengan PHPUnit

Roadmap berikutnya:

- CRUD controller dan halaman per fitur
- Implementasi autentikasi/otorisasi
- Penguatan business logic antrian dan jadwal
- Penambahan feature test untuk endpoint utama

## Referensi

- Laravel Documentation: https://laravel.com/docs/11.x
- Eloquent ORM: https://laravel.com/docs/11.x/eloquent
- Migrations: https://laravel.com/docs/11.x/migrations
- Testing: https://laravel.com/docs/11.x/testing
