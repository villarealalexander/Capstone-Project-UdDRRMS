import './bootstrap';

// Define a function for email validation
function setupEmailValidation() {
    var emailInput = document.getElementById('email');
    var emailError = document.getElementById('email-error');

    // Add event listener for input events (typing, modification)
    emailInput.addEventListener('input', function () {
        validateEmail(this.value.trim()); // Validate email on input change
    });

    // Add event listener for blur event (when input field loses focus)
    emailInput.addEventListener('blur', function () {
        validateEmail(this.value.trim()); // Validate email on blur
    });

    // Function to validate email format
    function validateEmail(emailValue) {
        if (emailValue === '') {
            emailError.textContent = ''; // Clear error message if email input is empty
        } else if (!emailValue.includes('@')) {
            emailError.textContent = 'Please include "@" in the email address.';
        } else if (!emailValue.endsWith('@cdd.edu.ph')) {
            emailError.textContent = 'Please use @cdd.edu.ph domain.';
        } else {
            emailError.textContent = ''; // Clear error message if email input is valid
        }
    }
}


// Call setupEmailValidation function when the DOM content is fully loaded
document.addEventListener('DOMContentLoaded', function () {
    setupEmailValidation(); // Initialize email validation
});

document.getElementById('selectAll').addEventListener('change', function() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_users[]"]');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
});