<?php

namespace App\Filament\Admin\Resources\MaterialUsageResource\Pages;

use App\Filament\Admin\Resources\MaterialUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMaterialUsage extends ViewRecord
{
    protected static string $resource = MaterialUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
