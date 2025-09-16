{{--@php use App\Models\AppelationData; @endphp--}}

{{--@php--}}
{{--    $overall = round($record->rating);--}}
{{--    $grade = match (true) {--}}
{{--        $overall >= 86 => 'Намунали',--}}
{{--        $overall >= 71 => 'Яхши',--}}
{{--        $overall >= 56 => 'Қониқарли',--}}
{{--        default => 'Қониқарсиз',--}}
{{--    };--}}
{{--@endphp--}}

{{--<x-filament::page>--}}
{{--    <div id="rating-section" class="mb-4 rounded-md " xmlns:x-filament="http://www.w3.org/1999/html">--}}
{{--        <div class="space-y-2">--}}

{{--            <x-filament::fieldset>--}}
{{--                <x-slot name="label">--}}
{{--                    Рейтинг маълумотлари--}}
{{--                </x-slot>--}}

{{--                <div class="flex items-center justify-between">--}}
{{--                    <div>--}}
{{--                        <div class="flex justify-start mb-4">--}}
{{--                            <a href="{{ route('judges.download-pdf', $record->id) }}"--}}
{{--                               target="_blank"--}}
{{--                               class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md bg-gray-300 text-white hover:bg-primary-500 transition"--}}
{{--                            >--}}
{{--                                <x-heroicon-o-arrow-down-on-square-stack class="w-4 h-4 mr-1" />--}}
{{--                                Юклаб олиш--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        <h1 class="text-sm leading-relaxed">--}}
{{--                            Судьянинг фаолияти самарадорлигини--}}
{{--                            электрон рейтинг баҳолаш натижаси <br>--}}
{{--                            <span class="font-bold">Баҳолаш даври: 01.05.2024 - 30.04.2025</span>--}}
{{--                        </h1>--}}
{{--                    </div>--}}


{{--                    <p class="text-center" style="font-size: 80px; font-weight: 800; color: #0a84d2;">--}}
{{--                        <span>{{ $record->rating }}</span>--}}
{{--                        <span class="text-lg pt-2 block text-center"> {{ $grade}}</span>--}}
{{--                    </p>--}}
{{--                </div>--}}

{{--            </x-filament::fieldset>--}}
{{--            <x-filament::fieldset>--}}
{{--                <div class="flex col-span-2 mb-4 justify-between">--}}
{{--                    <div class="w-full flex justify-start items-center">--}}
{{--                        <div class="" style="width: 150px;height: 150px">--}}
{{--                            <img--}}
{{--                                src="{{ $record->image ? asset('storage/' . $record->image) : asset('image/default.jpg') }}"--}}
{{--                                class="w-full h-full object-cover border rounded-full">--}}
{{--                        </div>--}}

{{--                        <div class="flex flex-col text-sm space-y-2 p-4 border-r ">--}}
{{--                            <div class="font-semibold text-2xl">--}}
{{--                                {{ trim("{$record->middle_name} {$record->first_name} {$record->last_name}") ?: 'Nomaʼlum' }}--}}
{{--                            </div>--}}

{{--                            <div>--}}
{{--                                <span class="font-bold">Лавозими:</span>--}}
{{--                                {{ $record->current_or_future_position_name ?? 'Nomaʼlum' }}--}}
{{--                            </div>--}}

{{--                            <div>--}}
{{--                                <span class="font-bold">Туғилган санаси: </span>--}}
{{--                                {{ $record->birth_date ? \Carbon\Carbon::parse($record->birth_date)->format('d.m.Y') : 'Nomaʼlum' }}--}}
{{--                            </div>--}}

{{--                            <div>--}}
{{--                                <span class="font-bold">Судьялик стажи: </span>--}}
{{--                                {{ $record->judges_stages->last()?->counter ?? 'Маълумот йўқ' }}--}}

{{--                            </div>--}}

{{--                            <div>--}}
{{--                                <span class="font-bold">Малака даражаси: </span>--}}
{{--                                {{ $record->birth_date ? \Carbon\Carbon::parse($record->birth_date)->format('d.m.Y') : 'Nomaʼlum' }}--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="w-full text-sm text-gray-400">--}}
{{--                        @php--}}
{{--                            $entry = $record->judge_activity_entries;--}}

{{--                            $fields = [--}}
{{--                                'criminal_first_instance_avg' => 'Жиноят иши 1-инстанцияда ўртача иш ҳажми',--}}
{{--                                'criminal_appeal_avg' => 'Жиноят иши апелляцияда ўртача иш ҳажми',--}}
{{--                                'criminal_cassation_avg' => 'Жиноят иши кассацияда ўртача иш ҳажми',--}}
{{--                                'admin_violation_first_instance_avg' => 'Маъмурий ҳуқуқбузарлик 1-инстанцияда ўртача иш ҳажми',--}}
{{--                                'admin_violation_appeal_avg' => 'Маъмурий ҳуқуқбузарлик апелляцияда ўртача иш ҳажми',--}}
{{--                                'admin_violation_cassation_avg' => 'Маъмурий ҳуқуқбузарлик кассацияда ўртача иш ҳажми',--}}
{{--                                'materials_first_instance_avg' => 'Материаллар 1-инстанцияда ўртача иш ҳажми',--}}
{{--                                'materials_appeal_avg' => 'Материаллар апелляцияда ўртача иш ҳажми',--}}
{{--                                'materials_cassation_avg' => 'Материаллар кассацияда ўртача иш ҳажми',--}}
{{--                                'civil_appeal_avg' => 'Фуқаролик иши апелляцияда ўртача иш ҳажми',--}}
{{--                                'civil_cassation_avg' => 'Фуқаролик иши кассацияда ўртача иш ҳажми',--}}
{{--                                'economic_first_instance_avg' => 'Иқтисодий иш 1-инстанцияда ўртача иш ҳажми',--}}
{{--                                'economic_appeal_avg' => 'Иқтисодий иш апелляцияда ўртача иш ҳажми',--}}
{{--                                'economic_cassation_avg' => 'Иқтисодий иш кассацияда ўртача иш ҳажми',--}}
{{--                                'administrative_case_first_instance_avg' => 'Маъмурий иш 1-инстанцияда ўртача иш ҳажми',--}}
{{--                                'administrative_case_appeal_avg' => 'Маъмурий иш апелляцияда ўртача иш ҳажми',--}}
{{--                                'administrative_case_cassation_avg' => 'Маъмурий иш кассацияда ўртача иш ҳажми',--}}
{{--                                'forum_topics_count' => 'Форумдаги мавзулар сони',--}}
{{--                                'forum_comments_count' => 'Форум изоҳлар сони',--}}
{{--                            ];--}}

{{--                            $hasData = collect($fields)->some(fn($label, $key) => ($entry?->$key ?? 0) > 0);--}}
{{--                        @endphp--}}


{{--                            <table class="table-auto w-full rounded-md text-sm text-left border-gray-200">--}}
{{--                                <thead class="bg-gray-100">--}}
{{--                                @if ($hasData)--}}
{{--                                <tr>--}}
{{--                                    <th class="px-4 py-2 border">Кўрсаткич</th>--}}
{{--                                    <th class="px-4 py-2 border text-center">Қиймат</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                @foreach ($fields as $key => $label)--}}
{{--                                    @if (($entry?->$key ?? 0) > 0)--}}
{{--                                        <tr>--}}
{{--                                            <td class="px-4 py-2 rounded-xl border-r border-b -ms-2 px-2 text-sm font-medium leading-6 text-gray-950 dark:text-white">{{ $label }}</td>--}}
{{--                                            <td class="px-4 py-2 text-center border-b text-gray-650">--}}
{{--                                                <span style=" color: #0a84d2;">--}}
{{--                                                    {{ $entry->$key }} та--}}
{{--                                                </span>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                                @else--}}
{{--                                    <x-filament::badge color="danger" class="mt-2">--}}
{{--                                        Маълумот киритилмаган--}}
{{--                                    </x-filament::badge>--}}
{{--                                @endif--}}
{{--                                </tbody>--}}
{{--                            </table>--}}

{{--                    </div>--}}
{{--                </div>--}}

{{--            </x-filament::fieldset>--}}

{{--            <x-filament::fieldset>--}}

{{--                <div class="flex grid-cols-4 justify-between">--}}
{{--                    <div class="w-full mr-2">--}}
{{--                        <x-filament::fieldset>--}}
{{--                            <x-slot name="label" text="sm">--}}
{{--                                <div class="flex justify-between items-center">--}}
{{--                                    <span>Суд қарорларининг сифати:</span>--}}
{{--                                    <span>--}}

{{--                    <x-filament::badge size="xl">--}}
{{--                        50 / {{ $record->quality_score }}--}}
{{--                    </x-filament::badge>--}}
{{--                </span>--}}
{{--                                </div>--}}
{{--                            </x-slot>--}}
{{--                            @php--}}

{{--                                // === 1) Asosiy sudya bo‘lgan ishlar (sizdagi mavjud hisob) ===--}}
{{--                                $allStages = collect();--}}
{{--                                foreach ($record->appeals as $appeal) {--}}
{{--                                    if ($appeal->appelationData) $allStages->push($appeal->appelationData);--}}
{{--                                    if ($appeal->cassationData)  $allStages->push($appeal->cassationData);--}}
{{--                                    if ($appeal->taftish1Data)   $allStages->push($appeal->taftish1Data);--}}
{{--                                    if ($appeal->taftish2Data)   $allStages->push($appeal->taftish2Data);--}}
{{--                                    if ($appeal->taftish3Data)   $allStages->push($appeal->taftish3Data);--}}
{{--                                }--}}

{{--                                // score tanlash (universal)--}}
{{--                                $getScore = fn($item) => (float)($item->score ?? $item->first_score ?? $item->appelation_score ?? 0);--}}

{{--                                // kategoriya bo‘yicha ajratish--}}
{{--                                $cancelled = $allStages->filter(fn($a) =>--}}
{{--                                    in_array($a->reason?->instances?->name, ['Тўлиқ бекор қилинган', 'Қисман бекор қилинган'])--}}
{{--                                );--}}
{{--                                $modified = $allStages->filter(fn($a) =>--}}
{{--                                    in_array($a->reason?->instances?->name, ['Тўлиқ ўзгартирилган', 'Қисман ўзгартирилган'])--}}
{{--                                );--}}

{{--                                // typeOfDecision bo‘yicha guruhlash--}}
{{--                                $cancelledGrouped = $cancelled->groupBy(fn($a) => $a->reason?->typeOfDecision?->name ?? '—');--}}
{{--                                $modifiedGrouped  = $modified->groupBy(fn($a) => $a->reason?->typeOfDecision?->name ?? '—');--}}

{{--                                // asosiy ishlar bo‘yicha yakuniy hisob--}}
{{--                                $totalCountMain = $cancelled->count() + $modified->count();--}}
{{--                                $totalScoreMain = $cancelled->sum($getScore) + $modified->sum($getScore);--}}


{{--                                // === 2) Apellyatsiyada ishtirok (shu sudya speaker/presiding/jury) ===--}}
{{--                                $judgeId = $record->getKey();--}}

{{--                                // ustun nomlari turlicha bo‘lsa ham ishlashi uchun variantlarni qo‘llaymiz--}}
{{--                                $asParticipant = AppelationData::query()--}}
{{--->with(['reason.instances', 'reason.typeOfDecision'])--}}
{{--                                    ->where(function($q) use ($judgeId) {--}}
{{--                                        // speaker: speaker_judge_id yoki speaker_judge--}}
{{--                                        $q->where(function($qq) use ($judgeId) {--}}
{{--                                            $qq->where('speaker_judge', $judgeId)--}}
{{--                                               ->orWhere('speaker_judge', $judgeId);--}}
{{--                                        })--}}
{{--                                        // presiding: presiding_judge_id yoki presiding_judge--}}
{{--                                        ->orWhere(function($qq) use ($judgeId) {--}}
{{--                                            $qq->where('presiding_judge', $judgeId)--}}
{{--                                               ->orWhere('presiding_judge', $judgeId);--}}
{{--                                        })--}}
{{--                                        // jury: jury_judge_id yoki jury_judge--}}
{{--                                        ->orWhere(function($qq) use ($judgeId) {--}}
{{--                                            $qq->where('jury_judge', $judgeId)--}}
{{--                                               ->orWhere('jury_judge', $judgeId);--}}
{{--                                        });--}}
{{--                                    })--}}
{{--                                    ->get();--}}

{{--                                // roli bo‘yicha vazn (speaker=1.0, presiding=0.5, jury=0.5)--}}
{{--                                // *_id va idsiz variantlarni tekshiradi--}}
{{--                                $roleWeight = function($a) use ($judgeId) {--}}
{{--                                    $speaker   = $a->speaker_judge_id   ?? $a->speaker_judge   ?? null;--}}
{{--                                    $presiding = $a->presiding_judge_id ?? $a->presiding_judge ?? null;--}}
{{--                                    $jury      = $a->jury_judge_id      ?? $a->jury_judge      ?? null;--}}

{{--                                    if ($speaker === $judgeId)   return 1.0;--}}
{{--                                    if ($presiding === $judgeId) return 0.5;--}}
{{--                                    if ($jury === $judgeId)      return 0.5;--}}
{{--                                    return 0.0;--}}
{{--                                };--}}

{{--                                // ishtirokdagi kategoriyalar--}}
{{--                                $asCancelled = $asParticipant->filter(fn($a) =>--}}
{{--                                    in_array($a->reason?->instances?->name, ['Тўлиқ бекор қилинган', 'Қисман бекор қилинган'])--}}
{{--                                );--}}
{{--                                $asModified = $asParticipant->filter(fn($a) =>--}}
{{--                                    in_array($a->reason?->instances?->name, ['Тўлиқ ўзгартирилган', 'Қисман ўзгартирилган'])--}}
{{--                                );--}}

{{--                                // vaznli yig‘indi (ayirilgan ball aynan shu sudyaga tegishli ulushi)--}}
{{--                                $sumWeighted = function($col) use ($getScore, $roleWeight) {--}}
{{--                                    return $col->sum(function($a) use ($getScore, $roleWeight) {--}}
{{--                                        return $getScore($a) * $roleWeight($a);--}}
{{--                                    });--}}
{{--                                };--}}

{{--                                $asCancelledCount         = $asCancelled->count();--}}
{{--                                $asModifiedCount          = $asModified->count();--}}
{{--                                $asCancelledScoreWeighted = $sumWeighted($asCancelled);--}}
{{--                                $asModifiedScoreWeighted  = $sumWeighted($asModified);--}}
{{--                                $asTotalWeighted          = $asCancelledScoreWeighted + $asModifiedScoreWeighted;--}}

{{--                                // === 3) Umumiy (asosiy + ishtirok) ===--}}
{{--                                $grandTotalCount = $totalCountMain + ($asCancelledCount + $asModifiedCount);--}}
{{--                                $grandTotalScore = $totalScoreMain + $asTotalWeighted; // ekranda manfiy ko‘rsatiladi: -{{ $grandTotalScore }}--}}

{{--                                // format helper (trailing zeroni olib tashlaydi)--}}
{{--                                $fmt = fn($n) => rtrim(rtrim(number_format((float)$n, 2, '.', ''), '0'), '.');--}}
{{--                            @endphp--}}

{{--                            --}}{{-- ======= RENDER ======= --}}

{{--                            @if($cancelledGrouped->isNotEmpty() || $modifiedGrouped->isNotEmpty())--}}
{{--                                <table class="w-full border text-sm text-left mt-2">--}}
{{--                                    <tbody>--}}
{{--                                    --}}{{-- Бекор қилинган (asosiy sudya) --}}
{{--                                    <tr class="font-bold text-blue-900 border-t">--}}
{{--                                        <td colspan="3" class="p-4">Бекор қилинган суд қарорлари (асосий)</td>--}}
{{--                                    </tr>--}}
{{--                                    @foreach($cancelledGrouped as $decisionName => $items)--}}
{{--                                        <tr class="border">--}}
{{--                                            <td class="pl-3 p-4 border-r">{{ $decisionName }}</td>--}}
{{--                                            <td class="text-center border-r">{{ $items->count() }}</td>--}}
{{--                                            <td class="text-center text-danger-600 font-semibold">-{{ $items->sum($getScore) }}</td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}

{{--                                    --}}{{-- Ўзгартирилган (asosiy sudya) --}}
{{--                                    <tr class="font-bold text-blue-900 border-t border-b">--}}
{{--                                        <td colspan="3" class="p-4">Ўзгартирилган суд қарорлари (асосий)</td>--}}
{{--                                    </tr>--}}
{{--                                    @foreach($modifiedGrouped as $decisionName => $items)--}}
{{--                                        <tr class="border">--}}
{{--                                            <td class="pl-3 p-4 border-r">{{ $decisionName }}</td>--}}
{{--                                            <td class="text-center border-r">{{ $items->count() }}</td>--}}
{{--                                            <td class="text-center text-danger-600 font-semibold">-{{ $items->sum($getScore) }}</td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}

{{--                                    --}}{{-- Жами (asosiy) --}}
{{--                                    <tr class="font-semibold text-blue-900 border-t">--}}
{{--                                        <td class="text-right mr-4 border p-2">Жами</td>--}}
{{--                                        <td class="text-center border">{{ $totalCountMain }}</td>--}}
{{--                                        <td class="text-center border text-danger-600">-{{ $fmt($totalScoreMain) }}</td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            @endif--}}

{{--                            @if($asParticipant->isNotEmpty())--}}
{{--                                <table class="w-full border text-sm text-left mt-4">--}}
{{--                                    <tbody>--}}
{{--                                    <tr class="font-bold text-blue-900 border-t">--}}
{{--                                        <td colspan="3" class="p-4">Апелляцияда иштирок этган ишлар (шу судя улуши)</td>--}}
{{--                                    </tr>--}}

{{--                                    --}}{{-- Бекор қилинган (ishtirok) --}}
{{--                                    <tr class="border">--}}
{{--                                        <td class="pl-3 p-4 border-r">Бекор қилинган</td>--}}
{{--                                        <td class="text-center border-r">{{ $asCancelledCount }}</td>--}}
{{--                                        <td class="text-center text-danger-600 font-semibold">-{{ $fmt($asCancelledScoreWeighted) }}</td>--}}
{{--                                    </tr>--}}

{{--                                    --}}{{-- Ўзгартирилган (ishtirok) --}}
{{--                                    <tr class="border">--}}
{{--                                        <td class="pl-3 p-4 border-r">Ўзгартирилган</td>--}}
{{--                                        <td class="text-center border-r">{{ $asModifiedCount }}</td>--}}
{{--                                        <td class="text-center text-danger-600 font-semibold">-{{ $fmt($asModifiedScoreWeighted) }}</td>--}}
{{--                                    </tr>--}}

{{--                                    --}}{{-- Жами (ishtirok) --}}
{{--                                    <tr class="font-semibold text-blue-900 border-t">--}}
{{--                                        <td class="text-right mr-4 border p-2">Жами</td>--}}
{{--                                        <td class="text-center border">{{ $asCancelledCount + $asModifiedCount }}</td>--}}
{{--                                        <td class="text-center border text-danger-600">-{{ $fmt($asTotalWeighted) }}</td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            @endif--}}

{{--                            --}}{{-- Umumiy yakun (asosiy + ishtirok) --}}
{{--                            @if(($cancelledGrouped->isNotEmpty() || $modifiedGrouped->isNotEmpty()) || $asParticipant->isNotEmpty())--}}
{{--                                <div class="mt-3 flex justify-end">--}}
{{--                                    <div class="text-sm font-semibold">--}}
{{--                                        Жами--}}
{{--                                        <span class="text-danger-600">-{{ $fmt($grandTotalScore) }}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endif--}}

{{--                        </x-filament::fieldset>--}}
{{--                    </div>--}}

{{--                    <div class="w-full mr-2">--}}
{{--                        <div class="mb-4">--}}
{{--                            <x-filament::fieldset>--}}
{{--                                <x-slot name="label" text="sm">--}}
{{--                                    <div class="flex justify-between items-center">--}}
{{--                                        <div>Судьянинг одоби ва масъулияти:</div>--}}
{{--                                        <div class="flex justify-end">--}}
{{--                                            @php--}}
{{--                                                $ratingSetting = \App\Models\RatingSetting::first();--}}
{{--                                                $ethicsMax = $ratingSetting->ethics_score ?? 45;--}}

{{--                                                $inspections = $record->serviceinspection()->with('prision_type')->get();--}}
{{--                                                $ethicsMistakes = $inspections->filter(fn($i) => $i->prision_type && $i->prision_type->score);--}}
{{--                                                $totalRemoved = $ethicsMistakes->sum(fn($i) => $i->prision_type->score);--}}

{{--                                                $ethicsScore = max(0, $ethicsMax - $totalRemoved);--}}

{{--                                                $totalEthicsWithEtiquette = $ethicsScore + ($record->etiquette_score ?? 0);--}}
{{--                                            @endphp--}}
{{--                                            <x-filament::badge size="xl">--}}
{{--                                                {{ $ethicsMax }} / {{ $ethicsScore }}--}}
{{--                                            </x-filament::badge>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </x-slot>--}}

{{--                                @php--}}
{{--                                    $inspections = $record->serviceinspection()->with('prision_type')->get();--}}

{{--                                    $ethicsMistakes = $inspections->filter(fn($i) => $i->prision_type && $i->prision_type->score);--}}

{{--                                    $totalRemoved = $ethicsMistakes->sum(fn($i) => $i->prision_type->score);--}}
{{--                                    $totalCount = $ethicsMistakes->count();--}}

{{--                                    $ethicsMax = $ratingSetting->ethics_score ?? 0;--}}
{{--                                @endphp--}}

{{--                                @if ($ethicsMistakes->isNotEmpty())--}}

{{--                                    <div class="flex justify-end mt-4">--}}
{{--                                        --}}{{-- Modal tugmasi --}}
{{--                                        <div x-data="{ open: false }" class="relative w-full text-right">--}}
{{--                                            --}}{{-- Tugma --}}
{{--                                            <x-filament::button size="xs" icon="heroicon-o-eye" color="info"--}}
{{--                                                                @click="open = true">--}}
{{--                                                Кўриш--}}
{{--                                            </x-filament::button>--}}

{{--                                            <div--}}
{{--                                                x-show="open"--}}
{{--                                                x-transition--}}
{{--                                                x-cloak--}}
{{--                                                class="fixed inset-0 z-40 bg-gray-950/50 dark:bg-gray-950/75 flex items-center justify-center bg-black bg-opacity-50"--}}
{{--                                                style="backdrop-filter: blur(2px);"--}}
{{--                                            >--}}
{{--                                                --}}{{-- Modal ichki kontent --}}
{{--                                                <div--}}
{{--                                                    class="bg-white w-11/12 max-w-12xl rounded-xl shadow-lg p-6 relative">--}}
{{--                                                    <button @click="open = false"--}}
{{--                                                            class="absolute top-4 right-4 text-2xl text-gray-400 hover:text-black">--}}
{{--                                                        &times;--}}
{{--                                                    </button>--}}
{{--                                                    <table class="w-full border text-sm text-left rounded">--}}
{{--                                                        <thead class="bg-gray-100 font-semibold">--}}
{{--                                                        <tr>--}}
{{--                                                            <th class="px-4 py-2 text-center w-8">№</th>--}}
{{--                                                            <th class="px-4 py-2">Сана</th>--}}
{{--                                                            <th class="px-4 py-2">Текширувчи</th>--}}
{{--                                                            <th class="px-4 py-2">Асос</th>--}}
{{--                                                            <th class="px-4 py-2">Жазо</th>--}}
{{--                                                            <th class="px-4 py-2 text-center">Балл</th>--}}
{{--                                                        </tr>--}}
{{--                                                        </thead>--}}
{{--                                                        <tbody>--}}
{{--                                                        @foreach ($ethicsMistakes as $index => $mistake)--}}
{{--                                                            <tr class="border-t">--}}
{{--                                                                <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>--}}
{{--                                                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($mistake->created_at)->format('d.m.Y') }}</td>--}}
{{--                                                                <td class="px-4 py-2">{{ $mistake->inspectionConducted?->name }}</td>--}}
{{--                                                                <td class="px-4 py-2">{{ $mistake->inspectionAdult?->name }}</td>--}}
{{--                                                                <td class="px-4 py-2">{{ $mistake->prision_type?->name }}</td>--}}
{{--                                                                <td class="px-4 py-2 text-center text-red-600">{{ $mistake->score ?? '' }}</td>--}}
{{--                                                            </tr>--}}
{{--                                                        @endforeach--}}
{{--                                                        </tbody>--}}
{{--                                                    </table>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}

{{--                                    <table class="w-full text-sm rounded-lg mt-2">--}}
{{--                                        <thead class="text-gray-700 font-semibold">--}}
{{--                                        <tr>--}}
{{--                                            <th class="px-4 py-2 text-left">Жазо тури</th>--}}
{{--                                            <th class="px-4 py-2 text-center">Сони</th>--}}
{{--                                            <th class="px-4 py-2 text-center">Балл</th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        @foreach ($ethicsMistakes as $mistake)--}}
{{--                                            <tr class="border-t text-left">--}}
{{--                                                <td class="px-4 py-2">--}}
{{--                                                    <x-filament::badge color="danger" size="sm">--}}
{{--                                                        {{ $mistake->prision_type?->name ?? '—' }}--}}
{{--                                                    </x-filament::badge>--}}
{{--                                                </td>--}}
{{--                                                <td class="px-4 py-2 text-center">1</td>--}}
{{--                                                <td class="px-4 py-2 text-center">--}}
{{--                                                    <x-filament::badge color="danger" size="sm">--}}
{{--                                                        -{{ $mistake->prision_type?->score ?? 0 }}--}}
{{--                                                    </x-filament::badge>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                        </tbody>--}}
{{--                                        <tfoot>--}}
{{--                                        <tr class=" font-semibold border-t">--}}
{{--                                            <td class="px-4 py-2 text-right">Жами:</td>--}}
{{--                                            <td class="px-4 py-2 text-center">{{ $ethicsMistakes->count() }}</td>--}}
{{--                                            <td class="px-4 py-2 text-center text-red-600">--}}
{{--                                                -{{ $ethicsMistakes->sum(fn($i) => $i->prision_type?->score ?? 0) }}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        </tfoot>--}}
{{--                                    </table>--}}
{{--                                @endif--}}

{{--                            </x-filament::fieldset>--}}
{{--                        </div>--}}

{{--                        <x-filament::fieldset>--}}
{{--                            <x-slot name="label" text="sm">--}}
{{--                                <div class="flex justify-between items-center">--}}
{{--                                    <div class="flex flex-wrap gap-2">--}}
{{--                                        @php--}}
{{--                                            $bonuses = $record->bonuses ?? collect();--}}
{{--                                            $totalBonusScore = $bonuses->sum('score');--}}

{{--                                            // Bonuslarni grouping qilish--}}
{{--                                            $groupedBonuses = $bonuses->groupBy('name');--}}
{{--                                        @endphp--}}

{{--                                        <span class="mr-4">Чет тилларини билиши:</span>--}}
{{--                                        <x-filament::badge color="success" size="lg">--}}
{{--                                            {{ $totalBonusScore }}+--}}
{{--                                        </x-filament::badge>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </x-slot>--}}

{{--                            --}}{{-- Jadval ichida bonuslar --}}
{{--                            @if ($bonuses->count() > 0)--}}
{{--                                <table class="table-auto w-full text-sm text-left">--}}
{{--                                    <thead>--}}
{{--                                    <tr class="">--}}
{{--                                        <th class="px-4 py-2 border-b "></th>--}}
{{--                                        <th class="px-4 py-2  border-b text-center">Сони</th>--}}
{{--                                        <th class="px-4 py-2  border-b text-center">Балл</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    @foreach ($groupedBonuses as $name => $group)--}}
{{--                                        @php--}}
{{--                                            $count = $group->count();--}}
{{--                                            $score = $group->sum('score');--}}
{{--                                        @endphp--}}
{{--                                        <tr>--}}
{{--                                            <td class="px-4 py-2  border ">{{ $name }}</td>--}}
{{--                                            <td class="px-4 py-2  border-r border-t text-center">{{$count}}</td>--}}
{{--                                            <td class="px-4 py-2  border-r border-t text-center text-green-600 font-bold">--}}
{{--                                                <x-filament::badge color="success">--}}
{{--                                                    +{{ $score }}--}}
{{--                                                </x-filament::badge>--}}

{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}

{{--                                    <!-- Жами -->--}}
{{--                                    <tr class="font-semibold bg-gray-50">--}}
{{--                                        <td class="px-4 py-2 border border-gray-200">Жами</td>--}}
{{--                                        <td class="px-4 py-2 border border-gray-200 text-center">{{ $bonuses->count() }}</td>--}}
{{--                                        <td class="px-4 py-2 border border-gray-200 text-center text-green-600 font-bold"></td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            @endif--}}

{{--                        </x-filament::fieldset>--}}

{{--                        <div class="mt-4">--}}
{{--                            <x-filament::fieldset>--}}
{{--                                <x-slot name="label" text="sm">--}}
{{--                                    <div class="flex justify-between items-center">--}}
{{--                                        <div class="flex flex-wrap gap-2">--}}
{{--                                            @php--}}
{{--                                                $bonuses = $record->bonuses ?? collect();--}}
{{--                                                $totalBonusScore = $bonuses->sum('score');--}}

{{--                                                // Bonuslarni grouping qilish--}}
{{--                                                $groupedBonuses = $bonuses->groupBy('name');--}}
{{--                                            @endphp--}}

{{--                                            <span class="mr-4">Қўшимча балл:</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </x-slot>--}}
{{--                                @if ($record->adding_rating > 0)--}}

{{--                                        <table class="table-auto w-full text-sm text-left border border-gray-200">--}}
{{--                                            <thead class="bg-gray-100">--}}
{{--                                            </thead>--}}
{{--                                            <tbody>--}}
{{--                                            <tr>--}}
{{--                                                <td class="px-4 py-2 border">Иш ҳажми</td>--}}
{{--                                                <td class="px-4 py-2 border text-green-600 font-bold text-center">--}}
{{--                                                    +{{ $record->adding_rating }}--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                            </tbody>--}}
{{--                                        </table>--}}
{{--                                @endif--}}
{{--                            </x-filament::fieldset>--}}
{{--                        </div>--}}


{{--                    </div>--}}
{{--                </div>--}}
{{--            </x-filament::fieldset>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</x-filament::page>--}}



{{--@push('scripts')--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"--}}
{{--            integrity="sha512-od3A0Ypbf6fKDNpXW+MPbR2RDbMe0C7jz7G8Mja+0nS+nzwr8K6ZUwYgf7xeqxjH3cFZUDjYpNCtvHw9ipLZUg=="--}}
{{--            crossorigin="anonymous" referrerpolicy="no-referrer"></script>--}}

{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', function () {--}}
{{--            document.getElementById("download-snapshot").addEventListener("click", function () {--}}
{{--                const element = document.getElementById("rating-section");--}}

{{--                const opt = {--}}
{{--                    margin: 0.3,--}}
{{--                    filename: 'sudya-reytingi.pdf',--}}
{{--                    image: {type: 'jpeg', quality: 0.98},--}}
{{--                    html2canvas: {scale: 2},--}}
{{--                    jsPDF: {unit: 'in', format: 'a4', orientation: 'landscape'} // 👉 horizontal--}}
{{--                };--}}

{{--                html2pdf().set(opt).from(element).save();--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}

