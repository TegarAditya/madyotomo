<?php

namespace App\Filament\Resources\EducationSubjectResource\Pages;

use App\Filament\Resources\EducationSubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEducationSubjects extends ManageRecords
{
    protected static string $resource = EducationSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
