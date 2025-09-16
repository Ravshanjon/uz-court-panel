<div>
    <form wire:submit.prevent="save" class="space-y-4 w-full">
        <x-filament::input.wrapper>
            <x-filament::input.select wire:model="status">
                <option value="draft">Draft</option>
                <option value="reviewing">Reviewing</option>
                <option value="published">Published</option>
            </x-filament::input.select>
        </x-filament::input.wrapper>

        <x-filament::button type="submit">Сақлаш</x-filament::button>
    </form>

</div>
