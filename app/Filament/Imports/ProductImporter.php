<?php

namespace App\Filament\Imports;

use App\Models\Curriculum;
use App\Models\EducationClass;
use App\Models\EducationLevel;
use App\Models\EducationSubject;
use App\Models\Product;
use App\Models\Semester;
use App\Models\Type;
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
            ImportColumn::make('curriculum_id')
                ->label('Kurikulum')
                ->requiredMapping()
                ->castStateUsing(function (string $state) {
                    return Curriculum::firstWhere('code', $state)->id;
                })
                ->rules(['required']),
            ImportColumn::make('semester_id')
                ->label('Semester')
                ->requiredMapping()
                ->castStateUsing(function (string $state) {
                    return Semester::firstWhere('code', $state)->id;
                })
                ->rules(['required']),
            ImportColumn::make('education_level_id')
                ->label('Jenjang')
                ->requiredMapping()
                ->castStateUsing(function (string $state) {
                    return EducationLevel::firstWhere('code', $state)->id;
                })
                ->rules(['required']),
            ImportColumn::make('education_class_id')
                ->label('Kelas')
                ->requiredMapping()
                ->castStateUsing(function (string $state) {
                    return EducationClass::firstWhere('code', $state)->id;
                })
                ->rules(['required']),
            ImportColumn::make('education_subject_id')
                ->label('Mata Pelajaran')
                ->requiredMapping()
                ->castStateUsing(function (string $state) {
                    return EducationSubject::firstWhere('code', $state)->id;
                })
                ->rules(['required']),
            ImportColumn::make('type_id')
                ->label('Tipe')
                ->requiredMapping()
                ->castStateUsing(function (string $state) {
                    return Type::firstWhere('code', $state)->id;
                })
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
