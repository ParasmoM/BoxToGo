document.addEventListener("DOMContentLoaded", function() {
    const actionButtons = document.querySelectorAll("#action-btn");
    let previousForm = null; 

    actionButtons.forEach(btn => {
        btn.addEventListener("click", (event) => {
            event.stopPropagation(); 

            if (previousForm) {
                previousForm.classList.remove('show');
            }

            const formElement = btn.nextElementSibling;
            
            if (formElement && formElement.tagName === 'FORM') {
                formElement.classList.add('show');
                previousForm = formElement; 
            }
        });
    });

    document.addEventListener("click", () => {
        if (previousForm) {
            previousForm.classList.remove('show');
        }
    });
});
