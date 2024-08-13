<?php

namespace App\Filament\Admin\Resources\InvoiceReportResource\Pages;

use App\Filament\Admin\Resources\InvoiceReportResource;
use App\Models\Invoice;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoiceReport extends CreateRecord
{
    protected static string $resource = InvoiceReportResource::class;

    protected function afterCreate(): void
    {
        $start_date = $this->record->start_date;
        $end_date = $this->record->end_date;
        $customer_id = $this->record->customer_id;

        $this->record->invoices()->attach(
            Invoice::whereHas('order', function ($query) use ($start_date, $end_date, $customer_id) {
                $query->where('customer_id', $customer_id)
                    ->whereBetween('entry_date', [$start_date, $end_date]);
            })->get()
        );
    }
}
