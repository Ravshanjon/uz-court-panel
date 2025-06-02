
@props([
    'rating' => null,  // Example: Score for Inspection

])

@php
    // Calculate overall score based on inspection and responsive
    $overall = round(($rating));

    // Determine the grade based on the overall score
    if ($overall >= 86) {
        $grade = 'Намунали'; // Excellent
    } elseif ($overall >= 71) {
        $grade = 'Яхши'; // Good
    } elseif ($overall >= 56) {
        $grade = 'Қониқарли'; // Satisfactory
    } else {
        $grade = 'Қониқарсиз'; // Unsatisfactory
    }
@endphp

<div id="chart" class="w-full max-w-2xl mx-auto"></div>

<div class="text-center">
    <p class="text-sm font-medium leading-6 text-gray-950 dark:text-white">{{ $grade }}</p>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush
@endonce

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: [{{ $overall }}],
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
                                color: undefined,
                                offsetY: 80
                            },
                            value: {
                                offsetY: 0,
                                fontSize: '85px',
                                color: '#444447',
                                formatter: function (val) {
                                    return val + " ";
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
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>
@endpush
