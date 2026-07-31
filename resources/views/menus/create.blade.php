<x-app-layout>
    <div class="container mx-auto px-4 sm:px-8 max-w-6xl py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 leading-tight">給食メニュー登録</h1>
            </div>
            <a href="{{ route('items.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 text-sm">
                ＋ マスタに新しい食材を追加
            </a>
        </div>

        <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('menus.store') }}" method="POST">
                @csrf

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
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50" required
                            placeholder="例：ハンバーグ">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">カテゴリ<span class="text-red-500">*</span></label>
                        <select name="dish_category"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50">
                            <option value="">-- 選択してください --</option>
                            @foreach(App\Enums\DishCategory::cases() as $category)
                                <option value="{{ $category->value }}" {{ old('dish_category') === $category->value ? 'selected' : '' }}>
                                    {{ $category->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('dish_category') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">総カロリー (kcal/人)</label>
                        <input type="number" name="calories" value="{{ old('calories') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50"
                            placeholder="任意">
                        @error('calories') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">提供人数(人)<span class="text-red-500">*</span></label>
                        <input type="number" id="servings-input" name="servings" value="{{ old('servings') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 bg-gray-50" required placeholder="何人分">
                        @error('servings') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                {{-- ==================== 使用する食材セクション ==================== --}}
                <div class="mb-6 bg-white p-4 border border-gray-100 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-3 text-gray-700 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>使用する食材 (カテゴリ1〜14)
                    </h2>
                    <div id="ingredient-container" class="space-y-3">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="flex gap-4 items-end item-row bg-gray-50 p-3 rounded">
                                <div class="flex-1 max-w-md">
                                    <label class="block text-xs font-medium text-gray-600">食材（検索）</label>
                                    <input list="ingredient-list" name="item_ids[]"
                                        class="mt-1 block w-full rounded border-gray-300 p-1.5 bg-white shadow-sm"
                                        placeholder="キーワードを入力して選択">
                                </div>

                                <div class="w-40">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">数量（g換算自動計算用）</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number"
                                            class="quantity-input block w-24 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="0"
                                            step="1">
                                        <span class="unit-display text-sm font-medium text-gray-600 min-w-[24px]"></span>
                                    </div>
                                </div>

                                <div class="w-32">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">必要量(g)</label>
                                    <div class="flex items-center gap-1">
                                        <input type="number" name="required_amounts[]"
                                            class="required-amount-input block w-20 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="0"
                                            step="0.1">
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

                                <div>
                                    <button type="button"
                                        class="remove-btn text-red-500 hover:text-red-700 font-bold p-1.5 hidden text-sm">削除</button>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <datalist id="ingredient-list">
                        @foreach($registered_items as $item)
                            <option value="{{ $item->name }}" data-id="{{ $item->id }}" data-unit="{{ $item->unit }}" data-gram-per-unit="{{ $item->gram_per_unit }}">
                                ID: {{ $item->id }} </option>
                        @endforeach
                    </datalist>

                    <button type="button" id="add-ingredient-btn"
                        class="mt-4 px-4 py-2 bg-emerald-600 text-white rounded text-sm hover:bg-emerald-700 shadow-sm transition">
                        + 食材枠を追加
                    </button>
                </div>


                {{-- ==================== 使用する調味料セクション ==================== --}}
                <div class="mb-6 bg-white p-4 border border-gray-100 rounded-lg shadow-sm">
                    <h2 class="text-lg font-semibold mb-3 text-gray-700 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>使用する調味料 (カテゴリ13〜19)
                    </h2>
                    <div id="seasoning-container" class="space-y-3">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="flex gap-4 items-end item-row bg-gray-50 p-3 rounded">
                                <div class="flex-1 max-w-md">
                                    <label class="block text-xs font-medium text-gray-600">調味料（検索）</label>
                                    <input list="seasoning-list" name="item_ids[]"
                                        class="mt-1 block w-full rounded border-gray-300 p-1.5 bg-white shadow-sm"
                                        placeholder="キーワードを入力して選択">
                                </div>

                                <div class="w-40"> <label class="block text-xs font-medium text-gray-600 mb-1">数量（g換算自動計算用）</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number"
                                            class="quantity-input block w-24 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="0"
                                            step="0.1">
                                        <span class="unit-display text-sm font-medium text-gray-600 min-w-[24px]"></span>
                                    </div>
                                </div>

                                <div class="w-32">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">必要量(g)</label>
                                    <div class="flex items-center gap-1">
                                        <input type="number" name="required_amounts[]"
                                            class="required-amount-input block w-20 rounded border-gray-300 p-1.5 bg-white shadow-sm" min="0"
                                            step="0.1">
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

                                <div>
                                    <button type="button"
                                        class="remove-btn text-red-500 hover:text-red-700 font-bold p-1.5 hidden text-sm">削除</button>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <datalist id="seasoning-list">
                        @foreach($seasoning_items as $item)
                            <option value="{{ $item->name }}" data-id="{{ $item->id }}" data-unit="{{ $item->unit }}" data-gram-per-unit="{{ $item->gram_per_unit }}">
                                ID: {{ $item->id }} </option>
                        @endforeach
                    </datalist>

                    <button type="button" id="add-seasoning-btn"
                        class="mt-4 px-4 py-2 bg-amber-600 text-white rounded text-sm hover:bg-amber-700 shadow-sm transition">
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

        @vite(['resources/js/pages/menus/create.js'])
    </div>
</x-app-layout>