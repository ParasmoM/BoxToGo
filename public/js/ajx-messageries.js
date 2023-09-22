console.log('Début du script');

const fetchData = async () => {
    console.log('Appel de fetchData');
    try {
        const senderDiv = document.querySelector('.sender-conv');
        const senderId = senderDiv.dataset.senderId;
        console.log('senderId:', senderId);

        const url = `/talks/${senderId}/fetch`;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
        });

        if (!response.ok) {
            throw new Error('La requête a échoué');
        }

        const data = await response.json();
        
        console.log(data.message);
        if (data.message === 'new message') {
            location.reload();
        }
        // const content = document.querySelector('.message-board__chat-window');
        // console.log(data, content);
        // content.innerHTML = data.content;
    } catch (error) {
        console.log('Une erreur est survenue: ', error);
    }
};

setInterval(fetchData, 10000);

console.log('Fin du script');
