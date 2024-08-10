<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InvoiceReportResource\Pages;
use App\Filament\Admin\Resources\InvoiceReportResource\RelationManagers\InvoicesRelationManager;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceReportResource extends Resource
{
    protected static ?string $model = InvoiceReport::class;

    protected static ?string $modelLabel = 'Rekap Invoice';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Order';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make()
                    ->label('Nomor Dokumen')
                    ->schema([
                        Forms\Components\Placeholder::make('document_number_create')
                            ->hiddenLabel()
                            ->visibleOn('create')
                            ->content(function (Get $get, Set $set) {
                                if ($get('entry_date') && $get('customer_id')) {
                                    $document_number = (new static)->getInvoiceReportNumber($get('entry_date'), $get('customer_id'));

                                    $set('document_number', $document_number);

                                    return $document_number;
                                }

                                return 'Lengkapi kolom di bawah ini';
                            }),
                        Forms\Components\Placeholder::make('document_number_view')
                            ->hiddenLabel()
                            ->hiddenOn('create')
                            ->content(fn ($record) => $record->document_number),
                        Forms\Components\Hidden::make('document_number'),
                    ]),
                Forms\Components\Fieldset::make()
                    ->label('Detail')
                    ->hiddenOn('view')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Pelanggan')
                            ->relationship('customer', 'name')
                            ->default(Customer::count() === 1 ? Customer::first()->getKey() : null)
                            ->live()
                            ->required(),
                        Forms\Components\DatePicker::make('entry_date')
                            ->label('Tanggal Buat')
                            ->live()
                            ->required(),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Awal')
                            ->live()
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Akhir')
                            ->live()
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->default('-')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Fieldset::make()
                    ->label('Detail')
                    ->visibleOn('view')
                    ->schema([
                        Forms\Components\Placeholder::make('customer')
                            ->label('Pelanggan')
                            ->content(fn ($record) => $record->customer->name),
                        Forms\Components\Placeholder::make('entry_date')
                            ->label('Tanggal Buat')
                            ->content(fn ($record) => $record->entry_date),
                        Forms\Components\Placeholder::make('start_date')
                            ->label('Tanggal Awal')
                            ->content(fn ($record) => $record->start_date),
                        Forms\Components\Placeholder::make('end_date')
                            ->label('Tanggal Akhir')
                            ->content(fn ($record) => $record->end_date),
                        Forms\Components\Placeholder::make('description')
                            ->label('Deskripsi')
                            ->content(fn ($record) => $record->description)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Fieldset::make()
                    ->label('Daftar Invoice Terkait')
                    ->hiddenOn('view')
                    ->schema([
                        Forms\Components\Placeholder::make('invoices')
                            ->hiddenLabel()
                            ->content(function (callable $get) {
                                $start = $get('start_date');
                                $end = $get('end_date');

                                return Invoice::whereBetween('entry_date', [$start, $end])->orderBy('entry_date')->get()->map(function (Invoice $invoice) {
                                    return $invoice->document_number;
                                })->implode(', ');
                            })
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Nomor Dokumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.code')
                    ->label('Pelanggan')
                    ->tooltip(fn ($record) => $record->customer->name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Tanggal Dibuat')
                    ->date('d-m-Y', 'Asia/Jakarta')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Awal')
                    ->date('d-m-Y', 'Asia/Jakarta')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Akhir')
                    ->date('d-m-Y', 'Asia/Jakarta')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn ($record) => $record->getInvoiceReportDocument()),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            InvoicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoiceReports::route('/'),
            'create' => Pages\CreateInvoiceReport::route('/create'),
            'view' => Pages\ViewInvoiceReport::route('/{record}'),
            'edit' => Pages\EditInvoiceReport::route('/{record}/edit'),
        ];
    }

    protected function getInvoiceReportNumber(string $entryDate, int $customerId): string
    {
        $latestOrder = InvoiceReport::orderBy('created_at', 'desc')->first()->document_number ?? null;
        $latestNumber = (int) (strpos($latestOrder, '/') !== false ? substr($latestOrder, 0, strpos($latestOrder, '/')) : 0);

        $used_number = (Invoice::all()->first()) ? $latestNumber + 1 : 1;
        $customer = Customer::find($customerId)->code ?? 'XXX';
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

        $document_number = "{$used_number}/MT/RTJC/{$customer}/{$romanMonth}/{$year}";

        return $document_number;
    }
}
