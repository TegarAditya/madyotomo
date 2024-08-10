<?php

namespace App\Filament\Admin\Resources\InvoiceReportResource\Pages;

use App\Filament\Admin\Resources\InvoiceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoiceReports extends ListRecords
{
    protected static string $resource = InvoiceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
