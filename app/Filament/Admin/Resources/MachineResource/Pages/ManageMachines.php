<?php

namespace App\Filament\Admin\Resources\MachineResource\Pages;

use App\Filament\Admin\Resources\MachineResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMachines extends ManageRecords
{
    protected static string $resource = MachineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
