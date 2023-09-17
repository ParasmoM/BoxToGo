// Cible la div parent
const parentDiv = document.getElementById('form_add_new_space_amenity_amenities');

// Sélectionne tous les inputs et les labels
const inputs = parentDiv.querySelectorAll('input');
const labels = parentDiv.querySelectorAll('label');

// Vérifie que le nombre d'inputs et de labels est le même
if (inputs.length === labels.length) {
  // Supprime le contenu original de la div parent
  while (parentDiv.firstChild) {
    parentDiv.removeChild(parentDiv.firstChild);
  }
  
  // Parcourt chaque input et label
  for (let i = 0; i < inputs.length; i++) {
    // Crée une nouvelle div
    const newDiv = document.createElement('div');
    
    // Ajoute l'input à la nouvelle div
    newDiv.appendChild(inputs[i]);
    
    // Crée un label vide
    const emptyLabel = document.createElement('label');
    
    // Ajoute le label vide à la nouvelle div
    newDiv.appendChild(emptyLabel);
    
    // Ajoute le label original à la nouvelle div
    newDiv.appendChild(labels[i]);
    
    // Ajoute la nouvelle div à la div parent
    parentDiv.appendChild(newDiv);
  }
} else {
  console.error("Le nombre d'inputs et de labels n'est pas le même.");
}
