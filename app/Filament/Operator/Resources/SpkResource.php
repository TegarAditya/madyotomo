<?php

namespace App\Filament\Operator\Resources;

use App\Filament\Operator\Resources\SpkResource\Pages;
use App\Filament\Operator\Resources\SpkResource\RelationManagers;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->numeric(),
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
                Forms\Components\TextInput::make('paper_config')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('configuration')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('note')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('print_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('spare')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sort')
                    ->numeric(),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('entry_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paper_config')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('configuration')
                    ->searchable(),
                Tables\Columns\TextColumn::make('print_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('spare')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort')
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListSpks::route('/'),
            'create' => Pages\CreateSpk::route('/create'),
            'edit' => Pages\EditSpk::route('/{record}/edit'),
        ];
    }
}
