<x-filament::modal id="add-taftish" :wire="false" width="4xl" >
    <x-slot name="header">
        Тафтиш қўшиш
    </x-slot>

    <form wire:submit.prevent="save">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Сақлаш
        </x-filament::button>
    </form>
</x-filament::modal>
