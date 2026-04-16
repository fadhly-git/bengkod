<?php

namespace App\Providers;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;
use App\Policies\DaftarPoliPolicy;
use App\Policies\JadwalPeriksaPolicy;
use App\Policies\PeriksaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(JadwalPeriksa::class, JadwalPeriksaPolicy::class);
        Gate::policy(DaftarPoli::class, DaftarPoliPolicy::class);
        Gate::policy(Periksa::class, PeriksaPolicy::class);
    }
}
