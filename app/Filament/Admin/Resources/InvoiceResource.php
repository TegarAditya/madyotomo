<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InvoiceResource\Pages;
use App\Filament\Admin\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationGroup = 'Master Order';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Invoice';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->label('SPK Order')
                    ->options(fn () => Order::query()->get()->pluck('proof_number', 'id')->toArray())
                    ->disableOptionWhen(fn (string $value) => Order::find($value)->hasInvoice())
                    ->searchable(['name', 'proof_number', 'document_number'])
                    ->columnSpanFull()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $order = Order::find($state);

                        if ($order) {
                            $set('entry_date', Carbon::parse($order->entry_date)->addDays(14)->format('Y-m-d'));
                        }
                    })
                    ->required(),
                Forms\Components\TextInput::make('document_number')
                    ->label('Nomor Invoice')
                    ->hiddenOn(['create'])
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('document_number')
                    ->hiddenOn(['update']),
                Forms\Components\TextInput::make('entry_date')
                    ->label('Tanggal Input')
                    ->hiddenOn(['edit'])
                    ->required(),
                Forms\Components\DatePicker::make('entry_date')
                    ->label('Tanggal Input')
                    ->hiddenOn(['create'])
                    ->required(),
                Forms\Components\DatePicker::make('due_date')
                    ->label('Tanggal Jatuh Tempo')
                    ->default(now()->addDays(7)->format('Y-m-d'))
                    ->hiddenOn(['create'])
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->suffix('/druk')
                    ->numeric()
                    ->prefix('Rp'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.proof_number')
                    ->label('SPK Order')
                    ->tooltip(fn (Invoice $invoice) => $invoice->order->name)
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Nomor Invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga/Druk')
                    ->money('IDR', locale: 'id-ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Jumlah')
                    ->numeric(decimalSeparator: ',', thousandsSeparator: '.')
                    ->default(fn (Invoice $record) => $record->order->order_products->sum('quantity'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR', locale: 'id-ID')
                    ->default(fn (Invoice $record) => $record->price * $record->order->order_products->sum('quantity'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('pdf')
                    ->label('Download')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->authorize(true)
                    ->action(fn (Invoice $record) => (new static)->downloadInvoice($record)),
                Tables\Actions\Action::make('open')
                    ->label('Open Order')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Invoice $record): string => OrderResource::getUrl('edit', ['record' => $record->order->id]) . '?activeRelationManager=3'),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('entry_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInvoices::route('/'),
        ];
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
