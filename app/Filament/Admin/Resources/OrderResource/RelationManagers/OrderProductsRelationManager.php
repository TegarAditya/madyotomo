<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use App\Models\OrderProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrderProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'order_products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.educationSubject.name')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('product.code')
                    ->label('Kode MMJ')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('product.educationSubject.name')
                    ->label('Mapel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.educationClass.name')
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('product.Curriculum.name')
                    ->label('Kurikulum'),
                Tables\Columns\TextColumn::make('product.Type.code')
                    ->label('Tipe')
                    ->tooltip(fn (OrderProduct $record) => $record->product->type->name),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Oplah')
                    ->numeric()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make(),
                    ]),
                Tables\Columns\TextColumn::make('result')
                    ->label('Hasil')
                    ->numeric()
                    ->default(0),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'gray',
                        'SPK Dibuat' => 'primary',
                        'Dicetak' => 'info',
                        'Dikirim' => 'success',
                        'Ditolak' => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Pending' => 'heroicon-o-clock',
                        'SPK Dibuat' => 'heroicon-o-clipboard-document-check',
                        'Dicetak' => 'heroicon-o-printer',
                        'Dikirim' => 'heroicon-o-truck',
                        'Ditolak' => 'heroicon-o-ban',
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
