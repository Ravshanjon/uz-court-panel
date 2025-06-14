@php
    $ratings = $ratings ?? collect();

    // Created_at bo‘yicha sort qilamiz
    $points = $ratings->sortBy('created_at')->map(function ($item) {
        return [
            'x' => \Carbon\Carbon::parse($item['created_at'])->toIso8601String(), // vaqt
            'y' => $item['rating'], // reyting
        ];
    });
@endphp

<div id="chart" class="w-full h-[400px] mt-6"></div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush
@endonce

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const options = {
                series: [{
                    name: 'Рейтинг',
                    data: @json($points)
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 500
                        }
                    }
                },
                dataLabels: {
                    enabled: true
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'datetime',
                    title: {
                        text: 'Вақт (создался)'
                    }
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: 'Рейтинг балли'
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd.MM.yyyy HH:mm:ss'
                    },
                    y: {
                        formatter: val => val + ' балл'
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
@endpush
