{{-- resources/views/filament/widgets/mismatched-regions-modal.blade.php --}}

<div x-data="{ open:false }" x-on:open-mismatch-modal.window="open = true">
    <!-- Backdrop -->
    <div x-show="open"
         x-transition.opacity
         class="fixed inset-0 z-40 bg-black/20 backdrop-blur-lg backdrop-saturate-150 backdrop-contrast-125">
    </div>

    <!-- Modal -->
    <div x-show="open" x-transition class="fixed z-50 inset-0 bg-black/50 flex items-center justify-center p-4">
        <div class="w-full max-w-4xl rounded-lg bg-white dark:bg-gray-900 shadow-xl">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <h2 class="text-lg font-bold">Туғилган ва иш жойи турлича бўлган судьялар</h2>
                <button class="text-gray-500 hover:text-gray-700" x-on:click="open = false">✕</button>
            </div>

            @php
                // Har bir sudya uchun eng so‘nggi stage (start_date bo‘yicha)
                use Illuminate\Support\Facades\DB;
                use Illuminate\Support\Str;$latestStage = DB::table('judges_stages as s')
                    ->select('s.judge_id', DB::raw('MAX(s.start_date) as max_start'))
                    ->groupBy('s.judge_id');
                $user = auth()->user();
$isMalaka = $user && $user->getRoleNames()->contains(fn($r) => Str::lower($r) === 'malaka');
$userRegionId = $user?->regions_id;

                // Judges.region_id va so‘nggi Stage.region_id farq qiladiganlar


$rows = \App\Models\Judges::query()
    ->from('judges as j')
    ->joinSub(
        DB::table('judges_stages as s')
            ->select('s.judge_id', DB::raw('MAX(s.start_date) as max_start'))
            ->groupBy('s.judge_id'),
        'ls',
        fn($q) => $q->on('ls.judge_id', '=', 'j.id')
    )
    ->join('judges_stages as js', function ($q) {
        $q->on('js.judge_id', '=', 'j.id')
          ->on('js.start_date', '=', 'ls.max_start');
    })
    ->when($isMalaka && $userRegionId, fn($q) => $q->where('js.region_id', $userRegionId)) // ⬅️ qo‘shildi
    ->join('regions as rj', 'rj.id', '=', 'j.region_id')
    ->join('regions as rs', 'rs.id', '=', 'js.region_id')
    ->leftJoin('positions as pos', 'pos.id', '=', 'js.position_id')
    ->whereColumn('j.region_id', '!=', 'js.region_id')
    ->select([
        'j.id',
        'j.region_id as judge_region_id',
        DB::raw("TRIM(CONCAT_WS(' ', j.last_name, j.first_name, j.middle_name)) as computed_full_name"),
        'j.age',
        'j.image',
        'rj.name as judge_region',
        'rs.name as stage_region',
        'pos.name as position_name',
    ])
    ->orderBy('computed_full_name')
    ->limit(200)
    ->get();
$mismatch = $rows->count();
$totalAll = \App\Models\Judges::count();
$percent2 = $totalAll > 0 ? round(($mismatch / $totalAll) * 100, 2) : 0;
            @endphp
            <div class="p-4 space-y-4">
                <div class=" flex justify-between items-center">
                    <div>
                        <div class="text-sm text-gray-500">Фоизда</div>
                        <div class="text-3xl text-blue-400 font-bold text-yellow-600 dark:text-yellow-400">
                            {{ $percent2 }}%
                        </div>
                    </div>
                    <div>
                        <h1>Юлкаб олиш</h1>
                    </div>
                </div>


                <div class="relative overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-600 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-3">№</th>
                            <th class="px-6 py-3">Расм</th>
                            <th class="px-6 py-3">Ф.И.Ш</th>
                            <th class="px-6 py-3">Туғилган вилояти</th>
                            <th class="px-6 py-3">Ишлаб турган вилоят</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($rows as $i => $j)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                <td class="px-6 py-4">{{ $i+1 }}</td>

                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white flex items-center gap-3">
                                    @if($j->image)
                                        <img src="{{ asset('storage/'.$j->image) }}"
                                             alt="{{ $j->computed_full_name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <img
                                            src="https://ui-avatars.com/api/?name={{ urlencode($j->computed_full_name ?? 'Judge') }}&background=0D8ABC&color=fff"
                                            alt="{{ $j->computed_full_name }}"
                                            class="w-10 h-10 rounded-full object-cover">
                                @endif

                                <td class="px-6 py-4 ">
                                    <ul class="space-y-0.5 w-64">
                                        <li class="font-medium text-blue-600 hover:underline truncate">
                                            <a href="{{ route('filament.admin.resources.judges.view', $j->id) }}">
                                                {{ $j->computed_full_name ?? '—' }}
                                            </a>
                                        </li>
                                        <li class="block text-xs max-w-full text-gray-500 line-clamp-2">
                                            {{ $j->position_name ?? '—' }}
                                        </li>
                                    </ul>
                                </td>
                                <td class="px-6 py-4">{{ $j->judge_region ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $j->stage_region ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-gray-500">Маълумот топилмади.</td>
                            </tr>
                        @endforelse

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

