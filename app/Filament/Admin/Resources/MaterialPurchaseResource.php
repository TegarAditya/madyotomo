<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MaterialPurchaseResource\Pages;
use App\Filament\Admin\Resources\MaterialPurchaseResource\RelationManagers;
use App\Models\MaterialPurchase;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class MaterialPurchaseResource extends Resource
{
    protected static ?string $model = MaterialPurchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Master Bahan';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Pembelian Bahan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('purhase_tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Pembelian')
                            ->schema([
                                // Writable fields
                                Forms\Components\Fieldset::make()
                                    ->label('Data Pembelian')
                                    ->hiddenOn('view')
                                    ->schema([
                                        Forms\Components\Select::make('material_supplier_id')
                                            ->label('Supplier')
                                            ->relationship('materialSupplier', 'name')
                                            ->required(),
                                        Forms\Components\TextInput::make('proof_number')
                                            ->label('Nomor Faktur')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('purchase_date')
                                            ->label('Tanggal Pembelian')
                                            ->required(),
                                        Forms\Components\DatePicker::make('paid_off_date')
                                            ->label('Tanggal Pelunasan'),
                                        Forms\Components\ToggleButtons::make('is_paid')
                                            ->label('Lunas?')
                                            ->inline()
                                            ->options([
                                                0 => 'Belum',
                                                1 => 'Sudah',
                                            ])
                                            ->required()
                                            ->live()
                                            ->hiddenOn('view'),
                                    ]),

                                // Read-only fields
                                Forms\Components\Fieldset::make()
                                    ->label('Data Pembelian')
                                    ->schema([
                                        Forms\Components\Placeholder::make('material_supplier_id')
                                            ->label('Supplier')
                                            ->content(fn(MaterialPurchase $record) => $record->materialSupplier->name),
                                        Forms\Components\Placeholder::make('proof_number')
                                            ->label('Nomor Faktur')
                                            ->content(fn(MaterialPurchase $record) => $record->proof_number),
                                        Forms\Components\Placeholder::make('purchase_date')
                                            ->label('Tanggal Pembelian')
                                            ->content(fn(MaterialPurchase $record) => $record->purchase_date),
                                        Forms\Components\Placeholder::make('paid_off_date')
                                            ->label('Tanggal Pelunasan')
                                            ->content(fn(MaterialPurchase $record) => $record->paid_off_date ?? '-'),
                                    ])
                                    ->visibleOn('view'),
                                Forms\Components\Fieldset::make()
                                    ->label('Catatan Pelunasan')
                                    ->schema([
                                        Forms\Components\Placeholder::make('notes_ph')
                                            ->hiddenLabel()
                                            ->hidden(fn($get) => $get('is_paid'))
                                            ->hiddenOn('view')
                                            ->content(function (MaterialPurchase $record) {
                                                if (! $record->is_paid) {
                                                    return 'Catatan pelunasan akan muncul setelah Anda memilih "Sudah" pada pilihan "Lunas?" di atas.';
                                                }

                                                return new HtmlString($record->notes ?? '-');
                                            })
                                            ->columnSpanFull(),
                                        Forms\Components\Placeholder::make('paid_notes')
                                            ->hiddenLabel()
                                            ->visibleOn('view')
                                            ->content(function (MaterialPurchase $record) {
                                                return new HtmlString($record->notes ?? '-');
                                            })
                                            ->columnSpanFull(),
                                        Forms\Components\RichEditor::make('notes')
                                            ->hidden(fn($get) => ! $get('is_paid'))
                                            ->hiddenOn('view')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Item Pembelian')
                            ->hiddenOn('view')
                            ->schema([
                                Repeater::make('items')
                                    ->relationship('items')
                                    ->columns([
                                        'lg' => 4
                                    ])
                                    ->schema([
                                        Forms\Components\Select::make('material_id')
                                            ->label('Bahan')
                                            ->relationship('material', 'name')
                                            ->live()
                                            ->afterStateUpdated(function ($state, $set) {
                                                $material = $state;
                                                $latestMaterialPurchase = \App\Models\MaterialPurchaseItem::latest()
                                                    ->whereHas('materialPurchase', function (Builder $query) use ($material) {
                                                        $query->where('material_id', $material);
                                                    })
                                                    ->first();
                                                $set('price', $latestMaterialPurchase ? $latestMaterialPurchase->price : 0);
                                            })
                                            ->required(),
                                        Forms\Components\TextInput::make('quantity')
                                            ->label('Jumlah')
                                            ->required()
                                            ->numeric()
                                            ->live(),
                                        Forms\Components\TextInput::make('price')
                                            ->label('Harga Satuan')
                                            ->required()
                                            ->numeric()
                                            ->live(),
                                        Forms\Components\Placeholder::make('total')
                                            ->label('Total')
                                            ->content(function (Get $get) {
                                                $quantity = intval($get('quantity')) ?? 0;
                                                $price = intval($get('price')) ?? 0;
                                                $total = $quantity * $price;
                                                return new HtmlString('<span class="text-right">Rp. ' . formatNumber($total) . '</span>');
                                            }),
                                    ]),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('materialSupplier.name')
                    ->label('Supplier')
                    ->sortable(),
                Tables\Columns\TextColumn::make('proof_number')
                    ->label('Nomor Faktur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_date')
                    ->label('Tanggal Pembelian')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Lunas?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('paid_off_date')
                    ->label('Tanggal Pelunasan')
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
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterialPurchases::route('/'),
            'create' => Pages\CreateMaterialPurchase::route('/create'),
            'view' => Pages\ViewMaterialPurchase::route('/{record}'),
            'edit' => Pages\EditMaterialPurchase::route('/{record}/edit'),
        ];
    }
}
