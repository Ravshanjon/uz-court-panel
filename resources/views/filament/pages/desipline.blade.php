<x-filament-panels::page>

    <x-card class="relative">
        <div class="w-20 mb-5 ">
            <x-filament::input.wrapper>
                <x-filament::input.select wire:model="status">
                    <option value="draft">2022</option>
                    <option value="reviewing">2023</option>
                    <option value="published">2024</option>
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </div>
        <div x-data="{ tab: 'tab1' }">
            <x-filament::tabs label="Content tabs">
                <x-filament::tabs.item @click="tab = 'tab1'" :alpine-active="'tab === \'tab1\''">
                    Судьялар
                </x-filament::tabs.item>
                <x-filament::tabs.item @click="tab = 'tab2'" :alpine-active="'tab === \'tab2\''">
                    Интизомий статистика
                </x-filament::tabs.item>

            </x-filament::tabs>
            <div class="mt-6">
                <div x-show ="tab === 'tab1'">
                    {{ $this->table }}
                </div>
                <div x-show="tab === 'tab2'">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('statistics.download') }}">
                            <x-filament::badge color="success">
                                Юклаб олиш
                            </x-filament::badge>
                        </a>
                    </div>


                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">

                            </thead>
                        </table>
                    </div>

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" style="margin-left: auto; margin-right: auto;" width="100%">
                            <tbody>
                            <tr style="height: 74px;">
                                <td style="width: 24.4436%; height: 157px;" rowspan="3">
                                    <p>&nbsp;</p>
                                </td>
                                <td style="width: 10%; height: 157px;" rowspan="3">
                                    <p><strong>Ўтказилган хизмат текшируви</strong></p>
                                </td>
                                <td style="width: 12%; height: 74px;" colspan="2">
                                    <p><strong>Тасдиғини <br /> топганми?</strong></p>
                                </td>
                                <td style="width: 16%; height: 74px;" colspan="2">
                                    <p><strong>Тасдиғини топгандан кейин интизомий қўзғатилганми?</strong></p>
                                </td>
                                <td style="width: 7%; height: 157px;" rowspan="3">
                                    <p><strong>Интизомий жазо қўлланил-<br /> ган</strong></p>
                                </td>
                                <td style="width: 30%; height: 74px;" colspan="5">
                                    <p><strong>Интизомий жазо чоралари</strong></p>
                                </td>
                                <td style="width: 7%; height: 157px;" rowspan="3">
                                    <p><strong>Жазо қўлланил-<br /> масдан тугатилган</strong></p>
                                </td>
                                <td style="width: 9%; height: 74px;" colspan="2">
                                    <p><strong>Интизомий иш қайта кўриб чиқилган</strong></p>
                                </td>
                            </tr>
                            <tr style="height: 35px;">
                                <td style="width: 6%; height: 35px;">
                                    <p><em>Йўқ</em></p>
                                </td>
                                <td style="width: 6%; height: 35px;">
                                    <p><em>Ҳа</em></p>
                                </td>
                                <td style="width: 6%; height: 35px;">
                                    <p><em>Йўқ</em></p>
                                </td>
                                <td style="width: 10%; height: 35px;">
                                    <p><em>Ҳа</em></p>
                                </td>
                                <td style="width: 6%; height: 83px;" rowspan="2">
                                    <p>Огоҳлан-<br /> тириш</p>
                                </td>
                                <td style="width: 5%; height: 83px;" rowspan="2">
                                    <p>Ҳайфсан</p>
                                </td>
                                <td style="width: 5%; height: 83px;" rowspan="2">
                                    <p>Жарима</p>
                                </td>
                                <td style="width: 7%; height: 83px;" rowspan="2">
                                    <p>Малака даражасини бир поғонага пасайтириш</p>
                                </td>
                                <td style="width: 7%; height: 83px;" rowspan="2">
                                    <p>Ваколат-ларини муддатидан илгари тугатиш</p>
                                </td>
                                <td style="width: 5%; height: 83px;" rowspan="2">
                                    <p>Тугатил-<br /> ган</p>
                                </td>
                                <td style="width: 4%; height: 83px;" rowspan="2">
                                    <p>Бекор қилин-<br /> ган</p>
                                </td>
                            </tr>
                            <tr style="height: 48px;">
                                <td style="width: 6%; height: 48px;">
                                    <p><strong>Тасдиғини топмаган</strong></p>
                                </td>
                                <td style="width: 6%; height: 48px;">
                                    <p><strong>Тасдиғини топган</strong></p>
                                </td>
                                <td style="width: 6%; height: 48px;">
                                    <p><strong>Қўзғатил-<br /> маган</strong></p>
                                </td>
                                <td style="width: 10%; height: 48px;">
                                    <p><strong>Қўзғатил-<br /> ган</strong></p>
                                </td>
                            </tr>
                            <tbody>

                        @foreach($rows as $row)
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
                        @php
                            $totals = [
                                'total' => null,
                                'approved_no' => null,
                                'approved_yes' => null,
                                'discipline_started_no' => null,
                                'discipline_started_yes' => null,
                                'punished' => null,
                                'warning' => null,
                                'rebuke' => null,
                                'fine' => null,
                                'demotion' => null,
                                'dismissal' => null,
                                'closed' => null,
                                'reconsidered' => null,
                                'canceled' => null,
                            ];

                            foreach ($rows as $row) {
                                foreach ($totals as $key => $val) {
                                    $totals[$key] += intval($row[$key] ?? '0'); // ✅ bu yerda tuzatildi
                                }
                            }
                        @endphp
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
        table thead th{
            text-align: center;
            border-right: 1px solid #ededed;
        }
    </style>

</x-filament-panels::page>

