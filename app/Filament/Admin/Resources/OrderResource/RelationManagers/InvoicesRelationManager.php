<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use App\Models\Invoice;
use App\Models\OrderProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use NumberFormatter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    protected static ?string $title = 'Invoice';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('document_number_ph')
                    ->label('Nomor Invoice')
                    ->content(function (callable $set, $get) {
                        $document_number = $this->getInvoiceNumber($get('entry_date'));

                        $set('document_number', $document_number);

                        return $document_number;
                    }),
                Forms\Components\Hidden::make('document_number'),
                Forms\Components\TextInput::make('price')
                    ->label('Harga/Druk')
                    ->required()
                    ->mask(RawJs::make('$money($input)'))
                    ->prefix('Rp')
                    ->stripCharacters(',')
                    ->numeric(),
                Forms\Components\DatePicker::make('entry_date')
                    ->required()
                    ->default(now()->format('Y-m-d')),
                Forms\Components\DatePicker::make('due_date')
                    ->required()
                    ->default(now()->format('Y-m-d')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_number')
            ->columns([
                Tables\Columns\TextColumn::make('document_number'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('pdf')
                    ->label('Download')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->authorize(true)
                    ->action(fn (Invoice $record) => $this->downloadInvoice($record)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    protected function getInvoiceNumber(string $entryDate): string
    {
        $latestOrder = Invoice::orderBy('created_at', 'desc')->first()->document_number ?? null;
        $latestNumber = (int) (strpos($latestOrder, '/') !== false ? substr($latestOrder, 0, strpos($latestOrder, '/')) : 0);

        $nomorTerakhir = $latestNumber + 1;
        $customer = $this->getOwnerRecord()->customer->code;
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

        $document_number = "{$nomorTerakhir}/MT/FT/{$customer}/{$romanMonth}/{$year}";

        return $document_number;
    }

    protected function getProductQuantity(int $orderProductId): int
    {
        return OrderProduct::query()
            ->where('id', $orderProductId)
            ->value('quantity');
    }

    protected function downloadInvoice(Invoice $record): StreamedResponse
    {
        return response()->streamDownload(function () use ($record) {
            $invoiceItems = $record->order->orderProducts->map(function ($orderProduct) use ($record) {
                $productName = $orderProduct->product->educationSubject->name . ' - ' . $orderProduct->product->educationClass->name;
                $productQuantity = $orderProduct->quantity;
                $productPrice = $record->price * $productQuantity;

                return [
                    'product' => $productName,
                    'quantity' => number_format($productQuantity, 0, ',', '.'),
                    'price' => number_format($productPrice, 2, ',', '.'),
                ];
            });

            $totalQuantity = number_format($record->order->orderProducts->sum('quantity'), 0, ',', '.');
            $totalPrice = number_format($record->price * $record->order->orderProducts->sum('quantity'), 2, ',', '.');

            $total = [
                'quantity' => $totalQuantity,
                'price' => $totalPrice,
            ];

            $index = 1;

            echo Pdf::loadView('pdf.invoice', ['record' => $record, 'invoiceItems' => $invoiceItems, 'total' => $total, 'index' => $index])
                ->setOption(['defaultFont' => 'sans-serif'])
                ->setPaper('a4', 'portrait')
                ->stream();
        }, str_replace('/', '_', $record->document_number) . '.pdf');
    }
}
