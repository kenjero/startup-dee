///////////////////////////////
//////// Lazada Order /////////
///////////////////////////////

document.addEventListener('DOMContentLoaded', function() {
    
    const addDatepicker = new flatpickr(document.querySelector('#dateRangeSearch'), {
        mode: 'range',
        dateFormat: 'Y-m-d',
    });

    const iconDatepicker = document.querySelector('#iconDatepicker');

    iconDatepicker.addEventListener('click', function() {
      addDatepicker.show();
    });

});

function searchLazadaOrder() {
    table_lazadaOrder();
}


function showModalLazadaOrder() {

    var formData = {
        method: "showModalLazadaOrder",
    };

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'order_lazada/order_lazada-function.php',
        data: formData,
        success: function(response) {
            var jsonData = JSON.parse(response);
            $('body').append(jsonData.modal);
            $('#uploadLazadaOrder').modal('show');
        },
        error: function(error) {
            console.error('Error uploading video to server:', error);
        }
    });
}

function table_lazadaOrder() {

    var search = $("#dateRangeSearch");

    var formData = {
        search : search.val(),
        method : "table_lazadaOrder",
    };

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL  + 'order_lazada/order_lazada-function.php',
        data: formData,
        success: function (response) {
            
            var table = $("#dbtable-lazadaOrder");
            var jsonData = JSON.parse(response);

            var dataTableJson = {
                                    lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                                    columnDefs: [
                                        { "width": "1%"  , "targets": [0, 6] },
                                        { "width": "8%"  , "targets": [3] },
                                        { "width": "18%" , "targets": [1, 2, 4, 5] },
                                        { className: "dt-left", targets: [1, 2] },
                                        { className: "dt-right", targets: [3] },
                                        { className: "dt-center", targets: [0, 4, 5, 6] },
                                        { orderable: false, targets: [0, 1, 2, 3, 4, 5, 6] },
                                    ],
                                    order: [ [ 5, "desc" ] ],
                                }

            if ($.fn.DataTable.isDataTable(table)) {
                table.DataTable().destroy();
            }
            
            table.html(jsonData.thead + jsonData.tload);
            table.DataTable(dataTableJson); 

            setTimeout(function() {
                table.DataTable().destroy();
                table.html(jsonData.thead + jsonData.tbody);
                table.DataTable(dataTableJson);
                shiftKeyCheckbox();
            }, 500);

            
        },
        error: function (error) {
            console.error('Error uploading video to server:', error);
        }
    });
}

function uploadExcel() {

    var allowedfileExtensions = ['xlsx', 'xls', 'csv'];
    var allowedMimeTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
        'application/vnd.ms-excel', // .xls
        'text/csv' // .csv
    ];

    var fileInput = $("#excel");
    var file = fileInput[0].files[0];
    var fileExtension = file.name.split('.').pop().toLowerCase();
    var fileType = file.type;

    var errorCheck = {};
    var errorMessages = {};

    if (!allowedfileExtensions.includes(fileExtension) || !allowedMimeTypes.includes(fileType)) {
        fileInput.addClass("is-invalid").removeClass("is-valid");
        $("#errorExcel").addClass("text-danger").removeClass("text-success");
        errorMessages.excel = '<i class="fa fa-times-circle"></i> Excel File is not valid.';
        errorCheck.excel = true;
    } else {
        delete errorCheck.excel;
        errorMessages.excel = '';
        fileInput.addClass("is-valid").removeClass("is-invalid");
    }

    if (Object.keys(errorCheck).length > 0) {
        $("#errorExcel").html(errorMessages.excel).show();
        return false;
    }else {
        $("#errorExcel").html('').hide();
    }

    var formData = new FormData();
    formData.append('excel', fileInput[0].files[0]);
    formData.append('method', 'uploadExcel');

    setTimeout(function() {
        fileInput.removeClass("is-invalid").removeClass("is-valid").addClass("is-perload");
    }, 500);

    $("#add").focus();

    $("#uploadProgress").show();
    $("#progressText").show();

    $(".progress-bar").css('width', '0%').attr('aria-valuenow', 0);
    $("#progress-text").text('0%');

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'order_lazada/order_lazada-function.php',
        data: formData,
        contentType: false,
        processData: false,
        xhr: function() {

            var xhr = new XMLHttpRequest();
            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    var percentComplete = Math.round((event.loaded / event.total) * 100);
                    $(".progress-bar").css('width', percentComplete + '%').attr('aria-valuenow', percentComplete);
                    $("#progress-text").text(percentComplete + '%');
                }
            };
            return xhr;
        },
        success: function (response) {
            var jsonData = JSON.parse(response);
            console.log(response);
            console.log(jsonData);
            
            if(jsonData.status === 'success'){
                fileInput.val('').removeClass("is-perload").prop("readonly", false).removeClass("is-valid");

                $("#uploadProgress").hide();
                $("#progressText").hide();

                closeModalUploadLazadaOrder();
                table_lazadaOrder();

            } else {

                setTimeout(function() {
                    if (jsonData.errorExcel) {
                        fileInput.removeClass().addClass("form-control is-invalid");
                        $("#errorExcel").addClass("text-danger").html(jsonData.errorExcel).show();
                    } else {
                        fileInput.removeClass().addClass("form-control is-valid");
                        $("#errorExcel").html('').hide();
                    }
                }, 600);

                $("#uploadProgress").hide();
                $("#progressText").hide();
                return false;
            }

            
        },
        error: function (error) {
            console.error('Error uploading video to server:', error);
        }
    });
}

function closeModalUploadLazadaOrder() {
    setTimeout(function() {
        $('#uploadLazadaOrder').modal('hide');
        $('#uploadLazadaOrder').remove();
    }, 500);
    
}

function deleteOrderLazada(id) {

    var formData = {
        id      : id,
        method  : "deleteOrderLazada",
    };

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
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
                url: JSON_HOST_NAME_URL + 'order_lazada/order_lazada-function.php',
                data: formData,
                success: function (response) {
                    var jsonData = JSON.parse(response);
                    if(jsonData.status === "success"){
                        notifier.show('Deleted!', 'Your order Lazada has been deleted.', 'danger', NOTIFIER_IMAGE_DANGER, 4000);
                        table_lazadaOrder();;
                    }else{
                        notifier.show('Error!', 'Something went wrong.', 'danger', NOTIFIER_IMAGE_WARRING, 4000);
                    }
                },
                error: function (error) {
                    console.error('Error deleted product to server:', error);
                }
            });
        }
    });

}


function btnDeleteAll() {

    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete!"
    }).then((result) => {
      if (result.isConfirmed) {
  
        const checkboxes = $('[name="checkItem[]"]:checked');
        const ids = checkboxes.map(function() {
          return $(this).val();
        }).get();
  
        ids.forEach(function(id, index) {
          setTimeout(function() {
            var formData = {
              id     : id,
              method  : "deleteOrderLazada",
            };
  
            $.ajax({
                type: 'POST',
                url: JSON_HOST_NAME_URL + 'order_lazada/order_lazada-function.php',
                data: formData,
                success: function (response) {
                    var jsonData = JSON.parse(response);
                    if(jsonData.status === "success"){
                        notifier.show('Deleted!', 'Your order Lazada been deleted.', 'danger', NOTIFIER_IMAGE_DANGER, 4000);
                    }

                    $('#tr' + id).delay(100).fadeOut(500, function() {
                        $(this).remove(); // Optionally remove the row from the DOM after fading out
                    });

                    if (index === ids.length - 1) {
                        table_lazadaOrder(); // Call table_lazadaOrder() after the last iteration
                    }
                },
            });
          }, index * 100); // 0.1 second delay
        });
      }
    });
}

window.onload = function() {
    table_lazadaOrder();
};
