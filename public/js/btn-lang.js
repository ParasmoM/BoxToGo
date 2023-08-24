document.addEventListener("DOMContentLoaded", function() {
    const btn = document.querySelector("#btn-lang");
    const ul = document.querySelector("ul"); // Remplacez ceci par votre sélecteur ul approprié si ce n'est pas juste "ul"

    btn.addEventListener("click", function(event) {
        ul.classList.toggle("show");
        event.stopPropagation(); // Cela empêche le document de détecter immédiatement ce clic et de fermer le menu
    });

    document.addEventListener("click", function() {
        ul.classList.remove("show");
    });

    ul.addEventListener("click", function(event) {
        event.stopPropagation(); // Cela empêche le clic sur le ul lui-même de fermer le menu
    });
});
