const masthead = document.querySelector('#masthead');
const link = document.querySelector('.masthead-nav__publish-link');

// Écoutez l'événement de défilement sur la fenêtre
window.addEventListener('scroll', function() {
    // Si le défilement est supérieur ou égal à 10rem (converti en pixels)
    if (window.scrollY >= 90) { // 16 est une supposition pour 1rem, cela dépend de la taille de base de votre navigateur.
        masthead.style.height = '12rem';
        link.style.display = 'none';
    } else {
        masthead.style.height = ''; // Réinitialisez à la valeur par défaut quand il est moins de 10rem.
        link.style.display = '';
    }
});

// Sélectionnez tous les éléments ayant l'ID btn-dropdown
const dropdowns = document.querySelectorAll('#btn-dropdown');

dropdowns.forEach(dropdown => {
    // Ajoutez un écouteur d'événements de clic
    dropdown.addEventListener('click', function(event) {
        // Sélectionnez le premier ul enfant de l'élément sur lequel vous avez cliqué
        const ulElement = this.querySelector('ul');
        if (ulElement) {
            // Basculez la classe show
            ulElement.classList.toggle('show');
        }
        // Empêche la propagation de l'événement, pour éviter que l'écouteur de document soit déclenché
        event.stopPropagation();
    });
});

// Ajoutez un écouteur d'événement sur document pour détecter un clic en dehors
document.addEventListener('click', function(event) {
    // Si le clic n'était pas sur un dropdown ou son ul enfant
    if (!event.target.closest('#btn-dropdown')) {
        // Retirez la classe show de tous les ul
        dropdowns.forEach(dropdown => {
            const ulElement = dropdown.querySelector('ul');
            if (ulElement) {
                ulElement.classList.remove('show');
            }
        });
    }
});
    