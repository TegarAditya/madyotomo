<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'document_number',
        'entry_date',
        'start_date',
        'end_date',
        'description',
    ];

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_report_invoice')->withTimestamps();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getInvoiceReportDocument()
    {
        $document = $this;
        $invoices = $this->invoices()->with([
            'order' => function ($query) {
                $query->select(['id', 'proof_number', 'name']);
            },
        ])->get()->map(function ($invoice) {
            return [
                'document_number' => $invoice['document_number'],
                'proof_number' => $invoice['order']['proof_number'],
                'order_name' => $invoice['order']['name'],
                'entry_date' => date('d-m-Y', strtotime($invoice['entry_date'])),
                'price' => $invoice['price'],
                'quantity' => $invoice['order']['order_products']->sum('quantity'),
                'amount' => $invoice['price'] * $invoice['order']['order_products']->sum('quantity'),
            ];
        })->toArray();

        $total_order = collect($invoices)->sum('quantity');
        $total_amount = collect($invoices)->sum('amount');

        $data = [
            'report' => [
                'entry_date' => date('d-m-Y', strtotime($document->entry_date)),
                'document_number' => $document->document_number,
                'customer' => $document->customer->name,
                'representative' => $document->customer->representative,
                'address' => $document->customer->address,
                'phone' => $document->customer->phone,
            ],
            'invoice' => $invoices,
            'total' => [
                'quantity' => $total_order,
                'amount' => $total_amount,
            ],
        ];

        return response()->streamDownload(function () use ($data) {
            echo (new \AnourValar\Office\SheetsService())
                ->generate(resource_path() . '/template/invoice_summary_template.xlsx', $data)
                ->save(\AnourValar\Office\Format::Xlsx);
        }, str_replace('/', '_', $this->document_number) . '.xlsx');
    }
}
