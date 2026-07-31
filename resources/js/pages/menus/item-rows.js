// メニュー登録・編集画面の食材行の動的な操作のロジック
// 1. 食材の検索に連動して単位とg換算係数を画面に反映し、必要量・g換算必要量・1人分の数値を計算し合う
// 2. 行の追加・削除
// 3. 提供人数の変更に応じて、必要量または1人分の値を再計算する

export function setupItemRows({
    containerId,
    buttonId,
    listId,
    servingsInputId = 'servings-input',
    rescaleOnServingsChange = false,
}) {
    const container = document.getElementById(containerId);
    const addButton = document.getElementById(buttonId);

    if (!container || !addButton) return; // ガード節: この画面に対象要素がなければ何もしない

    // 提供人数を取得する
    function getServings() {
        const servingsInput = document.getElementById(servingsInputId);
        return servingsInput ? parseFloat(servingsInput.value) : NaN;
    }

    // 必要量(g)を計算する（数量（個数など）が入力されたとき or 食材が変わったとき）
    function recalcRequiredAmount(row) {
        const quantityInput = row.querySelector('.quantity-input');
        const requiredInput = row.querySelector('.required-amount-input');
        const gramPerUnit = parseFloat(row.dataset.gramPerUnit); // dataset: キャメルケースに自動変換され、文字列として保存
        const quantity = parseFloat(quantityInput.value); // parseFloat: 文字列を数字に変換

        if (!isNaN(gramPerUnit) && !isNaN(quantity)) { // NaN === NaN が false になり比較演算子では判定できないため、isNaN()を使う
            requiredInput.value = Math.round(quantity * gramPerUnit * 10) / 10; //小数第一位で四捨五入
        } else {
            requiredInput.value = '';
        }

        syncPerServing(row); //必要量の計算を行ったら1人分も計算しなおす
    }

    // 1人分を計算して保持する(必要量(g)÷提供人数)
    function syncPerServing(row) {
        const requiredInput = row.querySelector('.required-amount-input');
        const perServingSpan = row.querySelector('.per-serving-display');

        const requiredAmount = parseFloat(requiredInput.value);
        const servings = getServings();

        if (!isNaN(requiredAmount) && !isNaN(servings) && servings > 0) {
            const perServing = requiredAmount / servings;
            row.dataset.perServing = perServing; // 保持（1人分の値をDOMに置く）
            perServingSpan.textContent = (Math.round(perServing * 10) / 10).toString();
        } else {
            perServingSpan.textContent = '-';
        }
    }

    // 提供人数変更時に必要量(g)を再計算する（1人分の基準値×提供人数）
    function applyServingsToRow(row) {
        const requiredInput = row.querySelector('.required-amount-input');
        const perServingSpan = row.querySelector('.per-serving-display');

        const perServing = parseFloat(row.dataset.perServing);
        const servings = getServings();

        if (!isNaN(perServing) && !isNaN(servings) && servings > 0) {
            requiredInput.value = Math.round(perServing * servings * 10) / 10;
            perServingSpan.textContent = (Math.round(perServing * 10) / 10).toString();
        }
    }

    // 削除ボタンの表示制御
    function toggleDeleteButtons() {
        const rows = container.querySelectorAll('.item-row'); // 全要素をNodeListとして返す
        rows.forEach((row) => {
            const btn = row.querySelector('.remove-btn');
            btn.classList.toggle('hidden', rows.length <= 1); // 行が1つだけなら削除ボタンを隠す
        });
    }

    // 1. 入力イベントの振り分け（食材選択 / 数量入力 / g直接入力）
    container.addEventListener('input', function (e) { // イベント委譲: 親で受けてe.target.closest()で行を特定する
        const row = e.target.closest('.item-row');

        if (e.target.name === 'item_ids[]') {
            const input = e.target;
            const selectedValue = input.value;
            const option = document.querySelector(`#${listId} option[value="${selectedValue}"]`);
            const unitSpan = row.querySelector('.unit-display');

            if (option) {
                unitSpan.textContent = option.dataset.unit; // 食材の単位を表示(textContentでXSS対策)
                row.dataset.gramPerUnit = option.dataset.gramPerUnit; // 保持(g換算係数をDOMに置く)
            } else {
                unitSpan.textContent = '';
                row.dataset.gramPerUnit = '';
            }

            // 必要量(g)の計算を呼び出す
            recalcRequiredAmount(row);

        } else if (e.target.classList.contains('quantity-input')) {
            // 数量（個数など）が操作されたら必要量(g)の計算から実行
            recalcRequiredAmount(row);
        } else if (e.target.classList.contains('required-amount-input')) {
            // 必要量(g)を直接操作されたら、その値をそのまま使って1人分を再計算
            syncPerServing(row);
        }
    });

    // 2-1. 行の追加
    addButton.addEventListener('click', function () {
        const firstRow = container.querySelector('.item-row');
        if (!firstRow) return;

        const newRow = firstRow.cloneNode(true); // 子孫要素も含めて複製

        // 入力値・単位表示・g換算値をクリア
        newRow.querySelectorAll('input').forEach((input) => (input.value = ''));
        newRow.querySelector('.unit-display').textContent = '';
        newRow.querySelector('.per-serving-display').textContent = '-';
        newRow.dataset.gramPerUnit = '';
        newRow.dataset.perServing = '';
        newRow.querySelector('.remove-btn').classList.remove('hidden');

        container.appendChild(newRow); // 画面出力（DOMに追加）
        toggleDeleteButtons(); // 削除ボタンの表示を再計算
    });

    // 2-2. 行の削除
    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-btn')) {
            // 最後の1行の場合は削除させない
            if (container.querySelectorAll('.item-row').length > 1) {
                e.target.closest('.item-row').remove();
                toggleDeleteButtons();
            }
        }
    });

    // 初期状態の削除ボタンチェック
    toggleDeleteButtons();

    // 初期表示時に、既存データから「1人分」の基準値を計算しておく（編集画面用）
    container.querySelectorAll('.item-row').forEach(syncPerServing);

    // 3. 提供人数の変更に応じて、必要量または1人分の値を再計算する
    // 編集画面 true->1人分を維持したまま、人数に応じて必要量を再計算
    // 登録画面 false->入力したg数はそのまま、1人分表示だけを更新する
    const servingsInput = document.getElementById(servingsInputId);
    if (servingsInput) {
        servingsInput.addEventListener('input', function () {
            container
                .querySelectorAll('.item-row')
                .forEach(rescaleOnServingsChange ? applyServingsToRow : syncPerServing);
        });
    }
}
