const modal = document.querySelector("#modal");

window.addEventListener("click", function(event) {
    if (event.target === modal) {
        modal.close();
    }
});

function openModal(nom, id, format) {
    let modal = document.querySelector("#modal");
    let modalTitle = document.querySelector("#modal-title");
    let form = document.querySelector("#modal-form");
    let bouteilleIdInput = document.querySelector("#bouteille-id");
    let bouteilleIdListe = document.querySelector("#bouteille-id-liste");
    let quantityInput = document.querySelector("#quantity");
    let quantiteActuelle = document.querySelector("#quantite-actuelle");
    let cellierIdInput = document.querySelector("#cellier-id");
    let infoVignette = document.querySelector("#info-vignette");

    infoVignette.innerHTML = format;
    modalTitle.innerText = nom;
    bouteilleIdInput.value = id;
    bouteilleIdListe.value = id;
    if(cellierIdInput != null){
        cellierIdInput.value = cellierId;
        quantityInput.value = parseInt(quantiteActuelle.innerHTML);
    }

    form.reset();

    modal.showModal();
}

function closeModal() {
    let modal = document.querySelector("#modal");
    let form = document.querySelector("#modal-form");
    
    form.reset();

    modal.close();
}

function decrementQuantity() {
    let quantityInput = document.querySelector("#quantity");
    let currentQuantity = parseInt(quantityInput.value);

    if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
    }
}

function incrementQuantity() {
    let quantityInput = document.querySelector("#quantity");
    let currentQuantity = parseInt(quantityInput.value);

    quantityInput.value = currentQuantity + 1;
}

function nouveauCellier(){
    modal.showModal();
}
