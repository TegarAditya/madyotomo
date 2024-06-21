<?php

namespace App\Filament\Operator\Resources\SpkResource\Pages;

use App\Filament\Operator\Resources\SpkResource;
use App\Models\Customer;
use App\Models\Machine;
use App\Models\OrderProduct;
use App\Models\Paper;
use App\Models\ProductReport;
use App\Models\Spk;
use App\Models\SpkProduct;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\HtmlString;
use Termwind\Components\Span;

class FillReport extends Page implements HasForms, HasInfolists
{
    use InteractsWithRecord, InteractsWithForms, InteractsWithInfolists;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string | Htmlable
    {
        return $this->record->report_number;
    }

    protected static string $resource = SpkResource::class;

    protected static string $view = 'filament.operator.resources.spk-resource.pages.fill-report';

    protected function getFormSchema(): array
    {
        
        return $this->generateForm($this->record);
    }

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
                            ->formatStateUsing(fn ($record) => $record->order->name ?? '-'),
                        Infolists\Components\TextEntry::make('order.customer')
                            ->label('Pelanggan')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($record) => $record->order->customer->name ?? '-'),
                        Infolists\Components\TextEntry::make('document_number')
                            ->label('Nomor Order')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($record) => $record->order->document_number ?? '-'),
                        Infolists\Components\TextEntry::make('document_number')
                            ->label('Nomor Laporan')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($record) => $record->document_number ?? '-'),
                        Infolists\Components\TextEntry::make('entry_date')
                            ->label('Tanggal Masuk')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($record) => Carbon::parse($record->entry_date)->translatedFormat('l, j F Y')),
                        Infolists\Components\TextEntry::make('deadline_date')
                            ->label('Tanggal Deadline')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($record) => Carbon::parse($record->deadline_date)->translatedFormat('l, j F Y')),
                        Infolists\Components\TextEntry::make('paper_config')
                            ->label('Konfigurasi Kertas')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($record) => $record->paper_config ?? '-'),
                        Infolists\Components\TextEntry::make('configuration')
                            ->label('Konfigurasi Warna')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($record) => $record->configuration ?? '-'),
                        Infolists\Components\TextEntry::make('print_type')
                            ->label('Jenis Cetak')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($record) => $record->print_type ?? '-'),
                        Infolists\Components\TextEntry::make('spare')
                            ->label('Spare')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($record) => $record->spare ?? '-'),
                        Infolists\Components\TextEntry::make('note')
                            ->columnSpanFull()
                            ->label('Catatan')
                            ->html()
                            ->formatStateUsing(fn ($record) => $record->note ?? '-'),
                    ]),
            ]);
    }

    private static function generateForm(Spk $record) {
        $spkProducts = $record->spkProducts->pluck('order_products');
        $formSchema = [];

        foreach ($spkProducts as $spkProduct) {
            $productName = '';
            $totalQuantity = 0;
            $spare = $record->spare;
            $key = 'product_' . $spkProduct[0];

            foreach ($spkProduct as $index => $productId) {
                $product = OrderProduct::find($productId)->product;
                $productName .= $product->educationSubject->name . ' - ' . $product->educationClass->name . ' -> ' . (OrderProduct::find($productId)->quantity + $spare) . ' sheet';

                // Append separator unless it's the last product
                if ($index < count($spkProduct) - 1) {
                    $productName .= '&nbsp;&nbsp; | &nbsp;&nbsp;';
                }

                $productQuantity = OrderProduct::find($productId)->quantity + $spare;
                $totalQuantity += $productQuantity / 2;
            }

            $productNameHtml = new HtmlString('<span class="font-thin">' . $productName . '</span>');

            $formSchema[] = Forms\Components\Section::make($productNameHtml)
                ->label($productNameHtml)
                ->schema([
                    Forms\Components\Repeater::make($key)
                        ->hiddenLabel()
                        ->columns(4)
                        ->schema([
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
                                ->required(),
                            Forms\Components\TimePicker::make('end_time')
                                ->label('Jam Selesai')
                                ->required(),
                            Forms\Components\TextInput::make('success_count')
                                ->label('Jumlah Sukses')
                                ->integer()
                                ->required(),
                            Forms\Components\TextInput::make('error_count')
                                ->label('Jumlah Gagal')
                                ->integer()
                                ->required(),
                        ])
                ]);
        }

        return $formSchema;
    }

    public static function canAccess(array $parameters = []): bool
    {
        return true;
    }
}
