<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SpksRelationManager extends RelationManager
{
    protected static string $relationship = 'spks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('document_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('report_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('entry_date')
                    ->required(),
                Forms\Components\DatePicker::make('deadline_date')
                    ->required(),
                Forms\Components\Select::make('print_type')
                    ->options([
                        'cetak' => 'Cetak',
                        'cetak potong' => 'Cetak & Potong',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('spare'),
                Forms\Components\RichEditor::make('note')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('spkProducts')
                    ->relationship('spkProducts')
                    ->addActionLabel('Tambah Produk')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Placeholder::make('quantity')
                            ->content(function ($get) {
                                if (count($get('products')) > 1) {
                                    $products = $get('products');
                                    $totalQuantity = 0;

                                    foreach ($products as $product) {
                                        $quantity = (Product::find($product)->quantity) / 2;
                                        $totalQuantity += $quantity;
                                    }

                                    return $totalQuantity;
                                } else if (count($get('products')) === 1) {
                                    $quantity = Product::find($get('products')[0])->quantity;
                                    return $quantity;
                                } else {
                                    return 0;
                                }
                            }),
                        Forms\Components\Select::make('products')
                            ->multiple()
                            ->options(
                                Order::find($this->getOwnerRecord()->id)
                                    ->products
                                    ->mapWithKeys(function (Product $product) {
                                        $subject = $product->educationSubject()->pluck('name')->implode(' ');
                                        $class = $product->educationClass()->pluck('name')->implode(' ');
                                        $quantity = $product->quantity;
                                        return [$product->id => $subject . ' - ' . $class . ' - ' . ' (' . 'oplah ' . $quantity . ')'];
                                    })
                            )
                            ->reactive()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->required()
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('entry_date')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline_date')
                    ->sortable(),
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
