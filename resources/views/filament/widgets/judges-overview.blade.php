<div x-data @open-senior-modal.window="$refs.seniorModal.showModal()">
    <dialog x-ref="seniorModal" class="bg-white p-4 rounded shadow-xl max-w-2xl w-full">
        <h2 class="text-lg font-bold mb-2">65 ёшдан ошган судьялар</h2>

        <ul class="list-disc pl-5 max-h-80 overflow-auto text-sm">
            @foreach(\App\Models\Judges::whereDate('birth_date', '<=', now()->subYears(65))->get() as $judge)
                <li>{{ $judge->full_name }} ({{ \Carbon\Carbon::parse($judge->birth_date)->format('d.m.Y') }})</li>
            @endforeach
        </ul>

        <form method="dialog">
            <button class="mt-4 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Yopish</button>
        </form>
    </dialog>
</div>
