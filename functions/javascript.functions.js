function regexEmail(){
   var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   return emailRegex;
}

function regexPassword(){
  /* var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/; */
  var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/;
  return passwordRegex;
}

function regexTextbox(minLength, maxLength) {
  const regex = new RegExp('^[A-Za-z0-9]{' + minLength + ',' + maxLength + '}$');
  return regex;
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

///////////////////////////////
//////// Table Function ///////
///////////////////////////////



function checkAll() {
  const checkAll   = $('#checkAll');
  const checkboxes = $('[name="checkItem[]"]');
  const btnCheckAll = $('#btnCheckAll');

  checkAll.on('change', function() {
    checkboxes.each(function() {
      $(this).prop('checked', checkAll.prop('checked'));
    });
  });

  checkboxes.each(function() {
    $(this).on('change', function() {
      if (!$(this).prop('checked')) {
        checkAll.prop('checked', false);
      } else {
        const allChecked = checkboxes.toArray().every(ch => $(ch).prop('checked'));
        checkAll.prop('checked', allChecked);
      }
    });
  });

  if (checkAll.prop('checked')) {
    btnCheckAll.removeClass('text-secondary').addClass('text-primary');
    btnCheckAll.html('<i class="fas fa-check-square mr-1"></i> Select All');
  } else {
    btnCheckAll.removeClass('text-primary').addClass('text-secondary');
    btnCheckAll.html('<i class="fas fa-square mr-1"></i> Select All');
  }
}

function btnCheckAll() {
  const checkAll   = $('#checkAll');
  const checkboxes = $('[name="checkItem[]"]');
  const btnCheckAll = $('#btnCheckAll');

  checkboxes.each(function() {
    $(this).prop('checked', !checkAll.prop('checked'));
  });

  checkAll.prop('checked', !checkAll.prop('checked'));

  if (checkAll.prop('checked')) {
    btnCheckAll.removeClass('text-secondary').addClass('text-primary');
  } else {
    btnCheckAll.removeClass('text-primary').addClass('text-secondary');
  }
}

function shiftKeyCheckbox() {

  var elements = Array.prototype.slice.call($('input[id="checkItem"]'));
  var last_checked;

  for (var i=0,len=elements.length; i<len; i++) {
      elements[i].addEventListener("click", modifyText, false);
  }

  function modifyText(event) {
      if (!last_checked) {
          last_checked = this;
          return
      }
      
      if (event.shiftKey) {
          var start = elements.indexOf(this);
          var end = elements.indexOf(last_checked);
          var checked_state = last_checked.checked;

              for (var i=Math.min(start, end),len=Math.max(start, end); i<len; i++) {
              elements[i].checked = checked_state;
          }
      }
      last_checked = this;
  }
}