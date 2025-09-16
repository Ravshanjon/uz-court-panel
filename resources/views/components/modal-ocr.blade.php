<div
    x-data="{ show: false, page: '', text: '' }"
    x-on:open-ocr-modal.window="
        show = true;
        page = $event.detail.page;
        text = $event.detail.text;
    "
    x-show="show"
    style="display: none"
    class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center"
>
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl max-h-[80vh] overflow-y-auto">
        <h2 class="text-lg font-semibold mb-4">📄 Sahifa <span x-text="page"></span></h2>
        <p class="text-sm whitespace-pre-line" x-text="text"></p>
        <div class="mt-4 text-right">
            <button class="bg-red-600 text-white px-4 py-2 rounded" @click="show = false">Yopish</button>
        </div>
    </div>
</div>
