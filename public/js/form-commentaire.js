
    let formCommentaire = document.querySelector('#form-commentaire');
    let boutonModifier = document.querySelector('#btn-modifier-commentaire');
    let commentaire = document.querySelector('.commentaire-note .commentaire');
    let note = document.querySelector('.commentaire-note .note');

    if(commentaire === null && note === null){
        formCommentaire.style.display = 'block';
    } else {
        console.log('ok');
        formCommentaire.style.display = 'none';
    }

    boutonModifier.addEventListener('click', function (e) {
        formCommentaire.style.display = 'block';
        boutonModifier.style.display = 'none';
        formCommentaire.scrollIntoView({behavior: "smooth", block: "end", inline: "nearest"});

    });

    formCommentaire.addEventListener('submit', function (e) {
        boutonModifier.style.display = 'block';
        formCommentaire.style.display = 'none';
    });
