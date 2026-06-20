<?php

namespace App\Exports;

use App\Models\Filing;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FilingsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private Builder $query)
    {
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Macroarea',
            'Province',
            'City',
            'Datation',
            'Certain date',
            'Religion',
            'Sacred dedication',
            'Text',
            'Bibliography',
            'Tags',
        ];
    }

    public function map($filing): array
    {
        return [
            $filing->id,
            $filing->macroarea,
            $filing->province,
            $filing->city,
            $filing->datation,
            $filing->is_certain_date ? 'Yes' : 'No',
            $filing->religion,
            $filing->is_sacred_dedication ? 'Yes' : 'No',
            $filing->text,
            $filing->formatBibliography()->pluck('text')->implode(' | '),
            $filing->tags->pluck('label')->implode(', '),
        ];
    }
}
