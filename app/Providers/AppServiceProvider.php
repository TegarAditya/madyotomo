<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
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
