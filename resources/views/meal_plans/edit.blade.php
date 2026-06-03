<x-app-layout>

<div class="container mx-auto p-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-8xl">献立の編集</h1>
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

        @php
            $categories = [
                1 => ['label' => '主菜 (Main)', 'key' => 'main'],
                2 => ['label' => '副菜 (Side)', 'key' => 'side'],
                3 => ['label' => '汁物 (Soup)', 'key' => 'soup'],
                4 => ['label' => 'おやつ (Snack)', 'key' => 'snack']
            ];
        @endphp

        @foreach($categories as $catId => $catInfo)
            @php
                // 現在の献立に登録されている、このカテゴリのメニューを取得
                $currentMenu = $mealPlan->menus->where('dish_category', $catId)->first();
            @endphp

            <div class="bg-white p-6 rounded-lg shadow-sm border category-section" data-category-id="{{ $catId }}">
                <h2 class="text-lg font-semibold text-gray-8xl mb-4 border-b pb-2">{{ $catInfo['label'] }}</h2>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">メニューを選択</label>
                    <select name="menus[{{ $catId }}][menu_id]" class="menu-select w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500" onchange="loadMenuIngredients(this, {{ $catId }})">
                        <option value="">-- なし --</option>
                        @foreach($menus->where('dish_category', $catId) as $menu)
                            <option value="{{ $menu->id }}" {{ (old("menus.$catId.menu_id", $currentMenu->id ?? null) == $menu->id) ? 'selected' : '' }}>
                                {{ $menu->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="ingredient-adjustment-area {{ $currentMenu ? '' : 'hidden' }}">
                    <h3 class="text-sm font-medium text-gray-600 mb-2">食材・分量の微調整 (1人あたり)</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3 ingredient-list">
                        
                        {{-- すでにメニューが紐づいている場合は、Blade側で最初から食材ループを展開しておく --}}
                        @if($currentMenu)
                            {{-- 練習用に、meal_plan_menu_item または menu->items (中間テーブル経由) から必要量を取得 --}}
                            @foreach($currentMenu->items as $index => $item)
                                @php
                                    // 実際の実装では、修正済みテーブル(meal_plan_menu_item)があればそちらの数量、なければデフォルトの必要量(item_menu)を出すロジックにします
                                    $amount = $item->pivot->adjusted_amount ?? $item->pivot->required_amount;
                                @endphp
                                <div class="flex items-center justify-between bg-white p-2 rounded border text-sm">
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-8xl">{{ $item->name }}</span>
                                        <span class="text-xs text-gray-500 ml-2">({{ $item->item_category }})</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="hidden" name="menus[{{ $catId }}][ingredients][{{ $index }}][item_id]" value="{{ $item->id }}">
                                        
                                        <label class="text-xs text-gray-500">必要量:</label>
                                        <input type="number" 
                                               name="menus[{{ $catId }}][ingredients][{{ $index }}][required_amount]" 
                                               value="{{ old("menus.$catId.ingredients.$index.required_amount", $amount) }}" 
                                               class="w-20 rounded-md border-gray-300 text-right text-sm focus:ring-blue-500"
                                               min="1">
                                        <span class="text-gray-600 text-xs w-8">{{ $item->unit }}</span>
                                    </div>
                                </div>
                            @endforeach
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