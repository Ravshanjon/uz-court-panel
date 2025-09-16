<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Судья маълумотлари</title>
    <style>
        @page {
            size: A4 landscape;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: #333;
        }

        h1, h2, h3 {
            margin: 0 0 10px 0;
        }

        .rating-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .rating-score {
            font-size: 60px;
            font-weight: 800;
            color: #0a84d2;
            text-align: center;
        }

        .fieldset {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .fieldset legend {
            font-weight: bold;
            padding: 0 10px;
        }

        .judge-info {
            display: flex;
            justify-content: space-between;
            gap: 40px;
        }

        .judge-details {
            display: flex;
        }

        .judge-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 100px;
            border: 1px solid #ccc;
        }

        .judge-text {
            margin-left: 20px;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-blue {
            background: #e0f0ff;
            color: #0a84d2;
        }

        .badge-red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-green {
            background: #dcfce7;
            color: #15803d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f3f4f6;
            text-align: left;
        }

        .table-title {
            margin-top: 20px;
            font-weight: bold;
            color: #0a84d2;
        }

    </style>
</head>
<body>

{{-- Рейтинг --}}
<div class="rating-box">
    <div>
        <h2>Судьянинг фаолияти самарадорлигини электрон рейтинг баҳолаш натижаси</h2>
        <strong>Баҳолаш даври:</strong> 01.05.2024 - 30.04.2025
    </div>
    <div class="rating-score">
        {{ $judge->rating ?? '—' }}
        <div style="font-size: 18px; margin-top: 5px;">{{ $grade ?? '' }}</div>
    </div>
</div>

{{-- Судья ҳақида --}}
<div class="fieldset">
    <legend>Судья маълумотлари</legend>
    <div class="judge-info">
        <div class="judge-details">
            <img src="{{ public_path('storage/' . ($judge->image ?? 'image/default.jpg')) }}" class="judge-image" alt="Судья сурати">
            <div class="judge-text">
                <h3>{{ trim("{$judge->middle_name} {$judge->first_name} {$judge->last_name}") ?: 'Nomaʼlum' }}</h3>
                <p><strong>Лавозими:</strong> {{ $judge->current_or_future_position_name ?? 'Nomaʼlum' }}</p>
                <p><strong>Туғилган сана:</strong> {{ $judge->birth_date ? \Carbon\Carbon::parse($judge->birth_date)->format('d.m.Y') : 'Nomaʼlum' }}</p>
                <p><strong>Судьялик стажи:</strong> {{ $judge->judges_stages->last()?->counter ?? 'Маълумот йўқ' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Фаолият кўрсаткичлари --}}
@php
    $entry = $judge->judge_activity_entries;
    $fields = [
        'criminal_first_instance_avg' => 'Жиноят иши 1-инстанцияда ўртача иш ҳажми',
        'criminal_appeal_avg' => 'Жиноят иши апелляцияда ўртача иш ҳажми',
        'criminal_cassation_avg' => 'Жиноят иши кассацияда ўртача иш ҳажми',
        'admin_violation_first_instance_avg' => 'Маъмурий ҳуқуқбузарлик 1-инстанцияда ўртача иш ҳажми',
        'admin_violation_appeal_avg' => 'Маъмурий ҳуқуқбузарлик апелляцияда ўртача иш ҳажми',
        'admin_violation_cassation_avg' => 'Маъмурий ҳуқуқбузарлик кассацияда ўртача иш ҳажми',
        'materials_first_instance_avg' => 'Материаллар 1-инстанцияда ўртача иш ҳажми',
        'materials_appeal_avg' => 'Материаллар апелляцияда ўртача иш ҳажми',
        'materials_cassation_avg' => 'Материаллар кассацияда ўртача иш ҳажми',
        'civil_appeal_avg' => 'Фуқаролик иши апелляцияда ўртача иш ҳажми',
        'civil_cassation_avg' => 'Фуқаролик иши кассацияда ўртача иш ҳажми',
        'economic_first_instance_avg' => 'Иқтисодий иш 1-инстанцияда ўртача иш ҳажми',
        'economic_appeal_avg' => 'Иқтисодий иш апелляцияда ўртача иш ҳажми',
        'economic_cassation_avg' => 'Иқтисодий иш кассацияда ўртача иш ҳажми',
        'administrative_case_first_instance_avg' => 'Маъмурий иш 1-инстанцияда ўртача иш ҳажми',
        'administrative_case_appeal_avg' => 'Маъмурий иш апелляцияда ўртача иш ҳажми',
        'administrative_case_cassation_avg' => 'Маъмурий иш кассацияда ўртача иш ҳажми',
        'forum_topics_count' => 'Форум мавзулари сони',
        'forum_comments_count' => 'Форум изоҳлари сони',
    ];
@endphp

<div class="fieldset">
    <legend>Фаолият кўрсаткичлари</legend>
    <table>
        <thead>
        <tr>
            <th>Кўрсаткич</th>
            <th>Қиймат</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($fields as $key => $label)
            @if (($entry?->$key ?? 0) > 0)
                <tr>
                    <td>{{ $label }}</td>
                    <td><span class="badge badge-blue">{{ $entry->$key }} та</span></td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>

{{-- TODO: Keyingi bo‘limlar — Sud qarorlari sifati, Одоб ва масъулият, Bonuslar — alohida qilish mumkin --}}
{{-- Agar xohlasangiz, keyingi javobda ularni ham davom ettirib beraman --}}

</body>
</html>
