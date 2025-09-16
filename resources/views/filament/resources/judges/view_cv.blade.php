{{--@dd($judges_stages)--}}
<table style="width: 100%; border: 1px solid #d4d4d4; border-collapse: collapse;">
    <tbody class="p-2">
    <tr style="height: 35px;">
        <td style="width: 270.641px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="3">
            Коди:<p>{{$judge->codes}}</p>
        </td>
        <td style="width: 412px; height: 35px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><em>{{$currentDate = \Illuminate\Support\Carbon::now()->format('d.m.Y')}} йил ҳолатига</em></p>
        </td>
        <td style="width: 133px; height: 236px; border: 1px solid #d4d4d4;" rowspan="4">
            <table width="100%">
                <tbody>
                <tr>
                    <td class="text-center">
                        <img src="{{ asset('storage/' . $judge->image) }}" alt="Judge Image" class="">
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr style="height: 35px;">
        <td style="width: 682.641px; height: 35px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="5">
            <p style="text-transform: uppercase;">
                <strong>{{$judge->middle_name}} {{$judge->first_name}} {{$judge->last_name}}</strong></p>
        </td>
    </tr>
    <tr style="height: 59px;">
        <td style="width: 682.641px; height: 59px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="5">

            @if($active_position_name)
                <p>{{ $active_position_name }}</p>
            @else
                <p></p>
            @endif

            @if($active_document_type_name)
                <p>Ҳужжат тури: {{ $active_document_type_name }}</p>
            @endif
        </td>
    </tr>
    <tr style="height: 107px;">
        <td style="width: 270.641px; height: 107px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="3">
            <p><strong><u>Судьялик ваколат муддати: </u></strong></p>
            <p>10 йил,</p>
            <p>18.07.2023 й. - 17.07.2033 й.</p>
            <p><strong><u>Малака даражаси:</u></strong> 2</p>
        </td>
        <td style="width: 412px; height: 107px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><strong><u>Умумий юридик стажи:</u></strong></p>

            <!-- Loop through all judge stages if is_judge_stage is 0 -->
            @if($is_judge_stage == 0)
                @foreach ($judges_stages as $stage)
                    <p>{{ $stage->counter }}</p>  <!-- Display all judge stages' counter (e.g. "2 йил, 7 ой, 6 кун") -->
                @endforeach
            @elseif($is_judge_stage == 1)
                <!-- Show only the first judge stage if is_judge_stage is 1 -->
                @if($judges_stages->isNotEmpty())
                    <p>{{ $judges_stages->first()->counter }}</p>  <!-- Display only the first judge stage's counter -->
                @endif
            @endif
            @if($judges_stages->isNotEmpty())
                <p><strong><u>Судьялик стажи: </u></strong>
                    @foreach ($judges_stages as $stage)
                        {{ $stage->counter }}
                        <br>
                    @endforeach
                </p>
            @endif

            <p class="flex justify-between items-center block"><strong>Рейтинг:</strong>
            <div class="text-lg font-semibold">{{$judge->rating}}
                @if($judge->rating >= 86 && $judge->rating <= 200)
                    намунали
                @elseif($judge->rating >= 71 && $judge->rating <= 85)
                    яхши
                @elseif($judge->rating >= 56 && $judge->rating <= 70)
                    қониқарли
                @elseif($judge->rating <= 55)
                    <span
                        class="bg-custom-600 text-danger-600 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-danger-800 dark:text-pink-300">қониқарсиз</span>
                @else
                    <span class="badge badge-secondary">N/A</span>
                    <!-- Optional fallback if rating is null or not set -->
                @endif
            </div>

            </p>
        </td>
    </tr>
    <tr style="height: 83px;">
        <td style="width: 185.641px; height: 83px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><strong><u>Туғилган йили:</u></strong></p>
            <p>{{ \Carbon\Carbon::parse($judge->birth_date)->format('d.m.Y') }}</p>
            <p><strong><u>Ёши:</u></strong> {{$judge->age}}</p>
        </td>
        <td style="width: 145px; height: 83px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><strong><u>Туғилган жойи:</u></strong></p>
            <p>{{$judge->region->name }}</p>
        </td>
        <td style="width: 485px; height: 83px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><strong><u>Паспорт:</u></strong> {{$judge->passport_name}}</p>
            <p><strong><u>ПИНФЛ:</u></strong> {{$judge->pinfl }}</p>
            <p><strong><u>Тел.:</u></strong> {{$judge->phone}}</p>
        </td>
    </tr>
    <tr style="height: 131px;">
        <td style="width: 185.641px; height: 131px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><strong><u>Миллати:</u></strong></p>
            <p>{{$judge->nationality->name}}</p>
            <p><strong><u>Чет тили: </u></strong></p>
            <p>йўқ</p>
        </td>
        <td style="width: 630px; height: 131px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="4">
            <p><strong><u>Яшаш манзили:</u></strong></p>
            <p>{{$judge->address}}</p>

            <p><strong><u>Турмуш ўртоғининг яшаш манзили:</u></strong></p>
            @forelse ($judge->familiesParents7 as $member)
                <p>
                    {{ $member->live_place ?? '—' }}
                </p>
            @empty
                <p>—</p>
            @endforelse
        </td>
    </tr>
    <tr style="height: 131px;">
        <td style="width: 330.641px; height: 238px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="4"
            rowspan="2">
            <p><strong><u>Оилавий аҳволи:</u></strong></p>
            <p>Оилали, {{ \App\Models\Family::where('judge_id', $judge->id)->whereIn('parents_id', [8, 9])->count() }}
                нафар фарзанди бор.</p>
            @php
                $members = \App\Models\Family::with('parents:id,name')
                    ->where('judge_id', $judge->id)
                    ->whereIn('parents_id', [1,2,7])
                    ->orderByRaw('FIELD(parents_id, 1,2,7)')
                    ->orderBy('name')
                    ->get(['id','name','live_place','parents_id','judge_id']);
            @endphp

            @forelse($members as $m)
                <p><strong>{{ $m->parents?->name ?? '—' }}</strong> — {{ $m->name ?? '—' }}
                    — {{ $m->live_place ?? '—' }}</p>
            @empty
            @endforelse
            @php
                $families = collect($judge->families ?? []);
            @endphp

            @forelse ($families as $member)
                @php
                    $parentName = is_iterable($member->parents ?? null)
                        ? (optional($member->parents->first())->name ?? ' ')
                        : ($member->parents?->name ?? ' ');

                    $note = '';
                    if (!empty($member->is_deceased)) {
                        $note = 'вафот этган';
                    } elseif (!empty($member->working_place)) {
                        $note = $member->working_place;
                    }

                    $birth = !empty($member->birth_date)
                        ? \Carbon\Carbon::parse($member->birth_date)->format('Y') . ' й.'
                        : '';

                    $region = $member->birth_place ?? '';
                @endphp

                <p>
                    <strong><u>{{ $parentName }}:</u></strong>
                    {{ $member->name ?? '—' }}
                    @if($birth)
                        , {{ $birth }}
                    @endif
                    @if($region)
                        , {{ $region }}
                    @endif
                    @if($note)
                        , {{ $note }}
                    @endif
                </p>
            @empty
                <p>—</p>
            @endforelse
        </td>
        <td style="width: 485px; height: 131px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><strong><u>Хусусий ажрим:</u></strong>
                {{ \App\Models\PrivateAward::where('judges_id', $judge->id)->count() }} та
            </p>

            <p><strong><u>Хизмат текшируви:</u></strong>
                {{ \App\Models\service_inspection::where('judge_id', $judge->id)->count() }} та
            </p>
            <p><strong><u>Тасдиғини топган:</u></strong>
                {{ \App\Models\service_inspection::where('judge_id', $judge->id)->where('inspection_cases_id', 1)->count() }}
                та
            </p>
            <ul>
                <li></li>
            </ul>
            <p>1. 16.03.2023 йилда тугатилган</p>
            <p>2. 18.10.2024 йилда огоҳлантириш</p>
        </td>
    </tr>
    <tr style="height: 107px;">
        <td style="width: 485px; height: 107px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><strong><u>Давлат мукофотлари:</u></strong> йўқ</p>
            <p><strong><u>Олий маълумоти:</u></strong></p>
            <p>{{ $judge->university?->name ?? '—' }}</p>

        </td>
    </tr>
    <tr style="height: 83px;">
        <td style="width: 330.641px; height: 83px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="4">
            <p><strong><u>Меҳнатга қобилиятсизлик даври:</u></strong></p>
            <p>1. 16.06.2023 й. - 20.06.2023 й. (4 кун)</p>
            <p>2. 17.11.2024 й. - 25.11.2024 й. (8 кун)</p>
        </td>
        <td style="width: 485px; height: 83px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><strong><u>Малака оширганлиги:</u></strong></p>
            <p>{{ \App\Models\Judges::whereKey($judge->id)->value('special_education') ?? '—' }}</p>

        </td>
    </tr>
    <tr style="height: 35px;">
        <td style="width: 815.641px; height: 35px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="6">
            <p><strong>СУДЬЯЛИК ФАОЛИЯТИ</strong></p>
        </td>
    </tr>
    <tr style="height: 59px;">
        <td style="width: 175.641px; height: 59px; border: 1px solid #d4d4d4; padding-left: 15px;">
            <p>{{$judge->positions}}</p>
            <p>22.08.2022 й.</p>
        </td>
        <td style="width: 640px; height: 59px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="5">
            <p>Тошкент шаҳар фуқаролик ишлари бўйича</p>
            <p>Мирзо Улуғбек туманлараро судининг судьяси</p>
        </td>
    </tr>

    </tbody>
</table>
