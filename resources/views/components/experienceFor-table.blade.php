<table class="table-auto w-full border-collapse text-sm">

    <thead>
    <tr class="bg-gray-200">
        <th class="border px-4 py-2">№</th>
        <th class="border px-4 py-2">Иш жойи</th>
        <th class="border px-4 py-2">Ишни бошлаган сана</th>
        <th class="border px-4 py-2">Тугатилган сана</th>
        <th class="border px-4 py-2">Стаж </th>
    </tr>
    </thead>
    <tbody>

    @foreach($judges_stages as $index => $judges_stages)
        <tr>
            <td class="border px-4 py-2 text-center font-semibold"> {{ (int)$index + 1 }}.</td>
            <td class="border px-4 py-2">{{ $judges_stages->working_place }}</td>

            {{-- Convert start_date to Carbon and format it --}}
            <td class="border px-4 py-2">
                {{ \Carbon\Carbon::parse($judges_stages->start_date)->format('d.m.Y') }}
            </td>

            {{-- Convert end_date to Carbon and format it --}}
            <td class="border px-4 py-2">
                {{ \Carbon\Carbon::parse($judges_stages->end_date)->format('d.m.Y') }}
            </td>

            {{-- Display the counter value directly (assuming it's not a date) --}}
            <td class="border border-gray-300 px-3 py-2">
                @php
                    // Example counter string like "3 йил, 6 ой, 9 кун"
                    $counter = $judges_stages['counter']; // Assuming counter is like "3 йил, 6 ой, 9 кун"

                    // Remove "0" year, month, or day
                    $counter = preg_replace('/\b0 (йил|ой|кун)(,|$)/', '', $counter); // Removing "0 yil", "0 oy", or "0 kun"

                    // Remove trailing commas after a single year, month, or day (i.e., "1 yil,", "1 oy,", "1 kun,")
                    $counter = preg_replace('/1 (йил|ой|кун),/', '1 $1', $counter);

                    // Clean up extra spaces if there are any after removal
                    $counter = preg_replace('/\s{2,}/', ' ', $counter); // Replacing multiple spaces with a single space
                @endphp

                {{ $counter }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
