@props([
    'rating' => null,
])

@php
    $overall = round($rating);
    if ($overall >= 86) {
        $grade = 'Намунали';
    } elseif ($overall >= 71) {
        $grade = 'Яхши';
    } elseif ($overall >= 56) {
        $grade = 'Қониқарли';
    } else {
        $grade = 'Қониқарсиз';
    }
@endphp

<div
    x-data="{
        rating: {{ $overall }},
        chart: null,
        init() {
            this.chart = new ApexCharts(this.$refs.chart, {
                series: [this.rating],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -10,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 1000,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 800
                        }
                    }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: '16px',
                                offsetY: 80
                            },
                            value: {
                                offsetY: 0,
                                fontSize: '85px',
                                color: '#444447',
                                formatter: function (val) {
                                    return val + ' ';
                                }
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 65, 91]
                    },
                },
                stroke: {
                    dashArray: 4
                },
                labels: [''],
            });

            this.chart.render();
        }
    }"
>
    <div x-ref="chart" class="w-full max-w-2xl mx-auto"></div>

    <div class="text-center mt-4">
        <p class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
            {{ $grade }}
        </p>
    </div>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush
@endonce
