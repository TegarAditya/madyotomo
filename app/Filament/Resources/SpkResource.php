<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpkResource\Pages;
use App\Filament\Resources\SpkResource\RelationManagers;
use App\Models\Machine;
use App\Models\Order;
use App\Models\Spk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpkResource extends Resource
{
    protected static ?string $model = Spk::class;

    protected static ?string $navigationGroup = 'Master Produksi';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order')
                    ->options(
                        Order::all()->pluck('name', 'id')
                    )
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('document_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('report_number')
                    ->required(),
                Forms\Components\DatePicker::make('entry_date')
                    ->required(),
                Forms\Components\DatePicker::make('deadline_date')
                    ->required(),
                Forms\Components\Select::make('machine_id')
                    ->options(
                        Machine::all()->pluck('name', 'id')
                    )
                    ->required(),
                Forms\Components\Select::make('print_type')
                    ->required()
                    ->options([
                        'Cetak' => 'Cetak',
                        'Cetak Potong' => 'Cetak Potong',
                    ]),
                Forms\Components\TextInput::make('spare')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('note')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('report_number')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('entry_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('machine_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('print_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('spare')
                    ->searchable(),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListSpks::route('/'),
            'create' => Pages\CreateSpk::route('/create'),
            'view' => Pages\ViewSpk::route('/{record}'),
            'edit' => Pages\EditSpk::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
