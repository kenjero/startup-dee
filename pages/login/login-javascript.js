///////////////////////////////
///////// Auth System /////////
///////////////////////////////

function togglePassword() {
    var passwordField = document.getElementById('password');
    var passwordIcon = document.getElementById('passwordIcon');

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

function toggleConfirmPassword() {
    var confirmPasswordField = document.getElementById('confirmPassword');
    var confirmPasswordIcon = document.getElementById('confirmPasswordIcon');

    if (confirmPasswordField.type === 'password') {
        confirmPasswordField.type = 'text';
        confirmPasswordIcon.classList.remove('ti-eye-off');
        confirmPasswordIcon.classList.add('ti-eye');
    } else {
        confirmPasswordField.type = 'password';
        confirmPasswordIcon.classList.remove('ti-eye');
        confirmPasswordIcon.classList.add('ti-eye-off');
    }
}

function postLogin() {
    var email    = $("#email").val();
    var password = $("#password").val();

    var formData = {
        email     : email,
        password  : password,
        method    : "postLogin",
    };
    
    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);

            if (jsonData.status === 'failed') {
                Swal.fire({
                    title: 'Error!',
                    text: jsonData.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            } else {
                window.location.href = 'index';
            }
        } 
    });
}

function changeLoginAccount(){

    var formData = {
        method    : "changeLoginAccount",
    };
    
    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);
            $("#loginHeader").html(jsonData.loginHeader);
            $("#loginBody").html(jsonData.loginBody);
            $("#loginFooter").html(jsonData.loginFooter);
            $("#loginSocial").show();
        } 
    });
}

////////////////////////////////
/////// Forgot  Password ///////
////////////////////////////////
function changeForgotPassword(){

    var formData = {
        method    : "changeForgotPassword",
    };
    
    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);
            $("#loginHeader").html(jsonData.loginHeader);
            $("#loginBody").html(jsonData.loginBody);
            $("#loginFooter").html(jsonData.loginFooter);
            $("#loginSocial").hide();
        } 
    });
}

function checkEmailForgotPassword(){
    var email = $("#email").val();

    var formData = {
        email   : email,
        method  : "checkEmailForgotPassword",
    };

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);

            $("#email").removeClass("is-invalid").removeClass("is-valid");
            $("#email").addClass("is-perload");
            
            setTimeout(function() {
                $("#email").removeClass("is-preload");
                if (jsonData.status === "valid") {
                    $("#email").removeClass("is-invalid").addClass("is-valid");
                } else {
                    $("#email").removeClass("is-valid").addClass("is-invalid");
                }
            }, 500);
        },
    });
}

function postForgotPasswordt() {
    var email           = $("#email").val();
    var forgotPassword  = $("#forgotPassword");
    

    var formData = {
        email     : email,
        method    : "postForgotPasswordt",
    };

    // Disable button and start countdown
    forgotPassword.prop('disabled', true);
    forgotPassword.removeClass("btn-primary").addClass("btn-light-secondary");
    var counter = 60;
    var interval = setInterval(function() {
        counter--;
        // Display the countdown on the button
        forgotPassword.text('Wait ' + counter + ' seconds');
        if (counter <= 0) {
            clearInterval(interval);
            forgotPassword.text('Forgot Password');
            forgotPassword.prop('disabled', false);
            forgotPassword.removeClass("btn-light-secondary").addClass("btn-primary");
        }
    }, 1000);
    
    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);

            if(jsonData.status === "too_frequent"){
                Swal.fire({
                    title: 'Warning! Wait a Moment',
                    text: 'You have sent an email recently. Please wait a minute before trying again.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-warning'
                    },
                    buttonsStyling: false,
                });
            }
            if(jsonData.status === "missing"){
                Swal.fire({
                    title: 'Error!',
                    text: 'Error! Your email is missing.',
                    icon: 'error', 
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                });
            }
            
            if(jsonData.status === "success"){
                Swal.fire({
                    title: 'Success!',
                    text: 'An link has been sent to your email. Please check your inbox to change your account.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    },
                    buttonsStyling: false,
                    timer: 5000,
                });
                setTimeout(function() {
                    changeLoginAccount();
                }, 60000);

            }
        } 
    });
}

////////////////////////////////
//////// Reset Password ////////
////////////////////////////////

function changeResetPassword(){

    var formData = {
        method    : "changeResetPassword",
    };
    
    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);
            $("#loginHeader").html(jsonData.loginHeader);
            $("#loginBody").html(jsonData.loginBody);
            $("#loginFooter").html(jsonData.loginFooter);
            $("#loginSocial").hide();
        } 
    });
}

function postResetPassword(){

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    const email = urlParams.get('email');
    const code = urlParams.get('code');
    var password = $("#password").val();
    var confirmPassword = $("#confirmPassword").val();

    var passwordRegex = regexPassword();


    if (!passwordRegex.test(password)|| password  === "") {
        $("#password").removeClass("is-valid").addClass("is-invalid");
        return;
    }

    if (!passwordRegex.test(confirmPassword)|| confirmPassword  === "") {
        $("#confirmPassword").removeClass("is-valid").addClass("is-invalid");
        return;
    }

    if (password !== confirmPassword) {
        $("#password").removeClass("is-valid").addClass("is-invalid");
        $("#confirmPassword").removeClass("is-valid").addClass("is-invalid");
        return;
    }

    var formData = {
        email           : email,
        code            : code,
        password        : password,
        confirmPassword : confirmPassword,
        method          : "postResetPassword",
    };

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);

            if(jsonData.status === "success"){
                Swal.fire({
                    title: 'Success!',
                    text: 'Reset password completed successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        changeLoginAccount();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error! Something went wrong.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        changeLoginAccount();
                    }
                });
            }

        },
    });
}


///////////////////////////////
//////// Create Account ///////
///////////////////////////////
function changeCreateAccount(){

    var formData = {
        method    : "changeCreateAccount",
    };
    
    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);
            $("#loginHeader").html(jsonData.loginHeader);
            $("#loginBody").html(jsonData.loginBody);
            $("#loginFooter").html(jsonData.loginFooter);
            $("#loginSocial").hide();
        } 
    });
}

function requestOTP(){
    var email  = $("#email").val();
    var otp    = $("#otp");
    var btnOTP = $("#btnOTP");

    if(email !== ''){
        var formData = {
            email    : email,
            method   : "sandOTP",
        };
        
        $.ajax({
            type: 'POST',
            url: JSON_HOST_NAME_URL + 'login/login-function.php',
            data: formData,
            success: function (response) {
                var jsonData = JSON.parse(response);

                if(jsonData.status === "too_frequent"){
                    Swal.fire({
                        title: 'Warning! Wait a Moment',
                        text: 'You have sent an email recently. Please wait a minute before trying again.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-warning'
                        },
                        buttonsStyling: false,
                    });
                }
                if(jsonData.status === "success"){
                    Swal.fire({
                        title: 'Success!',
                        text: 'An OTP has been sent to your email. Please check your inbox to verify your account.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        },
                        buttonsStyling: false,
                    });
                }
                if(jsonData.status === "failed"){
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was a problem sending the email.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false,
                    });
                }
                if(jsonData.status === "repeatedly"){
                    Swal.fire({
                        title: 'Error!',
                        text: 'This email has already been used.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false,
                    });
                }
            } 
        });

        // Disable button and start countdown
        btnOTP.prop('disabled', true);
        btnOTP.removeClass("btn-primary").addClass("btn-light-secondary");
        var counter = 60;
        var interval = setInterval(function() {
            counter--;
            // Display the countdown on the button
            btnOTP.text('Wait ' + counter + ' seconds');
            if (counter <= 0) {
                clearInterval(interval);
                btnOTP.text('Send OTP');
                btnOTP.prop('disabled', false);
                btnOTP.removeClass("btn-light-secondary").addClass("btn-primary");
            }
        }, 1000);

        otp.prop('disabled', false);

    } else {
        Swal.fire({
            title: 'Error!',
            text: 'The email field is not blank.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
}

function checkEmail(){

    var email = $("#email").val();
    var otp   = $("#otp").val();

    var formData = {
        email   : email,
        method  : "checkEmail",
    };

    // Email validation regex
    var emailRegex = regexEmail();

    if (!emailRegex.test(email)) {
        $("#email").removeClass("is-valid").addClass("is-invalid");
        return;
    } else {
        $("#email").removeClass("is-invalid").addClass("is-valid");
    }

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);

            $("#email").removeClass("is-invalid").removeClass("is-valid");
            $("#email").addClass("is-perload");
            
            setTimeout(function() {
                $("#email").removeClass("is-preload");
                if (jsonData.status === "valid") {
                    $("#email").removeClass("is-invalid").addClass("is-valid");
                } else {
                    $("#email").removeClass("is-valid").addClass("is-invalid");
                }

                if(otp !== "") {
                    checkOTP();
                }

            }, 500);
        },
    });
};


function checkOTP(){

    var email = $("#email").val();
    var otp   = $("#otp").val();

    var formData = {
        email   : email,
        otp     : otp,
        method  : "checkOTP",
    };

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);
            $("#otp").removeClass("is-invalid").removeClass("is-valid");
            $("#otp").addClass("is-perload");
            
            setTimeout(function() {
                $("#otp").removeClass("is-preload");
                if (jsonData.status === "valid") {
                    $("#otp").removeClass("is-invalid").addClass("is-valid");
                    $("#btnOTP").prop('disabled', true);
                } else {
                    $("#otp").removeClass("is-valid").addClass("is-invalid");
                    $("#btnOTP").prop('disabled', false);
                }
            }, 500);
        },
    });
};

function validatePassword() {
    var password             = $("#password").val();
    var confirmPassword      = $("#confirmPassword").val();
    var passwordRegex        = regexPassword();

    setTimeout(function() {
        $("#password").removeClass("is-preload");

        if (password === "") {
            $("#password").removeClass("is-invalid").removeClass("is-valid").removeClass("is-preload");
        } else if (passwordRegex.test(password)) {
            $("#password").removeClass("is-invalid").addClass("is-valid");
        } else {
            $("#password").removeClass("is-valid").addClass("is-invalid");
        }

    }, 500);

    if(confirmPassword !== "") {
        validateConfirmPassword();
    }

}

function validateConfirmPassword() {
    var password             = $("#password").val();
    var confirmPassword      = $("#confirmPassword").val();

    setTimeout(function() {
        $("#confirmPassword").removeClass("is-preload");

        if (confirmPassword === "") {
            $("#confirmPassword").removeClass("is-invalid").removeClass("is-valid").removeClass("is-preload");
        } else if (password === confirmPassword) {
            $("#confirmPassword").removeClass("is-invalid").addClass("is-valid");
        } else {
            $("#confirmPassword").removeClass("is-valid").addClass("is-invalid");
        }

    }, 500);
}


function postRegister(){

    var email = $("#email").val();
    var otp   = $("#otp").val();
    var password = $("#password").val();
    var confirmPassword = $("#confirmPassword").val();

    var passwordRegex = regexPassword();
    var emailRegex = regexEmail();

    if (!emailRegex.test(email) || email  === "") {
        $("#email").removeClass("is-valid").addClass("is-invalid");
        return;
    }

    if (otp === "") {
        $("#otp").removeClass("is-valid").addClass("is-invalid");
        return;
    }

    if (!passwordRegex.test(password) || password  === "") {
        $("#password").removeClass("is-valid").addClass("is-invalid");
        return;
    }

    if (!passwordRegex.test(confirmPassword) || confirmPassword  === "") {
        $("#confirmPassword").removeClass("is-valid").addClass("is-invalid");
        return;
    }

    if (password !== confirmPassword) {
        $("#password").removeClass("is-valid").addClass("is-invalid");
        $("#confirmPassword").removeClass("is-valid").addClass("is-invalid");
        return;
    }

    var formData = {
        email           : email,
        otp             : otp,
        password        : password,
        confirmPassword : confirmPassword,
        method          : "postRegister",
    };

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);

            if(jsonData.status === "success"){
                Swal.fire({
                    title: 'Success!',
                    text: 'Registration successful.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    timer: 3000,
                });

                changeLoginAccount();

            }else{
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong during registration.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            }
        },
    });
}

///////////////////////////////
//////// googleAuthAPI ////////
///////////////////////////////
function googleAuthAPI(authUrl) {
    // Calculate the position to center the window
    const screenWidth = window.screen.width;
    const screenHeight = window.screen.height;
    const windowWidth = 500; // Set your desired width
    const windowHeight = 700; // Set your desired height
  
    const left = (screenWidth - windowWidth) / 2;
    const top = (screenHeight - windowHeight) / 2;
  
    const gWin = window.open(authUrl, "GoogleLogin", `toolbar=yes,scrollbars=yes,resizable=yes,top=${top},left=${left},width=${windowWidth},height=${windowHeight}`);
  
    // Check if the window is closed every 500 milliseconds
    const checkWindowClosed = setInterval(function () {
      if (gWin.closed !== false) { // gWin.closed may not be accurate in some browsers
        clearInterval(checkWindowClosed); // Stop checking
        location.reload();  // Redirect to the main page
      }
    }, 500);
  }
