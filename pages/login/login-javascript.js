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

///////////////////////////////
//////// Create Account ///////
///////////////////////////////
function changeCreateAccount(){
    
    var loginHeader = $("#loginHeader");
    var loginBody   = $("#loginBody");
    var loginFooter = $("#loginFooter");

    var formData = {
        method    : "changeCreateAccount",
    };
    
    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'login/login-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);
            loginHeader.html(jsonData.loginHeader);
            loginBody.html(jsonData.loginBody);
            loginFooter.html(jsonData.loginFooter);
        } 
    });
}

function requestOTP(){
    var email           = $("#email").val();
    var otp             = $("#otp").val();
    var password        = $("#password").val();
    var confirmPassword = $("#confirmPassword").val();

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
                
                if(jsonData.status === "success"){
                    alert('ส่งเมลสำเร็จ');
                }
                if(jsonData.status === "repeatedly"){
                    alert('เมลซ้ำ');
                }
            } 
        });
    }
    
    
    
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
