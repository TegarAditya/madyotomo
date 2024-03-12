<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use App\Models\Curriculum;
use App\Models\EducationLevel;
use App\Models\Semester;
use App\Models\Type;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('semester_id')
                    ->label('Semester')
                    ->searchable()
                    ->options(
                        Semester::all()->pluck('name', 'id'),
                    )
                    ->dehydrated(false)
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('curriculum_id')
                    ->label('Kurikulum')
                    ->searchable()
                    ->options(
                        Curriculum::all()->pluck('name', 'id'),
                    )
                    ->dehydrated(false)
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('education_level_id')
                    ->label('Jenjang')
                    ->searchable()
                    ->options(
                        EducationLevel::all()->pluck('name', 'id')
                    )
                    ->dehydrated(false)
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('type_id')
                    ->name('Type')
                    ->searchable()
                    ->reactive()
                    ->options(
                        Type::all()->pluck('name', 'id'),
                    )
                    ->dehydrated(false)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('educationSubject.name')
                    ->searchable()
                    ->sortable()
                    ->label('Mapel'),
                Tables\Columns\TextColumn::make('educationClass.name')
                    ->searchable()
                    ->sortable()
                    ->label('Kelas'),
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
