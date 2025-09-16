
<div class="grid grid-cols-3 gap-4 w-full" xmlns:x-filament="http://www.w3.org/1999/html">
    <x-filament::fieldset>
        <x-slot name="label">
            <x-filament::badge color="info">Апелляция</x-filament::badge>
        </x-slot>
    </x-filament::fieldset>
    <x-filament::fieldset>
        <x-slot name="label">
            <x-filament::badge color="info">Кассация</x-filament::badge>
        </x-slot>
    </x-filament::fieldset>

    <x-filament::fieldset>
        <x-slot name="label">
            <x-filament::badge color="info">Тафтиш-1</x-filament::badge>
        </x-slot>
    </x-filament::fieldset>
    <x-filament::fieldset>
        <x-slot name="label">
            <x-filament::badge color="info">Тафтиш-2</x-filament::badge>
        </x-slot>
    </x-filament::fieldset>
    <x-filament::fieldset>
        <x-slot name="label">
            <x-filament::badge color="info">Тафтиш-3</x-filament::badge>
        </x-slot>
    </x-filament::fieldset>
</div>
@foreach ($record as $records)
    {{-- Asosiy row --}}
    <tr class="border-b hover:bg-gray-50 cursor-pointer"
        @click="openRow === {{ $records->id }} ? openRow = null : openRow = {{ $records->id }}">
        <td class="px-4 py-2">{{ $loop->iteration }}
        <td class="px-4 py-2">{{ $records->case_type }}</td>
        <td class="px-4 py-2">{{ $records->jobCategory?->name }}</td>
        <td class="px-4 py-2">{{ \Illuminate\Support\Str::limit($records->sides, 50) }}</td>
        <td class="px-4 py-2">{{ \Illuminate\Support\Str::limit($records->content, 50) }}</td>
        <td class="px-4 py-2">{{ $records->typeOfDecision?->name }}</td>
        <td class="px-4 py-2">-</td>
        <td class="px-4 py-2">
            <x-filament::modal id="edit-decision" width="4xl">
                <x-slot name="trigger">
                    <x-filament::button color="info">
                        Қарорни тахрирлаш
                    </x-filament::button>
                </x-slot>

                {{-- 🔘 Modal sarlavhasi --}}
                <x-slot name="heading">
                    Қарорни тахрирлаш
                </x-slot>

                {{-- 🔘 Modal kontenti --}}
                <x-slot name="content">
                    <livewire:decision-edit-form/>
                </x-slot>

                {{-- 🔘 Modal tagidagi amallar --}}
                <x-slot name="footer">
                    <x-filament::button color="success" form="decision-form" type="submit">
                        Сақлаш
                    </x-filament::button>
                </x-slot>
            </x-filament::modal>
        </td>
    </tr>

    {{-- Collapse row --}}
    <tr x-show="openRow === {{ $records->id }}">
        <td colspan="5" class="p-4 bg-white">
            <div class="grid grid-cols-3 gap-2 text-sm">
                <x-filament::fieldset
                    x-data
                    class="cursor-pointer hover:bg-gray-50 transition"
                    @click="$dispatch('open-modal', { id: 'create-appelation-modal', arguments: { appealId: {{ $records->id }} } })"
                >
                    <x-slot name="label">
                        <x-filament::badge color="info">Апелляция</x-filament::badge>
                    </x-slot>
                    <div>
                        <ul>
                            <li>Кўриб чиқилган сана:
                                <span class="font-semibold">

                </span>
                            </li>
                            <li>Ўзгартириш ёки бекор қилиш асослари:
                                <span class="font-semibold">
                </span>
                            </li>
                            <li>1-инст. суд қарори тақдири:
                                <span class="font-semibold">
                </span>
                            </li>
                        </ul>
                    </div>
                </x-filament::fieldset>

                <x-filament::fieldset>


                    <x-slot name="label">
                        <x-filament::badge color="info"></x-filament::badge>
                    </x-slot>
                </x-filament::fieldset>

                <x-filament::fieldset>
                    <x-slot name="label">

                    </x-slot>
                </x-filament::fieldset>

                <x-filament::fieldset>
                    <x-slot name="label">
                        <x-filament::badge color="info">Тафтиш-2</x-filament::badge>
                    </x-slot>
                </x-filament::fieldset>
                <x-filament::fieldset>
                    <x-slot name="label">
                        <x-filament::badge color="info">Тафтиш-3</x-filament::badge>
                    </x-slot>
                </x-filament::fieldset>


                {{--                        <div class="border p-4 rounded-md">--}}
                {{--                            <h1>Тафтиш-1</h1>--}}
                {{--                        </div>--}}
                {{--                        <div class="border p-4 rounded-md">--}}
                {{--                            <h1>Тафтиш-2</h1>--}}
                {{--                        </div>--}}
                {{--                        <div class="border p-4 rounded-md">--}}
                {{--                            <h1>Тафтиш-3</h1>--}}
                {{--                        </div>--}}
            </div>
        </td>
    </tr>
@endforeach

