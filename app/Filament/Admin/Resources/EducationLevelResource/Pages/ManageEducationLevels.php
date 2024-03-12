<?php

namespace App\Filament\Admin\Resources\EducationLevelResource\Pages;

use App\Filament\Admin\Resources\EducationLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEducationLevels extends ManageRecords
{
    protected static string $resource = EducationLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
