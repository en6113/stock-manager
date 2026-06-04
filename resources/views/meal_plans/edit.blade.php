<x-app-layout>

<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">献立の編集</h1>
        <form action="{{ route('meal_plans.destroy', $mealPlan->id) }}" method="POST" onsubmit="return confirm('本当にこの日の献立を削除しますか？在庫計算に影響が出る場合があります。')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold">この日の献立を削除</button>
        </form>
    </div>

    <form action="{{ route('meal_plans.update', $mealPlan->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white p-6 rounded-lg shadow-sm border">
            <label class="block text-sm font-medium text-gray-500 mb-1">提供日</label>
            <div class="text-lg font-bold text-gray-8xl">{{ \Carbon\Carbon::parse($mealPlan->date)->format('Y年m月d日') }}</div>
            <input type="hidden" name="date" value="{{ $mealPlan->date }}">
        </div>

        @foreach($categories as $category)
                    @php
            $currentMenu = $mealPlan->menus->where('category_id', $category->id)->first();
            $currentAdjustedItems = [];
            if ($currentMenu) {
                $currentMealPlanMenu = \DB::table('meal_plan_menu')
                    ->where('meal_plan_id', $mealPlan->id)
                    ->where('menu_id', $currentMenu->id)
                    ->first();

                if ($currentMealPlanMenu) {
                    $currentAdjustedItems = $adjustedItems->where('meal_plan_menu_id', $currentMealPlanMenu->id);
                }
            }
                    @endphp

                    <div class="bg-white p-6 rounded-lg shadow-sm border category-section" data-category-id="{{ $category->id }}">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ $category->name }}</h2>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">メニューを選択</label>
                            <select name="menus[{{ $category->id }}][menu_id]"
                                class="menu-select w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500"
                                onchange="loadMenuIngredients(this, {{ $category->id }})">
                                <option value="">-- なし --</option>
                                @foreach($menus->where('category_id', $category->id) as $menu)
                                    <option value="{{ $menu->id }}" {{ ($currentMenu && $currentMenu->id == $menu->id) ? 'selected' : '' }}>
                                        {{ $menu->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 食材・分量の微調整エリア --}}
                        <div class="ingredient-adjustment-area {{ $currentMenu ? '' : 'hidden' }}">
                            <h3 class="text-sm font-medium text-gray-600 mb-2">食材・分量の微調整</h3>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3 ingredient-list">

                                @if($currentMenu)
                                    {{-- パターンA: 微調整データ（MealPlanMenuItem）が存在する場合 --}}
                                    @if(count($currentAdjustedItems) > 0)
                                        @foreach($currentAdjustedItems as $index => $adjustedItem)
                                            <div class="flex items-center justify-between bg-white p-2 rounded border text-sm">
                                                <div class="flex-1">
                                                    <span class="font-medium text-gray-800">{{ $adjustedItem->item->name }}</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <input type="hidden" name="menus[{{ $category->id }}][ingredients][{{ $index }}][item_id]"
                                                        value="{{ $adjustedItem->item_id }}">

                                                    <label class="text-xs text-gray-500">必要量:</label>
                                                    <input type="number" name="menus[{{ $category->id }}][ingredients][{{ $index }}][required_amount]"
                                                        value="{{ old("menus.{$category->id}.ingredients.{$index}.required_amount", $adjustedItem->adjust_amount) }}"
                                                        class="w-20 rounded-md border-gray-300 text-right text-sm focus:ring-blue-500" min="1">
                                                    <span class="text-gray-600 text-xs w-8">{{ $adjustedItem->item->unit }}</span>
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- パターンB: 微調整データがない場合 ➔ メニュー本来の食材（Menu->items）を表示する --}}
                                    @else
                                        @foreach($currentMenu->items as $index => $item)
                                            <div class="flex items-center justify-between bg-white p-2 rounded border text-sm">
                                                <div class="flex-1">
                                                    <span class="font-medium text-gray-800">{{ $item->name }}</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <input type="hidden" name="menus[{{ $category->id }}][ingredients][{{ $index }}][item_id]"
                                                        value="{{ $item->id }}">

                                                    <label class="text-xs text-gray-500">必要量:</label>
                                                    <input type="number" name="menus[{{ $category->id }}][ingredients][{{ $index }}][required_amount]" 
                                                        value="{{ old("menus.{$category->id}.ingredients.{$index}.required_amount", $item->pivot->required_amount) }}"
                                                        class="w-20 rounded-md border-gray-300 text-right text-sm focus:ring-blue-500" min="1">
                                                    <span class="text-gray-600 text-xs w-8">{{ $item->unit }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
        @endforeach

        <div class="flex justify-end space-x-4">
            <a href="{{ route('meal_plans.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">キャンセル</a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-medium">変更を保存する</button>
        </div>
    </form>
</div>

<script>
    const menuIngredientsData = @json($menuIngredientsData ?? []); 

    function loadMenuIngredients(selectElement, categoryId) {
        const menuId = selectElement.value;
        const section = selectElement.closest('.category-section');
        const adjustmentArea = section.querySelector('.ingredient-adjustment-area');
        const ingredientList = section.querySelector('.ingredient-list');

        if (!menuId) {
            adjustmentArea.classList.add('hidden');
            ingredientList.innerHTML = '';
            return;
        }

        adjustmentArea.classList.remove('hidden');
        ingredientList.innerHTML = '';

        const ingredients = menuIngredientsData[menuId] || [];

        if(ingredients.length === 0) {
            ingredientList.innerHTML = '<p class="text-xs text-gray-500">登録されている食材はありません。</p>';
            return;
        }

        ingredients.forEach((ing, index) => {
            const html = `
                <div class="flex items-center justify-between bg-white p-2 rounded border text-sm">
                    <div class="flex-1">
                        <span class="font-medium text-gray-8xl">${ing.item_name}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="hidden" name="menus[${categoryId}][ingredients][${index}][item_id]" value="${ing.item_id}">
                        <label class="text-xs text-gray-500">必要量:</label>
                        <input type="number" name="menus[${categoryId}][ingredients][${index}][required_amount]" value="${ing.required_amount}" class="w-20 rounded-md border-gray-300 text-right text-sm" min="1">
                        <span class="text-gray-600 text-xs w-8">${ing.unit}</span>
                    </div>
                </div>
            `;
            ingredientList.insertAdjacentHTML('beforeend', html);
        });
    }
</script>
</x-app-layout>