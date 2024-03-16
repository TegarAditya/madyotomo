<?php

namespace App\Filament\Operator\Resources;

use App\Filament\Operator\Resources\SpkResource\Pages;
use App\Filament\Operator\Resources\SpkResource\RelationManagers;
use App\Models\OrderProduct;
use App\Models\Spk;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class SpkResource extends Resource
{
    protected static ?string $model = Spk::class;

    protected static ?string $modelLabel = 'Laporan';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('info')
                    ->label('Informasi SPK')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Placeholder::make('order_name')
                            ->label('Order')
                            ->content(fn (?Spk $record) => $record->order->name ?? '-'),
                        Forms\Components\Placeholder::make('order_customer')
                            ->label('Pelanggan')
                            ->content(fn (?Spk $record) => $record->order->customer->name ?? '-'),
                        Forms\Components\Placeholder::make('document_number')
                            ->label('Nomor SPK')
                            ->content(fn (?Spk $record) => $record->document_number ?? '-'),
                        Forms\Components\Placeholder::make('document_number')
                            ->label('Nomor Laporan')
                            ->content(fn (?Spk $record) => $record->document_number ?? '-'),
                        Forms\Components\Placeholder::make('entry_date')
                            ->content(function (?Spk $record) {
                                $date = $record->entry_date ?? now();

                                $dateString = Carbon::parse($date)->translatedFormat('l, j F Y');

                                return (new HtmlString('<strong>' . $dateString . '</strong>'));
                            }),
                        Forms\Components\Placeholder::make('deadline_date')
                            ->content(function (?Spk $record) {
                                $date = $record->deadline_date ?? now();

                                $dateString = Carbon::parse($date)->translatedFormat('l, j F Y');

                                return (new HtmlString('<strong>' . $dateString . '</strong>'));
                            }),
                        Forms\Components\Placeholder::make('paper_config')
                            ->content(fn (?Spk $record) => $record->paper_config ?? '-'),
                        Forms\Components\Placeholder::make('configuration')
                            ->content(fn (?Spk $record) => $record->configuration ?? '-'),
                        Forms\Components\Placeholder::make('print_type')
                            ->content(fn (?Spk $record) => $record->print_type ?? '-'),
                        Forms\Components\Placeholder::make('spare')
                            ->content(fn (?Spk $record) => $record->spare ?? '-'),
                        Forms\Components\Section::make('Catatan')
                            ->schema([
                                Forms\Components\Placeholder::make('note')
                                    ->hiddenLabel()
                                    ->content(function (?Spk $record) {
                                        $note = $record->note ?? '-';
                                        return (new HtmlString($note));
                                    })
                                    ->columnSpanFull(),
                            ]),
                    ]),
                Forms\Components\Section::make('Laporan')
                    ->schema(function (Spk $record) {
                        $formSchema = [];

                        $spkProducts = $record->spkProducts->pluck('order_products');

                        foreach ($spkProducts as $spkProduct) {
                            // dd(OrderProduct::find($spkProduct[0])->product->name);
                            $formSchema[] = Forms\Components\Section::make(OrderProduct::find($spkProduct[0])->product->educationSubject->name . ' - ' . OrderProduct::find($spkProduct[0])->product->educationClass->name)
                                ->schema([
                                    Forms\Components\Section::make()
                                        ->schema([
                                            // Forms\Components\Placeholder::make('paper_supply')
                                            //     ->label('Kebutuhan Kertas (½ plano)')
                                            //     ->content(fn (?Spk $record) => OrderProduct::whereIn('id', $record->spkProducts->pluck('order_products')) ?? '-'),
                                        ]),
                                    Forms\Components\Repeater::make('report')
                                        ->columns(3)
                                        ->minItems(1)
                                        ->schema([
                                            Forms\Components\DatePicker::make('date')
                                                ->label('Tanggal')
                                                ->required(),
                                            Forms\Components\TimePicker::make('start_time')
                                                ->label('Waktu Mulai')
                                                ->required(),
                                            Forms\Components\TimePicker::make('end_time')
                                                ->label('Waktu Selesai')
                                                ->required(),
                                            Forms\Components\TextInput::make('hasil_baik')
                                                ->label('Hasil Baik')
                                                ->required(),
                                            Forms\Components\TextInput::make('hasil_rusak')
                                                ->label('Hasil Rusak')
                                                ->required(),
                                        ])
                                ]);
                        }

                        return $formSchema;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Nomor SPK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('report_number')
                    ->label('Nomor Laporan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Tanggal Masuk')
                    ->date('l, j F Y', 'Asia/Jakarta')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline_date')
                    ->label('Tanggal Deadline')
                    ->date('l, j F Y', 'Asia/Jakarta')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paper_config')
                    ->label('Konfigurasi Kertas')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('configuration')
                    ->label('Konfigurasi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('print_type')
                    ->label('Jenis Cetak')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('spare')
                    ->label('Spare')
                    ->numeric()
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
                Tables\Filters\Filter::make('only_has_order')
                    ->label('Hanya SPK yang memiliki order')
                    ->query(fn (Builder $query) => $query->whereHas('order', fn (Builder $query) => $query->withTrashed()))
                    ->default(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel()
                    ->icon(null),
                Tables\Actions\Action::make('Penggunaan Kertas')
                    ->infolist(
                        function (Spk $record) {
                            $spkProducts = $record->spkProducts->pluck('order_products');

                            $infolist = [];

                            foreach ($spkProducts as $spkProduct) {
                                $product_1_name = OrderProduct::find($spkProduct[0])->product->educationSubject->name . ' - ' . OrderProduct::find($spkProduct[0])->product->educationClass->name;
                                $product_2_name = isset($spkProduct[1]) ? OrderProduct::find($spkProduct[1])->product->educationSubject->name . ' - ' . OrderProduct::find($spkProduct[1])->product->educationClass->name : '';

                                $productName = $product_1_name . ($product_2_name ? ' ' . $product_2_name : '');

                                $product_1_quantity = OrderProduct::find($spkProduct[0])->quantity;
                                $product_2_quantity = isset($spkProduct[1]) ? OrderProduct::find($spkProduct[1])->quantity : 0;

                                $productQuantity = $product_1_quantity + $product_2_quantity;

                                // dd($productQuantity);

                                $infolist[] = Section::make($productName)
                                    ->schema([
                                        TextEntry::make('id')
                                            ->label('Kebutuhan Kertas (1 PLANO)')
                                            ->formatStateUsing(function () use ($productQuantity) {
                                                return new HtmlString('<span class="font-bold text-2xl">' . $productQuantity /2 . '</span>');
                                            })
                                            ->color('primary'),
                                        TextEntry::make('id')
                                            ->label('Kebutuhan Kertas (½ PLANO)')
                                            ->formatStateUsing(function () use ($productQuantity) {
                                                return new HtmlString('<span class="font-bold text-2xl">' . $productQuantity / 1 . '</span>');
                                            })
                                            ->color('primary'),
                                    ])
                                    ->columns([
                                        'md' => 2
                                    ]);
                            }

                            return $infolist;
                        }
                        // [
                        //     Section::make('Personal Information')
                        //         ->schema([
                        //             TextEntry::make('first_name'),
                        //             TextEntry::make('last_name'),
                        //         ])
                        //         ->columns(),
                        // ]
                    ),
                Tables\Actions\Action::make('Laporan')
                    ->label('Laporan')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (Spk $record) => url('operator/spks/' . $record->id . '/fill-report')),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->recordUrl(null)
            ->recordAction(false);
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
            // 'create' => Pages\CreateSpk::route('/create'),
            'edit' => Pages\EditSpk::route('/{record}/edit'),
            'report' => Pages\FillReport::route('/{record}/fill-report'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
