<?php

namespace App\Filament\Admin\Resources\MaterialPurchaseResource\Pages;

use App\Filament\Admin\Resources\MaterialPurchaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMaterialPurchase extends ViewRecord
{
    protected static string $resource = MaterialPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
