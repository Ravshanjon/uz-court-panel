
<button
    onclick="toggleColumns()"
    class="mb-4 align-right bg-primary-500 text-white text-sm bg-gray-50 px-3 py-1 rounded mb-2">
    Паспорт ва ПИНФЛни кўриш
</button>
<table class="table-auto w-full border border-gray-300 text-sm">
    <thead class="bg-gray-100">
    <tr>
        <th class="border px-2 py-1">Қариндошлиги</th>
        <th class="border px-2 py-1">Ф.И.Ш.</th>
        <th class="border px-2 py-1">Туғилган йили ва жойи</th>
        <th class="border px-2 py-1">Иш жойи ва лавозими</th>
        <th class="border px-2 py-1">Яшаш жойи</th>
        <th class="border px-2 py-1 toggle-col hidden">Паспорти</th>
        <th class="border px-2 py-1 toggle-col hidden">ПИНФЛ</th>
    </tr>
    </thead>
    <tbody>
    @foreach($family as $members)
        <tr class="text-center">
            <td class="border px-2 py-1 font-semibold">Отаси</td>
            <td class="border px-2 py-1">{{$members->father_name}}</td>
            <td>{{ \Carbon\Carbon::parse($members->father_brith_date)->format('d.m.Y') }}</td>
            <td class="border px-2 py-1">Пенсияда, илгари Гулистон хўжалигининг ишчиси бўлган</td>
            <td class="border px-2 py-1">Гулистон тумани, Гулистон кўчаси, 100-уй, 7-хонадон</td>
            <td class="border px-2 py-1 toggle-col hidden">AD1235645</td>
            <td class="border px-2 py-1 toggle-col hidden">98754563215456</td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    function toggleColumns() {
        document.querySelectorAll('.toggle-col').forEach(col => {
            col.classList.toggle('hidden');
        });
    }
</script>
