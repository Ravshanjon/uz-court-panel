<?php
namespace App\Services;

use App\Models\Family;

class FamilyParserService
{
    public static function parseFromLines(array $lines, string $judgeId): void
    {
        foreach ($lines as $line) {
            if (preg_match('/^(Ота|Она|Ака|Ука|Опаси|Синглиси|Турмуш ўртоғи)\s+(.+)/u', $line, $match)) {
                $relation = $match[1];
                $rest = $match[2];
                $parts = explode(',', $rest);

                \App\Models\Family::create([
                    'judge_id'  => $judgeId,
                    'relation'  => $relation,
                    'full_name' => trim($parts[0] ?? ''),
                    'birth'     => trim($parts[1] ?? ''),
                    'job'       => trim($parts[2] ?? ''),
                    'address'   => trim($parts[3] ?? ''),
                ]);
            }
        }
    }
}
