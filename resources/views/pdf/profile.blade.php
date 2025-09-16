<!doctype html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<div class="relative overflow-x-auto sm:rounded-lg">
    <div class="flex justify-items-start">
        <div>
            <img src="{{ asset('storage/' . $judge->images) }}" alt="Sudya rasmi"
                 class="w-32 h-32 object-cover rounded-md">

        </div>
        <div>
            <h1>{{$judge->middle_name}} {{$judge->first_name}} {{$judge->last_name}}</h1>
        </div>
    </div>

    <table class="w-full text-sm text-left rtl:text-right text-blue-100 dark:text-blue-100">
        <thead class="text-xs text-white bg-gray-600 dark:text-white">
        <tr>
            <th scope="col" class="px-6 py-3">
                №
            </th>
            <th scope="col" class="px-6 py-3">
                Иш жойи
            </th>
            <th scope="col" class="px-6 py-3">
                Ишни бошлаган сана
            </th>
            <th scope="col" class="px-6 py-3">
                Тугатилган сана
            </th>
            <th scope="col" class="px-6 py-3">
                Стаж
            </th>
        </tr>
        </thead>
        <tbody>
        <tr class="border-b border-gray-50">
            <th scope="row" class="px-6 py-4 font-medium text-gray-600 whitespace-nowrap dark:text-blue-100">
                1
            </th>
            <td class="px-6 py-4">
                Silver
            </td>
            <td class="px-6 py-4">
                Laptop
            </td>
            <td class="px-6 py-4">
                $2999
            </td>

        </tr>
        </tbody>
    </table>
</div>
</body>
</html>



