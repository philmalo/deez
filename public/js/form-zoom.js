document.addEventListener("DOMContentLoaded", function() {
    const bouteilleContainer = document.querySelector(".bouteille-container");
    const bouteilleImg = document.querySelector(".bouteille-img");
    const textBox = document.querySelector(".description-container");
    const footer = document.querySelector("footer");
    
    function zoomBouteille() {
        bouteilleContainer.addEventListener("click", function() {
            console.log("zoom");
            
            bouteilleContainer.classList.toggle("bouteille-container"); // Étant donnée que la classe est déja existante, cette action va le toggle OFF
            bouteilleContainer.classList.toggle("bouteille-container-zoom"); // Celle-ci va simplement etre toggle ON... et vice-versa
            
            bouteilleImg.classList.toggle("bouteille-img");
            bouteilleImg.classList.toggle("bouteille-img-zoom");
            
            textBox.classList.toggle("description-container");
            textBox.classList.toggle("description-container-zoom");
            
            if(footer.classList.contains("footer-zoom")) {
                console.log("un")
                footer.classList.toggle("footer-none");
                // footer.classList.toggle("footer-zoom");
                setTimeout(function() {
                    footer.classList.toggle("footer-zoom");
                }, /* Add a delay in milliseconds, for example: */ 30);
            } else {
                footer.classList.toggle("footer-zoom");
                footer.addEventListener("transitionend", function transition(){
                    console.log("deux")
                    footer.classList.toggle("footer-none");
                    footer.removeEventListener("transitionend", transition);
                })
            }
        });
    }

    zoomBouteille();
});