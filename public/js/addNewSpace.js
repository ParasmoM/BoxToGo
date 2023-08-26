document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('form_add_new_space_equipment_spaceEquipments');
    
    if (!container) return; // Exit if container not found
    
    const inputs = Array.from(container.querySelectorAll('input[type="checkbox"]'));
    const labels = Array.from(container.querySelectorAll('label'));
    
    if (inputs.length !== labels.length) return; // Exit if input count does not match label count
    
    // Clear the container
    while (container.firstChild) {
        container.removeChild(container.firstChild);
    }
    
    let parentDiv;
    inputs.forEach((input, index) => {
        if (index % 10 === 0) {
            parentDiv = document.createElement('div');
            parentDiv.className = 'parent-div';
            container.appendChild(parentDiv);
        }
        
        const newDiv = document.createElement('div');
        newDiv.className = 'listing__template-step__input-checkbox';
        
        // Append input and labels
        newDiv.appendChild(input);
        
        // Insert empty label
        const emptyLabel = document.createElement('label');
        newDiv.appendChild(emptyLabel);
        
        newDiv.appendChild(labels[index]);
        parentDiv.appendChild(newDiv);
    });
});

const button = document.getElementById('myNextButton');
const sections = document.querySelectorAll('.listing__template-step');
const form = document.querySelector('.listing__template-steps'); // Sélectionnez le formulaire

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
        } else {
            // Si on est à la dernière section, soumettre le formulaire
            form.submit();
        }
        console.log(nextSectionIndex);
    }
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