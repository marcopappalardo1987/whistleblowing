import 'bootstrap';
import '../sass/app.scss';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    const selectAllButton = document.getElementById('select-all');
    if (selectAllButton) {
        selectAllButton.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        });
    }
    const confirmSubmitButton = document.getElementById('confirm-submit');
    if (confirmSubmitButton) {
        confirmSubmitButton.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]:checked');
            if (checkboxes.length === 0) {
                alert(this.getAttribute('message-at-least-one'));
            } else if (confirm(this.getAttribute('message'))) {
                this.closest('form').submit();
            }
        });
    }
});
