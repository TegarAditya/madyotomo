<?php

namespace App\Filament\Admin\Resources\PaperResource\Pages;

use App\Filament\Admin\Resources\PaperResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePapers extends ManageRecords
{
    protected static string $resource = PaperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
