
<x-filament-panels::page xmlns:x-filament="http://www.w3.org/1999/html">

    <x-filament::card>

        <div class="space-y-6">
            {{ $this->form }}
            <x-filament::button
                wire:click="compareJudges"
                x-on:click="showCharts = true"
                class="mt-4"
            >
                Taqqoslash
            </x-filament::button>
            @if($this->judgeA && $this->judgeB)
                <a href="{{ route('compare.judges.pdf', ['judgeAId' => $this->judgeA, 'judgeBId' => $this->judgeB]) }}"
                   target="_blank"
                   class="inline-flex mt-4 items-center px-4 py-2 bg-blue-600 text-gray-400 rounded">
                    📄 PDF yuklab olish
                </a>
            @endif
            @php
                $stats = $this->comparisonStats;
                $judgeA = $this->getJudgeAData();
                $judgeB = $this->getJudgeBData();
            @endphp

            @if ($judgeA = $this->getJudgeAData() and $judgeB = $this->getJudgeBData())

                <div id="spiderChart" class="mt-10 w-full max-w-3xl mx-auto"></div>

                    <div class="flex justify-center divide-x">
                        <div class="text-center">
                            <div class="relative mr-2">
                                <img src="{{ $judgeA->image ? asset('storage/' . $judgeA->image) : asset('image/default.jpg') }}"
                                     alt="Sudya rasmi"
                                     class="mb-4 aspect-square rounded-full object-cover border shadow w-24 h-20"/>
                                <div class="absolute top-0 left-0">
                                    <div x-data="{ percent: 0 }"
                                         x-init="let p = {{ $stats['judgeA']['percent'] }};
                      let i = setInterval(() => {
                          if (percent < p) percent++;
                          else clearInterval(i);
                      }, 20)"
                                         class="relative w-14 h-14 mx-auto">
                                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                            <path
                                                class="text-gray-100"
                                                stroke-width="3"
                                                stroke="currentColor"
                                                fill="none"
                                                d="M18 2.0845
                       a 15.9155 15.9155 0 0 1 0 31.831
                       a 15.9155 15.9155 0 0 1 0 -31.831"
                                            />
                                            <path
                                                class="text-gray-400"
                                                stroke-width="3"
                                                :stroke-dasharray="`${percent}, 100`"
                                                stroke-linecap="round"
                                                stroke="currentColor"
                                                fill="none"
                                                d="M18 2.0845
                       a 15.9155 15.9155 0 0 1 0 31.831
                       a 15.9155 15.9155 0 0 1 0 -31.831"
                                            />
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="text-sm font-bold text-blue-700" x-text="`${percent}%`"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-5 text-gray-600 text-sm">
                                {{$judgeA->last_name .' '. $judgeA->first_name .' '. $judgeA->middle_name}}
                            </div>
                            <div class="mt-5 text-gray-600 text-sm">
                                {{$judgeA->establishment->position->name}}
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="relative ml-2">
                                <img src="{{ $judgeB->image ? asset('storage/' . $judgeB->image) : asset('image/default.jpg') }}"
                                     alt="Sudya rasmi"
                                     class="mb-4 aspect-square rounded-full object-cover border shadow w-24 h-20"/>
                                <div class="absolute top-0 right-0">
                                    <div x-data="{ percent: 0 }"
                                         x-init="let p = {{ $stats['judgeB']['percent'] }};
                      let i = setInterval(() => {
                          if (percent < p) percent++;
                          else clearInterval(i);
                      }, 20)"
                                         class="relative w-14 h-14 mx-auto">
                                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                            <path
                                                class="text-gray-100"
                                                stroke-width="3"
                                                stroke="currentColor"
                                                fill="none"
                                                d="M18 2.0845
                       a 15.9155 15.9155 0 0 1 0 31.831
                       a 15.9155 15.9155 0 0 1 0 -31.831"
                                            />
                                            <path
                                                class="text-gray-400"
                                                stroke-width="3"
                                                :stroke-dasharray="`${percent}, 100`"
                                                stroke-linecap="round"
                                                stroke="currentColor"
                                                fill="none"
                                                d="M18 2.0845
                       a 15.9155 15.9155 0 0 1 0 31.831
                       a 15.9155 15.9155 0 0 1 0 -31.831"
                                            />
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="text-sm font-bold text-blue-700" x-text="`${percent}%`"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 text-gray-600 text-sm">
                                {{$judgeB->last_name .' '. $judgeB->first_name .' '. $judgeB->middle_name}}
                            </div>
                        </div>
                    </div>
                <div class="mt-6 text-center">
                    @if($stats['winner'] === 'A')
                        <div class="inline-flex items-center gap-2 text-blue-700 font-bold text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-200" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                            <x-filament::button outline>
                                {{ $judgeA->last_name }} {{ $judgeA->first_name }}
                            </x-filament::button>

                        </div>
                    @elseif($stats['winner'] === 'B')
                        <div class="inline-flex items-center gap-2 text-green-700 font-bold text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-700" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                            {{ $judgeB->last_name }} {{ $judgeB->first_name }}
                        </div>
                    @else
                        <div class="text-gray-600 font-bold text-lg">⚖️ Teng natija</div>
                    @endif

                </div>

            @endif

        </div>
        @if($this->spiderChartData && isset($this->spiderChartData['labels']))
            <div x-show="showCharts">
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="flex justify-between w-full">
                        <div
                            x-data="{
                    chart: null,
                    data: @js($this->spiderChartData),
                    init() {
                        const options = {
                            chart: {
                                height: 400,
                                type: 'line',
                                zoom: { enabled: false }
                            },
                            series: [
                                {
                                    name: '{{ $judgeA->last_name ?? 'Sudya A' }}',
                                    data: this.data.judgeA
                                },
                                {
                                    name: '{{ $judgeB->last_name ?? 'Sudya B' }}',
                                    data: this.data.judgeB
                                }
                            ],
                            stroke: {
                                curve: 'smooth',
                                width: 3
                            },
                            markers: {
                                size: 5
                            },
                            dataLabels: {
                                enabled: true
                            },
                            title: {
                                text: 'Sudya ko‘rsatkichlari',
                                align: 'left'
                            },
                            colors: ['#FF4560', '#008FFB'],
                            xaxis: {
                                categories: this.data.labels
                            },
                            yaxis: {
                                min: 0,
                                max: 100,
                                title: {
                                    text: 'Ball'
                                }
                            },
                            tooltip: {
                                y: {
                                    formatter: function (val) {
                                        return val + ' ball';
                                    }
                                }
                            }
                        };

                        this.chart = new ApexCharts(this.$refs.chart, options);
                        this.chart.render();
                    }
                }"
                            x-init="init()"
                            class="w-full"
                        >
                            <div x-ref="chart" class="w-full"></div>
                        </div>
                    </div>
                </div>
            </div>

        @endif

    </x-filament::card>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush
</x-filament-panels::page>

