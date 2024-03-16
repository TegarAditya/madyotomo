<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Order', fn () => Order::withoutTrashed()->count())
                ->icon('heroicon-o-envelope')
                ->url('orders'),
            Stat::make('Pelanggan', fn () => Customer::withoutTrashed()->count())
                ->icon('heroicon-o-identification')
                ->url('customers'),
            Stat::make('Produk', fn () => Product::withoutTrashed()->count())
                ->icon('heroicon-o-beaker')
                ->url('products'),
        ];
    }
}
