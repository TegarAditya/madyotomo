<?php

namespace App\Filament\Admin\Resources\ProductResource\RelationManagers;

use App\Models\OrderProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderProducts';

    protected static ?string $title = 'SPK Terkait';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderProduct::query()->whereHas('order')->where('product_id', $this->ownerRecord->id))
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('order.proof_number')
                    ->label('No. SPK')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.name')
                    ->label('Order'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('result')
                    ->label('Hasil')
                    ->numeric()
                    ->default(0),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'gray',
                        'SPK Dibuat' => 'primary',
                        'Dicetak' => 'info',
                        'Dikirim' => 'success',
                        'Ditolak' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Pending' => 'heroicon-o-clock',
                        'SPK Dibuat' => 'heroicon-o-clipboard-document-check',
                        'Dicetak' => 'heroicon-o-printer',
                        'Dikirim' => 'heroicon-o-truck',
                        'Ditolak' => 'heroicon-o-ban',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('spk')
                    ->label('Lihat SPK')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.admin.resources.orders.view', ['record' => $record->order])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
