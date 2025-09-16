
<x-filament::page>
    @foreach ($matchesByRelation as $relationType => $matches)
        <x-filament::section class="mb-6" collapsible>
            <x-slot name="heading">
                {{ $relationType }} — {{ count($matches) }} та ўхшашлик
            </x-slot>


            <div class="grid gap-4">
                @foreach ($matches as $match)
                    <div class="border rounded-xl p-4 shadow-sm bg-white">
                        <div class="text-sm text-gray-500 mb-2">
                            <strong>Қариндош номи:</strong> {{ $match['relative_name'] }}
                            <span class="ml-4 text-xs text-gray-400">({{ $match['match_percent'] }}%)</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($match['judges'] as $judge)
                                <div class="flex items-center gap-4">
                                    @if(!empty($judge['image_url']))
                                        <img src="{{ $judge['image_url'] }}" alt="Sudya rasmi" class="w-14 h-14 rounded-full object-cover">
                                    @else
                                        <div class="w-14 h-14 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">👤</div>
                                    @endif

                                    <div>
                                        <div class="font-semibold text-gray-800">{{ $judge['full_name'] }}</div>
                                        <div class="text-sm text-gray-600">{{ $judge['position'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </x-filament::section>
    @endforeach
</x-filament::page>

