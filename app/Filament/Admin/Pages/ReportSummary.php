<?php

namespace App\Filament\Admin\Pages;

use App\Models\OrderProduct;
use App\Models\ProductReport;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ReportSummary extends Page implements HasTable
{
    use InteractsWithTable;

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
                    ->weight(FontWeight::Bold)
                    ->default(fn(ProductReport $record) => new HtmlString($this->getProductsName($record))),
                Tables\Columns\TextColumn::make('machine.name')
                    ->label('Mesin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('success_count')
                    ->label('Hasil Baik')
                    ->weight(FontWeight::Bold)
                    ->summarize(Sum::make()->label('Total'))
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal Produksi')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                DateRangeFilter::make('date'),
                Tables\Filters\SelectFilter::make('machine_id')
                    ->label('Mesin')
                    ->options(fn() => \App\Models\Machine::pluck('name', 'id')->toArray()),
            ])
            ->actions([
                // ...
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
}
