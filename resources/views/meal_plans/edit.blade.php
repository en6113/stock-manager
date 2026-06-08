<x-app-layout>

    <div class="container mx-auto p-6 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">献立の編集</h1>
            <form action="{{ route('meal_plans.destroy', $mealPlan->id) }}" method="POST"
                onsubmit="return confirm('本当にこの日の献立を削除しますか？在庫計算に影響が出る場合があります。')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold">この日の献立を削除</button>
            </form>
        </div>

        <form action="{{ route('meal_plans.update', $mealPlan->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white p-6 rounded-lg shadow-sm border grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">提供日</label>
                    <div class="text-lg font-bold text-gray-8xl">
                        {{ \Carbon\Carbon::parse($mealPlan->date)->format('Y年m月d日') }}</div>
                    <input type="hidden" name="date" value="{{ $mealPlan->date }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">提供人数</label>
                    <input type="number" name="servings" id="servings-input"
                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        value="{{ old('servings', $currentServings) }}" min="1" required>
                </div>
            </div>

            @foreach($structuredData as $data)
                @php
                    $category = $data['category'];
                    $currentMenu = $data['current_menu'];
                    $ingredients = $data['ingredients'];
                @endphp

                <div class="bg-white p-6 rounded-lg shadow-sm border category-section"
                    data-category-id="{{ $category->id }}">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ $category->name }}</h2>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">メニューを選択</label>
                        <select name="menus[{{ $category->id }}][menu_id]"
                            class="menu-select w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500"
                            onchange="loadMenuIngredients(this, {{ $category->id }})">
                            <option value="">-- なし --</option>
                            @foreach($menus->where('dish_category_id', $category->id) as $menu)
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
                            @foreach($ingredients as $index => $ing)
                                <div class="flex items-center justify-between bg-white p-2 rounded border text-sm">
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-800">{{ $ing['name'] }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="hidden"
                                            name="menus[{{ $category->id }}][ingredients][{{ $index }}][item_id]"
                                            value="{{ $ing['item_id'] }}">

                                        <label class="text-xs text-gray-500">必要量:</label>
                                        <input type="number"
                                            name="menus[{{ $category->id }}][ingredients][{{ $index }}][required_amount]"
                                            data-per-person="{{ $ing['per_person_amount'] }}"
                                            value="{{ old("menus.{$category->id}.ingredients.{$index}.required_amount", $ing['total_amount']) }}"
                                            class="ingredient-amount-input w-20 rounded-md border-gray-300 text-right text-sm focus:ring-blue-500"
                                            min="0" step="0.1">
                                        <span class="text-gray-600 text-xs w-8">{{ $ing['unit'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-end space-x-4">
                <a href="{{ route('meal_plans.index') }}"
                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">キャンセル</a>
                <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-medium">変更を保存する</button>
            </div>
        </form>
    </div>

    <script>
        const menuIngredientsData = @json($menuIngredientsData ?? []);

        // メニュー変更時：マスタデータ(1人分) × 提供人数 で計算して表示
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

            if (ingredients.length === 0) {
                ingredientList.innerHTML = '<p class="text-xs text-gray-500">登録されている食材はありません。</p>';
                return;
            }

            // 現在入力されている提供人数を取得
            const servingsInput = document.getElementById('servings-input');
            const currentServings = servingsInput ? parseInt(servingsInput.value) || 50 : 50;

            ingredients.forEach((ing, index) => {
                const totalAmount = (ing.required_amount * currentServings).toFixed(1);

                const html = `
                <div class="flex items-center justify-between bg-white p-2 rounded border text-sm">
                    <div class="flex-1">
                        <span class="font-medium text-gray-8px">${ing.item_name}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="hidden" name="menus[${categoryId}][ingredients][${index}][item_id]" value="${ing.item_id}">
                        <label class="text-xs text-gray-500">必要量:</label>
                        <input type="number" 
                               name="menus[${categoryId}][ingredients][${index}][required_amount]" 
                               data-per-person="${ing.required_amount}"
                               value="${totalAmount}" 
                               class="ingredient-amount-input w-20 rounded-md border-gray-300 text-right text-sm" 
                               min="0" step="0.1">
                        <span class="text-gray-600 text-xs w-8">${ing.unit}</span>
                    </div>
                </div>
            `;
                ingredientList.insertAdjacentHTML('beforeend', html);
            });
        }

        // 提供人数（servings）が手動で変更された時、表示されている必要量を一斉に自動再計算
        document.addEventListener('DOMContentLoaded', function () {
            const servingsInput = document.getElementById('servings-input');

            if (servingsInput) {
                servingsInput.addEventListener('input', function () {
                    const currentServings = parseInt(this.value) || 0;

                    document.querySelectorAll('.ingredient-amount-input').forEach(input => {
                        const perPersonAmount = parseFloat(input.getAttribute('data-per-person')) || 0;
                        input.value = (perPersonAmount * currentServings).toFixed(1);
                    });
                });
            }
        });
    </script>
</x-app-layout>