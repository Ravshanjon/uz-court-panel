<x-filament-panels::page>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="space-y-4">
        <label class="font-semibold text-sm">Саволингизни ёзинг:</label>
        <textarea id="user-input" rows="5" class="text-sm border-gray-200 w-full p-3 rounded" placeholder="OpenAI'га савол..."></textarea>

        <button id="submit" class="bg-primary-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Юбориш
        </button>
        <p id="response" class="mt-4 text-sm p-4 bg-white card shadow-md rounded-md">

        </p>
    </div>

    <script>
        document.getElementById('submit').addEventListener('click', async () => {
            const inputText = document.getElementById('user-input').value;
            const responseDiv = document.getElementById('response');
            responseDiv.textContent = '⏳ Юкланмоқда...';

            try {
                const response = await fetch('/api/ask', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ text: inputText })
                });

                if (!response.ok) {
                    throw new Error(`HTTP ошибка! Статус: ${response.status}`);
                }

                const data = await response.json();
                responseDiv.textContent = data.response;
            } catch (error) {
                responseDiv.textContent = '❌ Хатолик: ' + error.message;
            }
        });
    </script>
</x-filament-panels::page>
