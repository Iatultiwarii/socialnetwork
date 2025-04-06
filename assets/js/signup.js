
document.addEventListener("DOMContentLoaded", function () {
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");
    const passwordError = document.getElementById("passwordError");
    const confirmPasswordError = document.getElementById("confirmPasswordError");
    const passwordPattern = /^[a-zA-Z0-9$%@&*]+$/;
    function validatePasswords() {
        const passVal = password.value.trim();
        const confirmVal = confirmPassword.value.trim();
        password.style.borderColor = "#ccc";
        confirmPassword.style.borderColor = "#ccc";
        passwordError.textContent = "";
        confirmPasswordError.textContent = "";
        if (!passwordPattern.test(passVal)) {
            passwordError.textContent = "Password can only contain letters (a-z, A-Z), numbers (0-9), and special characters ($%@&*)";
            password.style.borderColor = "red";
        }
        if (confirmVal.length > 0 && passVal !== confirmVal) {
            confirmPasswordError.textContent = "Passwords do not match!";
            confirmPassword.style.borderColor = "red";
        }
    }
    password.addEventListener("input", validatePasswords);
    confirmPassword.addEventListener("input", validatePasswords);
});

