{{-- resources/views/filament/widgets/senior-judges-modal.blade.php --}}
<div x-data="{ open:false }" x-on:open-senior-modal.window="open = true">
    <!-- Backdrop -->
    <div x-show="open"
         x-transition.opacity
         class="fixed inset-0 z-40
            bg-black/20
            backdrop-blur-lg backdrop-saturate-150 backdrop-contrast-125">
    </div>

    <!-- Modal -->
    <div x-show="open" x-transition  class="fixed z-50 inset-0 flex bg-black/50 items-center justify-center p-4">
        <div class="w-full max-w-4xl rounded-lg bg-white dark:bg-gray-900 shadow-xl">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800
                        flex items-center justify-between">
                <h2 class="text-lg font-bold">65 ёшдаги судьялар</h2>
                <button class="text-gray-500 hover:text-gray-700"
                        x-on:click="open = false">✕</button>
            </div>

            <div class="p-4 space-y-6">
                @php
                    $total   = \App\Models\Judges::count();
                    $count65 = \App\Models\Judges::where('age','>=',65)->count();
                    $percent = $total > 0 ? round(($count65 / $total)*100, 2) : 0;
                    $seniorJudges = \App\Models\Judges::with('region')
                        ->where('age','>=',65)
                        ->orderByDesc('age')
                        ->limit(50) // faqat 50 ta chiqaramiz
                        ->get();
                @endphp

                        <!-- Statistikalar -->


                        <div class="text-sm text-gray-500">Фоизда</div>
                        <div class="text-3xl text-blue-400 font-bold text-yellow-600 dark:text-yellow-400">
                            {{ $percent }}%
                        </div>



                <!-- Ruyxat -->
                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th scope="col" class="px-6 py-3">№</th>
                            <th scope="col" class="px-6 py-3">Ф.И.Ш</th>
                            <th scope="col" class="px-6 py-3">Туғилган жойи</th>
                            <th scope="col" class="px-6 py-3">Ёши</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($seniorJudges as $i => $judge)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                <!-- № -->
                                <td class="px-6 py-4">{{ $i+1 }}</td>

                                <!-- Ф.И.Ш + Rasm -->
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white flex items-center gap-3">
                                    @if($judge->photo ?? false)
                                        <img src="{{ asset('storage/'.$judge->photo) }}"
                                             alt="{{ $judge->full_name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($judge->full_name) }}&background=0D8ABC&color=fff"
                                             alt="{{ $judge->full_name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                    @endif
                                    <span>{{ $judge->full_name ?? '—' }}</span>
                                </td>

                                <!-- Туғилган жойи -->
                                <td class="px-6 py-4">
                                    {{ $judge->region->name ?? '—' }}
                                </td>

                                <!-- Ёш -->
                                <td class="px-6 py-4">
                                    {{ $judge->age ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-800 text-right">
                <x-filament::button color="gray" x-on:click="open = false">Ёпиш</x-filament::button>
            </div>
        </div>
    </div>
</div>
