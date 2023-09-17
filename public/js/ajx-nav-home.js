console.log('Début du script ajax navbar');
window.onload = () => {
    const BUTTONS = document.querySelectorAll('#btn-filter'); // changez #btn à .btn-filter dans votre HTML

    if (BUTTONS.length === 0) {
        console.log("Aucun bouton trouvé.");
        return;
    }

    BUTTONS.forEach((btn) => {
        btn.addEventListener('click', async (e) => {
            const TYPE_ID = btn.getAttribute('data-type-id');

            const fetchData = async () => {
                try {
                    const url = new URL(window.location.href);
                    url.searchParams.set('filter', TYPE_ID);
                    url.searchParams.set('page', 1);
                    
                    console.log('Envoi de la requête avec l\'URL', url.toString());

                    const response = await fetch(url.toString(), { // Utilisez l'URL modifiée
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    });
                    
                    
                    if (!response.ok) {
                        throw new Error('La requête a échoué');
                    }

                    const data = await response.json();
                    
                    const content = document.querySelector('.home__listing-container');
                    content.innerHTML = data.content;
                        
                    const pagination = document.querySelector('.knp-pagination');
                    pagination.innerHTML = data.pagination;
                    history.pushState({}, null, url);
                    
                } catch (error) {
                    console.error('Erreur:', error);
                }
            };
            
            fetchData();
        });
    });
}
