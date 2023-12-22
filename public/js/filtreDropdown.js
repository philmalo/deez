document.addEventListener("DOMContentLoaded", function() {
    const filters = [
        { button: ".filtre-button-couleurs", dropdown: ".filtre-dropdown-couleurs" },
        { button: ".filtre-button-pays", dropdown: ".filtre-dropdown-pays" },
        { button: ".filtre-button-prix", dropdown: ".filtre-dropdown-prix" },
        { button: ".filtre-button-cepages", dropdown: ".filtre-dropdown-cepages" },
        { button: ".filtre-button-pastilles", dropdown: ".filtre-dropdown-pastilles" }
    ];

    filters.forEach(filter => {
        const filtreButton = document.querySelector(filter.button);
        const filtreDropdown = document.querySelector(filter.dropdown);

        filtreButton.addEventListener("click", function(event) {
            event.preventDefault();
            filtreDropdown.classList.toggle("show");
            filtreDropdown.classList.toggle("borderbot");
        });
    });
});