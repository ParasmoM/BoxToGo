document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector('.space-detail__info-equipments');
    if (!container) return;
    
    const allParagraphs = Array.from(container.querySelectorAll('p'));
    let newDiv;
    
    allParagraphs.forEach((paragraph, index) => {
        if (index % 10 === 0) {
            if (newDiv) {
                container.appendChild(newDiv);
            }
            newDiv = document.createElement('div');
            newDiv.classList.add('group-of-10');
        }
        
        newDiv.appendChild(paragraph);
    });
    
    if (newDiv) {
        container.appendChild(newDiv);
    }
});