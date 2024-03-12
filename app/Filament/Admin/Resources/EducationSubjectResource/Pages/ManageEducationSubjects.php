<?php

namespace App\Filament\Admin\Resources\EducationSubjectResource\Pages;

use App\Filament\Admin\Resources\EducationSubjectResource;
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
