let dragged;
var form = document.querySelector('.master-form');

document.querySelectorAll('.draggable').forEach((img) => {
    img.addEventListener('dragstart', function(event) {
        dragged = img;  // Mettre à jour la variable dragged
        event.target.style.opacity = 0.5;
    });

    img.addEventListener('dragend', function(event) {
        event.target.style.opacity = "";
    });
});

document.querySelectorAll('.dropzone').forEach((dropzone) => {
    dropzone.addEventListener('dragover', function(event) {
        event.preventDefault();
    });
    dropzone.addEventListener('dragenter', function(event) {
        event.target.style.border = "3px dotted red";
    });
    dropzone.addEventListener('dragleave', function(event) {
        event.target.style.border = "";
    });
    dropzone.addEventListener('drop', function(event) {
        event.preventDefault();
        event.target.style.border = "";
        console.log(event.target)

        const targetImg = event.target.tagName === 'IMG' ? event.target : event.target.querySelector('img');
        
        if (targetImg && dragged) {
            const draggedParent = dragged.parentElement;
            const targetParent = targetImg.parentElement;
            draggedParent.appendChild(targetImg);
            targetParent.appendChild(dragged);
            
            console.log('drop event, calling updateHiddenFields');  // Log pour le debug
            updateHiddenFields(); // Mettre à jour les champs cachés
            form.dispatchEvent(new Event('input'));
        } else {
            console.error("Élément cible ou élément déplacé ne contient pas d'img");
        }
    });
});

function updateHiddenFields() {
    console.log('updateHiddenFields called');  // Log pour le debug
    const hiddenFieldsContainer = document.getElementById('hiddenFields');
    hiddenFieldsContainer.innerHTML = ''; // Réinitialiser les champs cachés

    document.querySelectorAll('.draggable').forEach((img, index) => {
        const id = img.dataset.id; // Assurez-vous que chaque image a un attribut data-id
        console.log(`ID: ${id}, Index: ${index}`);  // Log pour le debug
        const hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.name = `images[${id}]`;
        hiddenField.value = index;

        hiddenFieldsContainer.appendChild(hiddenField);
    });
}
