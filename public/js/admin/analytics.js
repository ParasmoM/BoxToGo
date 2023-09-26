
// Sélectionne toutes les div avec la classe "utils__items"
const utilsItems = document.querySelectorAll('.utils__items');

// Ajoute un écouteur d'événement à chaque div
utilsItems.forEach((item) => {
    item.addEventListener('click', function() {
        
        // Réinitialise la propriété maxHeight pour toutes les div
        utilsItems.forEach((resetItem) => {
            resetItem.style.maxHeight = '10rem';
        });

        // Définit la propriété maxHeight à "fit-content" pour la div cliquée
        this.style.maxHeight = '40rem';
    });
});


document.addEventListener("DOMContentLoaded", function() {
    const selectStatus = document.querySelector(".admin-analytics-search__input-status");
    const selectCategory = document.querySelector(".admin-analytics-search__input-category");
    
    // Fonction pour soumettre le formulaire
    const submitForm = (event) => {
        // Récupérer la valeur du select
        const value = event.target.value;
        
        // Vérifier si la valeur n'est pas vide
        if (value !== "") {
            const form = document.querySelector(".admin-analytics-search");
            form.submit();
        }
    };

    // Ajoutez des écouteurs d'événements pour le changement des selects
    selectStatus.addEventListener("change", submitForm);
    selectCategory.addEventListener("change", submitForm);
});

