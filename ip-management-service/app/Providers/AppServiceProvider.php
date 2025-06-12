<?php

namespace App\Providers;

use App\Interfaces\IpAddressInterface;
use App\Models\IpAddress;
use App\Observers\IpAddressObserver;
use App\Repositories\IpAddressRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            IpAddressInterface::class,
            IpAddressRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        IpAddress::observe(IpAddressObserver::class);
    }
}
