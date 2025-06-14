



    <x-filament::fieldset>
        <x-slot name="label">
            <x-filament::badge color="warning">
                Биринчи инстанция натижаси
            </x-filament::badge>
        </x-slot>

        <ul class="grid grid-cols-3 gap-4  ">
            <li class="text-gray-900 border-r">
                <span class="font-semibold">Суд қарори чиқарилган сана:</span>
                <span class="block">{{ \Carbon\Carbon::parse($record->appeal_date)->format('d.m.Y') }}</span>
            </li>
            <li class="text-gray-900 border-r">
                <span class="font-semibold">Иш раками</span>
                <span class="block">{{$record->case_type}}</span>
            </li>
            <li class="text-gray-900 border-r">
                <span class="font-semibold">Ишдаги тарафлар</span>
                <span class="block">{{$record->sides}}</span>
            </li>
            <!-- Qo'shimcha li lar shu yerga -->
            <li class="text-gray-900 border-r">
                <span class="font-semibold">Суд номи</span>
                <span class="block">{{$record->CourtName}}</span>
            </li>
            <li class="text-gray-900 border-r">
                <span class="font-semibold">Иш мазмуни</span>
                <span class="block">{{$record->content}}</span>
            </li>
            <li class="text-gray-900 border-r">
                <span class="font-semibold">Иш тоифаси</span>
                <span class="block">{{$record->jobCategory->name??'-'}}</span>
            </li>
            <li class="text-gray-900 border-r">
                <span class="font-semibold">Суд қарори тури</span>
                <span class="block">{{$record->typeOfDecision->name??'-'}}</span>
            </li>

            <li class="text-gray-900">...</li>
            <li class="text-gray-900">...</li>
            <li class="text-gray-900">
                <span class="font-semibold">Лавозим номи</span>
                <span class="block">{{$record->position}}</span>
            </li>
            <li class="text-gray-900">
                <span class="font-semibold">Файлни юклаш</span>
                <span class="block w-full">
        @if($record->file)
                        <a href="{{ asset('storage/' . $record->file) }}" target="_blank" rel="noopener noreferrer">
                <x-filament::button color="success" width="max-w-full" size="xs" icon="heroicon-o-newspaper">
                    Файлни кўриш
                </x-filament::button>
            </a>
                    @else
                        <span>Файл мавжуд эмас</span>
                    @endif
    </span>
            </li>
        </ul>
    </x-filament::fieldset>


