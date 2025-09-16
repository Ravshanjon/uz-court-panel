<div class="mt-6 p-4 rounded">
    @forelse ($groupedAppeals as $groupId => $group)
        <div class="mb-4">

                <x-filament::badge class="mb-4">
                    Гуруҳ ID: {{ $groupId }}
                </x-filament::badge>



            <table class="p-4 table-auto w-full text-sm border border-gray-200">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-3 py-1 text-left">Иш рақами</th>
                    <th class="px-3 py-1 text-left">Сабаб</th>
                    <th class="px-3 py-1 text-left">Балл</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($group as $appeal)
                    <tr class="border-t">
                        <td class="px-3 py-1">{{ $appeal->case_type }}</td>
                        <td class="px-3 py-1">{{ $appeal->reason->name ?? '-' }}</td>
                        <td class="px-3 py-1">{{ $appeal->reason->score ?? 0 }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p class="text-gray-500">Гуруҳланган апелляциялар йўқ.</p>
    @endforelse
</div>
