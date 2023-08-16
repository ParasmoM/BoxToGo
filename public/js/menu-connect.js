// Cible tous les éléments avec la classe .decoItems
const decoItems = document.querySelectorAll('#decoItems');
const decoStatus = document.querySelectorAll('#decoStatus');
const listingList = document.querySelector('.listing-form__description-list');
const form = document.querySelector('.listing-form__form-list');
const section = document.querySelector('.listing-form__form-container');
const button = document.getElementById('myButton');
let currentIndex = 0;  // Pour suivre l'élément actuellement animé.

button.addEventListener('click', function() {
    // Gestion des decoItems et decoStatus
    if (currentIndex < decoItems.length) {
        decoItems[currentIndex].classList.add('animate');
    }

    if (currentIndex < decoStatus.length) {
        if (decoStatus[currentIndex].classList.contains('active')) {
            decoStatus[currentIndex].classList.replace('active', 'actived-old');
        }

        if (currentIndex + 1 < decoStatus.length) {
            decoStatus[currentIndex + 1].classList.add('active');
        }
    }

    currentIndex++;
    if(currentIndex == 3) {
        listingList.style.marginTop = "-290px";
        form.submit(); // Décommentez si vous souhaitez soumettre le formulaire
    }

    section.style.marginTop += "-73rem";

});
