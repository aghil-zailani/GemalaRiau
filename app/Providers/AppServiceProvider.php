<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Models\Advertisement;

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
        config(['app.timezone' => 'Asia/Jakarta']);
            date_default_timezone_set(config('app.timezone'));
            Carbon::setLocale('id');

        View::composer('*', function ($view) {            
            $globalAds = Advertisement::where('is_active', true)
                                      ->get()
                                      ->groupBy('position');
            $view->with('globalAds', $globalAds);
        });
    }
}
