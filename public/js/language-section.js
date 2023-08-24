document.addEventListener('DOMContentLoaded', function() {
    const languageSection = document.querySelector('.language-section');
    const languageButtons = languageSection.querySelectorAll('.language-btn');
    const titleSets = languageSection.querySelectorAll('.title-input-set');
    const descriptionSets = languageSection.querySelectorAll('.description-textarea-set');

    languageButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            // Prévenir l'action par défaut du bouton
            event.preventDefault();
            
            // Retirer la classe active de tous les boutons
            languageButtons.forEach(btn => btn.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            button.classList.add('active');

            // Gérer les champs de titre et de description
            titleSets.forEach((titleSet, titleIndex) => {
                if(titleIndex === index) {
                    titleSet.classList.add('active');
                } else {
                    titleSet.classList.remove('active');
                }
            });

            descriptionSets.forEach((descriptionSet, descIndex) => {
                if(descIndex === index) {
                    descriptionSet.classList.add('active');
                } else {
                    descriptionSet.classList.remove('active');
                }
            });
        });
    });
});
