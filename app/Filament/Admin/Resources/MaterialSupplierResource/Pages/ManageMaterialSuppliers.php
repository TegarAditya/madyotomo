<?php

namespace App\Filament\Admin\Resources\MaterialSupplierResource\Pages;

use App\Filament\Admin\Resources\MaterialSupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMaterialSuppliers extends ManageRecords
{
    protected static string $resource = MaterialSupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
