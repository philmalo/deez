document.addEventListener("DOMContentLoaded", function() {
    const Labels = document.querySelectorAll(".label-simple");
    const couleursButton = document.querySelector(".filtre-button-couleurs");
    const checkboxes = document.querySelectorAll(".input-checkbox");

    Labels.forEach(label => {
        const checkbox = label.closest(".label-simple").querySelector(".input-checkbox");

        label.addEventListener("click", function() {
            checkbox.checked = !checkbox.checked; // equivalent d'un toggle pour un status, on check le input

            const checkedCheckboxes = document.querySelectorAll(".input-checkbox:checked"); // ici, on ajoute un border pour les pillules
            console.log(checkedCheckboxes.length)
            if (checkedCheckboxes.length > 0) {
                
                couleursButton.classList.add("bordertop");
            } else {
                couleursButton.classList.remove("bordertop");
        }
        });
    });

    // checkboxes.forEach(checkbox => {
    //     checkbox.addEventListener("click", function() {
    //         console.log("CHeckbox");
    //     })
    // })
});