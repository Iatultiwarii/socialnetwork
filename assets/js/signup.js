$(document).ready(function () {
    const $form = $('#signupform');
    const $password = $('#password');
    const $confirmPassword = $('#confirm_password');
    const $passwordError = $('#passwordError');
    const $confirmPasswordError = $('#confirmPasswordError');
    const $email = $('#email');
    const $emailError = $('#emailError');
    const $fullName = $('#fullname');
    const $fullNameError = $('#fullnameError');
    const $dob = $('#dob');
    const $profileInput = $('#profile_picture');
    const $profileImage = $('#profileImage');
    const passwordPattern = /^[a-zA-Z0-9$%@&*]+$/;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    function validatePasswords(){
        const passVal = $password.val().trim();
        const confirmVal = $confirmPassword.val().trim();
        $password.css('border-color', '#ccc');
        $confirmPassword.css('border-color','#ccc');
        $passwordError.text('');
        $confirmPasswordError.text('');
        if (!passwordPattern.test(passVal)) {
            $passwordError.text('Password can only contain letters (a-z, A-Z), numbers (0-9), and special characters ($%@&*)');
            $password.css('border-color', 'red');
            return false;
        }
        if (passVal !== confirmVal) {
            $confirmPasswordError.text('Passwords do not match!');
            $confirmPassword.css('border-color', 'red');
            return false;
        }
        return true;
    }
    function validateEmail() {
        const emailVal = $email.val().trim();
        $emailError.text('');
        $email.css('border-color', '#ccc');
        if (emailVal === '') {
            $emailError.text('Email is required!');
            $email.css('border-color', 'red');
            return false;
        }
        if (!emailPattern.test(emailVal)) {
            $emailError.text('Invalid email format!');
            $email.css('border-color', 'red');
            return false;
        }
        return true;
    }
    function validateFullName() {
        const name = $fullName.val().trim();
        $fullNameError.text('');
        if (name === '') {
            $fullNameError.text('Full name is required!');
            $fullName.css('border-color', 'red');
            return false;
        }
        $fullName.css('border-color', '#ccc');
        return true;
    }
    function previewProfileImage(input) {
        const file = input.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $profileImage.attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    }
    $password.on('input', validatePasswords);
    $confirmPassword.on('input', validatePasswords);
    $email.on('input', validateEmail);
    $fullName.on('input', validateFullName);
    $profileInput.on('change', function () {
        previewProfileImage(this);
    });
    $form.on('submit', function (e) {
        e.preventDefault();
        const isValidName = validateFullName();
        const isValidEmail = validateEmail();
        const isValidPassword = validatePasswords();
        const dob = $dob.val().trim();
        if (!isValidName || !isValidEmail || !isValidPassword || dob === '') {
            if (dob === '') $dob.css('border-color', 'red');
            else $dob.css('border-color', '#ccc');
            return;
        }
        const formData = new FormData(this);
        $.ajax({
            url: $form.attr('action'), 
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {    
            },
            success: function (response) {
                alert("Signup successful!");
                $form[0].reset();
                $profileImage.attr('src', 'assets/profilePicture/default-profile.png');
            },
            error: function (xhr) {
                alert("Something went wrong!");
            }
        });
    });
});
