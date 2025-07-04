<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Filament\Admin\Resources\ProductResource\RelationManagers;
use App\Filament\Exports\ProductExporter;
use App\Filament\Imports\ProductImporter;
use App\Models\Curriculum;
use App\Models\EducationClass;
use App\Models\EducationLevel;
use App\Models\EducationSubject;
use App\Models\Product;
use App\Models\Semester;
use App\Models\Type;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = 'Master Produksi';

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $modelLabel = 'Produk';

    protected static ?Product $lastProduct = null;

    public static function getLastProduct(): ?Product
    {
        return self::$lastProduct ??= Product::latest('created_at')->first();
    }

    public static function form(Form $form): Form
    {
        if (static::getLastProduct()) {
            static::$lastProduct = static::getLastProduct();
        }

        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'md' => 4,
                        'sm' => 1,
                    ])
                    ->hiddenOn(['view'])
                    ->schema([
                        Forms\Components\Select::make('semester_id')
                            ->label('Semester')
                            ->searchable()
                            ->options(
                                Semester::all()->pluck('name', 'id'),
                            )
                            ->default(fn () => static::$lastProduct ?->semester_id)
                            ->columnSpan([
                                'md' => 2,
                                'sm' => 1,
                            ])
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('curriculum_id')
                            ->label('Kurikulum')
                            ->searchable()
                            ->options(
                                Curriculum::all()->pluck('name', 'id'),
                            )
                            ->default(fn () => static::$lastProduct ?->curriculum_id)
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('education_level_id')
                            ->label('Jenjang')
                            ->searchable()
                            ->options(
                                EducationLevel::all()->pluck('name', 'id')
                            )
                            ->default(fn () => static::$lastProduct ?->education_level_id)
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('education_subject_id')
                            ->label('Mata Pelajaran')
                            ->searchable()
                            ->default(fn () => static::$lastProduct ?->education_subject_id)
                            ->columnSpan([
                                'md' => 2,
                                'sm' => 1,
                            ])
                            ->options(
                                EducationSubject::all()->pluck('name', 'id')
                            )
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('education_class_id')
                            ->label('Kelas')
                            ->searchable()
                            ->options(
                                EducationClass::all()->pluck('name', 'id')
                            )
                            ->default(fn () => static::$lastProduct ?->education_class_id)
                            ->reactive()
                            ->required(),
                        Forms\Components\Select::make('type_id')
                            ->name('Type')
                            ->searchable()
                            ->reactive()
                            ->options(
                                Type::all()->pluck('name', 'id'),
                            )
                            ->default(fn () => static::$lastProduct ?->type_id)
                            ->required(),
                    ]),
                Section::make()
                    ->columns(1)
                    ->hiddenOn(['view'])
                    ->schema([
                        Forms\Components\Placeholder::make('name')
                            ->label('Nama Produk')
                            ->content(function (callable $set, $get) {
                                $semester_id = $get('semester_id');
                                $curriculum_id = $get('curriculum_id');
                                $education_level_id = $get('education_level_id');
                                $type_id = $get('type_id');

                                $semester = Semester::find($semester_id)->name ?? '-';
                                $semesterCode = Semester::find($semester_id)->code ?? '-';
                                $curriculum = Curriculum::find($curriculum_id)->name ?? '-';
                                $curriculumCode = Curriculum::find($curriculum_id)->code ?? '-';
                                $level = EducationLevel::find($education_level_id)->name ?? '-';
                                $levelCode = EducationLevel::find($education_level_id)->code ?? '-';
                                $class = EducationClass::find($get('education_class_id'))->name ?? '-';
                                $classCode = EducationClass::find($get('education_class_id'))->code ?? '-';
                                $subject = EducationSubject::find($get('education_subject_id'))->name ?? '-';
                                $subjectCode = EducationSubject::find($get('education_subject_id'))->code ?? '-';
                                $type = Type::find($type_id)->name ?? '-';
                                $typeCode = Type::find($type_id)->code ?? '-';

                                $productName = "{$level} - KELAS {$class} - {$subject} - KURIKULUM {$curriculum} - {$semester} - ({$type})  |  C-{$levelCode}{$curriculumCode}{$subjectCode}{$classCode}{$semesterCode}/{$typeCode}";

                                $set('name', $productName);

                                return $productName;
                            }),
                        Forms\Components\Hidden::make('name')
                            ->unique()
                            ->validationMessages([
                                'unique' => 'The product has already been registered.',
                            ]),
                    ]),
                Section::make()
                    ->columns(2)
                    ->visibleOn(['view'])
                    ->schema([
                        Forms\Components\Placeholder::make('code')
                            ->label('Kode MMJ')
                            ->inlineLabel()
                            ->content(function (Product $record) {
                                return $record->code;
                            }),
                        Forms\Components\Placeholder::make('education_subject')
                            ->label('Mata Pelajaran')
                            ->inlineLabel()
                            ->content(function (Product $record) {
                                return $record->educationSubject->name;
                            }),
                        Forms\Components\Placeholder::make('education_grade')
                            ->label('Jenjang Kelas')
                            ->inlineLabel()
                            ->content(function (Product $record) {
                                return $record->educationLevel->name . ' - ' . $record->educationClass->name;
                            }),
                        Forms\Components\Placeholder::make('curriculum')
                            ->label('Kurikulum')
                            ->inlineLabel()
                            ->content(function (Product $record) {
                                return $record->curriculum->name;
                            }),
                        Forms\Components\Placeholder::make('semester')
                            ->label('Semester')
                            ->inlineLabel()
                            ->content(function (Product $record) {
                                return $record->semester->name;
                            }),
                        Forms\Components\Placeholder::make('type')
                            ->label('Tipe')
                            ->inlineLabel()
                            ->content(function (Product $record) {
                                return $record->type->name;
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode MMJ'),
                Tables\Columns\TextColumn::make('educationSubject.name')
                    ->label('Mapel')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('curriculum.code')
                    ->label('Kurikulum')
                    ->formatStateUsing(function (string $state) {
                        return Curriculum::firstWhere('code', $state)->name;
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('educationClass.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('educationLevel.name')
                    ->label('Jenjang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester.name')
                    ->label('Semester')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type.name')
                    ->searchable()
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
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('educationSubject')
                    ->label('Mapel')
                    ->searchable()
                    ->relationship('educationSubject', 'name'),
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->searchable()
                    ->relationship('type', 'name'),
                Tables\Filters\SelectFilter::make('semester')
                    ->label('Semester')
                    ->relationship('semester', 'name'),
                Tables\Filters\SelectFilter::make('educationLevel')
                    ->label('Jenjang')
                    ->relationship('educationLevel', 'name'),
                Tables\Filters\SelectFilter::make('educationClass')
                    ->label('Kelas')
                    ->relationship('educationClass', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\ImportAction::make()
                    ->importer(ProductImporter::class),
                Tables\Actions\ExportAction::make()
                    ->exporter(ProductExporter::class),
            ])
            ->defaultSort('created_at', 'desc')
            ->deferLoading();
    }

    protected function getProductCode($product): string
    {
        $semester = Semester::find($product->semester_id)->code ?? '-';
        $curriculum = Curriculum::find($product->curriculum_id)->code ?? '-';
        $level = EducationLevel::find($product->education_level_id)->code ?? '-';
        $class = EducationClass::find($product->education_class_id)->code ?? '-';
        $subject = EducationSubject::find($product->education_subject_id)->code ?? '-';
        $type = Type::find($product->type_id)->code ?? '-';

        return "C-{$level}{$curriculum}{$subject}{$class}{$semester}/{$type}";
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
            'view' => Pages\ViewProducts::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderProductsRelationManager::class,
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
