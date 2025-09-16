@props([
    'data' => [20, 100, 40, 30, 50, 80, 33],
    'categories' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
])

<div id="apexRadarChart2" class="w-full max-w-xl mx-auto"></div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush
@endonce

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: [{
                    name: 'Series 1',
                    data: {!! json_encode($data) !!}
                }],
                chart: {
                    height: 350,
                    type: 'radar',
                },
                dataLabels: {
                    enabled: true
                },
                plotOptions: {
                    radar: {
                        size: 140,
                        polygons: {
                            strokeColors: '#e9e9e9',
                            fill: {
                                colors: ['#f8f8f8', '#fff']
                            }
                        }
                    }
                },
                colors: ['#65a1e4'],
                markers: {
                    size: 4,
                    colors: ['#fff'],
                    strokeColor: '#65a1e4',
                    strokeWidth: 2
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                },
                xaxis: {
                    categories: {!! json_encode($categories) !!}
                },
                yaxis: {
                    labels: {
                        formatter: function(val, i) {
                            return i % 2 === 0 ? val : '';
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#apexRadarChart"), options);
            chart.render();
        });
    </script>
@endpush
