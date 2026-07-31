<x-app-layout>

<body class="bg-gray-50 p-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">メニューの編集</h1>

        <form action="{{ route('menus.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">メニュー名<span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $menu->name) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50" required>
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">カテゴリ<span class="text-red-500">*</span></label>
                        <select name="dish_category" class="px-4 py-2 border w-full sm:text-sm border-gray-300 rounded-md text-gray-600">
                            @foreach(App\Enums\DishCategory::cases() as $category)
                                <option value="{{ $category->value }}" {{ old('dish_category', $menu->dish_category?->value) === $category->value ? 'selected' : '' }}>
                                    {{ $category->label() }}
                                </option>
                            @endforeach
                        </select>
                    @error('dish_category') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">総カロリー (kcal)</label>
                    <input type="number" name="calories" value="{{ old('calories', $menu->calories) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50">
                    @error('calories') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">提供人数<span class="text-red-500">*</span></label>
                    <input type="number" id="servings-input" name="servings" value="{{ old('servings', $menu->items->first()?->pivot?->servings) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50">
                    @error('servings') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-3 text-gray-700">使用する食材・調味料の編集</h2>

                <div id="item-container" class="space-y-3">
                    @forelse($menu->items as $pivotItem)

                        <div class="flex gap-4 items-end item-row bg-gray-50 p-3 rounded"
                            data-gram-per-unit="{{ $pivotItem->gram_per_unit }}">
                            <div class="flex-1 max-w-md">
                                <label class="block text-xs font-medium text-gray-600">アイテム（検索）</label>
                                <input list="item-list" name="item_ids[]" value="{{ $pivotItem->name }}"
                                    class="mt-1 block w-full rounded border-gray-300 p-1.5 bg-white shadow-sm">
                            </div>

                            <div class="w-40">
                                <label class="block text-xs font-medium text-gray-600 mb-1">数量(g換算自動計算用)</label>
                                <div class="flex items-center gap-2">
                                    <input type="number"
                                        value="{{ old('raw_amounts.' . $loop->index, $pivotItem->gram_per_unit ? round($pivotItem->pivot->required_amount / $pivotItem->gram_per_unit, 2) : '') }}"
                                        class="quantity-input block w-24 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="0" step="0.1">
                                    <span class="unit-display text-sm font-medium text-gray-600 min-w-[24px]">
                                        {{ $pivotItem->unit }}
                                    </span>
                                </div>
                            </div>

                            <div class="w-32">
                                <label class="block text-xs font-medium text-gray-600 mb-1">必要量(g)</label>
                                <div class="flex items-center gap-1">
                                    <input type="number" name="required_amounts[]"
                                        value="{{ old('required_amounts.' . $loop->index, $pivotItem->pivot->required_amount) }}"
                                        class="required-amount-input block w-24 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="0" step="0.1">
                                    <span class="text-sm text-gray-600">g</span>
                                </div>
                            </div>

                            <div class="w-28">
                                <label class="block text-xs font-medium text-gray-600 mb-1">1人分</label>
                                <div class="flex items-center gap-1">
                                    <span class="per-serving-display text-sm font-semibold text-blue-600">-</span>
                                    <span class="text-sm text-gray-600">g</span>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="button" class="remove-btn text-red-500 hover:text-red-700 font-bold">削除</button>
                            </div>
                        </div>
                    @empty
                        <div class="flex gap-4 items-end item-row bg-gray-50 p-3 rounded">
                            <div class="flex-1 max-w-md">
                                <label class="block text-xs font-medium text-gray-600">アイテム（検索）</label>
                                <input list="item-list" name="item_ids[]"
                                    class="mt-1 block w-full rounded border-gray-300 p-1.5 bg-white shadow-sm">
                            </div>

                            <div class="w-40">
                                <label class="block text-xs font-medium text-gray-600 mb-1"数量(g換算自動計算用)></label>
                                <div class="flex items-center gap-2">
                                    <input type="number"
                                        class="quantity-input block w-24 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="0" step="0.1">
                                    <span class="unit-display text-sm font-medium text-gray-600 min-w-[24px]"></span>
                                </div>
                            </div>

                            <div class="w-32">
                                <label class="block text-xs font-medium text-gray-600 mb-1">必要量(g)</label>
                                <div class="flex items-center gap-1">
                                    <input type="number" name="required_amounts[]"
                                        class="required-amount-input block w-24 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="0" step="0.1">
                                    <span class="text-sm text-gray-600">g</span>
                                </div>
                            </div>

                            <div class="w-28">
                                <label class="block text-xs font-medium text-gray-600 mb-1">1人分</label>
                                <div class="flex items-center gap-1">
                                    <span class="per-serving-display text-sm font-semibold text-blue-600">-</span>
                                    <span class="text-sm text-gray-600">g</span>
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
                        <option value="{{ $item->name }}" data-id="{{ $item->id }}" data-unit="{{ $item->unit }}" data-gram-per-unit="{{ $item->gram_per_unit }}">ID:{{ $item->id }}</option>
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

    @vite(['resources/js/pages/menus/edit.js'])
</body>

</x-app-layout>