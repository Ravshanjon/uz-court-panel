
<div class="box">
        <table class="border p-6">
            <tbody>
            <tr>
                <td colspan="3" width="269">
                    <p>{{$profile->code}}</p>
                </td>
                <td colspan="2" width="260">
                    <p><em>17.01.2025 й. ҳолатига</em></p>
                </td>
                <td rowspan="4" width="141" style="vertical-align: top;height: 150px">
                    <table width="100%">
                        <tbody>
                        <tr>
                            <td class="border-none">
                                @if($profile->image)
                                    <img src="{{ asset('storage/' . $profile->image) }}" alt="Profile Image" class="border-none">                                @else
                                    <p class="text-gray-500">No Image Available</p>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5" width="528">
                    <p style="text-transform: uppercase;" class="">
                        <strong>
                            {{ $profile->name }}
                        </strong></p>
                </td>
            </tr>
            <tr>
                <td colspan="5" width="528">
                    <p>Тошкент шаҳри фуқаролик ишлари бўйича Яккасарой туман судининг раиси</p>
                    <p>№ 1946, 18.07.2023 й. - 17.07.2028 й.</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" width="269">
                    <p><strong><u>Судьялик ваколат муддати: </u></strong></p>
                    <p>10 йил,</p>
                    <p>18.07.2023 й. - 17.07.2033 й.</p>
                    <p><strong><u>Малака даражаси:</u></strong> 2</p>
                </td>
                <td colspan="2" width="260">
                    <p><strong><u>Умумий юридик стажи:</u></strong><strong> <br /> </strong>16 йил 2 ой</p>
{{--                    <p><strong><u>Судьялик стажи: </u></strong>{{ $profile->duration_years_months }}<strong><u>--}}
                                <br>Рейтинг:</u></strong> 82, яхши</p>
                </td>
            </tr>
            <tr>
                <td colspan="2" width="177">
                    <p><strong><u>Туғилган йили:</u></strong></p>
                    <p>06.10.1986</p>
                    <p><strong><u>Ёши:</u></strong> 38</p>
                </td>
                <td colspan="2" width="155">
                    <p><strong><u>Туғилган жойи:</u></strong></p>
                    <p>Қашқадарё вилояти</p>
                </td>
                <td colspan="2" width="336">
                    <p><strong><u>Паспорт:</u></strong> AD0197754</p>
                    <p><strong><u>ПИНФЛ:</u></strong> 31312852700020</p>
                    <p><strong><u>Тел.:</u></strong> +99&nbsp;077-25-72</p>
                </td>
            </tr>
            <tr>
                <td colspan="2" width="177">
                    <p><strong><u>Миллати:</u></strong></p>
                    <p>ўзбек</p>
                    <p><strong><u>Чет тили: </u></strong></p>
                    <p>йўқ</p>
                </td>
                <td colspan="4" width="492">
                    <p><strong><u>Яшаш манзили:</u></strong></p>
                    <p>Тошкент шаҳри, Янгиҳаёт тумани, 1А-даҳаси,</p>
                    <p>2-уй, 41-хонадон</p>
                    <p><strong><u>Турмуш ўртоғининг яшаш манзили:</u></strong></p>
                    <p>Қашқадарё в., Китоб тумани, Оқсув кўчаси, 5-уй</p>
                </td>
            </tr>
            <tr>
                <td colspan="4" rowspan="2" width="333">
                    <p><strong><u>Оилавий аҳволи:</u></strong></p>
                    <p>Оилали, 3 нафар фарзанди бор.</p>
                    <p><strong><u>Отаси:</u></strong>&nbsp;Шарипов Рахмон Анварович, 1938 й.т., Қашқадарё в., вафот этган.</p>
                    <p><strong><u>Онаси:</u></strong>&nbsp;Шарипова Нодира Расуловна <br /> 1946 й.т., Қашқадарё в., нафақада.</p>
                    <p><strong><u>Турмуш ўртоғи:</u></strong> Исакова Гулнафис Ибрагимовна, 1997 й., Қашқадарё в., корхона ходими.</p>
                </td>
                <td colspan="2" width="336">
                    <p><strong><u>Хусусий ажрим:</u></strong> 1 та</p>
                    <p><strong><u>Хизмат текшируви:</u></strong> 2 та</p>
                    <p><strong><u>Тасдиғини топган:</u></strong> 2 та</p>
                    <p>1. 16.03.2023 йилда тугатилган</p>
                    <p>2. 18.10.2024 йилда огоҳлантириш</p>
                </td>
            </tr>
            <tr>
                <td colspan="2" width="336">
                    <p><strong><u>Давлат мукофотлари:</u></strong> йўқ</p>
                    <p><strong><u>Олий маълумоти:</u></strong></p>
                    <p>2008 й. Самарқанд давлат университети</p>
                    <p>2020 й. Судьялар олий мактаби (магистр)</p>
                </td>
            </tr>
            <tr>
                <td colspan="4" width="333">
                    <p><strong><u>Меҳнатга қобилиятсизлик даври:</u></strong></p>
                    <p>1. 16.06.2023 й. - 20.06.2023 й. (4 кун)</p>
                    <p>2. 17.11.2024 й. - 25.11.2024 й. (8 кун)</p>
                </td>
                <td colspan="2" width="336">
                    <p><strong><u>Малака оширганлиги:</u></strong></p>
                    <p>Раҳбарлик захираси курслари (2023 й.)<strong><u> Илмий унвони:</u> </strong>йўқ</p>
                    <p><strong><u>Илмий даражаси:</u></strong> йўқ</p>
                </td>
            </tr>
            <tr>
                <td colspan="6" width="669">
                    <p class="text-center"><strong>МЕҲНАТ ФАОЛИЯТИ</strong></p>
                </td>
            </tr>
            <tr>
                <td width="150">
                    <p>12.07.2018 й.</p>
                    <p>22.08.2022 й.</p>
                </td>
                <td colspan="5" width="561">
                    <p>Тошкент шаҳар фуқаролик ишлари бўйича</p>
                    <p>Мирзо Улуғбек туманлараро судининг судьяси</p>
                </td>
            </tr>
            <tr>
                <td width="108">
                    <p>23.08.2022 й.</p>
                    <p>15.06.2023 й.</p>
                </td>
                <td colspan="5" width="561">
                    <p>Тошкент шаҳар фуқаролик ишлари бўйича</p>
                    <p>Шайхонтоҳур туманлараро суди раисининг ўринбосари</p>
                </td>
            </tr>
            <tr>
                <td width="108">
                    <p>16.06.2023 й.</p>
                    <p>12.07.2023 й.</p>
                </td>
                <td colspan="5" width="561">
                    <p>Тошкент шаҳар судининг фуқаролик ишлари бўйича судьяси</p>
                </td>
            </tr>
            <tr>
                <td width="108">
                    <p>18.07.2023 й.</p>
                </td>
                <td colspan="5" width="561">
                    <p>Тошкент шаҳри фуқаролик ишлари бўйича Яккасарой туман судининг раиси</p>
                </td>
            </tr>
            </tbody>
        </table>

        <p>&nbsp;</p>
    </div>
<style>
    .border-none{
        border: none!important;
    }
   .box table td{
        border: 1px solid #ededed;
        padding: 4px;
    }
</style>
