<?php

namespace App\Filament\Imports;

use App\Models\EducationSubject;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class EducationSubjectImporter extends Importer
{
    protected static ?string $model = EducationSubject::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->requiredMapping()
                ->label('Kode')
                ->rules(['max:255']),
            ImportColumn::make('name')
                ->label('Nama')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?EducationSubject
    {
        return EducationSubject::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'code' => $this->data['code'],
        ]);

        return new EducationSubject();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your education subject import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
