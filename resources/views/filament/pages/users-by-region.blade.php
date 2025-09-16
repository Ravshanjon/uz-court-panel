<x-filament::page>
    <x-filament::card>
        @foreach ($groupedUsers as $region)

                <div x-data="{ open: false }">
                    <button @click="open = !open"
                            class="w-full text-left p-2 border rounded-md mb-4 text-sm font-medium text-gray-700 dark:text-gray-200">
                        {{ $region->name }}
                    </button>

                    <div x-show="open">
                        <table class="w-full border-md  text-sm text-left rtl:text-right text-gray-500 border">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">F.I.Sh</th>
                                <th scope="col" class="px-6 py-3">Login</th>
                                <th scope="col" class="px-6 py-3">Holati</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($region->users as $index => $user)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ $user->name ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $user->email ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $user->username ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('filament.admin.resources.users.edit', $user->id) }}"
                                           class="text-blue-600 hover:underline text-sm">

                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
</svg>


                                        </a>

{{--                                        <form action="{{ route('filament.admin.resources.users.destroy', $user->id) }}"--}}
{{--                                              method="POST" class="inline">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                            <button type="submit"--}}
{{--                                                    onclick="return confirm('Haqiqatan o‘chirmoqchimisiz?')"--}}
{{--                                                    class="text-red-600 hover:underline text-sm ml-2">O‘chirish</button>--}}
{{--                                        </form>--}}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->is_active)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Faol</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Nofaol</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center italic text-gray-400 py-4">Sudya mavjud emas</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

        @endforeach
            </x-filament::card>
</x-filament::page>
