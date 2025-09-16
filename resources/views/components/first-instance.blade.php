@dd($record->judge)
<div class="flex justify-end items-center">
    <span class="text-sm text-gray-600">
        <x-filament::badge class="text-xs" icon="heroicon-o-folder-open">
           Иш рақами:  {{$record->case_type ?? '-' }}
        </x-filament::badge>
    </span>
</div>
<div class="grid grid-cols-3 gap-2">
    <div>
        <x-filament::fieldset class="mb-4">
            <x-slot name="label">
                <x-filament::badge color="warning">
                    Биринчи инстанция натижаси
                </x-filament::badge>
            </x-slot>
            <ul class="grid grid-cols-3 gap-4  ">
                <li class="text-gray-900 border-r">
                    <span class="font-semibold">Суд қарори чиқарилган сана:</span>
                    <span
                        class="block text-gray-600 text-xs">{{ \Carbon\Carbon::parse($record->appeal_date)->format('d.m.Y') }}</span>
                </li>
                <li class="text-gray-900 border-r">
                    <span class="font-semibold">Иш раками</span>
                    <span class="block text-gray-600 text-xs">
                        {{$record->case_type}}
                </span>
                </li>
                <li class="text-gray-900">
                    <span class="font-semibold">Ишдаги тарафлар</span>
                    <span class="block text-gray-600 text-xs">{{$record->sides}}</span>
                </li>

                <!-- Qo'shimcha li lar shu yerga -->
                <li class="text-gray-900 border-r">
                    <span class="font-semibold">Суд номи</span>
                    <span class="block text-gray-600 text-xs">
                     {{ $judge?->judges_stages?->first()?->court_type?->name ?? '-' }}
                </span>
                </li>
                <li class="text-gray-900 border-r">
                    <span class="font-semibold">Иш мазмуни</span>
                    <span class="block text-gray-600 text-xs">{{$record->content}}</span>
                </li>
                <li class="text-gray-900">
                    <span class="font-semibold">Иш тоифаси</span>
                    <span class="block text-gray-600 text-xs">{{$record->jobCategory->name??'-'}}</span>
                </li>
                <li class="text-gray-900 border-r">
                    <span class="font-semibold">Суд қарори тури</span>
                    <span class="block text-gray-600 text-xs">{{$record->typeOfDecision->name??'-'}}</span>
                </li>


                <li class="text-gray-900 mr-2">
                    <span class="font-semibold">Лавозим номи</span>
                    <span
                        class="block text-gray-600 text-xs">{{ $judge?->judges_stages?->first()?->position?->name ?? '-' }}</span>
                </li>
                <li class="text-gray-900">
                    <span class="font-semibold ">Файл</span>
                    <span class="block w-full text-gray-600 text-xs">
        @if($record->file)
                            <a href="{{ asset('storage/' . $record->file) }}" target="_blank" rel="noopener noreferrer">
                <x-filament::button color="success" width="max-w-full" size="xs" icon="heroicon-o-newspaper">
                    {{ basename($record->appelationData->file) }}
                </x-filament::button>
            </a>
                        @else
                            <span>Файл мавжуд эмас</span>
                        @endif
    </span>
                </li>
            </ul>
        </x-filament::fieldset>
    </div>
    <div>
        @if($record->appelationData)
            <x-filament::fieldset class="mb-4">
                <x-slot name="label">
                    <x-filament::badge color="info">
                        Апелляция натижаси
                    </x-filament::badge>
                </x-slot>

                <ul class="grid grid-cols-3 gap-4">
                    <li class="text-gray-900 border-r">
                        <span class="font-semibold">Апелляция кўриб чиқилган сана</span>
                        <span class="block text-xs text-gray-600">
                    {{ \Carbon\Carbon::parse($record->appelationData->appeal_date)->format('d.m.Y') }}
                </span>
                    </li>

                    <li class="text-gray-900 border-r">
                        <span class="font-semibold">Ҳудуд:</span>
                        <span class="block text-xs text-gray-600">
                    {{ $record->appelationData->region?->name ?? '-' }}
                </span>
                    </li>

                    <li class="text-gray-900">
                        <span class="font-semibold">Раислик қилувчи ва (Маърузачи) судья</span>
                        <span class="block text-xs text-gray-600">
    {{ \App\Models\Judges::find($record->appelationData->presiding_judge)?->full_name ?? '-' }}
</span>
                    </li>

                    <li class="text-gray-900 border-r">
                        <span class="font-semibold">Ҳайъат судьяси</span>
                        <span class="block text-xs text-gray-600">
    {{ \App\Models\Judges::find($record->appelationData->jury_judge)?->full_name ?? '-' }}
</span>

                    </li>

                    <li class="text-gray-900 border-r">
                        <span class="font-semibold">Ҳайъат судьяси</span>
                        <span class="block text-xs text-gray-600">
    {{ \App\Models\Judges::find($record->appelationData->speaker_judge)?->full_name ?? '-' }}
</span>

                    </li>

                    <li class="text-gray-900">
                        <span class="font-semibold  pr-4">Ўзгартириш ёки бекор қилиш асослари ва сабаблари</span>
                        <span class="block text-xs text-gray-600  pr-4">
                    {{$record->appelationData->reason->name?? '-'}}
                </span>
                    </li>

                    <li class="text-gray-900 border-r">
                        <span class="font-semibold">1-инст. суд қарори тақдири</span>
                        <span class="block text-xs text-gray-600">
                    {{$record->appelationData->instances->name?? '-'}}
                </span>
                    </li>

                    <li class="text-gray-900">
                        <span class="font-semibold">Файл:</span>
                        @if($record->appelationData->file)
                            <span class="block text-xs">
                        <a href="{{ asset('storage/' . $record->appelationData->file) }}" target="_blank">
                        <x-filament::button color="success" size="xs" icon="heroicon-o-document-text">
                            {{ basename($record->appelationData->file) }}
                        </x-filament::button>
                    </a>
                   </span>
                        @else
                            <span class="text-xs block text-gray-600">Файл мавжуд эмас</span>
                        @endif
                    </li>
                </ul>
            </x-filament::fieldset>
        @endif
    </div>
    <div>
        @if($record->auditFirstData)
            <x-filament::fieldset class="mb-4">
                <x-slot name="label">
                    <x-filament::badge color="danger">
                        Тафтиш-1 натижаси
                    </x-filament::badge>
                </x-slot>

                <ul class="grid grid-cols-3 gap-4">
                    <li class="text-gray-900 border-r">
                        <span class="font-semibold">Тафтиш-1 кўриб чиқилган сана</span>
                        <span class="block text-xs text-gray-600">
                    {{ \Carbon\Carbon::parse($record->taftish_1_date)->format('d.m.Y') }}
                </span>
                    </li>

                    <li class="text-gray-900">
                        <span class="font-semibold">Раислик қилувчи ва (Маърузачи) судья</span>
                        <span class="block text-xs text-gray-600">
    {{ \App\Models\Judges::find($record->taftish_1_speaker_judge)?->full_name ?? '-' }}
</span>
                    </li>

                    <li class="text-gray-900 border-r">
                        <span class="font-semibold">Ҳайъат судьяси</span>
                        <span class="block text-xs text-gray-600">
    {{ \App\Models\Judges::find($record->appelationData->jury_judge)?->full_name ?? '-' }}
</span>

                    </li>

                    <li class="text-gray-900 border-r">
                        <span class="font-semibold">Ҳайъат судьяси</span>
                        <span class="block text-xs text-gray-600">
    {{ \App\Models\Judges::find($record->appelationData->speaker_judge)?->full_name ?? '-' }}
</span>

                    </li>

                    <li class="text-gray-900">
                        <span class="font-semibold  pr-4">Ўзгартириш ёки бекор қилиш асослари ва сабаблари</span>
                        <span class="block text-xs text-gray-600  pr-4">
                    {{$record->appelationData->reason->name?? '-'}}
                </span>
                    </li>

                    <li class="text-gray-900 border-r">
                        <span class="font-semibold">1-инст. суд қарори тақдири</span>
                        <span class="block text-xs text-gray-600">
                    {{$record->appelationData->instances->name?? '-'}}
                </span>
                    </li>

                    <li class="text-gray-900">
                        <span class="font-semibold">Файл:</span>
                        @if($record->appelationData->file)
                            <span class="block text-xs">
                        <a href="{{ asset('storage/' . $record->appelationData->file) }}" target="_blank">
                        <x-filament::button color="success" size="xs" icon="heroicon-o-document-text">
                            {{ basename($record->appelationData->file) }}
                        </x-filament::button>
                    </a>
                   </span>
                        @else
                            <span class="text-xs block text-gray-600">Файл мавжуд эмас</span>
                        @endif
                    </li>
                </ul>
            </x-filament::fieldset>
        @endif
    </div>

</div>





