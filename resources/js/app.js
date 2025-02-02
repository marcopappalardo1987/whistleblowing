import 'bootstrap';
import '../sass/app.scss';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    // Seleziona il pulsante "Seleziona tutto" e aggiunge un gestore di eventi per il click
    const selectAllButton = document.getElementById('select-all');
    if (selectAllButton) {
        selectAllButton.addEventListener('click', function() {
            // Seleziona tutte le checkbox con il nome "selected_ids[]"
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            // Verifica se tutte le checkbox sono selezionate
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            
            // Alterna lo stato di selezione delle checkbox
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        });
    }
    // Seleziona il pulsante di conferma e aggiunge un gestore di eventi per il click
    const confirmSubmitButton = document.getElementById('confirm-submit');
    if (confirmSubmitButton) {
        confirmSubmitButton.addEventListener('click', function() {
            // Seleziona tutte le checkbox selezionate
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]:checked');
            // Controlla se nessuna checkbox è selezionata
            if (checkboxes.length === 0) {
                alert(this.getAttribute('message-at-least-one')); // Mostra un avviso
            } else if (confirm(this.getAttribute('message'))) { // Chiede conferma per l'invio
                this.closest('form').submit(); // Invia il modulo
            }
        });
    }

    // Questo codice gestisce l'apertura e la chiusura dei sottomenu nella sidebar.
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.sidebar'); // Seleziona la barra laterale
        const submenus = document.querySelectorAll('.collapse'); // Seleziona tutti i sottomenu
        const submenuLinks = document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]'); // Seleziona i link dei sottomenu

        // Chiude i sottomenu quando il cursore esce dalla barra laterale
        sidebar.addEventListener('mouseleave', function () {
            submenus.forEach(submenu => {
                submenu.classList.remove('show'); // Rimuove la classe 'show' dai sottomenu
            });
        });

        // Gestisce il click sui link dei sottomenu
        submenuLinks.forEach(link => {
            link.addEventListener('click', function () {
                // Chiude tutti i sottomenu tranne quello corrente
                submenus.forEach(submenu => {
                    if (submenu !== this.nextElementSibling) {
                        submenu.classList.remove('show'); // Rimuove la classe 'show' dai sottomenu
                    }
                });
                // Alterna la visibilità del sottomenu corrente
                this.nextElementSibling.classList.toggle('show'); // Aggiunge o rimuove la classe 'show'
            });
        });
    });
});
