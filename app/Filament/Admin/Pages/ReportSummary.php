<?php

namespace App\Filament\Admin\Pages;

use App\Models\OrderProduct;
use App\Models\ProductReport;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ReportSummary extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Master Produksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Rekap Produksi';

    protected static string $view = 'filament.admin.pages.report-summary';

    public function table(Table $table): Table
    {
        return $table
            ->query(ProductReport::query())
            ->columns([
                Tables\Columns\TextColumn::make('spk.order.proof_number')
                    ->label('No. SPK')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('spk.order.name')
                    ->sortable()
                    ->label('Order'),
                Tables\Columns\TextColumn::make('products')
                    ->label('Produk')
                    ->weight(FontWeight::SemiBold)
                    ->default(fn($record) => new HtmlString($this->getProductsName($record))),
                Tables\Columns\TextColumn::make('success_count')
                    ->label('Hasil Baik')
                    ->weight(FontWeight::Bold)
                    ->formatStateUsing(fn($record) => new HtmlString($this->getResult($record)))
                    ->summarize([
                        Summarizer::make()
                            ->label('Total')
                            ->using(fn($query): int => $query->sum('success_count') * 2)
                            ->formatStateUsing(fn($state) => formatNumber($state)),
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($record) => $record->status ? 'success' : 'info')
                    ->formatStateUsing(fn($record) => $record->status ? 'Selesai' : 'Belum Selesai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('machine.name')
                    ->label('Mesin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal Produksi')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                Tables\Filters\Filter::make('date')
                    ->label('Tanggal Produksi')
                    ->form([
                        DatePicker::make('date')
                            ->label('Tanggal Produksi')
                            ->suffixIcon('heroicon-o-calendar'),
                    ])
                    ->query(function ($query, $data) {
                        if (! $data['date']) {
                            return $query;
                        } else {
                            return $query->whereDate('date', $data['date']);
                        }
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['date']) {
                            return null;
                        }
                 
                        return 'Diproduksi pada ' . Carbon::parse($data['date'])->toFormattedDateString();
                    }),
                Tables\Filters\SelectFilter::make('machine_id')
                    ->label('Mesin')
                    ->options(fn() => \App\Models\Machine::pluck('name', 'id')->toArray()),
            ])
            ->actions([
                Tables\Actions\Action::make('Lihat Order')
                    ->button()
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn($record) => route('filament.admin.resources.orders.view', ['record' => $record->spk->order])),
            ])
            ->bulkActions([
                // ...
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function getProductsName(ProductReport $record): string
    {
        return collect($record->spkProduct->order_products)->map(function ($product) {
            return OrderProduct::find($product)->product->short_name;
        })->implode('<hr>');
    }

    protected function getResult(ProductReport $record): string
    {
        if (collect($record->spkProduct->order_products)->count() > 1) {
            $count = formatNumber($record->success_count);
            return new HtmlString("{$count}<hr>{$count}");
        } else {
            return formatNumber($record->success_count * 2);
        }
    }
}
