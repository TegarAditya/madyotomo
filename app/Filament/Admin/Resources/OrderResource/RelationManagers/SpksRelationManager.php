<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use App\Filament\Operator\Resources\SpkResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Spk;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use stdClass;

class SpksRelationManager extends RelationManager
{
    protected static string $relationship = 'spks';

    protected static ?string $title = 'SPK';

    public function form(Form $form): Form
    {
        $orderProducts = OrderProduct::where('order_id', $this->getOwnerRecord()->id)
            ->with(['product.educationSubject', 'product.educationClass'])
            ->get();

        return $form
            ->schema([
                Forms\Components\Placeholder::make('document_number_ph')
                    ->label('Nomor SPK')
                    ->visibleOn(['create'])
                    ->content(function (callable $set, $get) {

                        $nomor_order = $this->getOwnerRecord()->document_number;
                        $nomor = substr($nomor_order, 0, strpos($nomor_order, '/'));
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

                        $set('document_number', "{$nomor}/MT/SPK/{$romanMonth}/{$year}");
                        $set('report_number', "{$nomor}/MT/LP/{$romanMonth}/{$year}");

                        return "{$nomor}/MT/SPK/{$romanMonth}/{$year}";
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
                    ->default((new DateTime)->format('Y-m-d'))
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
                    ->label('Daftar Produk')
                    ->addActionLabel('Tambah Produk')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('spkProductOrderProducts')
                            ->multiple()
                            ->relationship('orderProducts', 'id')
                            ->label('Produk')
                            ->options(
                                $orderProducts
                                    ->mapWithKeys(fn($orderProduct) => [
                                        $orderProduct->id => "{$orderProduct->product->educationSubject->name} {$orderProduct->product->educationClass->name} (oplah - {$orderProduct->quantity})",
                                    ])
                                    ->toArray()
                            )
                            ->columnSpan(2)
                            ->maxItems(2)
                            ->reactive()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->required(),
                        Forms\Components\Placeholder::make('quantity_ph')
                            ->label('Jumlah InSheet')
                            ->content(function ($get) use ($orderProducts) {
                                if ($get('spkProductOrderProducts') !== null) {
                                    $products = $get('spkProductOrderProducts');
                                    $quantities = array_map(function ($product) use ($orderProducts) {
                                        return $orderProducts->find($product)->quantity;
                                    }, $products);

                                    $totalQuantity = $quantities[0];
                                    $quantitiesEqual = count(array_unique($quantities)) === 1;

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
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_number')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('No.')
                    ->default(fn(stdClass $rowLoop) => $rowLoop->index + 1),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Nomor SPK')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_number')
                    ->label('Nomor Laporan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Tanggal Masuk')
                    ->date('l, d F Y', 'Asia/Jakarta')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline_date')
                    ->label('Tanggal Deadline')
                    ->date('l, d F Y', 'Asia/Jakarta')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('Laporan')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn(Spk $record): string => SpkResource::getUrl('report', ['record' => $record], panel: 'operator')),
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
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public function generateDocumentNumber() {}

    public function isReadOnly(): bool
    {
        if (Auth::user()->can('create_order')) {
            return false;
        }

        return true;
    }
}
