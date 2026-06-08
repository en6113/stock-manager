<x-app-layout>

<body class="bg-gray-50 p-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">メニューの編集</h1>

        <form action="{{ route('menus.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">メニュー名</label>
                    <input type="text" name="name" value="{{ old('name', $menu->name) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">総カロリー (kcal)</label>
                    <input type="number" name="calories" value="{{ old('calories', $menu->calories) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">カテゴリ</label>
                    <select name="dish_category"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50">
                        <option value="1" {{ $menu->dish_category == 1 ? 'selected' : '' }}>主菜 (main)</option>
                        <option value="2" {{ $menu->dish_category == 2 ? 'selected' : '' }}>副菜 (side)</option>
                        <option value="3" {{ $menu->dish_category == 3 ? 'selected' : '' }}>汁物 (soup)</option>
                        <option value="4" {{ $menu->dish_category == 4 ? 'selected' : '' }}>おやつ (snack)</option>
                    </select>
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-3 text-gray-700">使用する食材・調味料の編集</h2>

                <div id="item-container" class="space-y-3">
                    @forelse($menu->items as $pivotItem)

                        <div class="flex gap-4 items-end item-row bg-gray-50 p-3 rounded">
                            <div class="flex-1 max-w-md">
                                <label class="block text-xs font-medium text-gray-600">アイテム（検索）</label>
                                <input list="item-list" name="item_ids[]" value="{{ $pivotItem->name }}"
                                    class="mt-1 block w-full rounded border-gray-300 p-1.5 bg-white shadow-sm">
                            </div>

                            <div class="w-48">
                                <label class="block text-xs font-medium text-gray-600 mb-1">必要量</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="required_amounts[]"
                                        value="{{ old('required_amounts.' . $loop->index, $pivotItem->pivot->required_amount) }}"
                                        class="block w-24 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="1" step="0.1">
                                    <span class="unit-display text-sm font-medium text-gray-600 min-w-[24px]">
                                        {{ $pivotItem->unit }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="button" class="remove-btn text-red-500 hover:text-red-700 font-bold">削除</button>
                            </div>
                        </div>
                    @empty
                        <div class="flex gap-4 items-center item-row bg-gray-50 p-3 rounded">
                            <div class="flex-1 max-w-md">
                                <label class="block text-xs font-medium text-gray-600">アイテム（検索）</label>
                                <input list="item-list" name="item_ids[]"
                                    class="mt-1 block w-full rounded border-gray-300 p-1.5 bg-white shadow-sm">
                            </div>
                            <div class="w-48">
                                <label class="block text-xs font-medium text-gray-600">必要量 </label>
                                    <div class="flex items-center gap-2"></div>
                                        <input type="number" name="required_amounts[]"
                                            class="mt-1 block w-full rounded border-gray-300 p-1.5 bg-white shadow-sm" min="1" step="0.1">
                                        <span class="unit-display text-sm font-medium text-gray-600 min-w-[24px]"></span>
                                    </div>
                            </div>
                            <div class="pt-4">
                                <button type="button"
                                    class="remove-btn text-red-500 hover:text-red-700 font-bold hidden">削除</button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <datalist id="item-list">
                    @foreach($registered_items as $item)
                        <option value="{{ $item->name }}" data-id="{{ $item->id }}" data-unit="{{ $item->unit }}">ID:{{ $item->id }}</option>
                    @endforeach
                </datalist>

                <button type="button" id="add-item-btn"
                    class="mt-4 px-4 py-2 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
                    + アイテム枠を追加
                </button>
            </div>

            <div class="flex justify-end gap-4 mt-8">
                <a href="{{ route('menus.index') }}"
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">キャンセル</a>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 shadow">更新する</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('item-container').addEventListener('input', function (e) {
                if (e.target.name === 'item_ids[]') {
                    const input = e.target;
                    const selectedValue = input.value; 
                    const option = document.querySelector(`#item-list option[value="${selectedValue}"]`);
                    const unitSpan = input.closest('.item-row').querySelector('.unit-display');

                    if (option) {
                        unitSpan.textContent = option.dataset.unit;
                    } else {
                        unitSpan.textContent = '';
                    }
                }
            });
        document.getElementById('add-item-btn').addEventListener('click', function () {
            const container = document.getElementById('item-container');
            const firstRow = container.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelector('.remove-btn').classList.remove('hidden');
            container.appendChild(newRow);
            toggleDeleteButtons();
        });

        document.getElementById('item-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-btn')) {
                e.target.closest('.item-row').remove();
                toggleDeleteButtons();
            }
        });

        function toggleDeleteButtons() {
            const rows = document.querySelectorAll('.item-row');
            rows.forEach((row) => {
                const btn = row.querySelector('.remove-btn');
                btn.classList.toggle('hidden', rows.length <= 1);
            });
        }
        toggleDeleteButtons();
    </script>
</body>

</x-app-layout>