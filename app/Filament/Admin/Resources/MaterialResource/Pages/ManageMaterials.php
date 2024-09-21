<?php

namespace App\Filament\Admin\Resources\MaterialResource\Pages;

use App\Filament\Admin\Resources\MaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMaterials extends ManageRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
