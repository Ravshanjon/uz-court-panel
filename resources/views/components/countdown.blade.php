@props(['start', 'end'])

@php
    $endTime = \Carbon\Carbon::parse($end);
    $now = now();
    $daysLeft = $now->diffInDays($endTime, false);
    $color = 'bg-green-100 text-green-800';

    if ($daysLeft <= 60 && $daysLeft > 30) {
        $color = 'bg-yellow-100 text-yellow-800';
    } elseif ($daysLeft <= 30) {
        $color = 'bg-red-100 text-red-800';
    }
@endphp

<div
    x-data="countdownComponent('{{ $endTime->format('YYYY-MM-DD') }}')"
    x-init="init"
    class="p-2 rounded-md {{ $color }}"
>
    <x-filament::badge>
        <span x-text="timer"></span>
    </x-filament::badge>

</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/duration.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
        <script>
            dayjs.extend(dayjs_plugin_duration);
            dayjs.extend(dayjs_plugin_relativeTime);
            dayjs.extend(dayjs_plugin_customParseFormat);

            function countdownComponent(endDateString) {
                return {
                    timer: '',
                    endDate: dayjs(endDateString, 'YYYY-MM-DD'),
                    init() {
                        this.updateCountdown();
                        setInterval(() => this.updateCountdown(), 1000);
                    },
                    updateCountdown() {
                        const now = dayjs();
                        if (now.isAfter(this.endDate)) {
                            this.timer = '⏰ Tugagan';
                            return;
                        }

                        let years = this.endDate.diff(now, 'year');
                        let temp = now.add(years, 'year');

                        let months = this.endDate.diff(temp, 'month');
                        temp = temp.add(months, 'month');

                        let days = this.endDate.diff(temp, 'day');

                        this.timer = `${years > 0 ? years + ' yil ' : ''}${months > 0 ? months + ' oy ' : ''}${days} kun`;
                    }
                };
            }
        </script>
    @endpush
@endonce
