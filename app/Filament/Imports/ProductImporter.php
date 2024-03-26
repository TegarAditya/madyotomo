<?php

namespace App\Filament\Imports;

use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nama')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('curriculum')
                ->label('Kurikulum')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('semester')
                ->label('Semester')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('educationLevel')
                ->label('Jenjang')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('educationClass')
                ->label('Kelas')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('educationSubject')
                ->label('Mata Pelajaran')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
            ImportColumn::make('type')
                ->label('Tipe')
                ->requiredMapping()
                ->relationship(resolveUsing: 'code')
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Product
    {
        // return Product::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Product();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
