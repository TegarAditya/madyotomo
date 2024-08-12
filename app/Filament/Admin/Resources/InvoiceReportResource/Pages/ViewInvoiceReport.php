<?php

namespace App\Filament\Admin\Resources\InvoiceReportResource\Pages;

use App\Filament\Admin\Resources\InvoiceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoiceReport extends ViewRecord
{
    protected static string $resource = InvoiceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Export')
                ->color('info')
                ->action(fn ($record) => $record->getInvoiceReportDocument($record->start_date, $record->end_date)),
            Actions\EditAction::make(),
        ];
    }
}
