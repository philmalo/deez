document.addEventListener("DOMContentLoaded", function() {
    const formNouveauCommentaire = document.querySelector('#form-commentaire');
    const commentaireExistant = document.querySelector('.commentaire-existant');
    const modifierBouton = document.querySelector('#btn-modifier-commentaire');
    const commentaireTextarea = document.querySelector('#commentaire');

    function MontrerForm() {
        modifierBouton.addEventListener("click", function() {
            console.log("allo");
            formNouveauCommentaire.classList.remove("form-invisible"); // on enleve la classe qui rend le form invisible
            commentaireExistant.classList.add("form-invisible"); // on ajoute la classe qui rend invisible au DOM du commentaire existant
            
            commentaireTextarea.value = commentaireTextarea.getAttribute("data-commentaire"); // En gros... on doit store la valeur du commentaire a l'interieur du tag HTML puisque utiliser les bracket {{ }} se fait cote serveur... donc on se retrouverait litteralement avec un string de l'appel de la variable et non la variable... On doit donc utiliser les dataset

        });
    }

    MontrerForm();
});