<?php

namespace App\Filament\Admin\Pages;

use App\Models\ProductReport;
use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Table;
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('spk.order.name')
                    ->label('Order'),
                Tables\Columns\TextColumn::make('spkProduct.products')
                    ->label('Produk'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal Produksi')
                    ->date(),
                Tables\Columns\TextColumn::make('success_count')
                    ->label('Hasil Baik')
                    ->weight(FontWeight::Bold)
                    ->numeric(),
                Tables\Columns\TextColumn::make('machine.name')
                    ->label('Mesin'),
            ])
            ->filters([
                DateRangeFilter::make('date'),
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ])
            ->defaultSort('created_at', 'desc');
    }
}
