@php
    $ratings = $ratings ?? collect();

    $points = $ratings->map(function ($item) {
        return [
            'x' => \Carbon\Carbon::parse($item->created_at)->format('d.m.Y'),
            'y' => $item->rating,
        ];
    });
@endphp

<div id="ratingHistoryChart" class="mt-6"></div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const options = {
            series: [{
                name: 'Reyting',
                data: @json($points),
            }],
            chart: {
                type: 'line',
                height: 350,
                zoom: { enabled: false },
            },
            stroke: { curve: 'smooth' },
            title: { text: 'Судья рейтинг тарихи', align: 'left' },
            xaxis: {
                type: 'category',
                title: { text: 'Сана' },
            },
            yaxis: {
                min: 0,
                max: 100,
                title: { text: 'Reyting' },
            },
            tooltip: {
                x: {
                    format: 'dd.MM.yyyy'
                },
                y: {
                    formatter: val => val + ' балл'
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#ratingHistoryChart"), options);
        chart.render();
    });
</script>
