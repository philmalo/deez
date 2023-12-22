const modifierQuantite = document.querySelectorAll('.modifierQuantite');
const modaleModifier = document.querySelector('.modalePage');
const fermerModale = modaleModifier.querySelector('.boutonCellier-cancel');

let inputBouteilles = modaleModifier.querySelector('.inputQuantite');
let formulaire = modaleModifier.querySelector('form');

let ajout = modaleModifier.querySelector('.plus');
let retrait = modaleModifier.querySelector('.moins');

modifierQuantite.forEach((bouteille) =>{
    bouteille.addEventListener('click', (e) => {
        inputBouteilles.value = bouteille.dataset.nombre;
        formulaire.action = formulaire.action.replace('id-bouteille', bouteille.dataset.id)
        modaleModifier.showModal();
        console.log('Dans Bouteille')

        fermerModale.addEventListener('click', (e) => {
            console.log('Dans fermerModal')
            e.preventDefault();
            modaleModifier.close();
        })
    })
})

ajout.addEventListener('click', (e) => {
    inputBouteilles.value = parseInt(inputBouteilles.value) + 1;
})

retrait.addEventListener('click', (e) => {
    if(parseInt(inputBouteilles.value) - 1 == 0){
        inputBouteilles.value = 1;
    }
    else{
        inputBouteilles.value = parseInt(inputBouteilles.value) - 1;
    }
})

// fermerModale.addEventListener('click', (e) => {
//     e.preventDefault();
//     modaleModifier.close();
// })

window.addEventListener('click', (e) => {
    if(e.target === modaleModifier){
        modaleModifier.close();
    }
})
