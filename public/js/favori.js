console.log('Début du script favorite');
// document.addEventListener('DOMContentLoaded', function() {
    // Récupérez tous les boutons avec la classe favorite
    let favButtons = document.querySelectorAll('.card-space__icon-btn');
    console.log(favButtons);
    // Ajoutez un gestionnaire d'événements à chaque bouton
    favButtons.forEach((favButton) => {
        favButton.addEventListener('click', (event) => {
            try {
                // Empêche le comportement par défaut du bouton (rechargement de la page)
                event.preventDefault();

                // Récupérez les attributs data du bouton cliqué
                const userId = favButton.getAttribute('data-user-id');
                const spaceId = favButton.getAttribute('data-space-id');
                
                // Fonction asynchrone pour effectuer la requête AJAX
                const sendRequest = () => {
                    fetch('/fetch/favorites', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ userId, spaceId }),
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('La requête a échoué');
                        }
                    })
                    // .then(data => {
                    //     // console.log('Données JSON:', data);
                            
                    //     const container = document.querySelector(".favorite__content-container");
                    //     if (container) {
                    //         document.querySelector(".favorite__content-container").innerHTML = data.content;
                    //     }
                    // })
                    .catch(error => {
                        console.error('Une erreur s\'est produite:', error);
                    });
                    
                };

                // Appel de la fonction 
                sendRequest();
                
            } catch (error) {
                console.error('Une erreur s\'est produite:', error);
            }
            console.log('click', favButton);
            favButton.classList.toggle('active');
            if (window.location.pathname === '/favorites') {
                favButton.parentNode.style.display = 'none'; // Cache l'élément parent
            }

        
        });
    });
// });