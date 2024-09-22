<?php

namespace App\Filament\Admin\Resources\MaterialPurchaseResource\Pages;

use App\Filament\Admin\Resources\MaterialPurchaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaterialPurchase extends EditRecord
{
    protected static string $resource = MaterialPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
