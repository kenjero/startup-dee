function togglePassword() {
    var passwordField = document.getElementById('password');
    var passwordIcon = document.querySelector('.input-group-text i');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordIcon.classList.remove('ti-eye-off');
        passwordIcon.classList.add('ti-eye');
    } else {
        passwordField.type = 'password';
        passwordIcon.classList.remove('ti-eye');
        passwordIcon.classList.add('ti-eye-off');
    }
}

function postLogin() {
    var username = $("#username").val();
    var password = $("#password").val();

    var formData = {
        username  : username,
        password  : password,
        method    : "authenticate",
    };
    
    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);
            console.log(jsonData); 

            if (jsonData.status === 'success') {
                notifier.show('Success !', jsonData.message, 'success', '', 1500); 
                window.location.href = 'index';
            } else {
                notifier.show('Error !', jsonData.message, 'danger', NOTIFIER_IMAGE_DANGER, 3000);
            }
        } 
    });
}