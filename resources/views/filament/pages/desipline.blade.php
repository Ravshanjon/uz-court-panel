@php
    use App\Models\Regions;
    use Illuminate\Support\Str;
    use Illuminate\Support\Carbon;

    $user = auth()->user();
    $isMalaka = $user && $user->getRoleNames()->contains(fn ($r) => Str::lower($r) === 'malaka');
    $userRegionId   = $user?->regions_id;
    $userRegionName = $userRegionId ? optional(Regions::find($userRegionId))->name : null;

    // 2-tab (statistika) uchun qatorlarni region nomi bo‘yicha filterlaymiz
    $rowsCollection = collect($rows ?? []);
    $rowsVisible = ($isMalaka && $userRegionName)
        ? $rowsCollection->where('region', $userRegionName)->values()
        : $rowsCollection->values();

    // 2-tab: JAMI (faqat ko‘rinayotgan qatorlardan)
    $totals = [
        'total' => 0,
        'approved_no' => 0,
        'approved_yes' => 0,
        'discipline_started_no' => 0,
        'discipline_started_yes' => 0,
        'punished' => 0,
        'warning' => 0,
        'rebuke' => 0,
        'fine' => 0,
        'demotion' => 0,
        'dismissal' => 0,
        'closed' => 0,
        'reconsidered' => 0,
        'canceled' => 0,
    ];
    foreach ($rowsVisible as $row) {
        foreach ($totals as $key => $_) {
            $totals[$key] += (int)($row[$key] ?? 0);
        }
    }

    // 3-tab (“Ўрганишда”) — judge region bo‘yicha filter
    $underStudyCollection = collect($this->underStudy ?? []);
    $underStudyVisible = ($isMalaka && $userRegionId)
        ? $underStudyCollection->filter(function ($r) use ($userRegionId) {
              $rid    = (int) optional($r->judge)->region_id;               // judge jadvalidagi region_id
              $ridRel = (int) optional(optional($r->judge)->region)->id;    // relation orqali region id
              return $rid === (int)$userRegionId || $ridRel === (int)$userRegionId;
          })->values()
        : $underStudyCollection->values();
@endphp

<x-filament-panels::page>
    <x-card class="relative">


        <div x-data="{ tab: 'tab1' }">
            <x-filament::tabs label="Content tabs">
                <x-filament::tabs.item @click="tab = 'tab1'" :alpine-active="'tab === \'tab1\''">
                    Интизомий ишлар рўйхати
                </x-filament::tabs.item>
                <x-filament::tabs.item @click="tab = 'tab2'" :alpine-active="'tab === \'tab2\''">
                    Интизомий ишлар статистикаси
                </x-filament::tabs.item>
                <x-filament::tabs.item @click="tab = 'tab3'" :alpine-active="'tab === \'tab3\''">
                    Ўрганишда
                </x-filament::tabs.item>
            </x-filament::tabs>

            <div class="mt-6">
                <!-- TAB 1 -->
                <div x-show="tab === 'tab1'">
                    <div class="m-2">
                        {{ $this->table }}
                    </div>
                </div>

                <!-- TAB 2 -->
                <div x-show="tab === 'tab2'">
                    <div class="flex justify-between mb-4">
                        <div>
                            <x-filament::input.wrapper>
                                <x-filament::input.select wire:model.live="year" class="min-w-40">
                                    <option value="">Барча йиллар</option>
                                    @for ($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>

                        <div>
                            <a href="{{ route('statistics.download') }}">
                                <x-filament::badge color="success" icon="heroicon-m-arrow-down-tray">Юклаб олиш</x-filament::badge>
                            </a>
                        </div>

                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                            <!-- (sarlavha bo'sh qolgan — o‘zgartirmadik) -->
                            </thead>
                        </table>
                    </div>

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                           style="margin-left: auto; margin-right: auto;" width="100%">
                        <tbody>
                        <tr style="height: 74px;">
                            <td style="width: 24.4436%; height: 157px;" rowspan="3">
                                <p>&nbsp;</p>
                            </td>
                            <td style="width: 10%; height: 157px;" rowspan="3">
                                <p><strong>Ўтказилган хизмат текшируви</strong></p>
                            </td>
                            <td style="width: 12%; height: 74px;" colspan="2">
                                <p><strong>Тасдиғини <br/> топганми?</strong></p>
                            </td>
                            <td style="width: 16%; height: 74px;" colspan="2">
                                <p><strong>Тасдиғини топгандан кейин интизомий қўзғатилганми?</strong></p>
                            </td>
                            <td style="width: 7%; height: 157px;" rowspan="3">
                                <p><strong>Интизомий жазо қўлланил-<br/> ган</strong></p>
                            </td>
                            <td style="width: 30%; height: 74px;" colspan="5">
                                <p><strong>Интизомий жазо чоралари</strong></p>
                            </td>
                            <td style="width: 7%; height: 157px;" rowspan="3">
                                <p><strong>Жазо қўлланил-<br/> масдан тугатилган</strong></p>
                            </td>
                            <td style="width: 9%; height: 74px;" colspan="2">
                                <p><strong>Интизомий иш қайта кўриб чиқилган</strong></p>
                            </td>
                        </tr>
                        <tr style="height: 35px;">
                            <td style="width: 6%; height: 35px;"><p><em>Йўқ</em></p></td>
                            <td style="width: 6%; height: 35px;"><p><em>Ҳа</em></p></td>
                            <td style="width: 6%; height: 35px;"><p><em>Йўқ</em></p></td>
                            <td style="width: 10%; height: 35px;"><p><em>Ҳа</em></p></td>
                            <td style="width: 6%; height: 83px;" rowspan="2"><p>Огоҳлан-<br/>тириш</p></td>
                            <td style="width: 5%; height: 83px;" rowspan="2"><p>Ҳайфсан</p></td>
                            <td style="width: 5%; height: 83px;" rowspan="2"><p>Жарима</p></td>
                            <td style="width: 7%; height: 83px;" rowspan="2"><p>Малака даражасини бир поғонага
                                    пасайтириш</p></td>
                            <td style="width: 7%; height: 83px;" rowspan="2"><p>Ваколат-ларини муддатидан илгари
                                    тугатиш</p></td>
                            <td style="width: 5%; height: 83px;" rowspan="2"><p>Тугатил-<br/> ган</p></td>
                            <td style="width: 4%; height: 83px;" rowspan="2"><p>Бекор қилин-<br/> ган</p></td>
                        </tr>
                        <tr style="height: 48px;">
                            <td style="width: 6%; height: 48px;"><p><strong>Тасдиғини топмаган</strong></p></td>
                            <td style="width: 6%; height: 48px;"><p><strong>Тасдиғини топган</strong></p></td>
                            <td style="width: 6%; height: 48px;"><p><strong>Қўзғатил-<br/> маган</strong></p></td>
                            <td style="width: 10%; height: 48px;"><p><strong>Қўзғатил-<br/> ган</strong></p></td>
                        </tr>

                        @foreach($rowsVisible as $row)
                            <tr class="odd:bg-white even:bg-gray-50 hover:bg-yellow-100 text-center border-t">
                                <td class="font-semibold p-2">{{ $row['region'] }}</td>
                                <td>{{ $row['total'] }}</td>
                                <td>{{ $row['approved_no'] }}</td>
                                <td>{{ $row['approved_yes'] }}</td>
                                <td>{{ $row['discipline_started_no'] }}</td>
                                <td>{{ $row['discipline_started_yes'] }}</td>
                                <td>{{ $row['punished'] }}</td>
                                <td>{{ $row['warning'] }}</td>
                                <td>{{ $row['rebuke'] }}</td>
                                <td>{{ $row['fine'] }}</td>
                                <td>{{ $row['demotion'] }}</td>
                                <td>{{ $row['dismissal'] }}</td>
                                <td>{{ $row['closed'] }}</td>
                                <td>{{ $row['reconsidered'] }}</td>
                                <td>{{ $row['canceled'] }}</td>
                            </tr>
                        @endforeach

                        <tr class="bg-orange-100 font-bold text-center border-t">
                            <td class="border p-1">ЖАМИ</td>
                            <td class="border p-1">{{ $totals['total'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['approved_no'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['approved_yes'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['discipline_started_no'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['discipline_started_yes'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['punished'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['warning'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['rebuke'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['fine'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['demotion'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['dismissal'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['closed'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['reconsidered'] ?: '' }}</td>
                            <td class="border p-1">{{ $totals['canceled'] ?: '' }}</td>
                        </tr>
                        </tbody>
                    </table>

                    <p style="text-align: center;">&nbsp;</p>
                </div>

                <!-- TAB 3 -->
                <div x-show="tab === 'tab3'">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('statistics.download') }}">
                            <x-filament::badge color="success">Юклаб олиш</x-filament::badge>
                        </a>
                    </div>

                    @if ($underStudyVisible->isEmpty())
                        <x-filament::badge color="gray">Ўрганишдаги маълумот йўқ</x-filament::badge>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                <tr class="border-b">
                                    <th class="py-2 text-center">Судья</th>
                                    <th class="py-2 text-center">Ҳудуд</th>
                                    <th class="py-2 text-center">Қанча кун бўлди</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($underStudyVisible as $row)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2">
                                            {{ $row->judge?->full_name ?? '—' }}
                                        </td>
                                        <td class="py-2">
                                            {{ $row->judge?->region?->name
                                                ?? $row->judge?->region
                                                ?? '—' }}
                                        </td>
                                        <td class="py-2">
                                            @php
                                                $started = $row->study_started_at
                                                    ? ($row->study_started_at instanceof Carbon
                                                        ? $row->study_started_at
                                                        : Carbon::parse($row->study_started_at))
                                                    : null;
                                            @endphp
                                            {{ $started ? $started->format('d.m.Y') : '—' }}
                                        </td>
                                        <td class="py-2">
                                            @php
                                                if ($started) {
                                                    $days  = $started->diffInDays(now());
                                                    $hours = $started->copy()->addDays($days)->diffInHours(now());
                                                    echo "{$days} кун {$hours} соат";
                                                } else {
                                                    echo '—';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-card>

    <style>
        table td {
            border-right: 1px solid #ededed;
            font-size: 13px;
            text-align: center;
        }

        table tr {
            border: 1px solid #ededed;
        }

        table thead th {
            text-align: center;
            border-right: 1px solid #ededed;
        }
    </style>
</x-filament-panels::page>
