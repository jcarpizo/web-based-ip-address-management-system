<?php

namespace App\Providers;

use App\Interfaces\IpAddressInterface;
use App\Interfaces\IpAddressLogsInterface;
use App\Models\IpAddress;
use App\Observers\IpAddressObserver;
use App\Repositories\IpAddressLogsRepository;
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
            IpAddressRepository::class,
        );

        $this->app->bind(
            IpAddressLogsInterface::class,
            IpAddressLogsRepository::class,
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
