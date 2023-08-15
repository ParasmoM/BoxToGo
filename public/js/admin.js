// Sélection des éléments
const FORMS_EDIT = document.querySelectorAll('dashboard-add__form');

FORMS_EDIT.forEach(form => {
    let inputElement = form.querySelector('input');
    let timeout;

    inputElement.addEventListener('change', (e) => {
        clearTimeout(timeout); // Si l'input change à nouveau avant les 5 secondes, on réinitialise le timer.

        timeout = setTimeout(() => {
            form.submit();
        }, 5000);
    });

    document.addEventListener('click', (e) => {
        // Si le clic est en dehors de l'input et que l'input a changé.
        if (!form.contains(e.target) && inputElement.value) {
            clearTimeout(timeout); // On réinitialise le timer.
            form.submit();
        }
    });
});