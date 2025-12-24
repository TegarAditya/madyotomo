<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Semester;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->fastPaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
    }

    public function getTabs(): array
    {
        $tabs = [
            null => Tab::make('All'),
        ];

        $semesters = Semester::latest()->take(3)->get();

        $semesters = $semesters->reverse();

        foreach ($semesters as $semester) {
            $tabs[$semester->id] = Tab::make($semester->name)->query(function ($query) use ($semester) {
                return $query
                    ->where('semester_id', $semester->id)
                    ->withCount(['invoices', 'deliveryOrders', 'spks']);
            });
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string|int|null
    {
        $default_tab = Semester::count() ?? 0;

        return $default_tab;
    }
}
