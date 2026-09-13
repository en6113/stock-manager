<x-app-layout>

<div class="container mx-auto p-6 max-w-4xl">
    <h1 class="text-2xl font-bold mb-6 text-gray-8xl">新規献立登録</h1>

    <form action="{{ route('meal_plans.store') }}" method="POST" class="space-y-6"
        data-menu-ingredients="{{ json_encode($menuIngredientsData ?? []) }}">
        @csrf

        <div class="bg-white p-6 rounded-lg shadow-sm border grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">提供日</label>
                <input type="date" name="date" id="date" value="{{ request('date', old('date')) }}"
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">提供人数</label>
                <input type="number" name="servings" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="50" min="1" required>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border category-section"
            data-category-id="{{ \App\Enums\DishCategory::Staple->value }}">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">主食</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">メニューを選択</label>
                <select name="menus[{{ \App\Enums\DishCategory::Staple->value }}][menu_id]"
                    class="menu-select w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- メニューを選択してください --</option>
                    @foreach($menus->where('dish_category', \App\Enums\DishCategory::Staple) as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->name }} ({{ $menu->calories }} kcal)</option>
                    @endforeach
                </select>
            </div>

            <div class="ingredient-adjustment-area hidden">
                <h3 class="text-sm font-medium text-gray-600 mb-2">食材・分量の微調整</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3 ingredient-list">
                </div>
                <div class="mt-3 flex items-center gap-2 add-item-row">
                    <select class="add-item-select flex-1 rounded-md border-gray-300 shadow-sm text-sm">
                        <option value="">-- 追加する食材を選択 --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-name="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <button type="button"
                        class="add-ingredient-btn bg-gray-600 hover:bg-gray-700 text-white text-xs px-3 py-2 rounded whitespace-nowrap">
                        + 食材を追加
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border category-section"
            data-category-id="{{ \App\Enums\DishCategory::Main->value }}">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">主菜</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">メニューを選択</label>
                <select name="menus[{{ \App\Enums\DishCategory::Main->value }}][menu_id]"
                    class="menu-select w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- メニューを選択してください --</option>
                    @foreach($menus->where('dish_category', \App\Enums\DishCategory::Main) as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->name }} ({{ $menu->calories }} kcal)</option>
                    @endforeach
                </select>
            </div>

            <div class="ingredient-adjustment-area hidden">
                <h3 class="text-sm font-medium text-gray-600 mb-2">食材・分量の微調整</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3 ingredient-list">
                </div>
                <div class="mt-3 flex items-center gap-2 add-item-row">
                    <select class="add-item-select flex-1 rounded-md border-gray-300 shadow-sm text-sm">
                        <option value="">-- 追加する食材を選択 --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-name="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <button type="button"
                        class="add-ingredient-btn bg-gray-600 hover:bg-gray-700 text-white text-xs px-3 py-2 rounded whitespace-nowrap">
                        + 食材を追加
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border category-section"
            data-category-id="{{ \App\Enums\DishCategory::Side->value }}">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">副菜</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">メニューを選択</label>
                <select name="menus[{{ \App\Enums\DishCategory::Side->value }}][menu_id]"
                    class="menu-select w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- メニューを選択してください --</option>
                    @foreach($menus->where('dish_category', \App\Enums\DishCategory::Side) as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->name }} ({{ $menu->calories }} kcal)</option>
                    @endforeach
                </select>
            </div>

            <div class="ingredient-adjustment-area hidden">
                <h3 class="text-sm font-medium text-gray-600 mb-2">食材・分量の微調整</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3 ingredient-list">
                </div>
                <div class="mt-3 flex items-center gap-2 add-item-row">
                    <select class="add-item-select flex-1 rounded-md border-gray-300 shadow-sm text-sm">
                        <option value="">-- 追加する食材を選択 --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-name="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <button type="button"
                        class="add-ingredient-btn bg-gray-600 hover:bg-gray-700 text-white text-xs px-3 py-2 rounded whitespace-nowrap">
                        + 食材を追加
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border category-section"
            data-category-id="{{ \App\Enums\DishCategory::Soup->value }}">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">汁もの</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">メニューを選択</label>
                <select name="menus[{{ \App\Enums\DishCategory::Soup->value }}][menu_id]"
                    class="menu-select w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- メニューを選択してください --</option>
                    @foreach($menus->where('dish_category', \App\Enums\DishCategory::Soup) as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->name }} ({{ $menu->calories }} kcal)</option>
                    @endforeach
                </select>
            </div>

            <div class="ingredient-adjustment-area hidden">
                <h3 class="text-sm font-medium text-gray-600 mb-2">食材・分量の微調整</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3 ingredient-list">
                </div>
                <div class="mt-3 flex items-center gap-2 add-item-row">
                    <select class="add-item-select flex-1 rounded-md border-gray-300 shadow-sm text-sm">
                        <option value="">-- 追加する食材を選択 --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-name="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <button type="button"
                        class="add-ingredient-btn bg-gray-600 hover:bg-gray-700 text-white text-xs px-3 py-2 rounded whitespace-nowrap">
                        + 食材を追加
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border category-section"
            data-category-id="{{ \App\Enums\DishCategory::Other->value }}">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">その他</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">メニューを選択</label>
                <select name="menus[{{ \App\Enums\DishCategory::Other->value }}][menu_id]"
                    class="menu-select w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- メニューを選択してください --</option>
                    @foreach($menus->where('dish_category', \App\Enums\DishCategory::Other) as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->name }} ({{ $menu->calories }} kcal)</option>
                    @endforeach
                </select>
            </div>

            <div class="ingredient-adjustment-area hidden">
                <h3 class="text-sm font-medium text-gray-600 mb-2">食材・分量の微調整</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3 ingredient-list">
                </div>
                <div class="mt-3 flex items-center gap-2 add-item-row">
                    <select class="add-item-select flex-1 rounded-md border-gray-300 shadow-sm text-sm">
                        <option value="">-- 追加する食材を選択 --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" data-name="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <button type="button"
                        class="add-ingredient-btn bg-gray-600 hover:bg-gray-700 text-white text-xs px-3 py-2 rounded whitespace-nowrap">
                        + 食材を追加
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('meal_plans.index') }}"
                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">キャンセル</a>
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 font-medium">この内容で登録する</button>
        </div>
    </form>
</div>

@vite(['resources/js/pages/meal-plans/index.js'])

</x-app-layout>