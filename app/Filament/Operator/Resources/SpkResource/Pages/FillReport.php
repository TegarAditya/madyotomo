<?php

namespace App\Filament\Operator\Resources\SpkResource\Pages;

use App\Filament\Operator\Resources\SpkResource;
use App\Models\Machine;
use App\Models\OrderProduct;
use App\Models\Spk;
use App\Models\SpkProduct;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class FillReport extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists, InteractsWithRecord;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->form->fill($this->record->productReports->toArray());
    }

    public function getTitle(): string|Htmlable
    {
        return $this->record->order->proof_number . ' - ' . $this->record->report_number;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Detail')
                ->button()
                ->icon('heroicon-o-information-circle')
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->modalHeading()
                ->stickyModalHeader()
                ->modalWidth(MaxWidth::FiveExtraLarge)
                ->infolist(fn(Spk $record) => SpkResource::generateInfolist($record)),
            Action::make('view')
                ->label('Lihat SPK')
                ->visible(fn() => Auth::user()->can('view_order'))
                ->url(route('filament.admin.resources.orders.view', ['record' => $this->record->order->id])),
        ];
    }

    protected static string $resource = SpkResource::class;

    protected static string $view = 'filament.operator.resources.spk-resource.pages.fill-report';

    public ?array $data = [];

    public function reportInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Infolists\Components\Section::make('Informasi SPK')
                    ->columns(['md' => 2])
                    ->schema([
                        Infolists\Components\TextEntry::make('order.name')
                            ->label('Order')
                            ->inlineLabel()
                            ->formatStateUsing(fn($record) => $record->order->name ?? '-'),
                        Infolists\Components\TextEntry::make('order.customer')
                            ->label('Pelanggan')
                            ->inlineLabel()
                            ->formatStateUsing(fn($record) => $record->order->customer->name ?? '-'),
                        Infolists\Components\TextEntry::make('document_number')
                            ->label('Nomor Order')
                            ->inlineLabel()
                            ->formatStateUsing(fn($record) => $record->order->document_number ?? '-'),
                        Infolists\Components\TextEntry::make('document_number')
                            ->label('Nomor Laporan')
                            ->inlineLabel()
                            ->formatStateUsing(fn($record) => $record->document_number ?? '-'),
                        Infolists\Components\TextEntry::make('entry_date')
                            ->label('Tanggal Masuk')
                            ->inlineLabel()
                            ->formatStateUsing(fn($record) => Carbon::parse($record->entry_date)->translatedFormat('l, j F Y')),
                        Infolists\Components\TextEntry::make('deadline_date')
                            ->label('Tanggal Deadline')
                            ->inlineLabel()
                            ->formatStateUsing(fn($record) => Carbon::parse($record->deadline_date)->translatedFormat('l, j F Y')),
                        Infolists\Components\TextEntry::make('paper_config')
                            ->label('Konfigurasi Kertas')
                            ->inlineLabel()
                            ->formatStateUsing(fn($record) => $record->paper_config ?? '-'),
                        Infolists\Components\TextEntry::make('configuration')
                            ->label('Konfigurasi Warna')
                            ->inlineLabel()
                            ->formatStateUsing(fn($record) => $record->configuration ?? '-'),
                        Infolists\Components\TextEntry::make('print_type')
                            ->label('Jenis Cetak')
                            ->inlineLabel()
                            ->formatStateUsing(fn($record) => $record->print_type ?? '-'),
                        Infolists\Components\TextEntry::make('spare')
                            ->label('Spare')
                            ->inlineLabel()
                            ->formatStateUsing(fn($record) => $record->spare ?? '-'),
                        Infolists\Components\TextEntry::make('note')
                            ->columnSpanFull()
                            ->label('Catatan')
                            ->html()
                            ->formatStateUsing(fn($record) => $record->note ?? '-'),
                    ]),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->record)
            ->schema([
                Forms\Components\Section::make('Input Laporan')
                    ->schema([
                        Forms\Components\Repeater::make('productReports')
                            ->relationship()
                            ->itemLabel(function ($state) {
                                if ($state['spk_order_product_id']) {
                                    return $this->getProductQuantity($state['spk_order_product_id']) . ' sheet';
                                }

                                return 'Oplah';
                            })
                            ->columns(4)
                            ->addActionLabel('Tambah Laporan')
                            ->defaultItems(1)
                            ->schema([
                                Forms\Components\Select::make('spk_order_product_id')
                                    ->label('Produk')
                                    ->columnSpan(4)
                                    ->options(
                                        function () {
                                            return SpkProduct::where('spk_id', $this->record->id)
                                                ->get()
                                                ->mapWithKeys(function ($spkProduct) {
                                                    $productName = '';
                                                    // $productQuantity = '';

                                                    foreach ($spkProduct->order_products as $index => $item) {
                                                        $orderProduct = OrderProduct::find($item);
                                                        $product = $orderProduct->product;
                                                        $productName .= $product->educationSubject->name . ' - ' . $product->educationClass->name;

                                                        if ($index < count($spkProduct->order_products) - 1) {
                                                            $productName .= ' & ';
                                                        }

                                                        // $productQuantity = number_format($orderProduct->quantity, 0, ',', '.');
                                                    }

                                                    return [$spkProduct->id => $productName];
                                                });
                                        }
                                    )
                                    ->reactive()
                                    ->required(),
                                Forms\Components\Select::make('machine_id')
                                    ->label('Mesin')
                                    ->columnSpan(2)
                                    ->options(Machine::all()->pluck('name', 'id')->toArray())
                                    ->required(),
                                Forms\Components\DatePicker::make('date')
                                    ->columnSpan(2)
                                    ->label('Tanggal')
                                    ->required(),
                                Forms\Components\TimePicker::make('start_time')
                                    ->label('Jam Mulai')
                                    ->seconds(false)
                                    ->live()
                                    ->required(),
                                Forms\Components\TimePicker::make('end_time')
                                    ->label('Jam Selesai')
                                    ->seconds(false)
                                    ->afterOrEqual('start_time')
                                    ->validationMessages([
                                        'after_or_equal' => 'Jam selesai harus setelah jam mulai',
                                    ])
                                    ->disabled(fn($get) => $get('start_time') === null)
                                    ->required(),
                                Forms\Components\TextInput::make('success_count')
                                    ->label('Jumlah Sukses')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('error_count')
                                    ->label('Jumlah Gagal')
                                    ->numeric()
                                    ->required(),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    // private static function generateInfolist(Spk $record): array
    // {
    //     $spkProducts = $record->spkProducts->pluck('order_products');
    //     $infolist = [];

    //     foreach ($spkProducts as $spkProduct) {
    //         $productName = '';
    //         $totalQuantity = 0;
    //         $spare = $record->spare;

    //         foreach ($spkProduct as $index => $productId) {
    //             $product = OrderProduct::find($productId)->product;
    //             $productName .= $product->educationSubject->name . ' - ' . $product->educationClass->name;

    //             // Append separator unless it's the last product
    //             if ($index < count($spkProduct) - 1) {
    //                 $productName .= '&nbsp;&nbsp; | &nbsp;&nbsp;';
    //             }

    //             $productQuantity = OrderProduct::find($productId)->quantity + $spare;
    //             $totalQuantity += $productQuantity / 2;
    //         }

    //         $productNameHtml = new HtmlString('<span class="font-reguler">' . $productName . '</span>');

    //         $infolist[] = Infolists\Components\Section::make($productNameHtml)
    //             ->schema([
    //                 Infolists\Components\TextEntry::make('id')
    //                     ->label('RIM')
    //                     ->formatStateUsing(fn() => formatReam($totalQuantity)),
    //                 Infolists\Components\TextEntry::make('id')
    //                     ->label('PLANO')
    //                     ->formatStateUsing(fn() => new HtmlString('<span class="font-bold text-lg">' . formatNumber($totalQuantity / 2) . '<span class="font-thin text-sm"> sheet</span></span>')),
    //                 Infolists\Components\TextEntry::make('id')
    //                     ->label('1/2 PLANO')
    //                     ->formatStateUsing(fn() => new HtmlString('<span class="font-bold text-lg">' . formatNumber($totalQuantity) . '<span class="font-thin text-sm"> sheet</span></span>')),
    //                 Infolists\Components\TextEntry::make('id')
    //                     ->label('HASIL')
    //                     ->formatStateUsing(fn() => new HtmlString('<span class="font-bold text-lg">' . formatNumber($totalQuantity * 2) . '<span class="font-thin text-sm"> sheet</span></span>'))
    //                     ->hidden(),
    //             ])
    //             ->columns(['md' => 3]);
    //     }

    //     return [
    //         Infolists\Components\Section::make('Kebutuhan Kertas')
    //             ->description('Kebutuhan kertas termasuk spare')
    //             ->schema($infolist),
    //     ];
    // }

    protected function getProductQuantity(int $id): string
    {
        $spkProduct = SpkProduct::find($id);
        $totalQuantity = '';
        $spare = SpkProduct::find($id)->spk->spare;

        foreach ($spkProduct->order_products as $productId) {
            $productQuantity = OrderProduct::find($productId)->quantity + $spare;
            $totalQuantity = number_format($productQuantity, 0, ',', '.');
        }

        return $totalQuantity;
    }

    /**
     * Saves the user detail form data.
     */
    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $hasReportArray = fn() => count($this->data['productReports']) > 0;
            $hasReportData = fn() => $this->record->productReports->count() > 0;

            if ($hasReportData) {
                if ($hasReportArray) {
                    $this->record->update($data);
                } else {
                    $this->record->productReports()->delete();
                }
            } else {
                if ($hasReportArray) {
                    $this->record->create($data);
                }
            }
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }

    public function create(): void
    {
        dd($this->data);
    }

    public static function canAccess(array $parameters = []): bool
    {
        return true;
    }
}
