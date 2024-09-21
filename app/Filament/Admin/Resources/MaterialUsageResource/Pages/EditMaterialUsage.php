<?php

namespace App\Filament\Admin\Resources\MaterialUsageResource\Pages;

use App\Filament\Admin\Resources\MaterialUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaterialUsage extends EditRecord
{
    protected static string $resource = MaterialUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
