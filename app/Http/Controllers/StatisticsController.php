<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;

class StatisticsController extends Controller
{
    public function download()
    {
        $rows = $this->getRegionStatistics();
        $html = view('statistics.download', compact('rows'))->render();

        return response()->streamDownload(function () use ($html) {
            echo Browsershot::html($html)
                ->format('A4')
                ->landscape()
                ->showBackground()
                ->margins(7, 7, 7, 7)
                ->pdf();
        }, 'hududiy-statistika.pdf');
    }

    public function getRegionStatistics()
    {
        $regions = DB::table('regions')->pluck('name', 'id');

        $rows = [];

        foreach ($regions as $regionId => $regionName) {
            $query = \App\Models\service_inspection::where('region_id', $regionId);

            $total = $query->count();

            $approvedYes = (clone $query)->where('inspection_cases_id', 1)->count();
            $approvedNo = (clone $query)->where('inspection_cases_id', 2)->count();

            $disciplineYes = (clone $query)->where('inspection_adults_id', 1)->count();
            $disciplineNo = (clone $query)->where('inspection_adults_id', 2)->count();

            $punished = (clone $query)->whereNotNull('inspection_regulations_id')->count();
            $warning = (clone $query)->where('inspection_regulations_id', 1)->count();
            $rebuke = (clone $query)->where('inspection_regulations_id', 2)->count();
            $fine = (clone $query)->where('inspection_regulations_id', 3)->count();
            $demotion = (clone $query)->where('inspection_regulations_id', 4)->count();
            $dismissal = (clone $query)->where('inspection_regulations_id', 5)->count();

            $closed = (clone $query)->where('inspection_conducted_id', 1)->count();
            $reconsidered = (clone $query)->where('inspection_conducted_id', 2)->count();
            $canceled = (clone $query)->where('inspection_conducted_id', 3)->count();

            $rows[] = [
                'region' => $regionName,
                'total' => $total,
                'approved_no' => $approvedNo,
                'approved_yes' => $approvedYes,
                'discipline_started_no' => $disciplineNo,
                'discipline_started_yes' => $disciplineYes,
                'punished' => $punished,
                'warning' => $warning,
                'rebuke' => $rebuke,
                'fine' => $fine,
                'demotion' => $demotion,
                'dismissal' => $dismissal,
                'closed' => $closed,
                'reconsidered' => $reconsidered,
                'canceled' => $canceled,
            ];
        }

        return $rows;
    }
}
