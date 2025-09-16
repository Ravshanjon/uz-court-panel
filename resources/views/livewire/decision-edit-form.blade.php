<form wire:submit.prevent="save" class="space-y-4">
    <x-filament::select
        label="Қарор тури"
        wire:model="type_of_decision_id"
        :options="$decisions"
        required
    />

    <x-filament::button type="submit" color="primary">Сақлаш</x-filament::button>
</form>
