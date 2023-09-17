// Sélectionne tous les éléments avec l'ID "myCard" (mauvaise pratique)
const cards = document.querySelectorAll('[id="myCard"]');

if (cards.length > 0) {
    // Applique le style et la classe seulement à la première carte
    const firstCard = cards[0];
    setTimeout(() => {
        firstCard.style.opacity = "1";
        firstCard.style.transform = "translateY(0)";
        firstCard.classList.add("show");
    }, 500);
}

const button = document.getElementById('myNextButton');
const sections = document.querySelectorAll('.listing__template-step');
const form = document.querySelector('.listing__template-steps'); // Sélectionnez le formulaire

button.addEventListener('click', function() {
    // Recherchez la section avec la classe .show
    let currentSection = null;
    let index; 
    for (let i = 0; i < sections.length; i++) {
        if (sections[i].classList.contains('show')) {
            currentSection = sections[i];
            index = i + 1;
            break;
        }
    }
    
    if (currentSection) {
        const focusElements = currentSection.querySelectorAll('.focus');
        let allFieldsFilled = true; // indicateur pour vérifier si tous les champs sont remplis

        for (let focusedElement of focusElements) {
            if (!focusedElement.value) {
                allFieldsFilled = false; // mettez l'indicateur à false si un champ est vide
                console.log('Élément avec la classe focus trouvé et est vide:', focusedElement);
                
                focusedElement.classList.add('highlight-focus');

                setTimeout(() => {
                    focusedElement.classList.remove('highlight-focus');
                }, 1000);

                break; // Sortez de la boucle dès qu'un élément vide est trouvé
            }
        }

        if (allFieldsFilled) {
            currentSection.classList.remove('show');
            currentSection.classList.add('noShow');
            cards[index - 1].classList.remove('show');
            cards[index - 1].classList.add('noShow');

            // Vérifiez s'il y a une section suivante
            const nextSectionIndex = Array.from(sections).indexOf(currentSection) + 1;
            if (nextSectionIndex < sections.length) {
                sections[nextSectionIndex].classList.add('show');
            } else {
                // Si on est à la dernière section, soumettre le formulaire
                form.submit();
            }

            setTimeout(() => {
                cards[index].style.opacity = "1";
                cards[index].style.transform = "translateY(0)";
                cards[index].classList.add("show");
                
                const topElement = cards[index - 1].querySelector('.card--animation');
                console.log(index);
                if (index % 2 === 0) {
                    topElement.classList.add('loadingEffectL');
                } else {
                    topElement.classList.add('loadingEffectR');
                }
            }, 500);

            if (index === 4) {
                console.log('Vérifie');
                setTimeout(() => {
                    cards[0].style.transform = "translateY(-230px)";
                    cards[1].style.transform = "translateY(-230px)";
                    cards[2].style.transform = "translateY(-230px)";
                    cards[3].style.transform = "translateY(-230px)";
                    cards[4].style.transform = "translateY(-230px)";
                }, 500);
            }
        }

    }



    // // Si une section avec .show est trouvée
    // if (currentSection) {
    //     currentSection.classList.remove('show');
    //     currentSection.classList.add('prev');
        
    //     // Vérifiez s'il y a une section suivante
    //     const nextSectionIndex = Array.from(sections).indexOf(currentSection) + 1;
    //     if (nextSectionIndex < sections.length) {
    //         sections[nextSectionIndex].classList.add('show');
    //     } else {
    //         // Si on est à la dernière section, soumettre le formulaire
    //         form.submit();
    //     }
    //     console.log(nextSectionIndex);
    // }
});


// ! j'hesite
// const btn_prev = document.getElementById('myPrevButton'); 
// const btn_next = document.getElementById('myNextButton'); 
// const sections = document.querySelectorAll('.listing__template-step');

// btn_next.addEventListener('click', function() {
//     moveSection(true);
// });

// function moveSection(isNext) {
//     let currentSection = null;
//     for (let i = 0; i < sections.length; i++) {
//         if (sections[i].classList.contains('show')) {
//             currentSection = sections[i];
//             break;
//         }
//     }

//     if (currentSection) {
//         currentSection.classList.remove('show');

//         let nextSectionIndex = Array.from(sections).indexOf(currentSection) + (isNext ? 1 : -1);
        
//         if (nextSectionIndex >= 0 && nextSectionIndex < sections.length) {
//             sections[nextSectionIndex].classList.add('show');
//         } else {
//             // Remettre la classe 'show' si on est à la limite (première ou dernière section)
//             currentSection.classList.add('show');
//         }
//     }
// }