document.addEventListener("DOMContentLoaded", function() {
    const etoiles = document.querySelectorAll(".material-symbols-outlined:not(.exclure)");
    let noteInput = document.querySelector("#note");

    function updateEtoile(note){
        etoiles.forEach((etoile, index) => {
            const iconElement = etoile.querySelector(".material-icons");
            if (index < note) { 
                etoile.classList.add("actif"); // on ajoute la class 'actif' si la note (plus bas) est plus grande que l'index de l'etoile

            } else {
                etoile.classList.remove("actif");

            }
        });
    }

    etoiles.forEach(etoile => {
        etoile.addEventListener("click", function() { // on met un listener sur chaque etoile
            let note = parseInt(this.dataset.note); // je prend le data-note assigner à l'étoile cliqué (1 a 5)
            console.log("Note:", note);
            noteInput.value = note;

            updateEtoile(note); // on appel la function 'updateEtoile' pour changer l'apparance en consequence de la note (qui est le parsint du data-note)
        })
    })

});