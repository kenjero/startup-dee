///////////////////////////////
/////// Setting Product ///////
///////////////////////////////

function table_product() {

    var formData = {
        method     : "table_product",
    };

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL  + 'product/product-function.php',
        data: formData,
        success: function (response) {
            
            var table = $("#dbtable-products");
            var jsonData = JSON.parse(response);

            var dataTableJson = {
                                    lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                                    columnDefs: [
                                        { "width": "1%"  , "targets": [0] },
                                        { "width": "2%"  , "targets": [1] },
                                        { "width": "8%"  , "targets": [2, 4, 5] },
                                        { "width": "14%" , "targets": [3,] },
                                        { className: "dt-left", targets: [3] },
                                        { className: "dt-right", targets: [] },
                                        { className: "dt-center", targets: [0, 1, 2, 4, 5] },
                                        { orderable: false, targets: [0, 1, 2, 3, 4, 5] },
                                    ],
                                    order: [ [ 1, "desc" ] ],
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

function checkTextboxProduct(addOrEdit) {
    var productName  = $("#productName");
    var image        = $("#image");
    var qrName       = $("#qrName");
    var sku          = $("#sku");

    var errorCheck = {};
    var errorMessages = {};

    var regexQrName = regexTextbox(1,10);
    var regexSku    = regexTextbox(1,3);

    if (productName.val() === "") {
        productName.addClass("is-invalid").removeClass("is-valid");
        $("#errorProductName").addClass("text-danger").removeClass("text-success");
        errorMessages.productName = '<i class="fa fa-times-circle"></i> Product Name can\'t be blank.';
        errorCheck.productName = true;
    } 
    else {
        delete errorCheck.productName;
        errorMessages.productName = '';
        productName.addClass("is-valid").removeClass("is-invalid");
    }

    if (addOrEdit === "add") {
        if (image.val() === "") {
            image.addClass("is-invalid").removeClass("is-valid");
            $("#errorImage").addClass("text-danger").removeClass("text-success");
            errorMessages.image = '<i class="fa fa-times-circle"></i> Image can\'t be blank.';
            errorCheck.image = true;
        }
        else if (!image[0].files[0].type.startsWith('image/')) {
            image.addClass("is-invalid").removeClass("is-valid");
            $("#errorImage").addClass("text-danger").removeClass("text-success");
            errorMessages.image = '<i class="fa fa-times-circle"></i> Image File is not valid.';
            errorCheck.image = true;
        } 
        else {
            delete errorCheck.image;
            errorMessages.image = '';
            image.addClass("is-valid").removeClass("is-invalid");
        }
    }else{
        if (image.val() !== "" && !image[0].files[0].type.startsWith('image/')) {
            image.addClass("is-invalid").removeClass("is-valid");
            $("#errorImage").addClass("text-danger").removeClass("text-success");
            errorMessages.image = '<i class="fa fa-times-circle"></i> Image File is not valid.';
            errorCheck.image = true;
        } 
        else {
            delete errorCheck.image;
            errorMessages.image = '';
            image.addClass("is-valid").removeClass("is-invalid");
        }
        
    }

    if (qrName.val() === "") {
        qrName.addClass("is-invalid").removeClass("is-valid");
        $("#errorQrName").addClass("text-danger").removeClass("text-success");
        errorMessages.qrName = '<i class="fa fa-times-circle"></i> QR-Code Product Name can\'t be blank.';
        errorCheck.qrName = true;
    } 
    else if (!regexQrName.test(qrName.val())) {
        qrName.addClass("is-invalid").removeClass("is-valid");
        $("#errorQrName").addClass("text-danger").removeClass("text-success");
        errorMessages.qrName = '<i class="fa fa-times-circle"></i> QR-Code Product input is invalid.';
        errorCheck.qrName = true;
    } 
    else {
        delete errorCheck.qrName;
        errorMessages.qrName = '';
        qrName.addClass("is-valid").removeClass("is-invalid");
    }

    if (sku.val() === "") { 
        sku.addClass("is-invalid").removeClass("is-valid");
        $("#errorSku").addClass("text-danger").removeClass("text-success");
        errorMessages.sku = '<i class="fa fa-times-circle"></i> SKU can\'t be blank.';
        errorCheck.sku = true;
    } 
    else if (!regexSku.test(sku.val())) {
        sku.addClass("is-invalid").removeClass("is-valid");
        $("#errorSku").addClass("text-danger").removeClass("text-success");
        errorMessages.sku = '<i class="fa fa-times-circle"></i> SKU input is invalid.';
        errorCheck.sku = true;
    } 
    else {
        delete errorCheck.sku;
        errorMessages.sku = '';
        sku.addClass("is-valid").removeClass("is-invalid");
    }

    if (Object.keys(errorCheck).length > 0) {
        $("#errorProductName").html(errorMessages.productName).show();
        $("#errorImage").html(errorMessages.image).show();
        $("#errorQrName").html(errorMessages.qrName).show();
        $("#errorSku").html(errorMessages.sku).show();
        return true;
    } else {
        return false;
    }
}

function checkAjaxTextboxProduct(jsonData) {
    var productName  = $("#productName");
    var image        = $("#image");
    var qrName       = $("#qrName");
    var sku          = $("#sku");

    if (jsonData.errorProductName) {
        productName.removeClass().addClass("form-control is-invalid");
        $("#errorProductName").addClass("text-danger").html(jsonData.errorProductName).show();
    } else {
        productName.removeClass().addClass("form-control is-valid");
        $("#errorProductName").html('').hide();
    }

    if (jsonData.errorImage) {
        image.removeClass().addClass("form-control is-invalid");
        $("#errorImage").addClass("text-danger").html(jsonData.errorImage).show();
    } else {
        image.removeClass().addClass("form-control is-valid");
        $("#errorImage").html('').hide();
    }

    if (jsonData.errorQrName) {
        qrName.removeClass().addClass("form-control is-invalid");
        $("#errorQrName").addClass("text-danger").html(jsonData.errorQrName).show();
    } else {
        qrName.removeClass().addClass("form-control is-valid");
        $("#errorQrName").html('').hide();
    }

    if (jsonData.errorSku) {
        sku.removeClass().addClass("form-control is-invalid");
        $("#errorSku").addClass("text-danger").html(jsonData.errorSku).show();
    } else {
        sku.removeClass().addClass("form-control is-valid");
        $("#errorSku").html('').hide();
    }
}

function addProduct() {
    var productName  = $("#productName");
    var image        = $("#image");
    var qrName       = $("#qrName");
    var sku          = $("#sku");
    
    if (checkTextboxProduct("add")) {
        return false;
    } else {
        $("#errorProductName").html('').hide();
        $("#errorImage").html('').hide();
        $("#errorQrName").html('').hide();
        $("#errorSku").html('').hide();
        
    }

    var formData = new FormData();
    formData.append('productName', productName.val());
    formData.append('image', image[0].files[0]);
    formData.append('qrName', qrName.val());
    formData.append('sku', sku.val());
    formData.append('method', 'addProduct');

    setTimeout(function() {
        productName.removeClass("is-invalid").removeClass("is-valid").addClass("is-perload");
        image.removeClass("is-invalid").removeClass("is-valid").addClass("is-perload");
        qrName.removeClass("is-invalid").removeClass("is-valid").addClass("is-perload");
        sku.removeClass("is-invalid").removeClass("is-valid").addClass("is-perload");
    }, 500);

    $("#add").focus();

    $("#uploadProgress").show();
    $("#progressText").show();

    $(".progress-bar").css('width', '0%').attr('aria-valuenow', 0);
    $("#progress-text").text('0%');

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'product/product-function.php',
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
            
            if(jsonData.status === 'success'){
                productName.val('').removeClass("is-perload").prop("readonly", false).removeClass("is-valid");
                image.val('').removeClass("is-perload").prop("readonly", false).removeClass("is-valid");
                qrName.val('').removeClass("is-perload").prop("readonly", false).removeClass("is-valid");
                sku.val('').removeClass("is-perload").prop("readonly", false).removeClass("is-valid");

                $("#uploadProgress").hide();
                $("#progressText").hide();

                closeModalAdd();
                table_product();

            } else {

                setTimeout(function() {
                    checkAjaxTextboxProduct(jsonData);
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

function showModalAddProduct() {

    var formData = {
        method: "showModalAddProduct",
    };

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'product/product-function.php',
        data: formData,
        success: function(response) {
            var jsonData = JSON.parse(response);
            $('body').append(jsonData.modal);
            $('#addProduct').modal('show');
        },
        error: function(error) {
            console.error('Error uploading video to server:', error);
        }
    });
}

function editProduct() {
    var productName  = $("#productName");
    var image        = $("#image");
    var qrName       = $("#qrName");
    var sku          = $("#sku");
    var id           = $("#id");

    if (checkTextboxProduct("edit")) {
        return false;
    } else {
        $("#errorProductName").html('').hide();
        $("#errorImage").html('').hide();
        $("#errorQrName").html('').hide();
        $("#errorSku").html('').hide();
    }

    var formData = new FormData();
    formData.append('productName', productName.val());
    formData.append('image', image[0].files[0]);
    formData.append('qrName', qrName.val());
    formData.append('sku', sku.val());
    formData.append('id', id.val());
    formData.append('method', 'editProduct');

    setTimeout(function() {
        productName.removeClass("is-invalid").removeClass("is-valid").addClass("is-perload");
        image.removeClass("is-invalid").removeClass("is-valid").addClass("is-perload");
        qrName.removeClass("is-invalid").removeClass("is-valid").addClass("is-perload");
        sku.removeClass("is-invalid").removeClass("is-valid").addClass("is-perload");
    }, 500);

    $("#edit").focus();

    $("#uploadProgress").show();
    $("#progressText").show();

    $(".progress-bar").css('width', '0%').attr('aria-valuenow', 0);
    $("#progress-text").text('0%');

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'product/product-function.php',
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

            if(jsonData.status === 'success'){
                productName.val('').removeClass("is-perload").prop("readonly", false).removeClass("is-valid");
                image.val('').removeClass("is-perload").prop("readonly", false).removeClass("is-valid");
                qrName.val('').removeClass("is-perload").prop("readonly", false).removeClass("is-valid");
                sku.val('').removeClass("is-perload").prop("readonly", false).removeClass("is-valid");

                $("#uploadProgress").hide();
                $("#progressText").hide();

                closeModalEdit();
                table_product();

            } else { 
                
                setTimeout(function() {
                    checkAjaxTextboxProduct(jsonData);
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
function closeModalAdd() {
    setTimeout(function() {
        $('#addProduct').modal('hide');
        $('#addProduct').remove();
    }, 500);
    
}

function closeModalEdit() {
    setTimeout(function() {
        $('#editProduct').modal('hide');
        $('#editProduct').remove();
    }, 500);
}

function showModalEditProduct(id) {

    var formData = {
        id     : id,
        method : "showModalEditProduct",
    };

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'product/product-function.php',
        data: formData,
        success: function(response) {
            var jsonData = JSON.parse(response);
            $('body').append(jsonData.modal);
            $('#editProduct').modal('show');
        },
        error: function(error) {
            console.error('Error uploading video to server:', error);
        }
    });
}


function deleteProduct(id) {

    var formData = {
        id      : id,
        method  : "deleteProduct",
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
                url: JSON_HOST_NAME_URL  + 'product/product-function.php',
                data: formData,
                success: function (response) {
                    var jsonData = JSON.parse(response);
                    if(jsonData.status === "success"){
                        notifier.show('Deleted!', 'Your product has been deleted.', 'danger', NOTIFIER_IMAGE_DANGER, 4000);
                        table_product();
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
              method  : "deleteProduct",
            };
  
            $.ajax({
                type: 'POST',
                url: JSON_HOST_NAME_URL  + 'product/product-function.php',
                data: formData,
                success: function (response) {
                    var jsonData = JSON.parse(response);
                    if(jsonData.status === "success"){
                        notifier.show('Deleted!', 'Your product has been deleted.', 'danger', NOTIFIER_IMAGE_DANGER, 4000);
                    }

                    $('#tr' + id).delay(100).fadeOut(500, function() {
                        $(this).remove(); // Optionally remove the row from the DOM after fading out
                    });

                    if (index === ids.length - 1) {
                        table_product(); // Call table_product() after the last iteration
                    }
                },
            });
          }, index * 100); // 0.1 second delay
        });
      }
    });
}

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

window.onload = function() {
    table_product();
};
