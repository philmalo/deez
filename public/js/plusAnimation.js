document.addEventListener("DOMContentLoaded", function() {

    const filters = [
        { plusSign: ".plus-couleurs", button: ".filtre-button-couleurs" },
        { plusSign: ".plus-pays", button: ".filtre-button-pays" },
        { plusSign: ".plus-prix", button: ".filtre-button-prix" },
        { plusSign: ".plus-cepages", button: ".filtre-button-cepages" },
        { plusSign: ".plus-pastilles", button: ".filtre-button-pastilles" }
    ];

    filters.forEach(filter => {
        const plusButton = document.querySelector(filter.button);
        const plusIcon = document.querySelector(filter.plusSign);
        
        plusButton.addEventListener("click", function(event) {
            plusIcon.classList.toggle("rotate");
            plusButton.classList.toggle("colorChange");
        });
    });
});