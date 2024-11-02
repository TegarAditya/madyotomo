<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use App\Models\DeliveryOrder;
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
use stdClass;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DeliveryOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'deliveryOrders';

    protected static ?string $title = 'Surat Jalan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('document_number_ph')
                    ->label('Nomor SPK')
                    ->content(function (callable $set, $get) {
                        $customer = $this->getOwnerRecord()->customer->code;

                        $latestOrder = DeliveryOrder::orderBy('created_at', 'desc')->first()->document_number ?? null;
                        $latestNumber = (int) (strpos($latestOrder, '/') !== false ? substr($latestOrder, 0, strpos($latestOrder, '/')) : 0);

                        $nomorTerakhir = (Spk::all()->first()) ? $latestNumber + 1 : 1;
                        $month = (new DateTime('@'.strtotime($get('entry_date'))))->format('m');
                        $year = (new DateTime('@'.strtotime($get('entry_date'))))->format('Y');
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

                        $document_number = "{$nomorTerakhir}/MT/SJ/{$customer}/{$romanMonth}/{$year}";

                        $set('document_number', $document_number);

                        return $document_number;
                    })
                    ->visibleOn(['create']),
                Forms\Components\TextInput::make('document_number')
                    ->label('Nomor SPK')
                    ->unique(),
                Forms\Components\Hidden::make('document_number')
                    ->visibleOn(['create']),
                Forms\Components\DatePicker::make('entry_date')
                    ->default(now())
                    ->required(),
                Forms\Components\RichEditor::make('note')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('deliveryOrderProducts')
                    ->hiddenLabel()
                    ->relationship()
                    ->columns([
                        'md' => 2,
                    ])
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Produk')
                    ->schema([
                        Forms\Components\Select::make('order_product_id')
                            ->label('Pilih Produk')
                            ->options(function () {
                                return OrderProduct::query()
                                    ->where('order_id', $this->getOwnerRecord()->id)
                                    ->orderBy('id')
                                    ->get()
                                    ->mapWithKeys(fn ($orderProduct) => [
                                        $orderProduct->id => $orderProduct->product->educationSubject->name.' - '.$orderProduct->product->educationClass->name,
                                    ])
                                    ->toArray();
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($set, $get) {
                                $quantity = OrderProduct::query()
                                    ->where('id', $get('order_product_id'))
                                    ->value('quantity');

                                $set('quantity', $quantity);
                            })
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->required(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('No.')
                    ->default(fn (stdClass $rowLoop) => $rowLoop->index + 1 .'.')
                    ->extraHeaderAttributes(['style' => 'width:80px']),
                Tables\Columns\TextColumn::make('document_number'),
            ])
            ->filters([
                //
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
                    ->action(fn (DeliveryOrder $record) => $this->downloadDeliveryOrder($record)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        if (Auth::user()->can('create_order')) {
            return false;
        }

        return true;
    }

    protected function downloadDeliveryOrder(DeliveryOrder $record): StreamedResponse
    {
        return response()->streamDownload(function () use ($record) {
            $deliveryItems = $record->deliveryOrderProducts->map(function ($deliveryOrderProduct) {
                return [
                    'product' => $deliveryOrderProduct->orderProduct->product->educationSubject->name.' - '.$deliveryOrderProduct->orderProduct->product->educationClass->name,
                    'quantity' => $deliveryOrderProduct->quantity,
                ];
            });

            $total = $record->deliveryOrderProducts->sum('quantity');

            $index = 1;

            echo Pdf::loadView('pdf.surat-jalan', ['record' => $record, 'deliveryItems' => $deliveryItems, 'total' => $total, 'index' => $index])
                ->setOption(['defaultFont' => 'sans-serif'])
                ->setPaper('a4', 'portrait')
                ->stream();
        }, str_replace('/', '_', $record->document_number).'.pdf');
    }
}
