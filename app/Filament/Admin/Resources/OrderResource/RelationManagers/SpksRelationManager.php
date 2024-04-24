<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Spk;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use stdClass;

class SpksRelationManager extends RelationManager
{
    protected static string $relationship = 'spks';

    protected static ?string $title = 'SPK';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('document_number_ph')
                    ->label('Nomor SPK')
                    ->visibleOn(['create'])
                    ->content(function (callable $set, $get) {
                        $latestOrder = Spk::orderBy('created_at', 'desc')->first()->document_number ?? null;
                        $latestNumber = (int) (strpos($latestOrder, '/') !== false ? substr($latestOrder, 0, strpos($latestOrder, '/')) : 0);

                        $nomorTerakhir = (Spk::all()->first()) ? $latestNumber + 1 : 1;
                        $month = (new DateTime('@' . strtotime($get('entry_date'))))->format('m');
                        $year = (new DateTime('@' . strtotime($get('entry_date'))))->format('Y');
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

                        $set('document_number', "{$nomorTerakhir}/MT/SPK/{$romanMonth}/{$year}");
                        $set('report_number', "{$nomorTerakhir}/MT/LP/{$romanMonth}/{$year}");

                        return "{$nomorTerakhir}/MT/SPK/{$romanMonth}/{$year}";
                    }),
                Forms\Components\Hidden::make('document_number')
                    ->visibleOn(['create']),
                Forms\Components\TextInput::make('document_number')
                    ->visibleOn(['edit']),
                Forms\Components\Placeholder::make('report_number_ph')
                    ->label('Nomor Laporan')
                    ->content(function ($get) {
                        return $get('report_number');
                    }),
                Forms\Components\Hidden::make('report_number'),
                Forms\Components\DatePicker::make('entry_date')
                    ->reactive()
                    ->default((new DateTime())->format('Y-m-d'))
                    ->required(),
                Forms\Components\DatePicker::make('deadline_date')
                    ->required(),
                Forms\Components\TextInput::make('paper_config')
                    ->required()
                    ->default($this->getOwnerRecord()->paper_config)
                    ->maxLength(255),
                Forms\Components\TextInput::make('configuration')
                    ->label('Color config')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('print_type')
                    ->options([
                        'cetak' => 'Cetak',
                        'cetak potong' => 'Cetak & Potong',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('spare')
                    ->required()
                    ->integer()
                    ->default(0),
                Forms\Components\RichEditor::make('note')
                    ->required()
                    ->default('-')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('spkProducts')
                    ->relationship('spkProducts')
                    ->addActionLabel('Tambah Produk')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('order_products')
                            ->multiple()
                            ->options(
                                Order::find($this->getOwnerRecord()->id)
                                    ->order_products
                                    ->mapWithKeys(function (OrderProduct $product) {
                                        $subject = $product->product->educationSubject()->pluck('name')->implode(' ');
                                        $class = $product->product->educationClass()->pluck('name')->implode(' ');
                                        $quantity = $product->quantity;
                                        return [$product->id => $subject . ' - ' . $class . ' - ' . ' (' . 'oplah ' . $quantity . ')'];
                                    })
                            )
                            ->columnSpan(2)
                            ->reactive()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->required(),
                        Forms\Components\Placeholder::make('quantity_ph')
                            ->label('Jumlah InSheet')
                            ->content(function ($get, $set) {
                                if ($get('order_products') !== null) {
                                    $products = $get('order_products');

                                    if (count($products) === 1) {
                                        $quantity = OrderProduct::find($products[0])->quantity / 2;
                                        return new HtmlString("<span class='text-2xl font-bold'>{$quantity}<span class='text-sm font-thin'> sheets</span></span>");
                                    }

                                    $firstQuantity = null;
                                    $quantitiesEqual = true;
                                    foreach ($products as $product) {
                                        $quantity = OrderProduct::find($product)->quantity / 2;

                                        if ($firstQuantity === null) {
                                            $firstQuantity = $quantity;
                                        }

                                        if ($quantity !== $firstQuantity) {
                                            $quantitiesEqual = false;
                                            break;
                                        }
                                    }

                                    $totalQuantity = $firstQuantity * count($products);

                                    if ($quantitiesEqual) {
                                        return new HtmlString(
                                            "<span class='text-2xl font-bold'>{$totalQuantity}<span class='text-sm font-thin'> sheets</span></span>"
                                        );
                                    } else {
                                        return new HtmlString("<span class='text-2xl font-bold mb-2'>{$totalQuantity}</span><br><span>Oplah tidak sama!</span>");
                                    }
                                } else {
                                    return 0;
                                }
                            }),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_number')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('No.')
                    ->default(fn (stdClass $rowLoop) => $rowLoop->index + 1),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('pdf')
                    ->label('Download')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->authorize(true)
                    ->action(function (Spk $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo Pdf::loadHtml(
                                Blade::render('pdf.spk', ['record' => $record])
                            )->stream();
                        }, str_replace('/', '_', $record->document_number) . '.pdf');
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
