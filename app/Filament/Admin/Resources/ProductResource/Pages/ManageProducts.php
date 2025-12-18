<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProducts extends ManageRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother()
                ->mutateFormDataUsing(function (array $data): array {
                    session()->put('product_form', [
                        'semester_id' => $data['semester_id'] ?? null,
                        'curriculum_id' => $data['curriculum_id'] ?? null,
                        'education_level_id' => $data['education_level_id'] ?? null,
                        'education_subject_id' => $data['education_subject_id'] ?? null,
                        'education_class_id' => $data['education_class_id'] ?? null,
                        'type_id' => $data['type_id'] ?? null,
                    ]);

                    return $data;
                }),
        ];
    }
}
