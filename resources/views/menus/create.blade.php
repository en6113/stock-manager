<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-6xl py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 leading-tight">給食メニュー登録</h1>
            </div>
            {{-- 別途食材を作成する画面へのリンク --}}
            <a href="{{ route('items.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 text-sm">
                ＋ マスタに新しい食材を追加
            </a>
        </div>

        <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('menus.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">メニュー名</label>
                        <input type="text" name="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50" required
                            placeholder="例：ハンバーグ">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">カテゴリ</label>
                        <select name="dish_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">何人前</label>
                        <input type="number" name="servings" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50" value="1" min="1" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">総カロリー (kcal/人)</label>
                        <input type="number" name="calories"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50" placeholder="任意">
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-3 text-gray-700">使用する食材</h2>
                    <div id="item-container" class="space-y-3">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="flex gap-4 items-end item-row bg-gray-50 p-3 rounded">
                                <div class="flex-1 max-w-md">
                                    <label class="block text-xs font-medium text-gray-600">アイテム（検索）</label>
                                    <input list="item-list" name="item_ids[]"
                                        class="mt-1 block w-full rounded border-gray-300 p-1.5 bg-white shadow-sm" placeholder="キーワードを入力して選択">
                                </div>

                                <div class="w-48"> <label class="block text-xs font-medium text-gray-600 mb-1">必要量</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" name="required_amounts[]"
                                            class="block w-24 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="1" step="0.1">
                                        <span class="unit-display text-sm font-medium text-gray-600 min-w-[24px]"></span>
                                    </div>
                                </div>

                                <div>
                                    <button type="button"
                                        class="remove-btn text-red-500 hover:text-red-700 font-bold p-1.5 hidden text-sm">削除</button>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <datalist id="item-list">
                        @foreach($registered_items as $item)
                            <option value="{{ $item->name }}" data-id="{{ $item->id }}" data-unit="{{ $item->unit }}">
                                ID: {{ $item->id }} </option>
                        @endforeach
                    </datalist>

                    <button type="button" id="add-item-btn"
                        class="mt-4 px-4 py-2 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
                        + 食材枠を追加
                    </button>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-3 text-gray-700">使用する調味料</h2>
                    <div id="item-container" class="space-y-3">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="flex gap-4 items-end item-row bg-gray-50 p-3 rounded">
                                <div class="flex-1 max-w-md">
                                    <label class="block text-xs font-medium text-gray-600">アイテム（検索）</label>
                                    <input list="item-list" name="item_ids[]"
                                        class="mt-1 block w-full rounded border-gray-300 p-1.5 bg-white shadow-sm" placeholder="キーワードを入力して選択">
                                </div>

                                <div class="w-48"> <label class="block text-xs font-medium text-gray-600 mb-1">必要量</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" name="required_amounts[]"
                                            class="block w-24 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="1" step="0.1">
                                        <span class="unit-display text-sm font-medium text-gray-600 min-w-[24px]"></span>
                                    </div>
                                </div>

                                <div>
                                    <button type="button"
                                        class="remove-btn text-red-500 hover:text-red-700 font-bold p-1.5 hidden text-sm">削除</button>
                                </div>
                            </div>
                        @endfor
                    </div>
                
                    <datalist id="item-list">
                        @foreach($registered_items as $item)
                            <option value="{{ $item->name }}" data-id="{{ $item->id }}" data-unit="{{ $item->unit }}">
                                ID: {{ $item->id }} </option>
                        @endforeach
                    </datalist>
                
                    <button type="button" id="add-item-btn"
                        class="mt-4 px-4 py-2 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
                        + 調味料枠を追加
                    </button>
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('menus.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">戻る</a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow">登録する</button>
                </div>
            </form>
        </div>

        <script>
            // 食材が選ばれたら単位を表示する
            document.getElementById('item-container').addEventListener('input', function (e) {
                if (e.target.name === 'item_ids[]') {
                    const input = e.target;

                    const selectedValue = input.value; // 入力された値（ID）

                    const option = document.querySelector(`#item-list option[value="${selectedValue}"]`);

                    // 単位を表示するspanタグを探す
                    const unitSpan = input.closest('.item-row').querySelector('.unit-display');

                    if (option) {
                        // optionに仕込んでおいた data-unit の値を読み取って表示する
                        unitSpan.textContent = option.dataset.unit;
                    } else {
                        unitSpan.textContent = '';
                    }
                }
            });
            
            document.getElementById('add-item-btn').addEventListener('click', function () {
                const container = document.getElementById('item-container');
                // 最初の行をコピーして新しい行を作成
                const firstRow = container.querySelector('.item-row');
                const newRow = firstRow.cloneNode(true);

                // 入力値をリセット
                newRow.querySelectorAll('input').forEach(input => input.value = '');

                // 削除ボタンを表示できるようにする
                const removeBtn = newRow.querySelector('.remove-btn');
                removeBtn.classList.remove('hidden');

                container.appendChild(newRow);
                toggleDeleteButtons();
            });

            // 削除機能のイベント委譲
            document.getElementById('item-container').addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-btn')) {
                    e.target.closest('.item-row').remove();
                    toggleDeleteButtons();
                }
            });

            // 最低1枠は残すための削除ボタン制御
            function toggleDeleteButtons() {
                const rows = document.querySelectorAll('.item-row');
                rows.forEach((row, index) => {
                    const btn = row.querySelector('.remove-btn');
                    if (rows.length > 1) {
                        btn.classList.remove('hidden');
                    } else {
                        btn.classList.add('hidden');
                    }
                });
            }
            // 初期実行（初期3つの時は削除ボタンを出しておく）
            toggleDeleteButtons();
        </script>
    </div>
</x-app-layout>