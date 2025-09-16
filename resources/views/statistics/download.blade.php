
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>
<body>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">

        </thead>
    </table>
</div>
@php use Carbon\Carbon;
  $date = Carbon::now()->format('d.m.Y');
@endphp
<div class="italic">
    {{ $date }} ҳолатига
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
            <td>{{ $row['total'] != 0 ? $row['total'] : '' }}</td>
            <td>{{ $row['approved_no'] != 0 ? $row['approved_no'] : '' }}</td>
            <td>{{ $row['approved_yes'] != 0 ? $row['approved_yes'] : '' }}</td>
            <td>{{ $row['discipline_started_no'] != 0 ? $row['discipline_started_no'] : '' }}</td>
            <td>{{ $row['discipline_started_yes'] != 0 ? $row['discipline_started_yes'] : '' }}</td>
            <td>{{ $row['punished'] != 0 ? $row['punished'] : '' }}</td>
            <td>{{ $row['warning'] != 0 ? $row['warning'] : '' }}</td>
            <td>{{ $row['rebuke'] != 0 ? $row['rebuke'] : '' }}</td>
            <td>{{ $row['fine'] != 0 ? $row['fine'] : '' }}</td>
            <td>{{ $row['demotion'] != 0 ? $row['demotion'] : '' }}</td>
            <td>{{ $row['dismissal'] != 0 ? $row['dismissal'] : '' }}</td>
            <td>{{ $row['closed'] != 0 ? $row['closed'] : '' }}</td>
            <td>{{ $row['reconsidered'] != 0 ? $row['reconsidered'] : '' }}</td>
            <td>{{ $row['canceled'] != 0 ? $row['canceled'] : '' }}</td>
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
    <tr class="bg-black/50 text-white font-bold text-center">
        <td class="p-1">ЖАМИ</td>
        <td class="p-1">{{ $totals['total'] ?: '' }}</td>
        <td class="p-1">{{ $totals['approved_no'] ?: '' }}</td>
        <td class="p-1">{{ $totals['approved_yes'] ?: '' }}</td>
        <td class="p-1">{{ $totals['discipline_started_no'] ?: '' }}</td>
        <td class="p-1">{{ $totals['discipline_started_yes'] ?: '' }}</td>
        <td class="p-1">{{ $totals['punished'] ?: '' }}</td>
        <td class="p-1">{{ $totals['warning'] ?: '' }}</td>
        <td class="p-1">{{ $totals['rebuke'] ?: '' }}</td>
        <td class="p-1">{{ $totals['fine'] ?: '' }}</td>
        <td class="p-1">{{ $totals['demotion'] ?: '' }}</td>
        <td class="p-1">{{ $totals['dismissal'] ?: '' }}</td>
        <td class="p-1">{{ $totals['closed'] ?: '' }}</td>
        <td class="p-1">{{ $totals['reconsidered'] ?: '' }}</td>
        <td class="p-1">{{ $totals['canceled'] ?: '' }}</td>
    </tr>

    </tbody>
</table>
<p style="text-align: center;">&nbsp;</p>
</body>
</html>
<style>
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ededed; padding: 4px; text-align: center; font-family: DejaVu Sans, sans-serif; }
</style>



