<?php

namespace App\Filament\Admin\Resources\EducationClassResource\Pages;

use App\Filament\Admin\Resources\EducationClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEducationClasses extends ManageRecords
{
    protected static string $resource = EducationClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
