{{--@dd($judges_stages)--}}
<table style="width: 100%; border: 1px solid #d4d4d4; border-collapse: collapse;">
    <tbody class="p-2">
    <tr style="height: 35px;">
        <td style="width: 270.641px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="3">
            <p>{{$judge->codes}}</p>
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
                <strong>{{$judge->first_name}} {{$judge->middle_name}} {{$judge->last_name}}</strong></p>
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

            <p class="flex justify-between items-center block"><strong><u>Рейтинг:</u></strong>
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
            <p>Тошкент шаҳри, Янгиҳаёт тумани, 1А-даҳаси,</p>
            <p>2-уй, 41-хонадон</p>
            <p><strong><u>Турмуш ўртоғининг яшаш манзили:</u></strong></p>
            <p>Қашқадарё в., Китоб тумани, Оқсув кўчаси, 5-уй</p>
        </td>
    </tr>
    <tr style="height: 131px;">
        <td style="width: 330.641px; height: 238px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="4"
            rowspan="2">
            <p><strong><u>Оилавий аҳволи:</u></strong></p>
            <p>Оилали, 3 нафар фарзанди бор.</p>
            <p><strong><u>Отаси:</u></strong> Шарипов Рахмон Анварович, 1938 й.т., Қашқадарё в., вафот этган.</p>
            <p><strong><u>Онаси:</u></strong> Шарипова Нодира Расуловна 1946 й.т., Қашқадарё в., нафақада.</p>
            <p><strong><u>Турмуш ўртоғи:</u></strong> Исакова Гулнафис Ибрагимовна, 1997 й., Қашқадарё в., корхона
                ходими.</p>
        </td>
        <td style="width: 485px; height: 131px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><strong><u>Хусусий ажрим:</u></strong> 1 та</p>
            <p><strong><u>Хизмат текшируви:</u></strong> 2 та</p>
            <p><strong><u>Тасдиғини топган:</u></strong> 2 та</p>
            <p>1. 16.03.2023 йилда тугатилган</p>
            <p>2. 18.10.2024 йилда огоҳлантириш</p>
        </td>
    </tr>
    <tr style="height: 107px;">
        <td style="width: 485px; height: 107px; border: 1px solid #d4d4d4; padding-left: 15px;" colspan="2">
            <p><strong><u>Давлат мукофотлари:</u></strong> йўқ</p>
            <p><strong><u>Олий маълумоти:</u></strong></p>
            <p>2008 й. Самарқанд давлат университети</p>
            <p>2020 й. Судьялар олий мактаби (магистр)</p>
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
            <p>Раҳбарлик захираси курслари (2023 й.)</p>
            <p><strong><u>Илмий унвони:</u></strong> йўқ</p>
            <p><strong><u>Илмий даражаси:</u></strong> йўқ</p>
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
