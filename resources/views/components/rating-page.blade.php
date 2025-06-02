<x-filament::page>
    <div id="rating-section" class="mb-4 rounded-md " xmlns:x-filament="http://www.w3.org/1999/html">
        <div class="space-y-2">
{{--            <div class="flex justify-end mb-4">--}}
{{--                <a href="{{ route('judges.downloadPdf', $record->id) }}"--}}
{{--                   target="_blank"--}}
{{--                   class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md bg-primary-600 text-white hover:bg-primary-500 transition"--}}
{{--                >--}}
{{--                    <x-heroicon-o-arrow-down-on-square-stack class="w-4 h-4 mr-1" />--}}
{{--                    Юклаб олиш--}}
{{--                </a>--}}
{{--            </div>--}}
            <x-filament::fieldset>

                <x-slot name="label">
                    Рейтинг маълумотлари
                </x-slot>
                <div class="flex items-center justify-between">
                    <h1 class="text-sm">
                        Судьянинг фаолияти самарадорлигини
                        электрон рейтинг баҳолаш натижаси <br>
                        <span class="font-bold">Баҳолаш даври: 01.05.2024 - 30.04.2025</span>
                    </h1>
                    <div class="pr-8 text-3xl card rounded-lg text-gray-400">
                        {{$record->rating}}
                    </div>

                </div>

            </x-filament::fieldset>
            <x-filament::fieldset>
                <div class="flex mb-4 items-start space-x-6">
                    {{-- Image --}}
                    <div class="shrink-0" style="width: 150px;height: 150px">
                        <img
                            src="{{ $record->image ? asset('storage/' . $record->image) : asset('image/default.jpg') }}"
                            class="w-full h-full object-cover border rounded-full">
                    </div>

                    {{-- Info --}}
                    <div class="flex flex-col text-sm space-y-2 p-4 max-w-xl border-r ">
                        <div class="font-semibold text-2xl">
                            {{ trim("{$record->middle_name} {$record->first_name} {$record->last_name}") ?: 'Nomaʼlum' }}
                        </div>

                        <div>
                            <span class="text-gray-600">Lavozimi: </span>
                            {{ $record->current_or_future_position_name ?? 'Nomaʼlum' }}
                        </div>

                        <div>
                            <span class="text-gray-600">Туғилган санаси: </span>
                            {{ $record->birth_date ? \Carbon\Carbon::parse($record->birth_date)->format('d.m.Y') : 'Nomaʼlum' }}
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="text-sm text-gray-400 p-4 ">
                        <p class="border border-l-4 ">Апелляция инстанциясида 1 та фуқаролик иши буйича маъруза
                            килган.</p>
                        <p class="border border-l-4 ">Кассация инстанциясида 179 та фуқаролик иши буйича маъруза
                            килган.</p>
                    </div>
                </div>
            </x-filament::fieldset>
            <x-filament::fieldset>
                <div class="flex grid-cols-4 justify-between">
                    <div class="w-full mr-2">
                        <x-filament::fieldset>
                            <x-slot name="label" text="sm">
                                <div class="flex justify-between items-center">
                                    <span>Суд қарорларининг сифати:</span>
                                    <span>
                        <x-filament::badge size="xl">
                          50/ {{$record->quality_score}}
                        </x-filament::badge>
                    </span>
                                </div>

                            </x-slot>

                            @php
                                // Kategoriyalarni aniqlash
                                $cancelled = $record->appeals->filter(fn($a) =>
                                    in_array($a->reason?->instances?->name, ['Тўлиқ бекор қилинган', 'Қисман бекор қилинган'])
                                );

                                $modified = $record->appeals->filter(fn($a) =>
                                    in_array($a->reason?->instances?->name, ['Тўлиқ ўзгартирилган', 'Қисман ўзгартирилган'])
                                );

                                // Subturlarni guruhlash
                                $cancelledGrouped = $cancelled->groupBy(fn($a) => $a->reason->typeOfDecision->name);
                                $modifiedGrouped = $modified->groupBy(fn($a) => $a->reason->typeOfDecision->name);

                                $totalCount = $cancelled->count() + $modified->count();
                                $totalScore = $cancelled->sum(fn($a) => $a->reason->score ?? 0) +
                                              $modified->sum(fn($a) => $a->reason->score ?? 0);
                            @endphp
                            @if ($cancelledGrouped->isNotEmpty())
                                <table class="w-full border text-sm text-left">
                                    <tbody>
                                    {{-- Бекор қилинган --}}
                                    <tr class="font-bold text-blue-900 border-t">
                                        <td colspan="3" class="p-4">Бекор қилинган суд қарорлари</td>
                                    </tr>
                                    @foreach($cancelledGrouped as $name => $items)
                                        <tr class="border">
                                            <td class="pl-3 p-4 border-r">{{ $name }}</td>
                                            <td class="text-center border-r">{{ $items->count() }}</td>
                                            <td class="text-center text-danger-600 font-semibold">{{ $items->sum(fn($a) => $a->reason->score ?? 0) }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- Ўзгартирилган --}}
                                    <tr class="font-bold text-blue-900 border-t border-b">
                                        <td colspan="3" class="p-4">Ўзгартирилган суд қарорлари</td>
                                    </tr>
                                    @foreach($modifiedGrouped as $name => $items)
                                        <tr>
                                            <td class="pl-3 p-4 border-r">{{ $name }}</td>
                                            <td class="text-center border-r ">{{ $items->count() }}</td>
                                            <td class="text-center text-danger-600 font-semibold ">{{ $items->sum(fn($a) => $a->reason->score ?? 0) }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- Жами --}}
                                    <tr class="font-semibold text-blue-900">
                                        <td class="text-right mr-4 border p-2 ">Жами</td>
                                        <td class="text-center border ">{{ $totalCount }}</td>
                                        <td class="text-center border text-danger-600">{{ $totalScore }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif

                        </x-filament::fieldset>
                    </div>
                    <div class="w-full mr-2">
                        <div class="mb-4">
                            <x-filament::fieldset>
                                <x-slot name="label" text="sm">
                                    <div class="flex justify-between items-center">
                                        <div>Судьянинг одоби ва масъулияти:</div>
                                        <div class="flex justify-end">
                                            @php
                                                $ratingSetting = \App\Models\RatingSetting::first();
                                                $ethicsMax = $ratingSetting->ethics_score ?? 45;

                                                $inspections = $record->serviceinspection()->with('prision_type')->get();
                                                $ethicsMistakes = $inspections->filter(fn($i) => $i->prision_type && $i->prision_type->score);
                                                $totalRemoved = $ethicsMistakes->sum(fn($i) => $i->prision_type->score);

                                                $ethicsScore = max(0, $ethicsMax - $totalRemoved);

                                                $totalEthicsWithEtiquette = $ethicsScore + ($record->etiquette_score ?? 0);
                                            @endphp
                                            <x-filament::badge size="xl">
                                                {{ $ethicsMax }} / {{ $ethicsScore }}
                                            </x-filament::badge>
                                        </div>
                                    </div>
                                </x-slot>

                                @php
                                    $inspections = $record->serviceinspection()->with('prision_type')->get();

                                    $ethicsMistakes = $inspections->filter(fn($i) => $i->prision_type && $i->prision_type->score);

                                    $totalRemoved = $ethicsMistakes->sum(fn($i) => $i->prision_type->score);
                                    $totalCount = $ethicsMistakes->count();

                                    $ethicsMax = $ratingSetting->ethics_score ?? 0;
                                @endphp

                                @if ($ethicsMistakes->isNotEmpty())
                                    <x-filament::modal width="7xl">
                                        <table
                                            class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                                            <thead class="bg-gray-100 font-semibold">
                                            <tr>
                                                <th class="px-4 py-2 text-center w-8">№</th>
                                                <th class="px-4 py-2">Хулоса тузилган сана</th>
                                                <th class="px-4 py-2">Хизмат текширувини ўтказган судья</th>
                                                <th class="px-4 py-2">Асос</th>
                                                <th class="px-4 py-2">Хато ва камчилик</th>
                                                <th class="px-4 py-2">Малака ҳайъатига юборилган сана</th>
                                                <th class="px-4 py-2">Жазо</th>
                                                <th class="px-4 py-2 text-center">Балл</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($ethicsMistakes as $index => $mistake)
                                                <tr class="border-t">

                                                    <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-2">
                                                        {{ \Carbon\Carbon::parse($mistake->created_at)->format('d.m.Y') }}
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        {{$mistake->inspectionConducted?->name??''}}
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        {{$mistake->inspectionAdult?->name??''}}
                                                    </td>
                                                    <td class="px-4 py-2">

                                                    </td>

                                                    <td class="px-4 py-2">
                                                        {{ $mistake->prision_type?->name??''}}
                                                    </td>
                                                    <td class="px-4 py-2 text-center text-red-600">

                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                        <x-slot name="trigger">
                                            <x-filament::button size="xs" icon="heroicon-o-eye" color="warning"
                                                                text="sm"
                                                                class="mt-4">
                                                Қўриш
                                            </x-filament::button>
                                        </x-slot>
                                    </x-filament::modal>
                                    <table class="w-full text-sm rounded-lg mt-2">
                                        <thead class="text-gray-700 font-semibold">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Жазо тури</th>
                                            <th class="px-4 py-2 text-center">Сони</th>
                                            <th class="px-4 py-2 text-center">Балл</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($ethicsMistakes as $mistake)
                                            <tr class="border-t text-left">
                                                <td class="px-4 py-2">
                                                    <x-filament::badge color="danger" size="sm">
                                                        {{ $mistake->prision_type?->name ?? '—' }}
                                                    </x-filament::badge>
                                                </td>
                                                <td class="px-4 py-2 text-center">1</td>
                                                <td class="px-4 py-2 text-center">
                                                    <x-filament::badge color="danger" size="sm">
                                                        -{{ $mistake->prision_type?->score ?? 0 }}
                                                    </x-filament::badge>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr class=" font-semibold border-t">
                                            <td class="px-4 py-2 text-right">Жами:</td>
                                            <td class="px-4 py-2 text-center">{{ $ethicsMistakes->count() }}</td>
                                            <td class="px-4 py-2 text-center text-red-600">
                                                -{{ $ethicsMistakes->sum(fn($i) => $i->prision_type?->score ?? 0) }}
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                @endif

                            </x-filament::fieldset>
                        </div>
                        <x-filament::fieldset>
                            <x-slot name="label" text="sm">
                                <div class="flex justify-between items-center">
                                    <div class="flex flex-wrap gap-2">
                                        <x-slot name="label" text="sm">
                                            @php
                                                $totalBonusScore = $record->bonuses->sum('score');
                                                $bonuses = $record->bonuses ?? collect();
                                            @endphp
                                            <div class="flex justify-between items-center">
                                                <span class="mr-4">Қўшимча баллар:</span>
                                                <span class="ml-4">

        </span>
                                            </div>
                                        </x-slot>
                                    </div>
                                    <span class="mr-4">Қўшимча баллар:</span>
                                    <span class="ml-4">
                        <x-filament::badge color="success" size="lg">
                             {{ $totalBonusScore }}+
                         </x-filament::badge>

                    </span>

                                </div>
                            </x-slot>


                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($bonuses as $bonus)
                                    <div class="justify-between flex mb-2">
                                        <x-filament::badge>
                                            {{ $bonus->name }}
                                        </x-filament::badge>
                                        <x-filament::badge>
                                            + {{ $bonus->score }}
                                        </x-filament::badge>
                                    </div>
                                @endforeach
                            </div>
                        </x-filament::fieldset>
                    </div>
                </div>
            </x-filament::fieldset>
        </div>
    </div>
</x-filament::page>



@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
            integrity="sha512-od3A0Ypbf6fKDNpXW+MPbR2RDbMe0C7jz7G8Mja+0nS+nzwr8K6ZUwYgf7xeqxjH3cFZUDjYpNCtvHw9ipLZUg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById("download-snapshot").addEventListener("click", function () {
                const element = document.getElementById("rating-section");

                const opt = {
                    margin: 0.3,
                    filename: 'sudya-reytingi.pdf',
                    image: {type: 'jpeg', quality: 0.98},
                    html2canvas: {scale: 2},
                    jsPDF: {unit: 'in', format: 'a4', orientation: 'landscape'} // 👉 horizontal
                };

                html2pdf().set(opt).from(element).save();
            });
        });
    </script>
@endpush

