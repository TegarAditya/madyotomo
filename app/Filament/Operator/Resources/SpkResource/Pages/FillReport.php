<?php

namespace App\Filament\Operator\Resources\SpkResource\Pages;

use App\Filament\Operator\Resources\SpkResource;
use App\Models\OrderProduct;
use App\Models\ProductReport;
use App\Models\SpkProduct;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class FillReport extends Page implements HasForms, HasInfolists
{
    use InteractsWithRecord, InteractsWithForms, InteractsWithInfolists;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected static string $resource = SpkResource::class;

    protected static ?string $title = 'Laporan Produksi';

    protected static string $view = 'filament.operator.resources.spk-resource.pages.fill-report';

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('document_number')
                        ->label('Nomor Laporan')
                        ->required(),
                ]),
        ];
    }
}
