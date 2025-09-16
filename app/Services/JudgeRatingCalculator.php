<?php

namespace App\Services;

use App\Models\Judges;
use App\Models\RatingSetting;
use Illuminate\Support\Facades\Log;

class JudgeRatingCalculator
{
    public static function calculate(): void
    {
        // 1. Faqat oddiy sudyalarni olish
        $ordinaryJudges = Judges::with('establishment')
            ->whereHas('establishment', fn($q) =>
            $q->where('position_category_id', 4) // Faqat "Судья"
            )
            ->get()
            ->filter(fn($j) => $j->rating > 0);

        // 2. Oliy sud raisi uchun hisoblash
        $totalRating = $ordinaryJudges->sum('rating');
        $totalJudges = $ordinaryJudges->count();
        $averageRating = round($ordinaryJudges->avg('rating'), 1);

        Log::info('📊 Oliy sud raisi uchun hisob-kitob:', [
            'sudyalar_soni' => $totalJudges,
            'reyting_yigindisi' => $totalRating,
            'urtacha_reyting' => $averageRating,
        ]);

        // 3. Oliy sud raisini topib, rating yozish
        $supremeJudge = Judges::with('establishment.court_type')
            ->get()
            ->first(fn($judge) =>
                $judge->establishment &&
                $judge->establishment->position_category_id === 1 && // Суд раиси
                trim($judge->establishment->court_type?->name) === 'Олий суд' &&
                is_null($judge->establishment->region_id)
            );

        if ($supremeJudge && $averageRating > 0 && round($supremeJudge->rating, 1) !== $averageRating) {
            $supremeJudge->forceFill(['rating' => $averageRating])->saveQuietly();
            Log::info("✅ Oliy sud raisi reytingi yangilandi", [
                'judge_id' => $supremeJudge->id,
                'new_rating' => $averageRating,
            ]);
        }

        // 4. Har bir viloyat uchun viloyat raisiga ball berish
        $regions = $ordinaryJudges->pluck('establishment.region_id')->unique()->filter();
        foreach ($regions as $regionId) {
            // Shu regiondagi oddiy sudyalarni olish
            $regionJudges = $ordinaryJudges->filter(fn($j) =>
                $j->establishment?->region_id === $regionId
            );

            $regionSum = $regionJudges->sum('rating');
            $regionCount = $regionJudges->count();
            $regionAvg = round($regionJudges->avg('rating'), 1);

            // Log orqali tekshirish
            Log::info("📍 Region sudyalar:", [
                'region_id' => $regionId,
                'count' => $regionCount,
                'sum' => $regionSum,
                'average' => $regionAvg,
            ]);

            // Regiondagi raisni olish
            $regionLeader = Judges::with('establishment')
                ->whereHas('establishment', fn($q) =>
                $q->where('region_id', $regionId)
                    ->where('position_category_id', 1) // Суд раиси
                )
                ->first();

            if ($regionLeader && round($regionLeader->rating, 1) !== $regionAvg) {
                $regionLeader->forceFill(['rating' => $regionAvg])->saveQuietly();

                Log::info("✅ Viloyat raisi yangilandi", [
                    'region_id' => $regionId,
                    'judge_id' => $regionLeader->id,
                    'average' => $regionAvg,
                    'sum' => $regionSum,
                ]);
            }
        }

        $specialties = $ordinaryJudges->pluck('establishment.court_specialty_id')->unique()->filter();
        foreach ($specialties as $specialtyId) {
            $filtered = $ordinaryJudges->filter(fn($j) =>
                $j->establishment->court_specialty_id === $specialtyId
            );

            $sum = $filtered->sum('rating');
            $avg = round($filtered->avg('rating'), 1);

            $deputy = Judges::with('establishment')
                ->whereHas('establishment', fn($q) =>
                $q->whereNull('region_id')
                    ->where('position_category_id', 3)
                    ->where('court_specialty_id', $specialtyId)
                )
                ->first();

            if ($deputy && $deputy->rating !== $avg && $avg > 0) {
                $deputy->forceFill(['rating' => $avg])->saveQuietly();
                Log::info('✅ Oliy sud rais o‘rinbosari yangilandi', [
                    'judge_id' => $deputy->id,
                    'specialty_id' => $specialtyId,
                    'average' => $avg,
                    'sum' => $sum,
                ]);
            }
        }
    }
}
