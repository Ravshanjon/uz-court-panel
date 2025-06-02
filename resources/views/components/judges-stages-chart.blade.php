{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', async function () {--}}
{{--            await loadScript('https://cdn.jsdelivr.net/npm/cal-heatmap@4.0.0/dist/cal-heatmap.umd.min.js');--}}

{{--            const cal = new CalHeatmap();--}}

{{--            cal.paint({--}}
{{--                range: 12,--}}
{{--                domain: 'month',--}}
{{--                subDomain: 'day',--}}
{{--                data: {--}}
{{--                    source: async () => {--}}
{{--                        const res = await fetch('{{ route('heatmap.data', ['judges' => $record->id]) }}');--}}
{{--                        return await res.json();--}}
{{--                    },--}}
{{--                    x: 'date',--}}
{{--                    y: d => d.count,--}}
{{--                },--}}
{{--                scale: {--}}
{{--                    color: {--}}
{{--                        type: 'quantize',--}}
{{--                        scheme: 'Greens',--}}
{{--                        domain: [0, 5],--}}
{{--                    },--}}
{{--                },--}}
{{--                domainLabelFormat: date =>--}}
{{--                    date.toLocaleString('default', { month: 'short' }),--}}
{{--            });--}}

{{--            function loadScript(src) {--}}
{{--                return new Promise((resolve, reject) => {--}}
{{--                    const script = document.createElement('script');--}}
{{--                    script.src = src;--}}
{{--                    script.onload = resolve;--}}
{{--                    script.onerror = reject;--}}
{{--                    document.head.appendChild(script);--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}

{{--    </script>--}}
