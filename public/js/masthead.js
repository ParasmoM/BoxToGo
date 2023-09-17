console.log('Running header main');
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


window.addEventListener('scroll', function() {
    const headerElement = document.getElementById('masthead');
    const logoImage = document.querySelector('.logo-image');
    
    if (window.scrollY >= 30) {
        logoImage.classList.add('shrink');
    } else {
        logoImage.classList.remove('shrink');
    }
    if (window.scrollY >= 100) {
        headerElement.classList.add('shrink');
    } else {
        headerElement.classList.remove('shrink');
    }
});



console.log('FIN header main');