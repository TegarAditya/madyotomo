<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MaterialUsageResource\Pages;
use App\Models\MaterialUsage;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaterialUsageResource extends Resource
{
    protected static ?string $model = MaterialUsage::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Master Bahan';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Pemakaian Bahan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make()
                    ->label('Data Pemakaian')
                    ->schema([
                        Forms\Components\DatePicker::make('usage_date')
                            ->label('Tanggal Pemakaian')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->hidden()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Fieldset::make()
                    ->label('Bahan yang Digunakan')
                    ->columns(1)
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->hiddenLabel()
                            ->columns(3)
                            ->schema([
                                Forms\Components\Select::make('material_id')
                                    ->label('Bahan')
                                    ->relationship('material', 'name')
                                    ->live()
                                    ->required(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->suffix(function ($get) {
                                        $unit = \App\Models\Material::find($get('material_id'));
                                        if (! $unit) {
                                            return '';
                                        }

                                        return $unit->unit;
                                    })
                                    ->numeric()
                                    ->required(),
                                Forms\Components\Select::make('machine_id')
                                    ->label('Untuk Mesin')
                                    ->relationship('machine', 'name')
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('usage_date')
                    ->date()
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
            'index' => Pages\ListMaterialUsages::route('/'),
            'create' => Pages\CreateMaterialUsage::route('/create'),
            'view' => Pages\ViewMaterialUsage::route('/{record}'),
            'edit' => Pages\EditMaterialUsage::route('/{record}/edit'),
        ];
    }
}
