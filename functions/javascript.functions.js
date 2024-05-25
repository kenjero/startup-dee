function regexEmail(){
   var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   return emailRegex;
}

function regexPassword(){
  /* var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/; */
  var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/;
  return passwordRegex;
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
 