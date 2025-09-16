<?php

namespace App\Observers;

use App\Models\JudgeActivity;
use App\Models\JudgeActivityEntry;
use App\Models\Judges;
use Illuminate\Support\Facades\Log;

class JudgeActivityEntryObserver
{
    protected function recalculateAddingRating($judgeId): void
    {
        $judge = Judges::with(['judge_activity_entries' => function ($q) {
            // kerak bo'lsa bu yerda 'date' yoki 'month' bo'yicha order ber
            $q->latest('id');
        }, 'judge_activity'])->find($judgeId);

        if (!$judge) {
            Log::debug('❌ Judge topilmadi!');
            return;
        }

        // ✅ eng so‘nggi activity entry
        $entry = $judge->judge_activity_entries->first(); // Collection->first()
        $norm  = $judge->judge_activity;                  // bitta model

        if (!$entry || !$norm) {
            $judge->update(['adding_rating' => -5]);
            Log::debug('⚠️ Entry yoki normativ yo‘q — -5 berildi');
            return;
        }

        $fields = [
            'criminal_first_instance_avg',
            'criminal_appeal_avg',
            'criminal_cassation_avg',
            'admin_violation_first_instance_avg',
            'admin_violation_appeal_avg',
            'admin_violation_cassation_avg',
            'materials_first_instance_avg',
            'materials_appeal_avg',
            'materials_cassation_avg',
            // Ehtiyot: civil_first_instance_avg kiritilmagan bo'lsa, qo'shing:
            // 'civil_first_instance_avg',
            'civil_appeal_avg',
            'civil_cassation_avg',
            'economic_first_instance_avg',
            'economic_appeal_avg',
            'economic_cassation_avg',
            'administrative_case_first_instance_avg',
            'administrative_case_appeal_avg',
            'administrative_case_cassation_avg',
        ];

        $epsilon = 1e-9; // float xatolariga kichik yostiqcha
        $failures = [];

        foreach ($fields as $field) {
            $e = data_get($entry, $field);
            $n = data_get($norm,  $field);

            // Agar normativ yo'q (null) bo'lsa, bu maydonni SKIP qilamiz (talab belgilanmagan)
            if ($n === null) {
                continue;
            }

            // Entry yo'q bo'lsa yoki son emas bo'lsa — yiqildi deb hisoblaymiz
            if ($e === null || !is_numeric($e)) {
                $failures[$field] = ['entry' => $e, 'norm' => $n];
                continue;
            }

            // Taqqoslash: entry >= norm bo‘lishi kerak
            if (((float)$e + $epsilon) < (float)$n) {
                $failures[$field] = ['entry' => $e, 'norm' => $n];
            }
        }

        $passed = empty($failures);

        $judge->update([
            'adding_rating' => $passed ? 5 : -5,
        ]);

        if ($passed) {
            Log::debug('✅ Normativdan o‘tdi — +5 berildi');
        } else {
            Log::debug('❌ Normativdan o‘tmadi — -5 berildi. Tafsilotlar:', $failures);
        }
    }


}
