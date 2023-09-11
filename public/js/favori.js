console.log('Début du script favorite');
document.addEventListener('DOMContentLoaded', function() {
    // Récupérez tous les boutons avec la classe favorite
    const favButtons = document.querySelectorAll('.card-space__icon-btn');
    console.log(favButtons, 'testing');
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
                    .then(data => {
                        console.log('Réponse du serveur:', data);
                                                
                        // Mettez à jour l'icône du cœur si l'action a réussi
                        if (data.success) {
                            const heartIcon = favButton.querySelector('img');
                            const isFavorited = heartIcon.src.includes('coeur_rempli');
                            heartIcon.src = isFavorited ? "{{ asset('images/icones/favorite.svg') }}" : "{{ asset('images/icones/heartColor.svg') }}";
                        }
                    })
                    .catch(error => {
                        console.error('Une erreur s\'est produite:', error);
                    });
                    
                };

                // Appel de la fonction 
                sendRequest();

            } catch (error) {
                console.error('Une erreur s\'est produite:', error);
            }
        });
    });
});
