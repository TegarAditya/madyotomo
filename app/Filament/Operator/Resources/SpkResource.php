<?php

namespace App\Filament\Operator\Resources;

use App\Filament\Operator\Resources\SpkResource\Pages;
use App\Models\OrderProduct;
use App\Models\Spk;
use Carbon\Carbon;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class SpkResource extends Resource
{
    protected static ?string $model = Spk::class;

    protected static ?string $modelLabel = 'Laporan';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.proof_number')
                    ->label('Nomor SPK Order')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('report_number')
                    ->label('Nomor Laporan')
                    ->sortable()
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
                    ->query(fn(Builder $query) => $query->whereHas('order', fn(Builder $query) => $query->withTrashed())),
            ])
            ->actions([
                Tables\Actions\Action::make('Detail')
                    ->button()
                    ->icon('heroicon-o-information-circle')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalHeading()
                    ->stickyModalHeader()
                    ->modalWidth(MaxWidth::FiveExtraLarge)
                    ->infolist(fn(Spk $record) => self::generateInfolist($record)),
                Tables\Actions\Action::make('Laporan')
                    ->button()
                    ->label('Laporan')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->url(fn(Spk $record) => url('operator/spks/' . $record->id . '/fill-report')),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('order.name')
                    ->label('Order')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(fn(Spk $record) => $record->order->name . ' - ' . $record->order->document_number)
                    ->collapsible(),
            ])
            ->defaultSort('entry_date', 'desc')
            ->recordUrl(fn(Spk $record) => url('operator/spks/' . $record->id . '/fill-report'))
            ->recordAction(false);
    }

    public static function generateInfolist(Spk $record): array
    {
        $spkProducts = $record->spkProducts->pluck('order_products');
        $infolist = [];

        foreach ($spkProducts as $spkProduct) {
            $productName = '';
            $totalQuantity = 0;
            $spare = $record->spare;

            foreach ($spkProduct as $index => $productId) {
                $product = OrderProduct::find($productId)->product;
                $productName .= $product->educationSubject->name . ' - ' . $product->educationClass->name;

                // Append separator unless it's the last product
                if ($index < count($spkProduct) - 1) {
                    $productName .= '&nbsp;&nbsp; | &nbsp;&nbsp;';
                }

                $productQuantity = OrderProduct::find($productId)->quantity + $spare;
                $totalQuantity += $productQuantity / 2;
            }

            $productNameHtml = new HtmlString('<span class="font-reguler">' . $productName . '</span>');

            $infolist[] = Section::make($productNameHtml)
                ->schema([
                    TextEntry::make('id')
                        ->label('RIM')
                        ->formatStateUsing(fn() => formatReam($totalQuantity)),
                    TextEntry::make('id')
                        ->label('PLANO')
                        ->formatStateUsing(fn() => new HtmlString('<span class="font-bold text-lg">' . formatNumber($totalQuantity / 2) . '<span class="font-thin text-sm"> sheet</span></span>')),
                    TextEntry::make('id')
                        ->label('1/2 PLANO')
                        ->formatStateUsing(fn() => new HtmlString('<span class="font-bold text-lg">' . formatNumber($totalQuantity) . '<span class="font-thin text-sm"> sheet</span></span>')),
                    TextEntry::make('id')
                        ->label('HASIL')
                        ->formatStateUsing(fn() => new HtmlString('<span class="font-bold text-lg">' . formatNumber($totalQuantity * 2) . '<span class="font-thin text-sm"> sheet</span></span>'))
                        ->hidden(),
                ])
                ->columns(['md' => 3]);
        }

        return [
            Section::make('Informasi SPK')
                ->columns(['md' => 2])
                ->schema([
                    TextEntry::make('order.name')
                        ->label('Order')
                        ->inlineLabel()
                        ->formatStateUsing(fn($record) => $record->order->name ?? '-'),
                    TextEntry::make('order.document_number')
                        ->label('Nomor Order')
                        ->inlineLabel()
                        ->formatStateUsing(fn($record) => $record->order->document_number ?? '-'),
                    TextEntry::make('document_number')
                        ->label('Nomor SPK')
                        ->inlineLabel()
                        ->formatStateUsing(fn($record) => $record->document_number ?? '-'),
                    TextEntry::make('report_number')
                        ->label('Nomor Laporan')
                        ->inlineLabel()
                        ->formatStateUsing(fn($record) => $record->report_number ?? '-'),
                    TextEntry::make('entry_date')
                        ->label('Tanggal Masuk')
                        ->inlineLabel()
                        ->formatStateUsing(fn($record) => Carbon::parse($record->entry_date)->translatedFormat('l, j F Y')),
                    TextEntry::make('deadline_date')
                        ->label('Tanggal Deadline')
                        ->inlineLabel()
                        ->formatStateUsing(fn($record) => Carbon::parse($record->deadline_date)->translatedFormat('l, j F Y')),
                    TextEntry::make('paper_config')
                        ->label('Konfigurasi Kertas')
                        ->inlineLabel()
                        ->formatStateUsing(fn($record) => $record->paper_config ?? '-'),
                    TextEntry::make('configuration')
                        ->label('Konfigurasi Warna')
                        ->inlineLabel()
                        ->formatStateUsing(fn($record) => $record->configuration ?? '-'),
                    TextEntry::make('print_type')
                        ->label('Jenis Cetak')
                        ->inlineLabel()
                        ->formatStateUsing(fn($record) => $record->print_type ?? '-'),
                    TextEntry::make('spare')
                        ->label('Spare')
                        ->inlineLabel()
                        ->formatStateUsing(fn($record) => $record->spare ?? '-'),
                    TextEntry::make('note')
                        ->columnSpanFull()
                        ->label('Catatan')
                        ->html()
                        ->formatStateUsing(fn($record) => $record->note ?? '-'),
                ]),
            Section::make('Kebutuhan Kertas')
                ->description('Kebutuhan kertas termasuk spare')
                ->schema($infolist),
        ];
    }

    public static function getRelations(): array
    {
        return [
            // Define the relations here
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
