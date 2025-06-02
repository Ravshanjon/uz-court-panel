<?php

namespace App\Filament\Pages;

use App\Exports\DesiplineExport;
use App\Models\Judges;
use App\Models\service_inspection;
use Filament\Pages\Page;

use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Svg\Tag\Text;

class Desipline extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Интизомий';

    protected static string $view = 'filament.pages.desipline';

    public function table(Table $table)
    {
        return $table
            ->query(service_inspection::query())
            ->columns([
                TextColumn::make('inspection_qualification_dates')->label('Текширув санаси')->date('d.m.Y'),
                TextColumn::make('judges.middle_name')->label('Фамилияси'),
                TextColumn::make('judges.first_name')->label('Исми'),
                TextColumn::make('judges.last_name')->label('Отасининг исми'),
                TextColumn::make('region.name')->label('Ҳудуд'),
                TextColumn::make('inspection_cases_id')->label('Ҳолат тасдиғини топдими?')->formatStateUsing(fn($state) => $state ? 'Ҳа' : 'Йўқ'),
            ])->filters([
                SelectFilter::make('create_at')->label('Санаси бўйича'),

                SelectFilter::make('inspection_cases_id')
                    ->label('Ҳолат тасдиғи')
                    ->options([
                        1 => 'Ҳа',
                        0 => 'Йўқ',
                    ]),

            ])->filtersLayout(FiltersLayout::AboveContent)
            ->headerActions([
                Action::make('exportExcel')
                    ->label('Excel yuklash')
                    ->url('/export/desipline')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->openUrlInNewTab(),
            ]);

    }
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('index')
                ->label('№')
                ->rowIndex()
                ->alignCenter(),

            TextColumn::make('inspection_qualification_dates')
                ->date('d.m.Y')
                ->label('Текширув санаси'),

            TextColumn::make('judges.last_name')
                ->label('Ф.И.Ш.')
                ->getStateUsing(fn($record) => optional($record->judges)?->middle_name . ' ' . optional($record->judges)?->first_name . ' ' . optional($record->judges)?->last_name)
                ->searchable([
                    'judges.middle_name',
                    'judges.first_name',
                    'judges.last_name',
                ]),

            TextColumn::make('judges.position.name')->label('Ихтисослик'),
            TextColumn::make('judges.positions.name')->label('Судьялик лавозими'),
            TextColumn::make('gender')->label('ЖИНСИ')
                ->formatStateUsing(fn($state) => $state == 1 ? 'Эркак' : 'Аёл'),

            TextColumn::make('inspection_conclusion_date')->label('Хизмат текшируви хулосаси тузилган сана')->limit(15)->date('d.m.Y'),
            TextColumn::make('inspection_offices')->label('Хизмат текширувини ўтказишга асос')->limit(15),
            TextColumn::make('by_council')->label('Кенгаш ташаббуси билан ўтказилганми?')->limit(15)
                ->formatStateUsing(fn($state) => $state ? 'Ҳа' : 'Йўқ'),

//            TextColumn::make('inspection_office.name')->label('Хизмат текшируви ўтказган идора'),
//            TextColumn::make('inspector_code')->label('Код'),
//            TextColumn::make('inspector_name')->label('Текширган судья ФИШ'),

            TextColumn::make('confirmation_found')->label('Ҳолат тасдиғини топдими?')
                ->formatStateUsing(fn($state) => $state ? 'Ҳа' : 'Йўқ'),

//            TextColumn::make('found_flaws')->label('Аниқланган хато ва камчиликлар'),
//
//            TextColumn::make('sent_to_council_date')->label('Малака ҳайъатига юборилган сана')->date('d.m.Y'),
//            TextColumn::make('opened_discipline')->label('Интизомий иш қўзғатилганми?')
//                ->formatStateUsing(fn($state) => $state ? 'Ҳа' : 'Йўқ'),

//            TextColumn::make('opened_discipline_date')->label('Интизомий иш қўзғатилган сана')->date('d.m.Y'),
//            TextColumn::make('judges.codes')->label('Код'),
//            TextColumn::make('council_speaker')->label('Маъруза қилган судья'),
//            TextColumn::make('discussion_date')->label('Муҳокама қилинган сана')->date('d.m.Y'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('region_id')
                ->relationship('region', 'name')
                ->label('Ҳудуд')
                ->multiple()
                ->preload()
                ->placeholder('Ҳудудни танланг'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Интизомийлар';
    }
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }
    public function getRegionStatistics()
    {
        $regions = DB::table('regions')->pluck('name', 'id'); // [id => 'Андижон', ...]

        $rows = [];

        foreach ($regions as $regionId => $regionName) {
            $query = service_inspection::where('region_id', $regionId);

            $total = $query->count();

            // Tasdiq holatlari (inspection_cases_id)
            $approvedYes = (clone $query)->where('inspection_cases_id', 1)->count(); // Tasdiq topgan
            $approvedNo  = (clone $query)->where('inspection_cases_id', 2)->count(); // Tasdiq topmagan

            // Intizomiy ish holati (inspection_adults_id)
            $disciplineYes = (clone $query)->where('inspection_adults_id', 1)->count(); // Intizomiy ish ochilgan
            $disciplineNo  = (clone $query)->where('inspection_adults_id', 2)->count(); // Ochilmagan

            // Jazolar (inspection_regulations_id)
            $punished = (clone $query)->whereNotNull('inspection_regulations_id')->count();
            $warning  = (clone $query)->where('inspection_regulations_id', 1)->count(); // Огоҳлантириш
            $rebuke   = (clone $query)->where('inspection_regulations_id', 2)->count(); // Ҳайфсан
            $fine     = (clone $query)->where('inspection_regulations_id', 3)->count(); // Жарима
            $demotion = (clone $query)->where('inspection_regulations_id', 4)->count(); // Малака пасайтirish
            $dismissal = (clone $query)->where('inspection_regulations_id', 5)->count(); // Ваколат муддатидан илгari тугатиш

            // Ish holatlari (inspection_conducted_id)
            $closed = (clone $query)->where('inspection_conducted_id', 1)->count(); // Жазосиз тугатилган
            $reconsidered = (clone $query)->where('inspection_conducted_id', 2)->count(); // Қайта кўрилган
            $canceled = (clone $query)->where('inspection_conducted_id', 3)->count(); // Бекор қилинган

            $valueOrDash = fn($value) => $value > 0 ? $value : ' ';

            $rows[] = [
                'region' => $regionName,
                'total' => $valueOrDash($total),
                'approved_no' => $valueOrDash($approvedNo),
                'approved_yes' => $valueOrDash($approvedYes),
                'discipline_started_no' => $valueOrDash($disciplineNo),
                'discipline_started_yes' => $valueOrDash($disciplineYes),
                'punished' => $valueOrDash($punished),
                'warning' => $valueOrDash($warning),
                'rebuke' => $valueOrDash($rebuke),
                'fine' => $valueOrDash($fine),
                'demotion' => $valueOrDash($demotion),
                'dismissal' => $valueOrDash($dismissal),
                'closed' => $valueOrDash($closed),
                'reconsidered' => $valueOrDash($reconsidered),
                'canceled' => $valueOrDash($canceled),
            ];
        }

        return $rows;
    }
    public function getViewData(): array
    {
        return [
            'rows' => $this->getRegionStatistics(),
        ];
    }

}
