<?php

namespace App\Filament\Admin\Resources\InvoiceReportResource\Pages;

use App\Filament\Admin\Resources\InvoiceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoiceReport extends EditRecord
{
    protected static string $resource = InvoiceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
