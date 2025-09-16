<div x-data="{ open: false }" class="border rounded shadow bg-white mb-4 p-4">
    {{-- Header: Always visible --}}
    <div @click="open = !open" class="cursor-pointer font-semibold text-base">
        {{ $record->case_type ?? '—' }}
    </div>

    {{-- Collapse content --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-screen"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 max-h-screen"
         x-transition:leave-end="opacity-0 max-h-0"
         class="overflow-hidden mt-4 space-y-2 text-sm text-gray-700">

        <div><strong>Иш тоифаси:</strong> {{ $record->typeOfDecision->jobCategory->name ?? '—' }}</div>
        <div><strong>Ишдаги тарафлар:</strong> {{ $record->sides ?? '—' }}</div>
        <div><strong>Иш мазмуни:</strong> {{ $record->content ?? '—' }}</div>
        <div><strong>Қарор тури:</strong> {{ $record->typeOfDecision->name ?? '—' }}</div>
        <div><strong>Апелляция натижаси:</strong> {{ $record->appelation ?? '—' }}</div>
        <div><strong>Кассация натижаси:</strong> {{ $record->cassation ?? '—' }}</div>
        <div><strong>Бахо:</strong> {{ $record->score ?? '—' }}</div>

        <div>
            <strong>Юкланган файл:</strong>
            @if($record->file)
                <a href="{{ Storage::url($record->file) }}" target="_blank" class="text-blue-600 underline">Юклаш</a>
            @else
                Йўқ
            @endif
        </div>
    </div>
</div>
