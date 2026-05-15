<?php

namespace App\Providers;

use App\Models\Pelanggan;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Perangkat;
use App\Models\Pertanyaan;
use App\Models\Notifikasi;
use App\Models\PermintaanUpgrade;
use App\Models\Tagihan;
use App\Models\Tiket;
use App\Models\Teknisi;
use App\Policies\PemasukanPolicy;
use App\Policies\PengeluaranPolicy;
use App\Policies\PelangganPolicy;
use App\Policies\PertanyaanPolicy;
use App\Policies\NotifikasiPolicy;
use App\Policies\PermintaanUpgradePolicy;
use App\Policies\PerangkatPolicy;
use App\Policies\TagihanPolicy;
use App\Policies\TiketPolicy;
use App\Policies\TeknisiPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; 

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
        Gate::policy(Pelanggan::class, PelangganPolicy::class);
        Gate::policy(Perangkat::class, PerangkatPolicy::class);
        Gate::policy(Teknisi::class, TeknisiPolicy::class);
        Gate::policy(Tiket::class, TiketPolicy::class);
        Gate::policy(Pengeluaran::class, PengeluaranPolicy::class);
        Gate::policy(Pemasukan::class, PemasukanPolicy::class);
        Gate::policy(Pertanyaan::class, PertanyaanPolicy::class);
        Gate::policy(Notifikasi::class, NotifikasiPolicy::class);
        Gate::policy(PermintaanUpgrade::class, PermintaanUpgradePolicy::class);
        Gate::policy(Tagihan::class, TagihanPolicy::class);

         // Daftarkan semua Blade Component UI
        Blade::component('ui.badge',         \App\View\Components\Ui\Badge::class);
        Blade::component('ui.stat-card',     \App\View\Components\Ui\StatCard::class);
        Blade::component('ui.flash-message', \App\View\Components\Ui\FlashMessage::class);
        Blade::component('layout.sidebar',   \App\View\Components\Layout\Sidebar::class);
        Blade::component('layout.navbar',    \App\View\Components\Layout\Navbar::class);
    }
}
