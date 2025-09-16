{{-- resources/views/livewire/countdown.blade.php --}}
<div wire:poll.1s="tick"
     class="inline-flex items-center gap-2 rounded-xl px-2.5 py-1.5 text-xs
            {{ $expired ? 'bg-red-600 text-white' : 'bg-amber-100 text-amber-900' }}">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M6 2h12M9 2v2m6-2v2M6 8h12v12H6V8z" stroke="currentColor" stroke-width="1.5"/></svg>
    <span>{{ $diff }}</span>
</div>
