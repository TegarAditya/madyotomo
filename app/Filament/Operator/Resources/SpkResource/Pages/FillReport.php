<?php

namespace App\Filament\Operator\Resources\SpkResource\Pages;

use App\Filament\Operator\Resources\SpkResource;
use App\Models\Machine;
use App\Models\OrderProduct;
use App\Models\SpkProduct;
use Carbon\Carbon;
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
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;

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
        return $this->record->order->proof_number.' - '.$this->record->report_number;
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

    public function form(Form $form): Form
    {
        return $form
            ->model($this->record)
            ->schema([
                Forms\Components\Section::make('Input Laporan')
                    ->schema([
                        Forms\Components\Repeater::make('productReports')
                            ->relationship()
                            ->hiddenLabel()
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

                                                    foreach ($spkProduct->order_products as $index => $item) {
                                                        $product = OrderProduct::find($item)->product;
                                                        $productName .= $product->educationSubject->name.' - '.$product->educationClass->name;

                                                        if ($index < count($spkProduct->order_products) - 1) {
                                                            $productName .= ' & ';
                                                        }
                                                    }

                                                    return [$spkProduct->id => $productName];
                                                });
                                        }
                                    )
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

    /**
     * Saves the user detail form data.
     */
    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $hasReportArray = fn () => count($this->data['productReports']) > 0;
            $hasReportData = fn () => $this->record->productReports->count() > 0;

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
