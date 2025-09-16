<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <title>Document</title>
</head>
<style>
    body {
        font-size: 12px; /* yoki 10px, 11px — bu "sm" o‘lchamga mos */
    }

    table {
        font-size: 12px;
    }

    th, td {
        padding: 4px 8px;
    }
</style>
<body>
<div class="text-right text-xs mb-4">
    Юклаб олган: {{ auth()->user()?->name ?? 'Номаълум' }} <br>
    Вақт: {{ now()->format('d.m.Y H:i') }}
</div>

<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right dark:text-gray-400">
        <thead class="text-xs bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-6 py-3 border">№</th>
            <th scope="col" class="px-6 py-3 border">Ҳудуд</th>
            <th scope="col" class="px-6 py-3 border">Ф.И.Ш.</th>
            <th scope="col" class="px-6 py-3 border">Эгаллаб турган лавозими</th>
            <th scope="col" class="px-6 py-3 border">Тавсия этилган лавозими</th>
            <th scope="col" class="px-6 py-3 border">Ҳужжат келган сана</th>
            <th scope="col" class="px-6 py-3 border">Ваколати тугайдиган сана</th>
            <th scope="col" class="px-6 py-3 border">ОМҲ хулосаси</th>
            <th scope="col" class="px-6 py-3 border">Ижрочи</th>
        </tr>
        </thead>
        <tbody>
        @foreach($candidates as $index => $item)

            <tr class="bg-white border dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <td class="border px-6 py-4 font-medium text-black whitespace-nowrap dark:text-white">
                    {{ $index + 1 }}
                </td>
                <td class="border px-6 py-4">
                    {{ $item->region?->name ?? '—' }}
                </td>
                <td class="border px-6 py-4">
                    {{ $item->full_name ?? '—' }}
                </td>
                <td class="border px-6 py-4">
                @php
                    $lastStage = $item->judges?->judges_stages?->sortByDesc('start_date')->first();
                @endphp
                    {{ $lastStage?->position?->name ?? '—' }}
                </td>
                <td class="border px-6 py-4">
                    {{ $item->appointment_info ?? '—' }}
                </td>
                <td class="border px-6 py-4">
                    {{ $item->renewed_date ? \Carbon\Carbon::parse($item->renewed_date)->format('d.m.Y') : '—' }}
                </td>
                <td class="border px-6 py-4">

{{--                    {{ $judges_stages?->end_date ? \Carbon\Carbon::parse($lastStage->end_date)->format('d.m.Y') : '—' }}--}}
                </td>
                <td class="border px-6 py-4">
                    {{ $item->conclusion ?? '—' }}
                </td>
                <td class="border px-6 py-4">
                    {{ $item->superme_judges?->name ?? '—' }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

</body>
</html>
{{--    @foreach($candidates as $index => $item)--}}
{{--        <tr>--}}
{{--            <td>{{ $index + 1 }}</td>--}}
{{--            <td>{{ $item->region?->name ?? '—' }}</td>--}}
{{--            <td>{{ $item->full_name }}</td>--}}
{{--            <td>{{ $item->working_place }}</td>--}}
{{--            <td>{{ $item->document_date ? \Carbon\Carbon::parse($item->document_date)->format('d.m.Y') : '—' }}</td>--}}
{{--            <td>{{ $item->superme_judges?->name ?? '—' }}</td>--}}
{{--        </tr>--}}
{{--    @endforeach--}}
