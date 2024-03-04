<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Curriculum;
use App\Models\Customer;
use App\Models\EducationClass;
use App\Models\EducationLevel;
use App\Models\EducationSubject;
use App\Models\Machine;
use App\Models\Order;
use App\Models\Paper;
use App\Models\Semester;
use App\Models\Type;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Master Order';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Order')
                    ->columns([
                        'md' => 2
                    ])
                    ->schema([
                        Forms\Components\Placeholder::make('document_number_pc')
                            ->label('Nomor Order')
                            ->content(function (callable $set, $get) {
                                $latestOrder = Order::orderBy('created_at', 'desc')->first()->document_number ?? null;
                                $latestNumber = (int) explode('/', $latestOrder)[0] ?? 0;

                                $nomorTerakhir = (Order::all()->first()) ? $latestNumber + 2 : 1;
                                $month = (new DateTime('@' . strtotime($get('entry_date'))))->format('m');
                                $year = (new DateTime('@' . strtotime($get('entry_date'))))->format('Y');
                                $customer = Customer::find($get('customer_id')) ? Customer::find($get('customer_id'))->code : '-';
                                $romanNumerals = [
                                    '01' => 'I',
                                    '02' => 'II',
                                    '03' => 'III',
                                    '04' => 'IV',
                                    '05' => 'V',
                                    '06' => 'VI',
                                    '07' => 'VII',
                                    '08' => 'VIII',
                                    '09' => 'IX',
                                    '10' => 'X',
                                    '11' => 'XI',
                                    '12' => 'XII',
                                ];
                                $romanMonth = $romanNumerals[$month];

                                $set('document_number', "{$nomorTerakhir}/MT/OC/{$customer}/{$romanMonth}/{$year}");

                                return "{$nomorTerakhir}/MT/OC/{$customer}/{$romanMonth}/{$year}";
                            }),
                        Forms\Components\Hidden::make('document_number')
                            ->default("-/MT/OC/-/-/-"),
                        Forms\Components\TextInput::make('proof_number')
                            ->label('Nomor Bukti')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Order')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->options(
                                Customer::all()->pluck('name', 'id'),
                            )
                            ->reactive()
                            ->searchable()
                            ->required(),
                        Forms\Components\DatePicker::make('entry_date')
                            ->label('Tanggal Masuk')
                            ->default((new DateTime())->format('Y-m-d H:i:s'))
                            ->reactive()
                            ->required(),
                        Forms\Components\DatePicker::make('deadline_date')
                            ->label('Tanggal Deadline')
                            ->required(),
                        Forms\Components\Select::make('paper_id')
                            ->label('Kertas')
                            ->options(
                                Paper::all()->pluck('name', 'id'),
                            )
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('paper_config')
                            ->label('Paper Config')
                            ->options(
                                Machine::distinct()->pluck('paper_config', 'paper_config'),
                            )
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('finished_size')
                            ->label('Ukuran Jadi')
                            ->required()
                            ->numeric()
                            ->suffix('mm'),
                        Forms\Components\TextInput::make('material_size')
                            ->label('Ukuran Bahan')
                            ->required()
                            ->numeric()
                            ->suffix('mm'),
                    ]),
                Section::make('Produk')
                    ->collapsed()
                    ->collapsible()
                    ->hiddenOn(['view'])
                    ->schema([
                        Section::make()
                            ->columns(4)
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
                            ]),
                        Forms\Components\Repeater::make('products')
                            ->relationship()
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->collapsible()
                                    ->schema([
                                        Forms\Components\Select::make('education_subject_id')
                                            ->label('Mata Pelajaran')
                                            ->searchable()
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
                                            ->reactive()
                                            ->required(),
                                    ]),
                                Section::make()
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\Placeholder::make('name')
                                            ->label('Nama Produk')
                                            ->content(function (callable $set, $get) {
                                                $semester_id = $get('../../semester_id');
                                                $curriculum_id = $get('../../curriculum_id');
                                                $education_level_id = $get('../../education_level_id');
                                                $type_id = $get('../../type_id');

                                                $semester = Semester::find($semester_id)->name ?? "-";
                                                $semesterCode = Semester::find($semester_id)->code ?? "-";
                                                $curriculum = Curriculum::find($curriculum_id)->name ?? "-";
                                                $curriculumCode = Curriculum::find($curriculum_id)->code ?? "-";
                                                $level = EducationLevel::find($education_level_id)->name ?? "-";
                                                $levelCode = EducationLevel::find($education_level_id)->code ?? "-";
                                                $class = EducationClass::find($get('education_class_id'))->name ?? "-";
                                                $classCode = EducationClass::find($get('education_class_id'))->code ?? "-";
                                                $subject = EducationSubject::find($get('education_subject_id'))->name ?? "-";
                                                $subjectCode = EducationSubject::find($get('education_subject_id'))->code ?? "-";
                                                $type = Type::find($type_id)->name ?? "-";
                                                $typeCode = Type::find($type_id)->code ?? "-";

                                                $productName = "{$level} - KELAS {$class} - {$subject} - KURIKULUM {$curriculum} - {$semester} - ({$type})  |  C-{$levelCode}{$curriculumCode}{$subjectCode}{$classCode}{$semesterCode}/{$typeCode}";

                                                $set('name', $productName);
                                                $set('semester_id', $semester_id);
                                                $set('curriculum_id', $curriculum_id);
                                                $set('education_level_id', $education_level_id);
                                                $set('type_id', $type_id);

                                                return $productName;
                                            }),
                                        Forms\Components\TextInput::make('quantity')
                                            ->name('Jumlah')
                                            ->numeric()
                                            ->suffix('oplah')
                                            ->required(),
                                        Forms\Components\Hidden::make('name'),
                                        Forms\Components\Hidden::make('semester_id'),
                                        Forms\Components\Hidden::make('curriculum_id'),
                                        Forms\Components\Hidden::make('education_level_id'),
                                        Forms\Components\Hidden::make('type_id'),
                                    ]),
                            ])
                            ->addActionLabel('Add product')
                            ->defaultItems(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Order')
                    ->searchable(isIndividual: true)
                    ->columnSpanFull(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Nomor Order')
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('proof_number')
                    ->label('Nomor Bukti')
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('paper.name')
                    ->label('Kertas')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('finished_size')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('material_size')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('entry_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deadline_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            RelationManagers\SpksRelationManager::class,
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
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
