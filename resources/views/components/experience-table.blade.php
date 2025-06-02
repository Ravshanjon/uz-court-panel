<table class="w-full rounded-xl table-auto border border-gray-300 text-sm">
    <thead class="rounded-xl bg-gray-100">
    <tr>
        <th class="border border-gray-300 px-3 py-2 text-left">№</th>
        <th class="border border-gray-300 px-3 py-2 text-left">Ишлаган жойи</th>
        <th class="border border-gray-300 px-3 py-2 text-left">Стаж бошланган сана</th>
        <th class="border border-gray-300 px-3 py-2 text-left">Стаж тугаган сана</th>
        <th class="border border-gray-300 px-3 py-2 text-left">Стаж (йил, ой, кун)</th>
        <th class="border border-gray-300 px-3 py-2 w-3">Таҳрирлаш</th>
    </tr>
    </thead>
    <tbody>


    @foreach ($stages as $index => $stage)
        @if (!empty($stage['counter']) && $stage['counter'] != 0)
            <tr>
                <td class="border border-gray-300 px-3 py-2 font-semibold">
                    {{ (int)$index + 1 }}
                </td>
                <td class="rounded-xl font-semibold border border-gray-300 px-3 py-2">
                    {{-- Check if working_place is available, otherwise fall back to position.name --}}
                    {{ $positionName['working_place'] ?? $positionName ?? 'N/A' }}
                </td>
                <td class="rounded-xl border border-gray-300 px-3 py-2">
                    {{ $stage['start_date'] ? \Carbon\Carbon::parse($stage['start_date'])->format('d.m.Y') : 'N/A' }}
                </td>
                <td class="border border-gray-300 px-3 py-2">
                    {{ $stage['end_date'] ? \Carbon\Carbon::parse($stage['end_date'])->format('d.m.Y') : 'N/A' }}
                </td>
                <td class="border border-gray-300 px-3 py-2">
                    @php
                        $counter = $stage['counter'];
                        $counter = preg_replace('/\b0 (йил|ой|кун)(,|$)/', '', $counter);
                        $counter = preg_replace('/\s{2,}/', ' ', $counter);
                    @endphp
                    {{ $counter }}
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
