const updateButtons = document.querySelectorAll('.boutonUpdate');
const updateDialog = document.querySelector('.modalePage');
const usernameInput = document.querySelector('[name="username"]');
const emailInput = document.querySelector('[name="email"]');
const updateForm = document.querySelector('.formulairePage');
const closeDialogButton = document.querySelector('.boutonCellier-cancel');


updateButtons.forEach(button => {

    button.addEventListener('click', (event) => {

        const userId = event.target.dataset.id
        const username = event.target.dataset.username
        const email = event.target.dataset.email

        updateForm.action = updateForm.action.replace('id-user', userId);

        usernameInput.value = username;
        emailInput.value = email;

        updateDialog.showModal();
    });

    closeDialogButton.addEventListener('click', (e) => {
        
        e.preventDefault();
        updateDialog.close();
    });
});
