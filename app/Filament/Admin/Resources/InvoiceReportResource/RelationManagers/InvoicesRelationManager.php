<?php

namespace App\Filament\Admin\Resources\InvoiceReportResource\RelationManagers;

use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    protected static ?string $title = 'Invoice terkait';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('document_number')
                    ->label('No. Invoice')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_number')
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('No. Invoice'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga/Druk')
                    ->money('IDR', locale: 'id-ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Jumlah')
                    ->numeric(decimalSeparator: ',', thousandsSeparator: '.')
                    ->default(fn (Invoice $record) => $record->order->order_products->sum('quantity')),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR', locale: 'id-ID')
                    ->default(fn (Invoice $record) => $record->price * $record->order->order_products->sum('quantity')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
