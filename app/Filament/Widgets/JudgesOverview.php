<?php

namespace App\Filament\Widgets;

use App\Models\Establishment;
use App\Models\Judges;
use App\Models\Judges_Stages;
use App\Models\Regions;
use Filament\Support\RawJs;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class JudgesOverview extends BaseWidget
{
    protected static ?int $sort = 10;

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $isMalaka = $user && $user->getRoleNames()->contains(fn($r) => Str::lower($r) === 'malaka');
        $scopeRegionId = $isMalaka ? ($user->regions_id ?? null) : (request()->integer('region_id') ?: null);

        // ✅ Legacy nomdan foydalanish uchun:
        $regionId = $scopeRegionId;

        // === Bazaviy so'rovlar (role ga qarab region scope qo'llanadi) ===
        $judgesQ = Judges::query();
        $establishQ = Establishment::query();
        $stagesQ = Judges_Stages::query();

        if ($isMalaka && $regionId) {
            $judgesQ->where('region_id', $regionId);
            $establishQ->where('region_id', $regionId);
            $stagesQ->where('region_id', $regionId);
        }

        // === Filtrlangan sanashlar (Malaka bo'lsa regionga cheklangan) ===
        $judgesTotalFiltered = (clone $judgesQ)->count();
        $femaleJudgesCount = (clone $judgesQ)->where('gender', 0)->count();
        $femalePercent = $judgesTotalFiltered > 0
            ? round(($femaleJudgesCount / $judgesTotalFiltered) * 100, 2)
            : 0;

        // Vitrina uchun jami sudyalar soni:
        $judgesTotal = $isMalaka ? $judgesTotalFiltered : Judges::count();

        // 65+
        $seniorJudgesCount = $isMalaka
            ? (clone $judgesQ)->where('age', '>=', 65)->count()
            : Judges::where('age', '>=', 65)->count();

        $seniorPercent = $judgesTotal > 0
            ? round(($seniorJudgesCount / $judgesTotal) * 100, 2)
            : 0;

        // Shtatlar / band / bo'sh
        $establishmentCount = (clone $establishQ)->count();
        $usedCount = (clone $stagesQ)->count();
        $vacantCount = max(0, $establishmentCount - $usedCount);
        $vacantPercent = $establishmentCount > 0
            ? round(($vacantCount / $establishmentCount) * 100, 2)
            : 0;

        // === Mismatch: modal mantiqi bilan bir xil (HOZIR ishlayotganlar kesimida) ===
        $latestStage = DB::table('judges_stages as s')
            ->select('s.judge_id', DB::raw('MAX(s.start_date) as max_start'))
            ->groupBy('s.judge_id');

        // Hozir ishlayotganlar bazasi (role-aware)
        $workingBase = DB::table('judges as j')
            ->joinSub($latestStage, 'ls', fn($q) => $q->on('ls.judge_id', '=', 'j.id'))
            ->join('judges_stages as js', function ($q) {
                $q->on('js.judge_id', '=', 'j.id')
                    ->on('js.start_date', '=', 'ls.max_start');
            })
            ->when($isMalaka && $regionId, fn($q) => $q->where('js.region_id', $regionId)); // ⬅️ malaka: faqat hozir shu viloyatda ishlayotganlar

        // Jami hozir shu scope’da ishlayotgan sudyalar
        $workingTotal = (clone $workingBase)->distinct('j.id')->count('j.id');

        // Tug‘ilgan region ≠ hozirgi ish regioni bo‘lganlar (mismatch)
        $mismatchedCount = (clone $workingBase)
            ->whereColumn('j.region_id', '!=', 'js.region_id')
            ->distinct('j.id')
            ->count('j.id');

        // Foiz
        $mismatchPercent = $workingTotal > 0
            ? round(($mismatchedCount / $workingTotal) * 100, 2)
            : 0;

        // UI sarlavhalarida region nomini ko'rsatish (Malaka bo'lsa)
        $scopeSuffix = '';
        if ($isMalaka && $regionId) {
            $regionName = Regions::find($regionId)?->name;
            if ($regionName) $scopeSuffix = ' (' . $regionName . ')';
        }

        return [

            Stat::make('Умумий штатлар сони' . $scopeSuffix, $establishmentCount)
                ->color('gray'),

            Stat::make('Судьялар сони' . $scopeSuffix, $judgesTotal)
                ->description(new HtmlString(
                    '<span class="text-gray-500">Жами (фильтрланган): ' . $judgesTotalFiltered . ' та – </span>' .
                    '<span class="text-2xl">' .
                    ($establishmentCount > 0 ? round(($judgesTotal / $establishmentCount) * 100, 1) : 0) . '%</span>'
                ))
                ->color('success'),

            Stat::make('Аёл судьялар сони' . $scopeSuffix, $femaleJudgesCount)
                ->description(new HtmlString(
                    "Жами (фильтрланган): {$judgesTotalFiltered} та – " .
                    "<span class='text-2xl'>{$femalePercent}%</span>"
                ))
                ->color('pink'),

            Stat::make('Бўш лавозимлар сони' . $scopeSuffix, $vacantCount)
                ->description(new HtmlString(
                    "Жами: {$establishmentCount} та, Банд: {$usedCount} та – " .
                    "<span class='text-2xl'>{$vacantPercent}%</span> бўш"
                ))
                ->color('warning'),

            Stat::make('65 ва шу ёшдан ошган судьялар' . $scopeSuffix, $seniorJudgesCount)
                ->description(new HtmlString(
                    "Жами: {$judgesTotal} та судьянинг – " .
                    "<span class='text-2xl'>{$seniorPercent}%</span>"
                ))
                ->extraAttributes([
                    'x-data' => '{}',
                    'x-on:click' => RawJs::make("\$dispatch('open-senior-modal')"),
                    'class' => 'cursor-pointer',
                ])
                ->color('danger'),

            // ✅ Endi mismatch modal bilan 1x1: malaka bo‘lsa faqat o‘z viloyatida HOZIR ishlayotganlar
            Stat::make('Туғилган ва иш жойи турлича бўлган судьялар' . $scopeSuffix, $mismatchedCount)
                ->description(new HtmlString(
                    "Жами: {$workingTotal} та судьянинг – " .
                    "<span class='text-2xl'>{$mismatchPercent}%</span>"
                ))
                ->extraAttributes([
                    'x-data' => '{}',
                    'x-on:click' => RawJs::make("\$dispatch('open-mismatch-modal')"),
                    'class' => 'cursor-pointer',
                ])
                ->color('gray'),
        ];
    }
}
