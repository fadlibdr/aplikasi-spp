<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public static function home()
    {
        if (auth()->check()) {
            if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('operator')) {
                return route('dashboard.admin');
            }
            if (auth()->user()->hasRole('siswa')) {
                return route('dashboard.student');
            }
        }
        return '/';
    }

    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
