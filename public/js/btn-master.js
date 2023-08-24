document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner le formulaire et le bouton
    var form = document.querySelector('.master-form');
    var button = form.querySelector('.btn-master');
    
    // Ajouter un écouteur d'événement pour détecter tout changement dans le formulaire
    form.addEventListener('input', function() {
        button.style.bottom = 0;
    });
});
