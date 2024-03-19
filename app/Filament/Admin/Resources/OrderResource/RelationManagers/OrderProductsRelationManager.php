<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

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
                    ->default(fn (stdClass $rowLoop) => $rowLoop->index + 1),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Kode MMJ')
                    ->toggleable()
                    ->formatStateUsing(function (string $state) {
                        $parts = explode('|', $state);
                        return trim($parts[1]);
                    }),
                Tables\Columns\TextColumn::make('product.educationSubject.name')
                    ->label('Mapel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.educationClass.name')
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('product.Curriculum.name')
                    ->label('Kurikulum'),
                Tables\Columns\TextColumn::make('product.Type.name')
                    ->label('Tipe'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Oplah')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make(),
                    ]),
                Tables\Columns\TextColumn::make('status')
                    ->default('Pending')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'gray',
                        'Dicetak' => 'warning',
                        'Dikirim' => 'success',
                        'Ditolak' => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Pending' => 'heroicon-o-clock',
                        'Dicetak' => 'heroicon-o-printer',
                        'Dikirim' => 'heroicon-o-truck',
                        'Ditolak' => 'heroicon-o-ban',
                    }),
            ])
            ->filters([
                //
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
