import { setupItemRows } from './item-rows';

document.addEventListener('DOMContentLoaded', function () {
    // 食材枠の設定
    setupItemRows({
        containerId: 'ingredient-container',
        buttonId: 'add-ingredient-btn',
        listId: 'ingredient-list',
    });

    // 調味料枠の設定
    setupItemRows({
        containerId: 'seasoning-container',
        buttonId: 'add-seasoning-btn',
        listId: 'seasoning-list',
    });
});
