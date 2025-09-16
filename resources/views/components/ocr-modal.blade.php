<div
    x-data="{ showModal: false, page: '', text: '' }"
    x-on:open-ocr-modal.window="
        page = $event.detail.page;
        text = $event.detail.text;
        showModal = true;
    "
>
    <x-filament::modal
        :show="showModal"
        width="4xl"
        x-on:close="showModal = false"
    >
        <x-slot name="header">
            <h2 class="text-lg font-semibold">Саҳифа <span x-text="page"></span></h2>
        </x-slot>

        <div class="p-4 text-sm whitespace-pre-wrap" x-text="text"></div>

        <x-slot name="footer">
            <x-filament::button color="gray" @click="showModal = false">Ёпиш</x-filament::button>
        </x-slot>
    </x-filament::modal>
</div>
