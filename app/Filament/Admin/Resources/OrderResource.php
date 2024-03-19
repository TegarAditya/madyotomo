<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Filament\Admin\Resources\OrderResource\RelationManagers;
use App\Models\Curriculum;
use App\Models\Customer;
use App\Models\EducationClass;
use App\Models\EducationLevel;
use App\Models\EducationSubject;
use App\Models\Machine;
use App\Models\Order;
use App\Models\Paper;
use App\Models\Product;
use App\Models\Semester;
use App\Models\Type;
use Carbon\Carbon;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Master Order';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('order_tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Order')
                            ->columns(2)
                            ->schema([
                                Forms\Components\Placeholder::make('document_number_pc')
                                    ->label('Nomor Order')
                                    ->content(function (callable $set, $get) {
                                        $latestOrder = Order::orderBy('created_at', 'desc')->first()->document_number ?? null;
                                        $latestNumber = (int) (strpos($latestOrder, '/') !== false ? substr($latestOrder, 0, strpos($latestOrder, '/')) : 0);

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
                                    ->visibleOn(['create', 'edit'])
                                    ->maxLength(255),
                                Forms\Components\Placeholder::make('proof_number_ph')
                                    ->label('Nomor Bukti')
                                    ->content(function ($get) {
                                        return $get('proof_number');
                                    })
                                    ->visibleOn(['view']),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Order')
                                    ->required()
                                    ->visibleOn(['create', 'edit'])
                                    ->maxLength(255),
                                Forms\Components\Placeholder::make('name_ph')
                                    ->label('Nama Order')
                                    ->content(function ($get) {
                                        return $get('name');
                                    })
                                    ->visibleOn(['view']),
                                Forms\Components\Select::make('customer_id')
                                    ->label('Customer')
                                    ->options(
                                        Customer::all()->pluck('name', 'id'),
                                    )
                                    ->reactive()
                                    ->searchable()
                                    ->required()
                                    ->visibleOn(['create', 'edit']),
                                Forms\Components\Placeholder::make('customer_ph')
                                    ->label('Customer')
                                    ->content(function ($get) {
                                        return (new HtmlString('<strong>' . Customer::find($get('customer_id'))->name . '</strong>'));
                                    })
                                    ->visibleOn(['view']),
                                Forms\Components\DatePicker::make('entry_date')
                                    ->label('Tanggal Masuk')
                                    ->default((new DateTime())->format('Y-m-d H:i:s'))
                                    ->reactive()
                                    ->required()
                                    ->visibleOn(['create', 'edit']),
                                Forms\Components\Placeholder::make('entry_date_ph')
                                    ->label('Tanggal Masuk')
                                    ->content(function ($get) {
                                        $dateString = Carbon::parse($get('entry_date'))->translatedFormat('l, j F Y');

                                        return (new HtmlString('<strong>' . $dateString . '</strong>'));
                                    })
                                    ->visibleOn(['view']),
                                Forms\Components\DatePicker::make('deadline_date')
                                    ->label('Tanggal Deadline')
                                    ->required()
                                    ->visibleOn(['create', 'edit']),
                                Forms\Components\Placeholder::make('deadline_date_ph')
                                    ->label('Tanggal Deadline')
                                    ->content(function ($get) {
                                        $dateString = Carbon::parse($get('deadline_date'))->translatedFormat('l, j F Y');

                                        return (new HtmlString('<strong>' . $dateString . '</strong>'));
                                    })
                                    ->visibleOn(['view']),
                                Forms\Components\Select::make('paper_id')
                                    ->label('Kertas')
                                    ->options(
                                        Paper::all()->pluck('name', 'id'),
                                    )
                                    ->searchable()
                                    ->required()
                                    ->visibleOn(['create', 'edit']),
                                Forms\Components\Placeholder::make('paper_ph')
                                    ->label('Kertas')
                                    ->content(fn ($get) => Paper::find($get('paper_id'))->name)
                                    ->visibleOn(['view']),
                                Forms\Components\Select::make('paper_config')
                                    ->label('Paper Config')
                                    ->options(
                                        Machine::distinct()->pluck('paper_config', 'paper_config'),
                                    )
                                    ->searchable()
                                    ->required()
                                    ->visibleOn(['create', 'edit']),
                                Forms\Components\Placeholder::make('paper_config_ph')
                                    ->label('Paper Config')
                                    ->content(fn ($get) => $get('paper_config'))
                                    ->visibleOn(['view']),
                                Forms\Components\TextInput::make('finished_size')
                                    ->label('Ukuran Jadi')
                                    ->required()
                                    ->suffix('cm')
                                    ->visibleOn(['create', 'edit']),
                                Forms\Components\Placeholder::make('finished_size_ph')
                                    ->label('Ukuran Jadi')
                                    ->content(fn ($get) => $get('finished_size'))
                                    ->visibleOn(['view']),
                                Forms\Components\TextInput::make('material_size')
                                    ->label('Ukuran Bahan')
                                    ->required()
                                    ->suffix('cm')
                                    ->visibleOn(['create', 'edit']),
                                Forms\Components\Placeholder::make('material_size_ph')
                                    ->label('Ukuran Bahan')
                                    ->content(fn ($get) => $get('material_size'))
                                    ->visibleOn(['view']),
                            ]),
                        Forms\Components\Tabs\Tab::make('Tambah Produk')
                            ->hiddenOn(['view'])
                            ->schema([
                                Section::make('Filter')
                                    ->columns(4)
                                    ->schema([
                                        Forms\Components\Select::make('semester_id')
                                            ->label('Semester')
                                            ->searchable()
                                            ->options(
                                                Semester::all()->pluck('name', 'id'),
                                            )
                                            ->dehydrated(false)
                                            ->reactive(),
                                        Forms\Components\Select::make('curriculum_id')
                                            ->label('Kurikulum')
                                            ->searchable()
                                            ->options(
                                                Curriculum::all()->pluck('name', 'id'),
                                            )
                                            ->dehydrated(false)
                                            ->reactive(),
                                        Forms\Components\Select::make('education_level_id')
                                            ->label('Jenjang')
                                            ->searchable()
                                            ->options(
                                                EducationLevel::all()->pluck('name', 'id')
                                            )
                                            ->dehydrated(false)
                                            ->reactive(),
                                        Forms\Components\Select::make('type_id')
                                            ->name('Type')
                                            ->searchable()
                                            ->reactive()
                                            ->options(
                                                Type::all()->pluck('name', 'id'),
                                            )
                                            ->dehydrated(false),
                                    ]),
                                Forms\Components\Repeater::make('order_products')
                                    ->label('Order Produk')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('Produk')
                                            ->options(
                                                function ($get) {
                                                    $semester_id = $get('../../semester_id') ?? '%';
                                                    $curriculum_id = $get('../../curriculum_id') ?? '%';
                                                    $education_level_id = $get('../../education_level_id') ?? '%';
                                                    $type_id = $get('../../type_id') ?? '%';

                                                    $productData = Product::where('semester_id', 'like', $semester_id)
                                                        ->where('curriculum_id', 'like', $curriculum_id)
                                                        ->where('education_level_id', 'like', $education_level_id)
                                                        ->where('type_id', 'like', $type_id)
                                                        ->with(['educationSubject' => function ($query) {
                                                            $query->select('id', 'name');
                                                        }])
                                                        ->with('educationClass')
                                                        ->get();

                                                    $formattedData = $productData->mapWithKeys(function ($product) {
                                                        return [$product->id => $product->educationSubject->name . ' - ' . $product->educationClass->name];
                                                    });

                                                    return $formattedData;
                                                }
                                            )
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                            ->searchable()
                                            ->required(),
                                        Forms\Components\TextInput::make('quantity')
                                            ->label('Oplah')
                                            ->required()
                                            ->numeric()
                                            ->default(1),
                                    ])
                                    ->addActionLabel('Tambah Produk')
                                    ->columns(2)
                                    ->defaultItems(1),
                                Forms\Components\Placeholder::make('total')
                                    ->content(function ($get) {
                                        $total = collect($get('order_products'))->pluck('quantity')->sum();

                                        return new HtmlString('<span class="font-bold text-xl">'. $total .'</span>');
                                    }),
                            ]),

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
            RelationManagers\OrderProductsRelationManager::class,
            RelationManagers\SpksRelationManager::class,
            RelationManagers\DeliveryOrdersRelationManager::class,
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
