// Cible tous les éléments avec la classe .decoItems
const decoItems = document.querySelectorAll('#decoItems');
const decoStatus = document.querySelectorAll('#decoStatus');
const listingList = document.querySelector('.listing-form__description-list');

let currentIndex = 0;  // Pour suivre l'élément actuellement animé. Notez que j'ai changé const en let

const button = document.getElementById('myButton');
button.addEventListener('click', function() {
    // Si l'index courant est encore dans la plage des éléments
    if (currentIndex < decoItems.length) {
        // Ajouter la classe animate à l'élément courant
        decoItems[currentIndex].classList.add('animate');
    }

    if (currentIndex < decoStatus.length) {
        // Si decoStatus[currentIndex] a la classe 'active'
        if (decoStatus[currentIndex].classList.contains('active')) {
            // Remplacez 'active' par 'actived-old'
            decoStatus[currentIndex].classList.replace('active', 'actived-old');
        }
        
        // Si l'index suivant est dans la plage, ajoutez la classe 'active' à cet élément
        if (currentIndex + 1 < decoStatus.length) {
            decoStatus[currentIndex + 1].classList.add('active');
        }
    }

    currentIndex++;
    if(currentIndex == 3) {
        listingList.style.marginTop = "-290px";
    }
});
