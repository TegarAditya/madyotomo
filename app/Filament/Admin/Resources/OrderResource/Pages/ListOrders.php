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

        $semesters = \App\Models\Semester::latest()->take(3)->pluck('name', 'id')->toArray();

        $semesters = array_reverse($semesters, true);

        foreach ($semesters as $id => $name) {
            $tabs[$id] = Tab::make($name)->query(function ($query) use ($id) {
                $semester = \App\Models\Semester::find($id);

                if ($semester->code === '0126') return $query->where('semester_id', $id);

                $startDate = $semester->start_date;
                $endDate = $semester->end_date;

                return $query->whereBetween('entry_date', [$startDate, $endDate]);
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
