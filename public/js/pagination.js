/* document.addEventListener('DOMContentLoaded', function () {

  const boutonPagination = document.querySelector(".numeroPage");

  console.log(boutonPagination);

  boutonPagination.addEventListener("keydown", function(event) {
    console.log("test")

    if (event.key === "Enter" || event.keyCode === 13) {
      event.preventDefault();
      // attributions des valeurs dans des variables pour rendre le tout plus lisible
      const numeroPage = parseInt(boutonPagination.value);
      const dernierePage = parseInt(boutonPagination.dataset.dernierePage);

      if (numeroPage >= 1 && numeroPage <= dernierePage) {
        // affichage de la page ciblée
        window.location.href = window.location.origin + '/bouteilles?page=' + numeroPage;
      } else {
        // en cas de valeur non valide, retour à la page 1
        window.location.href = window.location.origin + '/bouteilles?page=1';
      }
    }
  });
}); */


// Sélectionnez la DIV qui contient les résultats de recherche
const divResultats = document.querySelector('.carte-vin-container');

// Créez un nouvel observateur de mutations
const observer = new MutationObserver(function (mutationsList, observer) {
    const boutonPagination = document.querySelector(".numeroPage");

    console.log(boutonPagination);

    boutonPagination.addEventListener("keydown", function(event) {
        console.log("test")

        if (event.key === "Enter" || event.keyCode === 13) {
            event.preventDefault();
            // attributions des valeurs dans des variables pour rendre le tout plus lisible
            const numeroPage = parseInt(boutonPagination.value);
            const dernierePage = parseInt(boutonPagination.dataset.dernierePage);

            if (numeroPage >= 1 && numeroPage <= dernierePage) {
                // affichage de la page ciblée
                window.location.href = window.location.origin + '/bouteilles?page=' + numeroPage;
            } else {
                // en cas de valeur non valide, retour à la page 1
                window.location.href = window.location.origin + '/bouteilles?page=1';
            }
        }
    });
});

// Options pour l'observateur de mutations
const options = { childList: true, subtree: true };

// Commencez à observer la DIV des résultats
observer.observe(divResultats, options);
