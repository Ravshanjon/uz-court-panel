<?php

use App\Exports\DesiplineExport;
use App\Http\Controllers\JudgesController;
use App\Http\Controllers\OpenAiController;
use App\Http\Controllers\PDFExportController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;


Route::get('/judges/public/{judge}', function (\App\Models\Judges $judge) {
    return view('judges.public', ['judge' => $judge]);
})->name('judges.public');
Route::get('/test-judge', [JudgesController::class, 'testJudgeStage']);

Route::post('/ask', [OpenAIController::class, 'handle']);

Route::get('/judges/{id}/download-pdf', [PDFExportController::class, 'downloadPDF'])->name('judges.download-pdf');
Route::get('/judges/{id}/profile-pdf', [PDFExportController::class, 'profilePdf'])->name('judges.profile-pdf');
Route::get('/judge/{id}/cv', [JudgesController::class, 'showCv'])->name('judge.cv');


Route::get('/admin/heatmap/{judge}', function ($judgeId) {
    $data = DB::table('judges_stages')
        ->where('judge_id', $judgeId)
        ->selectRaw('DATE(start_date) as date, COUNT(*) as count')
        ->groupBy('date')
        ->get();
    return $data;
})->name('heatmap.data');


Route::get('/export/desipline', function () {
    return Excel::download(new DesiplineExport, 'desipline.xlsx');
});
Route::get('/statistics/download', [StatisticsController::class, 'download'])->name('statistics.download');

Route::get('/compare-judges/pdf/{judgeAId}/{judgeBId}', [PDFExportController::class, 'generate'])
    ->name('compare.judges.pdf');
