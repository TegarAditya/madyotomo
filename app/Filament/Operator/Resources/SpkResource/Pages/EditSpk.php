<?php

namespace App\Filament\Operator\Resources\SpkResource\Pages;

use App\Filament\Operator\Resources\SpkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpk extends EditRecord
{
    protected static string $resource = SpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
