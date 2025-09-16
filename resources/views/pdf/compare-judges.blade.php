<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Sudya Taqqoslash</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>

<div class="w-full rounded-md bg-gray-200 mb-4 dark:bg-gray-700 p-4">
    <div class="text-gray-600 rounded-full dark:bg-gray-300">
        <h2 class="font-semibold">Sudya Taqqoslash Natijalari</h2>
        <span>{{\Illuminate\Support\Carbon::now()->format('d.m.Y')}} ҳолатига</span>
    </div>
</div>

<div class="container mx-auto">
    <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
            <div class="flex items-center ps-3">
                <div class="text-center mx-auto">
                    <img class="rounded-xl mb-4 w-32 h-32" src="{{ public_path('storage/' . $judgeA->image) }}" alt="Sudya A rasmi">
                    <h2>{{ $judgeA->last_name }} {{ $judgeA->first_name }} {{ $judgeA->middle_name }}</h2>

                </div>
            </div>
        </li>

        <li class="w-full dark:border-gray-600">
            <div class="flex items-center ps-3">
                <div class="text-center mx-auto">
                    <img class="rounded-xl mb-4 w-32 h-32" src="{{ public_path('storage/' . $judgeB->image) }}" alt="Sudya B rasmi">
                    <h2>{{ $judgeB->last_name }} {{ $judgeB->first_name }} {{ $judgeB->middle_name }}</h2>
                </div>

            </div>
        </li>
    </ul>

    <div class="grid sm:grid-cols-2 gap-8 mt-6">

        {{-- Sudya A --}}
            <div>
                <div class="flex items-center mb-5">
                    <p class="bg-blue-100 text-blue-800 text-sm font-semibold inline-flex items-center p-1.5 rounded-sm dark:bg-blue-200 dark:text-blue-800">
                        {{ $judgeA->last_name }}
                    </p>
                </div>

                @php
                    $metrics = [
                        'Суд қарорларининг сифати' => ['a' => $judgeA->quality_score, 'b' => $judgeB->quality_score],
                        'Судьянинг одоби' => ['a' => $judgeA->ethics_score, 'b' => $judgeB->ethics_score],
                        'Хизмат текшируви' => ['a' => $judgeA->etiquette_score, 'b' => $judgeB->etiquette_score],
                        'Чет тили бонуси' => ['a' => $judgeA->foreign_language_bonus, 'b' => $judgeB->foreign_language_bonus],
                        'Қўшимча баллар' => ['a' => $judgeA->adding_rating, 'b' => $judgeB->adding_rating],
                    ];
                @endphp

                @foreach ($metrics as $label => $values)
                    @php
                        $a = $values['a'];
                        $b = $values['b'];
                        $total = $a + $b;
                        $aPercent = $total > 0 ? round(($a / $total) * 100) : 50;
                        $bPercent = 100 - $aPercent;
                    @endphp

                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</dt>
                        <dd class="flex items-center mb-3">
                            <div class="w-full bg-gray-200 rounded-sm h-2.5 dark:bg-gray-700 me-2 flex">
                                <div class="bg-blue-600 h-2.5 rounded-l-sm" style="width: {{ $aPercent }}%"></div>
                                <div class="bg-green-600 h-2.5 rounded-r-sm" style="width: {{ $bPercent }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-blue-600 me-1">{{ $aPercent }}%</span>
                            <span class="text-xs text-gray-400">A</span>
                            <span class="text-sm font-medium text-green-600 mx-2">{{ $bPercent }}%</span>
                            <span class="text-xs text-gray-400">B</span>
                        </dd>
                    </dl>
                @endforeach
            </div>

            {{-- Sudya B --}}
            <div>
                <div class="flex items-center mb-5">
                    <p class="bg-green-100 text-green-800 text-sm font-semibold inline-flex items-center p-1.5 rounded-sm dark:bg-green-200 dark:text-green-800">
                        {{ $judgeB->last_name }}
                    </p>
                </div>

                @php
                    $metricsB = [
                        'Суд қарорларининг сифати' => $judgeB->quality_score,
                        'Судьянинг одоби' => $judgeB->ethics_score,
                        'Хизмат текшируви' => $judgeB->etiquette_score,
                        'Чет тили бонуси' => $judgeB->foreign_language_bonus,
                        'Қўшимча баллар' => $judgeB->adding_rating,
                    ];
                @endphp

                @foreach ($metricsB as $label => $score)
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</dt>
                        <dd class="flex items-center mb-3">
                            <div class="w-full bg-gray-200 rounded-sm h-2.5 dark:bg-gray-700 me-2">
                                <div class="bg-green-600 h-2.5 rounded-sm dark:bg-green-500" style="width: {{ min(100, $score) }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $score }}</span>
                        </dd>
                    </dl>
                @endforeach
            </div>
    </div>
    <p><strong>Файлни юклаб олган фойдаланувчи:</strong> {{ $user->name }}</p>
    <p><strong>Юклаб олиш санаси:</strong> {{ now()->format('d.m.Y H:i') }}</p>
</div>


</body>
</html>
