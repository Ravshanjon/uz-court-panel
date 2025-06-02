{{--<div class="overflow-x-auto">--}}
{{--    <table class="min-w-[1500px] border-collapse border border-gray-300 text-sm">--}}
{{--        <thead class="bg-gray-100 text-left">--}}
{{--        <tr class="bg-blue-100 font-semibold text-center">--}}
{{--            <th colspan="9" class="border px-2 py-1">Х И З М А Т   Т Е К Ш И Р У В И</th>--}}
{{--            <th colspan="6" class="border px-2 py-1">И Н Т И З О М И Й    И Ш</th>--}}
{{--            <th colspan="6" class="border px-2 py-1">И Н Т И З О М И Й    Ж А В О Б Г А Р Л И К</th>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <th class="border px-2 py-1">№</th>--}}
{{--            <th class="border px-2 py-1">Хулоса тузилган сана</th>--}}
{{--            <th class="border px-2 py-1">Кенгаш ташаббуси биланми?</th>--}}
{{--            <th class="border px-2 py-1">Ўтказган идора</th>--}}
{{--            <th class="border px-2 py-1">Код</th>--}}
{{--            <th class="border px-2 py-1">Ўтказган судья Ф.И.Ш.</th>--}}
{{--            <th class="border px-2 py-1">Ҳолатлар тасдиқланди</th>--}}
{{--            <th class="border px-2 py-1">Аниқланган хато ва камчиликлар</th>--}}
{{--            <th class="border px-2 py-1">Малака ҳайъатига юборилган сана</th>--}}

{{--            <th class="border px-2 py-1">Қўзғатилганми?</th>--}}
{{--            <th class="border px-2 py-1">Қўзғатилган сана</th>--}}
{{--            <th class="border px-2 py-1">Код</th>--}}
{{--            <th class="border px-2 py-1">Маъруза қилган судья Ф.И.Ш.</th>--}}
{{--            <th class="border px-2 py-1">Муҳокама қилинган сана</th>--}}
{{--            <th class="border px-2 py-1">Қўлланган жазо тури</th>--}}

{{--            <th class="border px-2 py-1">Қайта кўрилиб тугатилганми?</th>--}}
{{--            <th class="border px-2 py-1">Шикоят қилинганми?</th>--}}
{{--            <th class="border px-2 py-1">Бекор қилинганми?</th>--}}
{{--            <th class="border px-2 py-1">Ўзгартирилганми?</th>--}}
{{--            <th class="border px-2 py-1">Ўзгартирилган жазо тури</th>--}}
{{--            <th class="border px-2 py-1">Олиб ташланган сана</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--        @if ($basis->serviceinspection && $basis->serviceinspection->count())--}}
{{--            @foreach ($basis->serviceinspection as $index => $inspection)--}}
{{--                <tr>--}}
{{--                    <td class="border px-2 py-1">{{ $index + 1 }}</td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ \Carbon\Carbon::parse($inspection->inspection_qualification_dates)->format('d.m.Y') }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->inspection_conducted_id == 1 ? 'Ҳа' : 'Йўқ' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ optional($inspection->office)->name ?? '—' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->inspection_offices_id }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ optional($inspection->conductedJudge)->full_name ?? '—' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->inspection_cases_id == 1 ? 'Ҳа' : 'Йўқ' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->inspection_regulations_id ?? '—' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ \Carbon\Carbon::parse($inspection->created_at)->format('d.m.Y') }}--}}
{{--                    </td>--}}

{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->disciplinary_started ? 'Ҳа' : 'Йўқ' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ optional($inspection->disciplinary_date)->format('d.m.Y') ?? '—' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->disciplinary_code ?? '—' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ optional($inspection->reportingJudge)->full_name ?? '—' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ optional($inspection->discussion_date)->format('d.m.Y') ?? '—' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->punishment_type ?? '—' }}--}}
{{--                    </td>--}}

{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->reconsidered_closed ? 'Ҳа' : 'Йўқ' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->appealed ? 'Ҳа' : 'Йўқ' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->decision_cancelled ? 'Ҳа' : 'Йўқ' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->decision_changed ? 'Ҳа' : 'Йўқ' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ $inspection->updated_punishment_type ?? '—' }}--}}
{{--                    </td>--}}
{{--                    <td class="border px-2 py-1">--}}
{{--                        {{ optional($inspection->early_removed_date)->format('d.m.Y') ?? '—' }}--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--        @else--}}
{{--            <tr>--}}
{{--                <td colspan="21" class="border px-4 py-2 text-center text-gray-500">--}}
{{--                    Текширув маълумотлари топилмади.--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @endif--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--</div>--}}
