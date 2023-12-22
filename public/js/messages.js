// Wait for the DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.querySelector('.alert-success');
    if (successMessage) {
        const closeButton = successMessage.querySelector('.close');
        
        closeButton.addEventListener('click', function() {
            // Remove the alert element when the close button is clicked
            successMessage.remove();
        });
    }
});