<?php

use App\Http\Controllers\JudgesController;
use App\Http\Controllers\OpenAIController;
use App\Models\Judges;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Api;
use Illuminate\Support\Facades\Http;


//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::get('/ask', [OpenAIController::class, 'handle']);

Route::get('/judges/{fullName}', function ($fullName) {
    $queryName = mb_strtolower($fullName); // harf farqini oldini olamiz

    $judges = \App\Models\Judges::query()
        ->whereRaw("LOWER(CONCAT(last_name, ' ', first_name, ' ', middle_name)) LIKE ?", ["%$queryName%"])
        ->get();

    if ($judges->isEmpty()) {
        return response()->json(['message' => 'Sudya topilmadi'], 404);
    }

    return response()->json(
        $judges->map(function ($judge) {
            return [
                'photo_url'   => $judge->image
                    ? str_replace(
                        'http://127.0.0.1:8000',
                        'https://2cc3-185-203-238-157.ngrok-free.app',
                        asset('storage/' . $judge->image)
                    )
                    : null,

                'pdf_urls' => $judge->serviceinspection
                    ->filter(fn($inspection) => !empty($inspection->file))
                    ->map(function ($inspection) {
                        return str_replace(
                            'http://127.0.0.1:8000',
                            'https://2cc3-185-203-238-157.ngrok-free.app',
                            asset('storage/' . $inspection->file)
                        );
                    })
                    ->values(),

                'id'   => $judge->id,
                'last_name'   => $judge->last_name,
                'first_name'  => $judge->first_name,
                'middle_name' => $judge->middle_name,
                'age'         => $judge->age,
                'birth_place' => $judge->region?->name ?? 'Nomaʼlum',
                'birth_date'  => \Carbon\Carbon::parse($judge->birth_date)->format('d.m.Y'),
                'position'    => $judge->current_or_future_position_name,
                'rating'      => $judge->overall_score,

            ];
        })
    );
});

//Route::get('judges',JudgesController::class . '@getJudges');
Route::get('judges',[JudgesController::class, 'index'])->name('judges.index');
Route::get('/test-pdf',[JudgesController::class, 'testPdfRead'])->name('testPdfRead');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Login yoki parol noto‘g‘ri'], 401);
    }

    $token = $user->createToken('flutter-login')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user,
    ]);
});
Route::middleware('auth:sanctum')->get('/judges/self', [JudgesController::class, 'self']);
Route::get('/login', function () {
    return response()->json(['message' => 'Not allowed'], 403);
})->name('login');


