// Cible tous les éléments avec la classe .decoItems
const decoItems = document.querySelectorAll('#decoItems');
const decoStatus = document.querySelectorAll('#decoStatus');
const listingList = document.querySelector('.listing-form__description-list');

const button = document.getElementById('myButton');
let currentIndex = 0;  // Pour suivre l'élément actuellement animé.

button.addEventListener('click', function() {
    if(currentIndex == 4) {
        const form = document.querySelector('form');  
        form.submit();
    }
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
        // form.submit(); // Décommentez si vous souhaitez soumettre le formulaire
    }
});

const sections = document.querySelectorAll('.listing-form__step');

button.addEventListener('click', function() {
    // Recherchez la section avec la classe .show
    let currentSection = null;
    for (let i = 0; i < sections.length; i++) {
        if (sections[i].classList.contains('show')) {
            currentSection = sections[i];
            break;
        }
    }

    // Si une section avec .show est trouvée
    if (currentSection) {
        currentSection.classList.remove('show');
        currentSection.classList.add('prev');
        
        // Vérifiez s'il y a une section suivante
        const nextSectionIndex = Array.from(sections).indexOf(currentSection) + 1;
        if (nextSectionIndex < sections.length) {
            sections[nextSectionIndex].classList.add('show');
        }
    }
});


const container = document.querySelector('#steps_form_formStep3_spaceEquipments');
const inputs = Array.from(container.querySelectorAll('input[type="checkbox"]'));

const div1 = document.createElement('div');
const div2 = document.createElement('div');

let halfwayPoint = Math.ceil(inputs.length / 2);

inputs.forEach((input, index) => {
    const label = container.querySelector(`label[for="${input.id}"]`);
    
    const p = document.createElement('p');
    p.appendChild(input);
    p.appendChild(label);

    if (index < halfwayPoint) {
        div1.appendChild(p);
    } else {
        div2.appendChild(p);
    }
});

container.appendChild(div1);
container.appendChild(div2);

