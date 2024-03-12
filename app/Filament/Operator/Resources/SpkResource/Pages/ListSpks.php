<?php

namespace App\Filament\Operator\Resources\SpkResource\Pages;

use App\Filament\Operator\Resources\SpkResource;
use Filament\Actions;
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
}
