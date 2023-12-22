const boutonsSupp = document.querySelectorAll('.boutonSupp');
const boiteModale = document.querySelector('.confirmerDel');
const formulaireDels = document.querySelectorAll('.formulaireDel'); // Select all forms
const boutonAnnuler = boiteModale.querySelector('button:last-of-type');
const boutonConfirmer = boiteModale.querySelector('button:first-of-type');
const titreDel = document.getElementById('titreDel');
const texteDel = document.getElementById('texteDel');
// Function to open the modal
function openModal() {
    boiteModale.showModal();
}

// Attach event listener for cancelling modal
boutonAnnuler.addEventListener("click", function(e){
    boiteModale.close();
});

// Attach event listener to close modal when clicking outside
window.addEventListener("click", function(event) {
    if (event.target === boiteModale) {
        boiteModale.close();
    }
});

// Attach event listener for confirming deletion for each form
formulaireDels.forEach(formulaireDel => {
    formulaireDel.addEventListener("submit", function(e) {
        e.preventDefault();
        openModal();
    });
});

// Attach event listener for confirming deletion
boutonConfirmer.addEventListener("click", function(e){
    formulaireDels.forEach(formulaireDel => {
        formulaireDel.submit();
    });
});

// Attach event listener to each delete button
boutonsSupp.forEach(boutonSupp => {
    boutonSupp.addEventListener("click", function(e){
        e.preventDefault();
        const text = boutonSupp.getAttribute('data-text');
        const title = boutonSupp.getAttribute('data-title');
        titreDel.textContent = title;
        texteDel.textContent = text;
        openModal();
    });
});