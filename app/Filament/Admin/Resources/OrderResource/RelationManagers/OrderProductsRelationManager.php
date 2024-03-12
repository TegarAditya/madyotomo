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
                Tables\Columns\TextColumn::make('product.educationSubject.name')
                    ->label('Mapel'),
                Tables\Columns\TextColumn::make('product.educationClass.name')
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('product.Curriculum.name')
                    ->label('Kurikulum'),
                Tables\Columns\TextColumn::make('product.Type.name')
                    ->label('Tipe'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->default(function ($record) {
                        if ($record->spks->count() > 0) {
                            return 'Sudah Cetak';
                        } else if ($record->deliveryOrders->count() > 0) {
                            return 'Sudah Dikirim';
                        } else {
                            return 'Pending';
                        }
                    })
                    ->badge()
                    ->color(function ($record) {
                        if ($record->spks->count() > 0) {
                            return 'success';
                        } else if ($record->deliveryOrders->count() > 0) {
                            return 'primary';
                        } else {
                            return 'danger';
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
