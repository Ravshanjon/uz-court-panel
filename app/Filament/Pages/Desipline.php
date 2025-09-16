<?php

namespace App\Filament\Pages;

use App\Exports\DesiplineExport;
use App\Models\Judges;
use App\Models\service_inspection;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;
use Svg\Tag\Text;

class Desipline extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Хизмат текшируви';
    protected static string $view = 'filament.pages.desipline';

    public ?int $year = null;

    protected $queryString = [
        'year' => ['except' => ''], // URL bilan bog'lash ixtiyoriy
    ];

    public function updatedYear()
    {
        $this->resetTablePage();
    }

    public function table(Table $table)
    {

        $user = auth()->user();
        $isMalaka = $user && $user->getRoleNames()->contains(fn($r) => Str::lower($r) === 'malaka');
        $rid = $user?->regions_id;

        return $table
            ->query(
                service_inspection::query()
                    ->when($isMalaka && $rid, fn ($q) => $q->where('region_id', $rid))
                    ->when($this->year, fn ($q) => $q->whereYear('inspection_qualification_dates', (int) $this->year))


            )
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
                    ->label('Ҳолат тасдиғни топдими')
                    ->options([
                        1 => 'Ҳа',
                        0 => 'Йўқ',
                    ]),
                SelectFilter::make('region')->label('Вилоятлар бўйича')
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

            TextColumn::make('confirmation_found')->label('Ҳолат тасдиғини топдими?')
                ->formatStateUsing(fn($state) => $state ? 'Ҳа' : 'Йўқ'),
            TextColumn::make(''),
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
        return 'Хизмат текшируви';
    }

    public function getRegionStatistics()
    {
        $user = auth()->user();
        $isMalaka = $user && $user->getRoleNames()->contains(fn($r) => Str::lower($r) === 'malaka');
        $rid = $user?->regions_id;

        // ✅ Malaka bo‘lsa: faqat o‘z regionini hisoblaymiz
        $regionsQ = DB::table('regions');
        if ($isMalaka && $rid) {
            $regionsQ->where('id', $rid);
        }
        $regions = $regionsQ->pluck('name', 'id');

        $rows = [];
        foreach ($regions as $regionId => $regionName) {
            $base = \App\Models\service_inspection::query()->where('region_id', $regionId);

            $total = (clone $base)->count();

            $approvedYes = (clone $base)->where('inspection_cases_id', 1)->count();
            $approvedNo = (clone $base)->where('inspection_cases_id', 0)->count();

            $disciplineYes = (clone $base)->where('inspection_adults_id', 1)->count();
            $disciplineNo = (clone $base)->where('inspection_adults_id', 0)->count();

            $punished = $warning = $rebuke = $fine = $demotion = $dismissal = 0;

            if (Schema::hasColumn('service_inspections', 'prision_type_id')) {
                $punished = (clone $base)->whereNotNull('prision_type_id')->count();
                $warning = (clone $base)->where('prision_type_id', 1)->count();
                $rebuke = (clone $base)->where('prision_type_id', 2)->count();
                $fine = (clone $base)->where('prision_type_id', 3)->count();
                $demotion = (clone $base)->where('prision_type_id', 4)->count();
                $dismissal = (clone $base)->where('prision_type_id', 5)->count();
            }

            $valueOrDash = fn($v) => $v > 0 ? $v : ' ';

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
                'closed' => $valueOrDash((clone $base)->where('inspection_conducted_id', 1)->count()),
                'reconsidered' => $valueOrDash((clone $base)->where('inspection_conducted_id', 2)->count()),
                'canceled' => $valueOrDash((clone $base)->where('inspection_conducted_id', 3)->count()),
            ];
        }

        return $rows;
    }

    #[\Livewire\Attributes\Url(as: 'judgeId')]
    public ?string $judgeId = null;

    public function getViewData(): array
    {
        return [
            'rows' => $this->getRegionStatistics(),
        ];
    }

    public function getUnderStudyProperty()
    {
        $user = auth()->user();
        $isMalaka = $user && $user->getRoleNames()->contains(fn($r) => Str::lower($r) === 'malaka');
        $rid = $user?->regions_id;

        return service_inspection::query()
            ->when($this->judgeId, fn($q) => $q->where('judge_id', $this->judgeId))
            // ✅ Malaka bo‘lsa: faqat o‘z regioni
            ->when($isMalaka && $rid, fn($q) => $q->where('region_id', $rid))
            ->where('under_study', true)
            ->with([
                'judges:id,first_name,middle_name,last_name,codes',
                'region:id,name',
                'inspectionOffice:id,name',
                'mistake:id,name',
                'prision_type:id,name',
            ])
            ->orderByDesc('study_started_at')
            ->get();
    }

//    public static function canAccess(): bool
//    {
//        $u = auth()->user();
//        // URL orqali ham faqat malaka kira oladi
//        return $u && $u->getRoleNames()->contains(fn($r) => Str::lower($r) === 'malaka');
//    }
    public static function canAccess(): bool
    {
        $u = auth()->user();
        return $u && $u->hasAnyRole(['super_admin', 'malaka']); // ro‘l nomlarini sizda qanday saqlangan bo‘lsa, shunday yozing
    }
}
