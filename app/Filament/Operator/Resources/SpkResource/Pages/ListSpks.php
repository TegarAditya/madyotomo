<?php

namespace App\Filament\Operator\Resources\SpkResource\Pages;

use App\Filament\Operator\Resources\SpkResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListSpks extends ListRecords
{
    protected static string $resource = SpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            null => Tab::make('All'),
        ];

        $semesters = \App\Models\Semester::orderBy('created_at', 'asc')->take(2)->pluck('name', 'id')->toArray();

        foreach ($semesters as $id => $name) {
            $tabs[$id] = Tab::make($name)->query(function ($query) use ($id) {
                $semester = \App\Models\Semester::find($id);

                $startDate = $semester->start_date;
                $endDate = $semester->end_date;

                return $query->whereBetween('entry_date', [$startDate, $endDate]);
            });
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 2;
    }
}
