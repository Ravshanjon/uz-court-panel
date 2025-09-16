<?php

namespace App\Exports;

use App\Models\service_inspection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DesiplineExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return service_inspection::query()->with(['judges', 'region']);
    }

    public function headings(): array
    {
        return [
            'Суд',
            'Код',
            'Судья Ф.И.Ш.',
            'Ихтисослик',
            'Судьялик лавозими',
            'ЖИНСИ',
            'Хизмат текшируви хулосаси тузилган сана',
            'Хизмат текширувини ўтказишга асос',
            'Хизмат текшируви Кенгаш ташаббуси билан ўтказилганми?',
            'Хизмат текшируви ўтказган идора',
            'Код',
            'Хизмат текширувини ўтказган судья Ф.И.Ш.',
            'Хизмат текширувида ҳолатлар тасдиғини топдими?',
            'Хизмат текширувида аниқланган хато ва камчиликлар
             (Низомнинг 4-иловаси)',
            'Интизомий иш қўзғатиш учун малака ҳайъатига юборилган сана',
            'Интизомий иш қўзғатиш учун малака ҳайъатига юборилган сана',
        ];
    }

    public function map($row): array
    {
        return [
            optional($row->judges)?->last_name . ' ' .
            optional($row->judges)?->first_name . ' ' .
            optional($row->judges)?->middle_name,
            optional($row->region)?->name,
            optional($row->code)?->code,
            optional($row->inspection_qualification_dates)?->format('d.m.Y'),
        ];
    }

}
