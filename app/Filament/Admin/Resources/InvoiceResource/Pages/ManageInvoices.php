<?php

namespace App\Filament\Admin\Resources\InvoiceResource\Pages;

use App\Filament\Admin\Resources\InvoiceResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageInvoices extends ManageRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data) {
                    $data['document_number'] = $this->getInvoiceNumber($data['entry_date'], $data['order_id']);

                    $data['due_date'] = $data['due_date'] ?? now()->addDays(7)->format('Y-m-d');

                    return $data;
                }),
        ];
    }

    protected function getInvoiceNumber(string $entryDate, int $order_id): string
    {
        $order = Order::find($order_id);
        $used_number = strstr($order->document_number, '/', true);
        $customer = $order->customer->code;
        $month = (new \DateTime('@' . strtotime($entryDate)))->format('m');
        $year = (new \DateTime('@' . strtotime($entryDate)))->format('Y');
        $romanNumerals = [
            '01' => 'I',
            '02' => 'II',
            '03' => 'III',
            '04' => 'IV',
            '05' => 'V',
            '06' => 'VI',
            '07' => 'VII',
            '08' => 'VIII',
            '09' => 'IX',
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII',
        ];
        $romanMonth = $romanNumerals[$month];

        $document_number = "{$used_number}/MT/FT/{$customer}/{$romanMonth}/{$year}";

        return $document_number;
    }
}
