<?php

namespace App\Filament\Admin\Pages;

use App\Models\OrderProduct;
use App\Models\ProductReport;
use Auth;
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
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class ReportSummary extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Master Produksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Rekap Produksi';

    protected static string $view = 'filament.admin.pages.report-summary';

    public function table(Table $table): Table
    {
        return $table
            ->query(ProductReport::query()->whereHas('spk'))
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
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durasi Produksi')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Selesai',
                        0 => 'Belum Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('Lihat Order')
                    ->button()
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn() => Auth::user()->hasRole('super_admin'))
                    ->url(fn($record) => route('filament.admin.resources.orders.view', ['record' => $record->spk->order])),
                Tables\Actions\Action::make('Lihat Laporan')
                    ->button()
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn($record) => route('filament.operator.resources.spks.report', ['record' => $record->spk])),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('Tandai Selesai')
                    ->button()
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn(Collection $records) => $records->each->update(['status' => true])),
                Tables\Actions\BulkAction::make('Tandai Belum Selesai')
                    ->button()
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn(Collection $records) => $records->each->update(['status' => false])),
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
            $count = $record->success_count;

            return new HtmlString("{$count}<hr>{$count}");
        } else {
            return $record->success_count * 2;
        }
    }
}
