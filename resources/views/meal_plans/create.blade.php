<x-app-layout>

<div class="container mx-auto p-6 max-w-4xl">
    <h1 class="text-2xl font-bold mb-6 text-gray-8xl">新規献立登録</h1>

    <form action="{{ route('meal_plans.store') }}" method="POST" class="space-y-6">
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

        @foreach($categories as $category)
            <div class="bg-white p-6 rounded-lg shadow-sm border category-section" data-category-id="{{ $category->id }}">

                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ $category->name }}</h2>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">メニューを選択</label>

                    <select name="menus[{{ $category->id }}][menu_id]"
                        class="menu-select w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        onchange="loadMenuIngredients(this, {{ $category->id }})">
                        <option value="">-- メニューを選択してください --</option>

                        @foreach($menus->where('dish_category_id', $category->id) as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->name }} ({{ $menu->calories }} kcal)</option>
                        @endforeach
                    </select>
                </div>

                <div class="ingredient-adjustment-area hidden">
                    <h3 class="text-sm font-medium text-gray-600 mb-2">食材・分量の微調整</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3 ingredient-list">
                    </div>
                </div>
            </div>
        @endforeach

        <div class="flex justify-end space-x-4">
            <a href="{{ route('meal_plans.index') }}"
                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">キャンセル</a>
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 font-medium">この内容で登録する</button>
        </div>
    </form>
</div>

<script>
    const menuIngredientsData = @json($menuIngredientsData ?? []);

    // メニューが選択された時の処理
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
        const servingsInput = document.querySelector('input[name="servings"]');
        const currentServings = servingsInput ? parseInt(servingsInput.value) || 50 : 50;

        ingredients.forEach((ing, index) => {
            // ★ 計算：1人分の量(required_amount) × 提供人数
            const totalAmount = ing.required_amount * currentServings;

            const html = `
                <div class="flex items-center justify-between bg-white p-2 rounded border text-sm">
                    <div class="flex-1">
                        <span class="font-medium text-gray-8xl">${ing.item_name}</span>
                        <span class="text-xs text-red-500 ml-2">${ing.allergens ? '（アレルギー: ' + ing.allergens + '）' : ''}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="hidden" name="menus[${categoryId}][ingredients][${index}][item_id]" value="${ing.item_id}">

                        <label class="text-xs text-gray-500">必要量:</label>
                        <input type="number" 
                                name="menus[${categoryId}][ingredients][${index}][required_amount]" 
                                data-per-person="${ing.required_amount}" 
                                value="${totalAmount}" 
                                class="ingredient-amount-input w-20 rounded-md border-gray-300 text-right text-sm focus:ring-blue-500"
                                min="0" step="0.1">
                        <span class="text-gray-600 text-xs w-8">${ing.unit}</span>
                    </div>
                </div>
            `;
            ingredientList.insertAdjacentHTML('beforeend', html);
        });
    }

    // ★ 新機能：提供人数（servings）が変更されたら、画面上の全食材の必要量を一斉に再計算する
    document.addEventListener('DOMContentLoaded', function () {
        const servingsInput = document.querySelector('input[name="servings"]');

        if (servingsInput) {
            servingsInput.addEventListener('input', function () {
                const currentServings = parseInt(this.value) || 0;

                // 画面上に生成されているすべての必要量inputをループ処理
                document.querySelectorAll('.ingredient-amount-input').forEach(input => {
                    const perPersonAmount = parseFloat(input.getAttribute('data-per-person')) || 0;
                    // 新しい人数で掛け算して数値を更新
                    input.value = (perPersonAmount * currentServings).toFixed(1);
                });
            });
        }
    });
</script>

</x-app-layout>