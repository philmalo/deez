document.addEventListener("DOMContentLoaded", function() {
    const couleursButton = document.querySelector(".filtre-button-couleurs");
    const checkboxes = document.querySelectorAll(".input-checkbox");

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function() {

            const checkedCheckboxes = document.querySelectorAll(".input-checkbox:checked");
                console.log(checkedCheckboxes.length)
            if (checkedCheckboxes.length > 0) {
                
                couleursButton.classList.add("bordertop");
            } else {
                couleursButton.classList.remove("bordertop");
            }
        });
    });
});