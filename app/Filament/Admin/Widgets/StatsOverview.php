<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Semester;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Order', fn () => Order::withoutTrashed()->count())
                ->icon('heroicon-o-envelope')
                ->url('orders'),
            Stat::make('Order Semester Berjalan', fn() => $this->getThisSemesterOrderCount())
                ->icon('heroicon-o-envelope')
                ->url('orders'),
            Stat::make('Produk', fn() => Product::withoutTrashed()->count())
                ->icon('heroicon-o-beaker')
                ->url('products'),
        ];
    }

    protected function getThisSemesterOrderCount()
    {
        $currentSemester = Semester::orderBy('id', 'desc')->first();

        $startDate = $currentSemester->start_date;
        $endDate = $currentSemester->end_date;

        return Order::withoutTrashed()
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->count();
    }
}
