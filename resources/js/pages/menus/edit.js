import { setupItemRows } from './item-rows';

document.addEventListener('DOMContentLoaded', function () {
    setupItemRows({
        containerId: 'item-container',
        buttonId: 'add-item-btn',
        listId: 'item-list',
        rescaleOnServingsChange: true,
    });
});
