<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Master Data')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Master Produksi')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Master Order'),
                NavigationGroup::make()
                    ->label(fn () => config('filament-users.group')),
                NavigationGroup::make()
                    ->label(__('filament-shield::filament-shield.nav.group'))
                    ->collapsed(),
            ]);
        });
    }
}
