<?php

namespace App\Providers;

use App\Models\UserLogs;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class LogoutEventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(Logout::class, function (Logout $event) {
            UserLogs::create([
                'user_id' => $event->user->id,
                'action_event' => 'logout',
                'guard' => $event->guard,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });
    }
}
