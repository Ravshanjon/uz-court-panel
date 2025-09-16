<?php

namespace App\Filament\Pages;

use App\Models\Family;
use App\Models\Judges;
use App\Models\Parents;
use App\Models\RelatedJudgeMatch;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RelatedJudgesPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Ота/Она бўйича ўхшашлик';
    protected static ?string $title = 'Қариндош судялар';
    protected static string $view = 'filament.pages.related-judges-page';
    public function getHeaderActions(): array
    {
        return [
            Action::make('generateMatches')
                ->label('Ўхшашликларни янгилаш')
                ->color('primary')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->action(fn () => $this->generateRelationshipMatches())
                ->successNotificationTitle('Ўхшашликлар янгиланди!'),
        ];
    }
    public array $matchesByRelation = [];
    public function mount(): void
    {
        $this->matchesByRelation = RelatedJudgeMatch::with(['judgeA.judges_stages.position', 'judgeB.judges_stages.position'])
            ->get()
            ->groupBy('relation_type')
            ->map(function ($group) {
                return $group->map(function ($match) {
                    return [
                        'relative_name' => $match->relative_name,
                        'match_percent' => $match->match_percent,
                        'judges' => [
                            [
                                'full_name' => $match->judgeA?->full_name,
                                'image_url' => $match->judgeA?->image_url,
                                'position' => $match->judgeA?->judges_stages?->sortBy('start_date')->first()?->position?->name ?? '—',
                            ],
                            [
                                'full_name' => $match->judgeB?->full_name,
                                'image_url' => $match->judgeB?->image_url,
                                'position' => $match->judgeB?->judges_stages?->sortBy('start_date')->first()?->position?->name ?? '—',
                            ],
                        ]
                    ];
                });
            })
            ->toArray();
    }
    public function generateRelationshipMatches(): void
    {
        RelatedJudgeMatch::truncate(); // eski natijalarni tozalash

        $judges = Judges::with('family', 'judges_stages.position')->get();

        foreach ($judges as $judgeA) {
            foreach ($judges as $judgeB) {
                if ($judgeA->id === $judgeB->id) continue;

                // Ota-ona tekshiruv
                $this->compareByParent($judgeA, $judgeB, 'Отаси');
                $this->compareByParent($judgeA, $judgeB, 'Онаси');

                // Opa-singil, aka-uka (ota-ona nomi bir xil bo‘lsa)
                $this->compareByBothParents($judgeA, $judgeB, ['Синглиси', 'Опаси'], 'Опа-Сингил');
                $this->compareByBothParents($judgeA, $judgeB, ['Акаси', 'Укаси'], 'Ака-Ука');

                // Sudya A ning turmush o‘rtog‘i B ning opasi/singlisi/akasi/ukasi bo‘lsa
                $this->compareSpouseSiblingMatch($judgeA, $judgeB);
            }
        }
    }

    protected function isSimilar(string $a, string $b): bool
    {
        similar_text(mb_strtolower($a), mb_strtolower($b), $percent);
        return $percent >= 70;
    }

    protected function compareByParent($judgeA, $judgeB, $relation)
    {
        $a = $judgeA->family->firstWhere('parents.name', $relation)?->name;
        $b = $judgeB->family->firstWhere('parents.name', $relation)?->name;

        if ($a && $b && $this->isSimilar($a, $b)) {
            RelatedJudgeMatch::updateOrCreate([
                'judge_a_id' => $judgeA->id,
                'judge_b_id' => $judgeB->id,
                'relation_type' => $relation,
            ], [
                'relative_name' => $a,
                'match_percent' => 70,
            ]);
        }
    }

    protected function compareByBothParents($judgeA, $judgeB, $relationType)
    {
        $aFather = $judgeA->family->firstWhere('parents.name', 'Отаси')?->name;
        $aMother = $judgeA->family->firstWhere('parents.name', 'Онаси')?->name;

        $bFather = $judgeB->family->firstWhere('parents.name', 'Отаси')?->name;
        $bMother = $judgeB->family->firstWhere('parents.name', 'Онаси')?->name;

        if ($aFather && $aMother && $bFather && $bMother &&
            $this->isSimilar($aFather, $bFather) &&
            $this->isSimilar($aMother, $bMother)) {

            RelatedJudgeMatch::updateOrCreate([
                'judge_a_id' => $judgeA->id,
                'judge_b_id' => $judgeB->id,
                'relation_type' => $relationType,
            ], [
                'relative_name' => $aFather . ' + ' . $aMother,
                'match_percent' => 90,
            ]);
        }
    }

    protected function compareSpouseSiblingMatch($judgeA, $judgeB)
    {
        $spouse = $judgeA->family->firstWhere('parents.name', 'Турмуш ўртоғи')?->name;
        if (!$spouse) return;

        foreach ($judgeB->family as $fam) {
            $relation = $fam->parents?->name;
            if (!in_array($relation, ['Синглиси', 'Опаси', 'Акаси', 'Укаси'])) continue;

            if ($this->isSimilar($spouse, $fam->name)) {
                RelatedJudgeMatch::updateOrCreate([
                    'judge_a_id' => $judgeA->id,
                    'judge_b_id' => $judgeB->id,
                    'relation_type' => 'Турмуш ўртоғи - ' . $relation,
                ], [
                    'relative_name' => $spouse,
                    'match_percent' => 85,
                ]);
            }

        }
    }
    public static function canAccess(): bool
    {
        $user = auth()->user();

        // malaka bo'lsa URL orqali ham kira olmaydi (403)
        return ! ($user && $user->getRoleNames()->contains(
                fn ($r) => Str::lower($r) === 'malaka'
            ));
    }

}
