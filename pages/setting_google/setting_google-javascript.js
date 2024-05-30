///////////////////////////////
////// Setting Recording //////
///////////////////////////////

function authGoogle() {

    var folder = $("#folder");
    var email  = $("#email");
    var name   = $("#name");
    var google = $("#connectGoogle");

    var formData = {
        method  : "authGoogle",
    };

    google.html(CARD_LOADING);
    email.addClass("is-perload");
    name.addClass("is-perload");
    folder.addClass("is-perload");

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL  + 'setting_google/setting_google-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);

            setTimeout(function() {
                email.val(jsonData.email).removeClass("is-perload");
                name.val(jsonData.name).removeClass("is-perload");
                folder.val(jsonData.folder).removeClass("is-perload");
                google.html(jsonData.google);
            }, 500);
           

        },
        error: function (error) {
            console.error('Error uploading video to server:', error);
        }
    });
}


function outGoogleAuthAPI() {

    var formData = {
        method  : "outGoogleAuthAPI",
    };
    Swal.fire({
        title: 'Are you sure?',
        text: "Disconnect Google Drive!?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: JSON_HOST_NAME_URL  + 'setting_google/setting_google-function.php',
                data: formData,
                success: function (response) {
                    var jsonData = JSON.parse(response);

                    if (jsonData.status === "success") {
                        notifier.show('Deleted!', 'Your record has been deleted.', 'danger', NOTIFIER_IMAGE_DANGER, 4000);
                    }

                    authGoogle();
                },
                error: function (error) {
                    console.error('Error uploading video to server:', error);
                }
            });
        }
    });

}


function fileGoogleDrive() {

    var formData = {
        method  : "fileGoogleDrive",
    };

    $("#fileGoogleDrive").html(CARD_LOADING);

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL  + 'setting_google/setting_google-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);
            
            setTimeout(function() {
                $("#fileGoogleDrive").html(jsonData.message);
            }, 1000);
            
        },
        error: function (error) {
            console.error('Error uploading video to server:', error);
        }
    });
}



function submitFolder() {

    var folder = $("#folder").val();

    var formData = {
        folder  : folder,
        method  : "submitFolder",
    };

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL  + 'setting_google/setting_google-function.php',
        data: formData,
        success: function (response) {
            var jsonData = JSON.parse(response);
            console.log(jsonData);

            $("#folder").removeClass("is-valid");
            $("#folder").addClass("is-perload");

            setTimeout(function() {
                $("#folder").removeClass("is-perload").addClass("is-valid");
                $("#folder").val(jsonData.folder);

                setTimeout(function() {
                    $("#folder").removeClass("is-valid");
                }, 1000);

                fileGoogleDrive();

            }, 500);

            
        },
        error: function (error) {
            console.error('Error uploading video to server:', error);
        }
    });
}


window.onload = function() {
    authGoogle();
    fileGoogleDrive();
};
