<?php

namespace App\Filament\Operator\Resources\SpkResource\Pages;

use App\Filament\Operator\Resources\SpkResource;
use App\Models\Semester;
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

        $semesters = Semester::latest()->take(3)->pluck('name', 'id')->toArray();

        $semesters = array_reverse($semesters, true);

        foreach ($semesters as $id => $name) {
            $tabs[$id] = Tab::make($name)->query(function ($query) use ($id) {
                return $query->whereHas('order', function ($q) use ($id) {
                    $q->where('semester_id', $id);
                });
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
