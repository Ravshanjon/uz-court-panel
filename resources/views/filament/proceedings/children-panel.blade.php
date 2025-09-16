@php
    /** @var bool $show */
@endphp
@if($show)
    <div class="mt-4 rounded-xl border bg-white">
        <div x-data="{ open: true }" class="p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold">Bosqichlar</h3>
                <button type="button" class="text-sm text-slate-600" x-on:click="open = !open">
                    <span x-show="open">Yopish</span>
                    <span x-show="!open">Ochish</span>
                </button>
            </div>

            <div x-show="open" class="mt-4">
                {{-- child form fields --}}
                {{ $this->getChildForm() }}

                <div class="mt-3 flex gap-2">
                    <x-filament::button wire:click="saveChildren" icon="heroicon-o-check">
                        Saqlash
                    </x-filament::button>
                    <x-filament::button color="gray" wire:click="$set('activeProceedingId', null)">
                        Bekor qilish
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
@endif
