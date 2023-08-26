document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner la section contenant les boutons de langue
    const languageSection = document.querySelector('.settings__description-langs');
    // Sélectionner tous les boutons de langue
    const languageButtons = languageSection.querySelectorAll('.langBtn');
    // Sélectionner toutes les zones de texte de description
    const descriptionSets = languageSection.querySelectorAll('.settings__description');

    languageButtons.forEach((button, index) => {
        button.addEventListener('click', function(event) {
            // Prévenir l'action par défaut du bouton
            event.preventDefault();

            // Retirer la classe active de tous les boutons
            languageButtons.forEach(btn => btn.classList.remove('active'));

            // Ajouter la classe active au bouton cliqué
            button.classList.add('active');

            // Gérer les zones de texte de description
            descriptionSets.forEach((descriptionSet, descIndex) => {
                if (descIndex === index) {
                    descriptionSet.classList.add('active');
                } else {
                    descriptionSet.classList.remove('active');
                }
            });
        });
    });
});
