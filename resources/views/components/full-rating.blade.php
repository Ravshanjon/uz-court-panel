<div class="card border-l-4 border-green-500 p-4 ont-medium text-sm border rounded-lg mb-5">
    <div class="flex justify-between grid-2">
                <span class="mb-4">
                        Судьянинг фаолияти самарадорлигини
                        электрон рейтинг баҳолаш натижаси
                         <span class="text-base-600 font-medium">
                                Баҳолаш даври:01.06.2024 - 30.04.2025
                         </span>
                   </span>
        <div class="text-3xl">
            {{$record->rating}}
        </div>
    </div>

</div>
<div class="flex gap-3 col-span-3">
    <div class="border rounded p-4  rounded-lg">
        <img src="{{ $record->image ? asset('storage/' . $record->image) : asset('image/default.jpg') }}" alt="Rasm"
             class="w-32 h-32 object-cover rounded">
    </div>
    <div>
            {{$record->last_name." ". $record->first_name." ". $record->middle_name}}

    </div>
    <div>3</div>
</div>
<div class="flex grid-cols-2 justify-between">
        <div class="w-full flex grid-cols-2 justify-between border align-middle">
                <div class="text-sm">Суд қарорларининг сифати:</div>
                <div class="bg-primary-500">50/50</div>
        </div>
        <div class="w-full flex grid-cols-2 justify-between border align-middle">
                <div class="text-sm">Суд қарорларининг сифати:</div>
                <div class="bg-primary-500">50/50</div>
        </div>
</div>



