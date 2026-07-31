// 献立登録・編集画面で共通の「メニュー選択に連動した食材一覧」制御ロジック
// - メニュー選択 → 食材一覧を表示（1人分 × 提供人数で計算）
// - メニューに無い食材の追加・削除
// - 提供人数変更時、表示中の全食材の必要量を一斉に再計算

export function initMenuIngredients(formSelector = '[data-menu-ingredients]') { // 属性セレクタ: IDを付け替えても壊れない
    const form = document.querySelector(formSelector);
    if (!form) return; // ガード節

    // 最初にメニュー材料を全て渡す(メニューを選んだ際に通信が発生せず速いが、材料が増えるとHTMLが重くなる）
    // JSON.parse(): 文字列をオブジェクトに復元して渡す
    // 未登録時は空オブジェクトを渡すことで、例外をなげてJSが止まることを防ぐ
    const menuIngredientsData = JSON.parse(form.dataset.menuIngredients || '{}');

    // 提供人数のデフォルト数
    const DEFAULT_SERVINGS = 50;

    // 提供人数を取得
    function getServingsInput() {
        return form.querySelector('#servings-input, input[name="servings"]');
    }

    // 食材のデータを取得し、選択されたメニューの食材データを画面に表示
    // name・allergensはこの時点でHTMLに埋め込まず、挿入後にfillIngredientRowTextでtextContentとして差し込む(XSS対策)
    function ingredientRowHtml({ categoryId, index, itemId, perPerson, amount }) {
        return `
            <div class="flex items-center justify-between bg-white p-2 rounded border text-sm ingredient-row">
                <div class="flex-1">
                    <span class="font-medium text-gray-800 item-name-display"></span>
                    <span class="text-xs text-red-500 ml-2 allergen-display hidden"></span>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="hidden" name="menus[${categoryId}][ingredients][${index}][item_id]" value="${itemId}">

                    <label class="text-xs text-gray-500">必要量:</label>
                    <input type="number"
                        name="menus[${categoryId}][ingredients][${index}][required_amount]"
                        data-per-person="${perPerson}"
                        value="${amount}"
                        class="ingredient-amount-input w-20 rounded-md border-gray-300 text-right text-sm focus:ring-blue-500"
                        min="0" step="0.1">
                    <span class="text-gray-600 text-xs w-8">g</span>
                    <span class="text-xs text-gray-500 ml-2">1人分:</span>
                    <span class="per-serving-display text-xs font-semibold text-blue-600">${Math.round(perPerson * 10) / 10}</span>
                    <span class="text-gray-600 text-xs w-4">g</span>
                    <button type="button" class="remove-ingredient-btn text-red-500 hover:text-red-700 text-xs ml-2">削除</button>
                </div>
            </div>
        `;
    }

    // 食材名・アレルギー表示をtextContentで差し込む（HTMLとして解釈させないためのXSS対策）
    function fillIngredientRowText(row, name, allergens) {
        row.querySelector('.item-name-display').textContent = name;

        const allergenSpan = row.querySelector('.allergen-display');
        if (allergens) {
            allergenSpan.textContent = `（アレルギー: ${allergens}）`;
            allergenSpan.classList.remove('hidden');
        }
    }

    // メニューが選択された時の処理（1人分 × 提供人数 で計算して表示）
    function loadMenuIngredients(selectElement) {
        const menuId = selectElement.value;
        const section = selectElement.closest('.category-section'); // closest(): セレクトボックスが属するカテゴリ区画を特定して操作
        const categoryId = section.dataset.categoryId; // dataset: キャメルケースに自動変換して文字列として保存
        const adjustmentArea = section.querySelector('.ingredient-adjustment-area');
        const ingredientList = section.querySelector('.ingredient-list');

        // メニュー選択が解除された場合、区画を隠して中身を空にする
        if (!menuId) {
            adjustmentArea.classList.add('hidden');
            ingredientList.innerHTML = '';
            return;
        }

        // メニューが選択された場合、区画を一度空にしてから作り直す
        adjustmentArea.classList.remove('hidden');
        ingredientList.innerHTML = '';

        // メニュー材料データからメニューIDキーで材料を取り出す。メニューが存在しない場合は空配列を返してエラーを防ぐ
        const ingredients = menuIngredientsData[menuId] || [];

        // メニューはあるが食材が未登録の場合、メッセージを返す
        if (ingredients.length === 0) {
            ingredientList.innerHTML = '<p class="text-xs text-gray-500">登録されている食材はありません。</p>';
            return;
        }

        // 提供人数を整数に変換し、返還失敗時はデフォルトの人数にする
        const servingsInput = getServingsInput();
        const currentServings = servingsInput ? parseInt(servingsInput.value) || DEFAULT_SERVINGS : 50;

        // 材料ごとの必要量を取得（indexごとに処理）
        ingredients.forEach((ing, index) => {
            const totalAmount = (ing.perServing * currentServings).toFixed(1); // 小数第一位に丸めた文字列

            // 指定位置（中身の末尾）にHTMLを挿入
            ingredientList.insertAdjacentHTML( // insertAdjacentHTML: 既存要素に触れず追記するため安全で速い
                'beforeend',
                ingredientRowHtml({
                    categoryId,
                    index,
                    itemId: ing.item_id,
                    perPerson: ing.perServing,
                    amount: totalAmount,
                })
            );

            fillIngredientRowText(ingredientList.lastElementChild, ing.item_name, ing.allergens);
        });
    }

    // メニューに無い食材を追加する
    function addIngredientRow(buttonEl) {
        const row = buttonEl.closest('.add-item-row');
        const select = row.querySelector('.add-item-select');
        const itemId = select.value;

        if (!itemId) return; //ガード節: 未選択なら何もしない

        const section = buttonEl.closest('.category-section');
        const categoryId = section.dataset.categoryId;
        const ingredientList = section.querySelector('.ingredient-list');

        // すでに同じ食材が追加されていないかチェック
        // Array.from(): 配列に変換してsomeが使えるようにしている、some: 条件を満たす要素が1つでもあればtrueを返す
        const alreadyAdded = Array.from(ingredientList.querySelectorAll('input[name$="[item_id]"]')).some(
            (input) => input.value === itemId
        );
        if (alreadyAdded) {
            alert('すでに追加されている食材です。');
            return;
        }

        // 新たな食材が追加されたメニューの材料にindexをつけなおす
        const selectedOption = select.options[select.selectedIndex];
        const itemName = selectedOption.dataset.name;
        const index = `new_${Date.now()}`; // 既存のindexと衝突しないユニークなキー

        // 指定位置（中身の末尾）にHTMLを挿入
        ingredientList.insertAdjacentHTML(
            'beforeend',
            ingredientRowHtml({
                categoryId, index, itemId,
                perPerson: 0,
                amount: 0,
            })
        );

        fillIngredientRowText(ingredientList.lastElementChild, itemName, '');

        // 追加したらセレクトを未選択に戻す（連続追加しやすくするため）
        select.value = '';
    }

    // イベントの振り分け（メニューが選択されたら、該当メニューの材料を取得）
    // change: 値が確定した時に発火、<select>は選んだ瞬間が確定なのでinput（1文字打つたびに発火）ではなくchangeを使用
    form.addEventListener('change', function (e) { // イベント委譲: フォームで受けてe.target.classList()で行を特定する
        if (e.target.classList.contains('menu-select')) {
            loadMenuIngredients(e.target);
        }
    });

    // 必要量(g)が直接編集されたら、その値を新しい「1人分」の基準として保持する
    // （data-per-personを更新しておかないと、提供人数変更時に手入力した値が古い基準値で上書きされてしまうため）
    form.addEventListener('input', function (e) {
        if (!e.target.classList.contains('ingredient-amount-input')) return;

        const servingsInput = getServingsInput();
        const currentServings = servingsInput ? parseInt(servingsInput.value) || 0 : 0;
        const amount = parseFloat(e.target.value) || 0;
        const perServing = currentServings > 0 ? amount / currentServings : 0;

        e.target.dataset.perPerson = perServing;

        const perServingSpan = e.target.closest('.ingredient-row')?.querySelector('.per-serving-display');
        if (perServingSpan) {
            perServingSpan.textContent = (Math.round(perServing * 10) / 10).toString();
        }
    });

    // 食材の追加と削除
    form.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-ingredient-btn')) {
            addIngredientRow(e.target);
        } else if (e.target.classList.contains('remove-ingredient-btn')) {
            e.target.closest('.ingredient-row').remove();
        }
    });

    // 提供人数が変更されたら、画面上の全食材の必要量を再計算する
    const servingsInput = getServingsInput();
    if (servingsInput) {
        servingsInput.addEventListener('input', function () {
            const currentServings = parseInt(this.value) || 0; // アロー関数はthisが使えないためfunction()を使用

            form.querySelectorAll('.ingredient-amount-input').forEach((input) => {
                const perPersonAmount = parseFloat(input.getAttribute('data-per-person')) || 0;
                input.value = (perPersonAmount * currentServings).toFixed(1);
            });
        });
    }
}
