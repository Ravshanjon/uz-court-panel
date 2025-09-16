<?php

namespace App\Filament\Exports;

use App\Models\Judges;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class JudgesExporter extends Exporter
{
    protected static ?string $model = Judges::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('image'),
            ExportColumn::make('vacancy_start'),
            ExportColumn::make('vacancy_status'),
            ExportColumn::make('eligibility_submission_date'),
            ExportColumn::make('documents_submitted'),
            ExportColumn::make('code'),
            ExportColumn::make('last_name'),
            ExportColumn::make('first_name'),
            ExportColumn::make('middle_name'),
            ExportColumn::make('pinfl'),
            ExportColumn::make('passport_name'),
            ExportColumn::make('birth_date'),
            ExportColumn::make('birth_place'),
            ExportColumn::make('address'),
            ExportColumn::make('gender'),
            ExportColumn::make('nationality_id'),
            ExportColumn::make('appointment_date'),
            ExportColumn::make('document_date'),
            ExportColumn::make('document_type'),
            ExportColumn::make('document_number'),
            ExportColumn::make('duration'),
            ExportColumn::make('previous_appointment'),
            ExportColumn::make('previous_duration'),
            ExportColumn::make('legal_experience'),
            ExportColumn::make('judicial_experience'),
            ExportColumn::make('age_extension_date'),
            ExportColumn::make('university_id'),
            ExportColumn::make('graduation_year'),
            ExportColumn::make('special_education'),
            ExportColumn::make('leadership_experience'),
            ExportColumn::make('leadership_reserve'),
            ExportColumn::make('is_featured'),
            ExportColumn::make('region_id'),
            ExportColumn::make('court_type_id'),
            ExportColumn::make('provinces_district_id'),
            ExportColumn::make('district_type_id'),
            ExportColumn::make('court_specialty_id'),
            ExportColumn::make('court_name_id'),
            ExportColumn::make('position_id'),
            ExportColumn::make('position_category_id'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your judges export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
