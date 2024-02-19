<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\SpksRelationManager;
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
use Closure;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Master Produksi';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public int $semester_id;

    public int $curriculum_id;

    public int $education_level_id;

    public int $type_id;

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
                            })
                            ->hidden(),
                        Forms\Components\TextInput::make('document_number')
                            ->name('Nomor Order')
                            ->default("-/MT/OC/-/-/-"),
                        Forms\Components\TextInput::make('proof_number')
                            ->name('Nomor Bukti')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->name('Nama Order')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('customer_id')
                            ->name('Customer')
                            ->options(
                                Customer::all()->pluck('name', 'id'),
                            )
                            ->reactive()
                            ->searchable()
                            ->required(),
                        Forms\Components\DatePicker::make('entry_date')
                            ->name('Tanggal Masuk')
                            ->default((new DateTime())->format('Y-m-d H:i:s'))
                            ->reactive()
                            ->required(),
                        Forms\Components\DatePicker::make('deadline_date')
                            ->name('Tanggal Deadline')
                            ->required(),
                        Forms\Components\Select::make('paper_id')
                            ->name('Kertas')
                            ->options(
                                Paper::all()->pluck('name', 'id'),
                            )
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('finished_size')
                            ->name('Ukuran Jadi')
                            ->required()
                            ->numeric()
                            ->suffix('mm'),
                        Forms\Components\TextInput::make('material_size')
                            ->name('Ukuran Bahan')
                            ->required()
                            ->numeric()
                            ->suffix('mm'),
                    ]),
                Section::make('Products')
                    ->collapsed()
                    ->collapsible()
                    ->schema([
                        Section::make()
                            ->columns(3)
                            ->schema([
                                Forms\Components\Select::make('semester_id')
                                    ->label('Semester')
                                    ->options(
                                        Semester::all()->pluck('name', 'id'),
                                    )
                                    ->afterStateUpdated(function ($state) {
                                        $this->semester_id = $state;
                                    })
                                    ->reactive()
                                    ->required(),
                                Forms\Components\Select::make('curriculum_id')
                                    ->label('Kurikulum')
                                    ->options(
                                        Curriculum::all()->pluck('name', 'id'),
                                    )
                                    ->afterStateUpdated(function ($state) {
                                        $this->curriculum_id = $state;
                                    })
                                    ->reactive()
                                    ->required(),
                                Forms\Components\Select::make('education_level_id')
                                    ->label('Jenjang')
                                    ->options(
                                        EducationLevel::all()->pluck('name', 'id')
                                    )
                                    ->afterStateUpdated(function ($state) {
                                        $this->education_level_id = $state;
                                    })
                                    ->reactive()
                                    ->required(),
                                Forms\Components\Select::make('type_id')
                                    ->name('Type')
                                    ->afterStateUpdated(function ($state) {
                                        $this->type_id = $state;
                                    })
                                    ->reactive()
                                    ->options(
                                        Type::all()->pluck('name', 'id'),
                                    )
                                    ->required(),
                            ]),
                        Forms\Components\Repeater::make('products')
                            ->relationship()
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->collapsible()
                                    ->collapsed()
                                    ->schema([
                                        Forms\Components\Select::make('education_class_id')
                                            ->label('Kelas')
                                            ->options(
                                                EducationClass::all()->pluck('name', 'id')
                                            )
                                            ->reactive()
                                            ->required(),
                                        Forms\Components\Select::make('education_subject_id')
                                            ->label('Mata Pelajaran')
                                            ->options(
                                                EducationSubject::all()->pluck('name', 'id')
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
                                                $semester = Semester::find($this->semester_id)->name ?? "-";
                                                $semesterCode = Semester::find($this->semester_id)->code ?? "-";
                                                $curriculum = Curriculum::find($this->curriculum_id)->name ?? "-";
                                                $curriculumCode = Curriculum::find($this->curriculum_id)->code ?? "-";
                                                $level = EducationLevel::find($get($this->education_level_id))->name ?? "-";
                                                $levelCode = EducationLevel::find($get($this->education_level_id))->code ?? "-";
                                                $class = EducationClass::find($get('education_class_id'))->name ?? "-";
                                                $classCode = EducationClass::find($get('education_class_id'))->code ?? "-";
                                                $subject = EducationSubject::find($get('education_subject_id'))->name ?? "-";
                                                $subjectCode = EducationSubject::find($get('education_subject_id'))->code ?? "-";
                                                $type = Type::find($get('type_id'))->name ?? "-";

                                                $productName = "{$subject} - {$level} - KELAS {$class} - KURIKULUM {$curriculum} - {$semester} - ({$type})  |  C-{$levelCode}{$curriculumCode}{$subjectCode}{$classCode}{$semesterCode}/{$type}";

                                                $set('name', $productName);

                                                return $productName;
                                            }),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Produk')
                                            ->hidden()
                                            ->required(),
                                        Forms\Components\TextInput::make('quantity')
                                            ->name('Jumlah')
                                            ->numeric()
                                            ->suffix('oplah')
                                            ->required(),
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
