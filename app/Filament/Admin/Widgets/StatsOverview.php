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
            Stat::make('Order', fn () => Order::withoutTrashed()->count()),
            Stat::make('Customer', fn () => Customer::withoutTrashed()->count()),
            Stat::make('Product', fn () => Product::withoutTrashed()->count()),
        ];
    }
}
