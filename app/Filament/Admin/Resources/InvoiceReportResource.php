<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InvoiceReportResource\Pages;
use App\Filament\Admin\Resources\InvoiceReportResource\RelationManagers;
use App\Models\Invoice;
use App\Models\InvoiceReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
                Forms\Components\TextInput::make('document_number')
                    ->required()
                    ->hiddenOn(['create'])
                    ->maxLength(255),
                Forms\Components\Placeholder::make('document_number')
                    ->content(function (callable $get) {
                        return $get('document_number') ?? 'Auto-generated';
                    })
                    ->hiddenOn(['edit']),
                Forms\Components\DatePicker::make('entry_date')
                    ->live()
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('invoice_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entry_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_id')
                    ->numeric()
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            //
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

    protected function getInvoiceReportNumber(string $entryDate): string
    {
        $latestOrder = InvoiceReport::orderBy('created_at', 'desc')->first()->document_number ?? null;
        $latestNumber = (int) (strpos($latestOrder, '/') !== false ? substr($latestOrder, 0, strpos($latestOrder, '/')) : 0);

        $used_number = (Invoice::all()->first()) ? $latestNumber + 1 : 1;
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

        $document_number = "{$used_number}/MT/RTJC/{$customer}/{$romanMonth}/{$year}";

        return $document_number;
    }
}
