let inputNom = document.querySelector('.input-edit-nom');
let boutonCrayon = document.querySelector('.crayon-edit-icon');
let boutonAnnulerModif = document.querySelector('.bouton-annuler');
let boutonEnregistrer = document.querySelector('.bouton-enregistrer');
let formModifier = document.querySelector('.form-modifier');
let nomCellier = inputNom.value;
let change = false;

inputNom.disabled = true;
boutonAnnulerModif.style.display = 'none';
boutonEnregistrer.style.display = 'none';
inputNom.style.padding = '5px';

boutonCrayon.addEventListener('click', function() {
    boutonCrayon.disabled = true;
    inputNom.classList.add('clickable');
    inputNom.disabled = false;
    boutonAnnulerModif.style.display = 'block';
    boutonCrayon.style.display = 'none';
})  

inputNom.addEventListener('input', function() {
    change = true;

    if(inputNom.value == nomCellier) {
        change = false;
        boutonEnregistrer.style.display = 'none';
        boutonAnnulerModif.style.display = 'block';
    } else {
        boutonEnregistrer.style.display = 'block';
        boutonAnnulerModif.style.display = 'none';
    }
})

boutonAnnulerModif.addEventListener('click', function(event) {
    event.preventDefault();

    if (change) {
        formModifier.submit();
    } else {
        boutonAnnulerModif.style.display = 'none';
        boutonCrayon.style.display = 'block';
        inputNom.disabled = true;
        boutonCrayon.disabled = false;
        inputNom.classList.remove('clickable');
    }
})
