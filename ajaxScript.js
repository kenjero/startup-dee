// ===================================================== //
// =================== Action ======================== //
// ===================================================== //

function check_selectAll() {
    var checkboxes = document.querySelectorAll('.checkbox');

    checkboxes.forEach(function (checkbox) {
        checkbox.checked = true;
    });
}
function check_UnselectAll() {
    var checkboxes = document.querySelectorAll('.checkbox');

    checkboxes.forEach(function (checkbox) {
        checkbox.checked = false;
    });
}

function enableShiftClickSelection() {
    var $chkboxes = $('.checkbox');
    var lastChecked = null;

    $chkboxes.click(function (e) {
        if (!lastChecked) {
            lastChecked = this;
            return;
        }

        if (e.shiftKey) {
            var start = $chkboxes.index(this);
            var end = $chkboxes.index(lastChecked);

            $chkboxes.slice(Math.min(start, end), Math.max(start, end) + 1).prop('checked', lastChecked.checked);
        }

        lastChecked = this;
    });
}

$(document).ready(function () {
    enableShiftClickSelection();
});

function setupSelectAllCheckbox() {
    // Get the "Select All" checkbox
    var selectAllCheckbox = document.getElementById('select-all');

    // Get all checkboxes in the table body
    var checkboxes = document.querySelectorAll('.checkbox');

    // Add a change event listener to the "Select All" checkbox
    selectAllCheckbox.addEventListener('change', function () {
        // Loop through all checkboxes and set their checked property
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });

    // Add a change event listener to each individual checkbox
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // If any individual checkbox is unchecked, uncheck the "Select All" checkbox
            if (!checkbox.checked) {
                selectAllCheckbox.checked = false;
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    setupSelectAllCheckbox();
});


function setupProgress(colspan,bgcolor) {
  var tb = $("#listProductTable");
  tb.html('<tr><td colspan="'+colspan+'">' +
          '<center>' +
          '<div class="progress mb-4" id="progress-bar-container">' +
          '<div class="progress-bar progress-bar-striped progress-bar-animated bg-'+bgcolor+'" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">' +
          '<span id="process-number"></span>' +
          '</div>' +
          '</div>' +
          '</center></td></tr>');
}


// ===================================================== //
// =================== Back-End ======================== //
// ===================================================== //

function elapsedTime(startTime) {
    var elapsedSeconds = (performance.now() - startTime) / 1000;
    return elapsedSeconds.toFixed(2);

    // xhr: function () {
    //     var percentComplete = 0;
    //     var xhr = new window.XMLHttpRequest();
    //     xhr.upload.addEventListener("progress", function (evt) {
    //         if (evt.lengthComputable) {
    //             percentComplete = (evt.loaded / evt.total) * 100;
    //             $('#box-Progress').show();
    //             $('#progressBar').css('width', percentComplete + '%').attr('aria-valuenow', percentComplete);
    //             $('#progressText').text('<span>'+ percentComplete.toFixed(0) +'%</span> <span>'+ elapsedTime(startTime) +' sec remaining</span>');
    //         }
    //     }, false);
    //     return xhr;
    // },

}


/////////////////////////////////////
/////////////// Login ///////////////
/////////////////////////////////////
function getIP() {
  ip = response.ip;
  return ip;
}

function convertToUpperCase(inputId) {
  var inputElement = document.getElementById(inputId);

  inputElement.addEventListener('input', function() {
    setTimeout(function () {
      inputElement.value = inputElement.value.replace(/[^a-zA-Z0-9 ]/g, '').toUpperCase();
    }, 100);
  });
}

function postLogin() {
    var username = $("#username").val();
    var password = $("#password").val();
    var loggedin = $("#loggedin").val();

    var formData = {
        username: username,
        password: password,
        loggedin: loggedin,
    };

    $.ajax({
        type: 'POST',
        url: HOST_NAME_URL+'/getAjaxServer.php',
        data: formData,
        success: function (response) {
            if (response == 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: 'Login Success!',
                    icon: 'success',
                    allowOutsideClick: false,
                    timer: 1500,
                }).then((result) => {
                    window.location.href = 'index.php';
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Login failed!',
                    icon: 'error',
                    allowOutsideClick: false,
                    timer: 1500,
                });
            }
        }
    });
}

///////////////////////////////////////
////////////// Products  //////////////
//////////////////////////////////////
function addProduct() {
  var product_name      = $("#product_name").val();
  var product_name_qr   = $("#product_name_qr").val();
  var product_sku       = $("#product_sku").val();
  var product_image     = $("#product_image")[0].files[0];

  var btn = $("#submitAddProduct");
  btn.html('<i class="fas fa-sync-alt fa-spin"></i> Loading...');
  btn.prop('disabled', true);

  if (product_name === '' || product_name_qr === '' || product_sku === '') {
      notifier.show('Error !', 'This field is required and cannot be empty!', 'danger', '', 6000);
      btn.html('<i class="fas fa-save"></i> Save product');
      btn.prop('disabled', false);
      return;
  }

  var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
  var fileType = product_image.type;

  if (!allowedTypes.includes(fileType)) {
    notifier.show('Image Error!', 'Invalid file type image', 'danger', '', 6000);
    btn.html('<i class="fas fa-save"></i> Save product');
    btn.prop('disabled', false);
    return;
  }

  var maxFileSize = 1 * 1024 * 1024;
  if (maxFileSize <= product_image.size) {
    notifier.show('Image Error!', 'Image Error! File size exceeds the limit (1 MB)', 'danger', '', 6000);
    btn.html('<i class="fas fa-save"></i> Save product');
    btn.prop('disabled', false);
    return;
  }

  const formData = new FormData();
        formData.append('product_name', product_name);
        formData.append('product_name_qr', product_name_qr);
        formData.append('product_sku', product_sku);
        formData.append('product_image', product_image);
        formData.append('product_imgSize', product_image.size);
        formData.append('product_imgType', product_image.type);
        formData.append('submitAddProduct', 'submitAddProduct');

  $.ajax({
      type: 'POST',
      url: HOST_NAME_URL+'/getAjaxServer.php',
      data: formData,
      contentType: false,
      processData: false,
      enctype: false,
      success: function (response) {
          if (response == 'success') {
              notifier.show('Success !', 'Add product success ', 'success', '', 3000);
              btn.html('<i class="fas fa-sync-alt fa-spin"></i> Loading...');
              btn.prop('disabled', true);
              window.location.href = 'products';
          } else {
              notifier.show('Error !', response, 'danger', '', 3000);
              btn.html('<i class="fas fa-save"></i> Save product');
              btn.prop('disabled', false);
          }
      }
  });

}

function editProduct() {
  var product_name      = $("#product_name").val();
  var product_name_qr   = $("#product_name_qr").val();
  var product_sku       = $("#product_sku").val();
  var product_image     = $("#product_image")[0].files[0];
  var p_img             = $("#p_img").val();
  var productID         = $("#productID").val();

  var btn = $("#submitAddProduct");
  btn.html('<i class="fas fa-sync-alt fa-spin"></i> Loading...');
  btn.prop('disabled', true);

  if (product_name === '' || product_name_qr === '' || product_sku === '') {
      notifier.show('Error !', 'This field is required and cannot be empty!', 'danger', '', 6000);
      btn.html('<i class="fas fa-save"></i> Save product');
      btn.prop('disabled', false);
      return;
  }

  if (typeof product_image !== 'undefined') {
      var image = '';
      var imageSize = '';
      var imageType = '';

      var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
      var fileType = product_image.type;

      if (!allowedTypes.includes(fileType)) {
          notifier.show('Image Error!', 'Invalid file type image', 'danger', '', 6000);
          btn.html('<i class="fas fa-save"></i> Save product');
          btn.prop('disabled', false);
          return;
      }

      var maxFileSize = 1 * 1024 * 1024;
      if (maxFileSize <= product_image.size) {
          notifier.show('Image Error!', 'Image Error! File size exceeds the limit (1 MB)', 'danger', '', 6000);
          btn.html('<i class="fas fa-save"></i> Save product');
          btn.prop('disabled', false);
          return;
      }

      image = product_image;
      imageSize = product_image.size;
      imageType = product_image.type;

  } else {
      image = '';
      imageSize = '';
      imageType = '';
  }


  const formData = new FormData();
        formData.append('product_name', product_name);
        formData.append('product_name_qr', product_name_qr);
        formData.append('product_sku', product_sku);
        formData.append('product_image', image);
        formData.append('product_imgSize', imageSize);
        formData.append('product_imgType', imageType);
        formData.append('p_img', p_img);
        formData.append('productID', productID);
        formData.append('submitEditProduct', 'submitEditProduct');

  $.ajax({
      type: 'POST',
      url: HOST_NAME_URL+'/getAjaxServer.php',
      data: formData,
      contentType: false,
      processData: false,
      enctype: false,
      success: function (response) {
          if (response == 'success') {
              notifier.show('Success !', 'Add product success ', 'success', '', 3000);
              btn.html('<i class="fas fa-sync-alt fa-spin"></i> Loading...');
              btn.prop('disabled', true);
              window.location.reload();
          } else {
              notifier.show('Error !', response, 'danger', '', 3000);
              btn.html('<i class="fas fa-save"></i> Save product');
              btn.prop('disabled', false);
          }
      }
  });

}

function deleteProduct(id) {

    Swal.fire({
      title: 'ยืนยันการลบ',
      text: 'คุณแน่ใจหรือไม่ที่ต้องการลบรายการนี้?',
      icon: 'error',
      showCancelButton: true,
      confirmButtonColor: '#F1C40F',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'ใช่, คัดลอก!',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {

          setupProgress(6,'danger');

          setTimeout(function () {
              var progress = Math.ceil( 1 / 1 * 100);
              $('#progress-bar-container .progress-bar').css('width', progress + '%');
              $('#process-number').html(' [ <i class="fas fa-sync-alt fa-spin"></i> Process : ' + progress + ' %   <i class="icon feather icon-trash-2"></i> ] ');

              const formData = new FormData();
              formData.append('id', id);
              formData.append('deleteProduct', 'deleteProduct');

              $.ajax({
                  type: 'POST',
                  url: HOST_NAME_URL + '/getAjaxServer.php',
                  data: formData,
                  contentType: false,
                  processData: false,
                  enctype: false,
                  success: function (response) {
                      notifier.show('<i class="icon feather icon-trash-2"></i> Delete success !', 'Delete product id : [ "'+id+'" ] success ', 'danger', '', 3000);
                      setTimeout(function () {
                          listProductTable();
                      }, 500);
                  },
              });
          }, 200);
      }
    });
}




function SelectAllCheckbox() {
    var checkboxes = document.querySelectorAll('.checkbox');
    var selectedCheckboxes = [];

    checkboxes.forEach(function(checkbox) {
      if (checkbox.checked && checkbox.id !== "select-all") {
        var checkboxValue = checkbox.id.replace('del_', '');
        selectedCheckboxes.push(checkboxValue);
      }
    });
}

function editProductByCheckbox() {
    var checkboxes = document.querySelectorAll('.checkbox');
    var selectedCheckboxes = [];

    checkboxes.forEach(function(checkbox) {
      if (checkbox.checked && checkbox.id !== "select-all") {
        var checkboxValue = checkbox.id.replace('del_', '');
        selectedCheckboxes.push(checkboxValue);
      }
    });

    if (selectedCheckboxes.length > 0) {
        window.location.href = 'product_edit?productID=' + selectedCheckboxes[0];
    }
}

function copyProductByCheckbox() {
    var checkboxes = document.querySelectorAll('.checkbox');
    var selectedCheckboxes = [];

    checkboxes.forEach(function(checkbox) {
      if (checkbox.checked && checkbox.id !== "select-all") {
        var checkboxValue = checkbox.id.replace('del_', '');
        selectedCheckboxes.push(checkboxValue);
      }
    });

    Swal.fire({
      title: 'ยืนยันการคัดลอก',
      text: 'คุณแน่ใจหรือไม่ที่ต้องการคัดลอกรายการนี้?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#F1C40F',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'ใช่, คัดลอก!',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {

        if (selectedCheckboxes.length > 0) {

          setupProgress(6,'warning');

          for (var index = 0; index < selectedCheckboxes.length; index++) {
              setTimeout((function (i) {
                  return function () {
                      var progress = Math.ceil((i + 1) / selectedCheckboxes.length * 100);
                      $('#progress-bar-container .progress-bar').css('width', progress + '%');
                      $('#process-number').html(' [ <i class="fas fa-sync-alt fa-spin"></i> Process : ' + progress + ' %   <i class="icon feather icon-copy"></i> ] ');

                      const formData = new FormData();
                      formData.append('id', selectedCheckboxes[i]);
                      formData.append('copyProductByCheckbox', 'copyProductByCheckbox');

                      $.ajax({
                          type: 'POST',
                          url: HOST_NAME_URL + '/getAjaxServer.php',
                          data: formData,
                          contentType: false,
                          processData: false,
                          enctype: false,
                          success: function (response) {
                              // console.log('timeLoop:', i + " = " +selectedCheckboxes.length);
                              notifier.show('<i class="icon feather icon-copy"></i> Copy success !', 'Copy product success ', 'warning', '', 3000);
                              if(i === (selectedCheckboxes.length - 1)){
                                  setTimeout(function () {
                                      listProductTable();
                                  }, 500);
                              }
                          },
                      });
                  }
              })(index), index * 200);
          }

        }
      }
    });
}

function copyProduct(id) {

    Swal.fire({
      title: 'ยืนยันการคัดลอก',
      text: 'คุณแน่ใจหรือไม่ที่ต้องการคัดลอกรายการนี้?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#F1C40F',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'ใช่, คัดลอก!',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {

          setupProgress(6,'warning');

          setTimeout(function () {
              var progress = Math.ceil( 1 / 1 * 100);
              $('#progress-bar-container .progress-bar').css('width', progress + '%');
              $('#process-number').html(' [ <i class="fas fa-sync-alt fa-spin"></i> Process : ' + progress + ' %   <i class="icon feather icon-copy"></i> ] ');

              const formData = new FormData();
              formData.append('id', id);
              formData.append('copyProductByCheckbox', 'copyProductByCheckbox');

              $.ajax({
                  type: 'POST',
                  url: HOST_NAME_URL + '/getAjaxServer.php',
                  data: formData,
                  contentType: false,
                  processData: false,
                  enctype: false,
                  success: function (response) {
                      notifier.show('<i class="icon feather icon-copy"></i> Copy success !', 'Copy product success ', 'warning', '', 3000);
                      setTimeout(function () {
                          listProductTable();
                      }, 500);
                  },
              });
          }, 200);

      }
    });
}

function deleteProductByCheckbox() {
    var checkboxes = document.querySelectorAll('.checkbox');
    var selectedCheckboxes = [];

    checkboxes.forEach(function(checkbox) {
      if (checkbox.checked && checkbox.id !== "select-all") {
        var checkboxValue = checkbox.id.replace('del_', '');
        selectedCheckboxes.push(checkboxValue);
      }
    });

    Swal.fire({
      title: 'ยืนยันการลบ',
      text: 'คุณแน่ใจหรือไม่ที่ต้องการลบรายการนี้?',
      icon: 'error',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'ใช่, ลบ!',
      cancelButtonText: 'ยกเลิก'
    }).then((result) => {
      if (result.isConfirmed) {

        if (selectedCheckboxes.length > 0) {

            setupProgress(6,'danger');

            for (var index = 0; index < selectedCheckboxes.length; index++) {
                setTimeout((function (i) {
                    return function () {
                        var progress = Math.ceil((i + 1) / selectedCheckboxes.length * 100);
                        $('#progress-bar-container .progress-bar').css('width', progress + '%');
                        $('#process-number').html(' [ <i class="fas fa-sync-alt fa-spin"></i> Process : ' + progress + ' %   <i class="icon feather icon-trash-2"></i> ] ');

                        const formData = new FormData();
                        formData.append('id', selectedCheckboxes[i]);
                        formData.append('deleteProductByCheckbox', 'deleteProductByCheckbox');

                        $.ajax({
                            type: 'POST',
                            url: HOST_NAME_URL + '/getAjaxServer.php',
                            data: formData,
                            contentType: false,
                            processData: false,
                            enctype: false,
                            success: function (response) {
                                // console.log('timeLoop:', i + " = " +selectedCheckboxes.length);
                                notifier.show('<i class="icon feather icon-trash-2"></i> Delete success !', 'Delete product id : [ "'+selectedCheckboxes[i]+'" ] success ', 'danger', '', 3000);
                                if(i === (selectedCheckboxes.length - 1)){
                                    setTimeout(function () {
                                        listProductTable();
                                    }, 500);
                                }
                            },
                        });
                    }
                })(index), index * 200);
            }

        }

      }
    });
}

function listProductTable() {

  var cbtn = $("#cbtn-selectors");
  var tb = $("#listProductTable");

  const formData = new FormData();
        formData.append('listProductTable', 'listProductTable');

  $.ajax({
      type: 'POST',
      url: HOST_NAME_URL + '/getAjaxServer.php',
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {

        if ($.fn.DataTable.isDataTable(cbtn)) {
            $(cbtn).DataTable().destroy();
        }

        $(cbtn).empty().html(response);

        var table = $(cbtn).DataTable({
          dom: 'Bfrtip',
          buttons: [
                {
                    extend: 'pageLength',
                    className: 'btn btn-sm btn-light-secondary mr-1',
                },
                {
                    text: '<i class="fas fa-plus-square"></i> Add Product',
                    className: 'btn btn-sm btn-light-primary mr-1',
                    action: function (e, dt, node, config) {
                        window.location.href = 'product_add';
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-sm btn-light-success mr-1',
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-sm btn-light-danger mr-1',
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-sm btn-light-primary mr-1',
                },
          ],
          "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
          columnDefs: [
                  { targets: 0, orderable: false } // ทำให้ไม่สามารถเรียงลำดับคอลัมน์ที่ 0
          ],
          order: [[1, 'desc']],
        });

        var column0Header = table.column(0).header();
        $(column0Header).removeClass('sorting_desc');

        setupSelectAllCheckbox();
        enableShiftClickSelection();

      },
  });

}

///////////////////////////////////////
/////////////// QR CODE ///////////////
///////////////////////////////////////


/////////////// QR CODE ///////////////
function postQrcode() {

    var btn = $("#submitQrcode");
    btn.html('<i class="fas fa-sync-alt fa-spin"></i> Loading...'); // เปลี่ยน icon เป็นการโหลด
    btn.prop('disabled', true); // ปิดการใช้งานปุ่มในขณะที่โหลด

    $('#progressBar').parent('.progress').show();
    $('#progressBar').css('width', '0%').attr('aria-valuenow', 0).text('Loading... 0%');

    $('#loadingModal').modal({
      backdrop: 'static', // กำหนดไม่ให้สามารถปิด Modal ได้จากการคลิกภายนอก Modal
      keyboard: false, // กำหนดไม่ให้สามารถปิด Modal ด้วยการกดปุ่ม ESC
    }).modal('show');

    var addqrcode = $("#addqrcode").val();
    var product_id = $("#product_id").val();

    var formData = {
        addqrcode: addqrcode,
        product_id: product_id,
        submitQrcode: 'submitQrcode',
    };

    if (addqrcode === '') {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
          }
        });
        Toast.fire({
          icon: "error",
          title: "Error!!",
          text: 'QR Code add failed!',
        });
        btn.html('<i class="fas fa-qrcode"></i> Add QR Code');
        btn.prop('disabled', false);
        $('#loadingModal').modal('hide'); // ปิด Modal เมื่อโหลดสำเร็จ
        return;
    }
    if (product_id === '') {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
          }
        });
        Toast.fire({
          icon: "error",
          title: "Error!!",
          text: 'Select Product failed!',
        });
        btn.html('<i class="fas fa-qrcode"></i> Add QR Code');
        btn.prop('disabled', false);
        $('#loadingModal').modal('hide'); // ปิด Modal เมื่อโหลดสำเร็จ
        return;
    }

    $.ajax({
    type: 'POST',
    url: HOST_NAME_URL+'/getAjaxServer.php',
    data: formData,
    xhr: function () {
        var percentComplete = 0;
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function (evt) {
            if (evt.lengthComputable) {
                percentComplete = (evt.loaded / evt.total) * 100;
                $('#progressBar').css('width', percentComplete + '%').attr('aria-valuenow', percentComplete).text('Loading... ' + percentComplete.toFixed(0) + '%');
            }
        }, false);
        return xhr;
    },
    success: function(response) {
      if (response == 'success') {
        Swal.fire({
          title: 'Success!',
          text: 'Add Success!',
          icon: 'success',
          allowOutsideClick: false,
          timer: 1500,
        }).then(() => {
          QrcodeCountAll();
          document.getElementById('RefreshQRCode').click();
          $('#progressBar').parent('.progress').hide();
          $('#loadingModal').modal('hide'); // ปิด Modal เมื่อโหลดสำเร็จ
        });
      } else {
        Swal.fire({
          title: 'Error!',
          text: 'Add failed!',
          icon: 'error',
          allowOutsideClick: false,
          timer: 1500,
        });
        $('#progressBar').parent('.progress').hide();
        $('#loadingModal').modal('hide'); // ปิด Modal เมื่อเกิดข้อผิดพลาด
      }
    },
    complete: function() {
      btn.html('<i class="fas fa-qrcode"></i> Add QR Code');
      btn.prop('disabled', false);
      $('#loadingModal').modal('hide'); // ปิด Modal เมื่อเสร็จสิ้น
    }
  });
}

/////////////// Qr code Count All ///////////////
function QrcodeCountAll() {
    var formData = {
        ShowQrcodeCountAll: 'ShowQrcodeCountAll',
    };

    $.ajax({
        type: 'POST',
        url: HOST_NAME_URL+'/getAjaxServer.php',
        data: formData,
        success: function(response) {
          $('#QrcodeCountShow').html(Number(response).toLocaleString());
        }
    });
}


/////////////// Search QRCode list ///////////////

function postSearchQRCodeList() {

    var QRProductID  = $('#qr_product_id').val();
    var QRCode_Start = $('#qrcode_start').val();
    var QRCode_End   = $('#qrcode_end').val();
    var QRActivate   = $('#activate').val();
    var QRStatus     = $('#status').val();

    var formData = {
        QRProductID   : QRProductID,
        QRCode_Start  : QRCode_Start,
        QRCode_End    : QRCode_End,
        QRActivate    : QRActivate,
        QRStatus      : QRStatus,
        SearchQRCode  : 'SearchQRCode',
    };

    var btn = $("#SearchQRCode");
    btn.html('<i class="fas fa-sync-alt fa-spin"></i> Searching...'); // เปลี่ยน icon เป็นการโหลด
    btn.prop('disabled', true); // ปิดการใช้งานปุ่มในขณะที่โหลด

    $('#cbtn-selectors').empty().html('<td valign="top" colspan="5" class="dataTables_empty"><i class="fas fa-spinner fa-spin"></i> Searching...</td>');

    if (QRProductID === '') {
        $('#cbtn-selectors').empty().html('<td valign="top" colspan="5" class="dataTables_empty">⚠️ Error Search QRCode List</td>');

        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
          }
        });
        Toast.fire({
          icon: "warning",
          title: "Warning!!",
          text: "Search QRCode Error!!"
        });

        return;
    }

    $.ajax({
        type: 'POST',
        url: HOST_NAME_URL+'/getAjaxServer.php',
        data: formData,
        success: function (response) {

            btn.html('<i class="fas fa-search"></i> Search'); // เปลี่ยน icon เป็นการโหลด
            btn.prop('disabled', false); // ปิดการใช้งานปุ่มในขณะที่โหลด

            if ($.fn.DataTable.isDataTable('#cbtn-selectors')) {
                $('#cbtn-selectors').DataTable().destroy();
            }

            $('#cbtn-selectors').empty().html(response);

            $('#cbtn-selectors').DataTable({
                lengthMenu: [
                    [20, 100, 150, -1],
                    ['20', '100', '150', 'Show all']
                ],
                order: [[0, 'desc']],
            });
        }
    });
}

function RefreshQRCode() {
    var formData = {
        RefreshQRCode: 'RefreshQRCode',
    };

    $.ajax({
        type: 'POST',
        url: HOST_NAME_URL+'/getAjaxServer.php',
        data: formData,
        success: function (response) {
            if ($.fn.DataTable.isDataTable('#cbtn-selectors')) {
                $('#cbtn-selectors').DataTable().destroy();
            }

            $('#cbtn-selectors').empty().html(response);

            $('#cbtn-selectors').DataTable({
                lengthMenu: [
                    [20, 100, 150, -1],
                    ['20', '100', '150', 'Show all']
                ],
                order: [[0, 'desc']],
            });
        }
    });
}

// ====================================================== //
// =================== Front-End ======================== //
// ====================================================== //

var iconXCircle = '<i class="bi bi-x-circle-fill"></i>';
var iconCheck = '<i class="bi bi-check-circle-fill"></i>';

var alertRequired = `${iconXCircle} This field is required and cannot be empty!`;
var alertPassed = `${iconCheck} Passed inspection`;
var alertUsername = `${iconXCircle} Only letters (a-z), numbers (0-9) and length 5 character are allowed!`;
var alertEmailFormat = `${iconXCircle} Invalid email format!`;
var alertMobileFormat = `${iconXCircle} Invalid mobile format!`;



/////////////////////////////////////////////

function toggleSubmitButton() {
    var acceptCheckbox = $("#accept");
    var submitButton = $("#submit");

    if (acceptCheckbox.is(":checked")) {
        submitButton.removeClass('btn-secondary').addClass('btn-success');
        submitButton.prop("disabled", false);
    } else {
        submitButton.removeClass('btn-success').addClass('btn-secondary');
        submitButton.prop("disabled", true);
    }
}

function toggleUnderstoodButton() {
    var acceptCheckbox = $("#accept");
    var submitButton = $("#submit");
    var understoodButton = $("#understood");

    $("#accept").prop("checked", true);
    toggleSubmitButton();
}

function validateisEmpty(input, labelId) {
    let textbox = input.value;

    if (textbox === null || textbox === undefined || textbox.trim() === '') {
        $(`#${labelId}`).removeClass('text-success').addClass('text-danger').html(alertRequired).fadeIn().show();
    } else {
        $(`#${labelId}`).html(alertPassed).hide();
    }
}

function validateUsername(input, labelId) {
    const userPattern = /[^a-zA-Z0-9]/g;
    let user = input.value;

    if (user === null || user === undefined || user.trim() === '') {
        $(`#${labelId}`).removeClass('text-success').addClass('text-danger').html(alertRequired).fadeIn().show();
    }
    else if (userPattern.test(user) || user.length <= 5) {
        $(`#${labelId}`).removeClass('text-success').addClass('text-danger').html(alertUsername).fadeIn().show();
    }
    else {
        $(`#${labelId}`).html(alertPassed).hide();
    }

}

function validateEmail(input, labelId) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let email = input.value;

    if (email === null || email === undefined || email.trim() === '') {
        $(`#${labelId}`).removeClass('text-success').addClass('text-danger').html(alertRequired).show();
    }
    else if (emailPattern.test(email)) {
        $(`#${labelId}`).html(alertPassed).hide();
    } else {
        $(`#${labelId}`).removeClass('text-success').addClass('text-danger').html(alertEmailFormat).show();
    }
}


function validateMobile(input, labelId) {
    var thaiMobileFormat = /^(0[0-9]\d{8}|0[0-9]\d{2}-\d{3}-\d{4}|0[0-9]\d{2} \d{3} \d{4})$/;

    let mobile = input.value;

    if (mobile === null || mobile === undefined || mobile.trim() === '') {
        $(`#${labelId}`).removeClass('text-success').addClass('text-danger').html(alertRequired).show();
    }
    else if (thaiMobileFormat.test(mobile)) {

        let cleanedInput = mobile.replace(/\D/g, '');  // Remove non-digit characters
        let formattedNumber = cleanedInput.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        input.value = formattedNumber; // Update the input value with the formatted number

        $(`#${labelId}`).html(alertPassed).hide();

    } else {

        let cleanedInput = mobile.replace(/\D/g, '');  // Remove non-digit characters
        let formattedNumber = cleanedInput.replace(/(\d{3})(\d{3})(\d{4})/, '$1$2$3');
        input.value = formattedNumber; // Update the input value with the formatted number

        $(`#${labelId}`).removeClass('text-success').addClass('text-danger').html(alertMobileFormat).show();

    }
}

///////////////////////////////////////////

function validateisTextboxEmpty(textbox, labelId) {

    if (textbox === null || textbox === undefined || textbox.trim() === '') {
        $(`#${labelId}`).removeClass('text-success').addClass('text-danger').html(alertRequired).fadeIn().show();
    } else {
        $(`#${labelId}`).html('').show();
    }
}


//////////////////////////////////////////////////
//////////// Ajax Member Login!  /////////////
////////////////////////////////////////////////

function postSignIn() {
    var email         = $("#username").val();
    var password      = $("#password").val();
    var secret_code   = $("#secret_code").val();

    var formData = {
        email     : email,
        password  : password,
        signIn    : "signIn",
    };

    $.ajax({
        type: 'POST',
        url: HOST_NAME_URL+'/getAjaxServer.php',
        data: formData,
        success: function (response) {
            if (response == 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: 'Login Success!',
                    icon: 'success',
                    allowOutsideClick: false,
                    timer: 1500,
                }).then((result) => {
                    if (window.location.pathname.endsWith('/login')) {
                        window.location.href = 'user-profile';
                    }else{
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Login failed!'+response,
                    icon: 'error',
                    allowOutsideClick: false,
                    // timer: 1500,
                });
            }
        }
    });
}

function logOut() {
  var formData = {
      logOut    : "logOut",
  };

  $.ajax({
      type: 'POST',
      url: HOST_NAME_URL+'/getAjaxServer.php',
      data: formData,
      success: function (response) {

        $('#loaderModal').modal('show');
        setTimeout(function () {
            $('#loaderModal').modal('hide');
            window.location.reload();
        }, 2000);
      }
  });
}

//////////////////////////////////////////

function checkingOldPassword() {
  var oldPassword = $("#oldPassword").val();

  var formData = {
      oldPassword      : oldPassword,
      checkOldPassword : "checkOldPassword",
  };

  $.ajax({
      type: 'POST',
      url: HOST_NAME_URL+'/getAjaxServer.php',
      data: formData ,
      success: function (data) {
        if (data == "success") {
          $('#check_oldPassword').removeClass('text-danger').addClass('text-success')
            .html('<i class="bi bi-check-circle-fill text-success"></i> <b>รหัสผ่าน</b> ถูกต้อง').show();
        }else{
          $('#check_oldPassword').removeClass('text-success').addClass('text-danger')
            .html('<i class="bi bi-x-circle-fill text-danger"></i> <b>รหัสผ่าน</b> ไม่ถูกต้อง').show();

        }
      }
  });
}

function checkingConfirmPassword() {
  var newPassword     = $("#newPassword").val();
  var confirmPassword = $("#confirmPassword").val();

  if (newPassword == confirmPassword) {
      $('#check_confirmPassword').removeClass('text-danger').addClass('text-success')
        .html('<i class="bi bi-check-circle-fill text-success"></i> <b>รหัสผ่าน</b> ตรงกัน').show();
  }else{
      $('#check_confirmPassword').removeClass('text-success').addClass('text-danger')
        .html('<i class="bi bi-x-circle-fill text-danger"></i> <b>รหัสผ่าน</b> ไม่ตรงกัน').show();
  }

}

function getChangePasswordButton() {
  var oldPassword     = $("#oldPassword").val();
  var newPassword     = $("#newPassword").val();
  var confirmPassword = $("#confirmPassword").val();

  var formData = {
    oldPassword     : oldPassword,
    newPassword     : newPassword,
    confirmPassword : confirmPassword,
    changePassword  : "changePassword",
  };

  if (oldPassword.trim() === '' || newPassword.trim() === '' || confirmPassword.trim() === '') {
    Swal.fire({
      title: 'Warning!',
      text: 'กรุณากรอกข้อมูลให้ครบถ้วน',
      icon: 'warning',
      allowOutsideClick: false,
    });
  }
  else if (newPassword !== confirmPassword) {
    Swal.fire({
      title: 'Warning!',
      text: 'รหัสผ่าน ไม่ตรงกัน',
      icon: 'warning',
      allowOutsideClick: false,
    });
  }
  else {
    $.ajax({
      type: 'POST',
      url: HOST_NAME_URL + '/getAjaxServer.php',
      data: formData,
      success: function (data) {
        if (data === "success") {
          Swal.fire({
            title: 'Success',
            text: 'เปลี่ยนรหัสผ่านสำเร็จ',
            icon: 'success',
            allowOutsideClick: false,
            timer: 1500,
          }).then((result) => {
            window.location.reload();
          });
        }
        else if (data === "null") {
          Swal.fire({
            title: 'Warning!',
            text: 'กรุณากรอกข้อมูลให้ครบถ้วน',
            icon: 'warning',
            allowOutsideClick: false,
          });
        } else {
          Swal.fire({
            title: 'Error!',
            text: 'รหัสผ่านเก่า ไม่ถูกต้อง',
            icon: 'error',
            allowOutsideClick: false,
          });
        }
      },
      error: function (xhr, textStatus, errorThrown) {
        console.error("Error:", textStatus, errorThrown);
        // Handle AJAX errors more gracefully
        Swal.fire({
          title: 'Error!',
          text: 'พบข้อผิดพลาดในการเปลี่ยนรหัสผ่าน',
          icon: 'error',
          allowOutsideClick: false,
        });
      },
    });

  }
}

//////////////////////////////////////////////////
//////////// change Image File!  /////////////
////////////////////////////////////////////////

function getChangeImageFile() {
    const profile       = $("#imgProfile");
    const imgProfile    = $("#imgProfile")[0].files[0];
    const fileInputPath = $("#imgProfile").val();
    const createObjectURL = URL.createObjectURL(imgProfile);

    const formData = new FormData();
          formData.append('imgProfile', imgProfile);
          formData.append('imgSize', imgProfile.size);
          formData.append('imgType', imgProfile.type);
          formData.append('changeImageFile', 'changeImageFile');

    $.ajax({
        type: 'POST',
        url: HOST_NAME_URL+'/getAjaxServer.php',
        data: formData ,
        contentType: false,
        processData: false,
        enctype: false,
        success: function (result) {
          if(result == "success"){
              Swal.fire({
                  title: 'Success!',
                  text: 'Image uploaded successfully!',
                  icon: 'success',
                  allowOutsideClick: false,
              }).then((result) => {
                $('#iconImgProfile').removeClass('bi-pencil').addClass('bi-check-lg');
                $('#outputPerson').attr('src', URL.createObjectURL(imgProfile));
              });
          }
          else if(result == 'notImage'){
              Swal.fire({
                  title: 'Error!',
                  text: 'Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.',
                  icon: 'error',
                  allowOutsideClick: false,
              });
          }
          else if(result == 'notSize'){
              Swal.fire({
                  title: 'Error!',
                  text: 'File size exceeds the limit (1 MB)!',
                  icon: 'error',
                  allowOutsideClick: false,
              });
          }
          else {
              Swal.fire({
                  title: 'Error!',
                  text: 'Image upload failed!',
                  icon: 'error',
                  allowOutsideClick: false,
              });
          }

        }
    });

}


//////////////////////////////////////////////////
//////////// Ajax กรอกข้อมูลยืนยันตน!  /////////////
////////////////////////////////////////////////


function getCustomerQrcode() {

    var url = new URL(window.location.href);
    var secretCode = url.searchParams.get("secret_code");

    var formData = {
          app           : $("#app").val(),
          fullName      : $("#fullName").val(),
          email         : $("#email").val(),
          mobile        : $("#mobile").val(),
          province      : $("#province").val(),
          districts     : $("#districts").val(),
          subdistricts  : $("#subdistricts").val(),
          ipinfoData    : $("#ipinfoData").val(),
          accept        : $("#accept").val(),
          secret_code   : secretCode,
          status        : $("#status").val(),
          secretCode    : "secretCode",
        };

    Object.entries(formData).forEach(([key, value]) => {
          checker_textbox(key, value);
    });

    $.ajax({
        type: 'POST',
        url: HOST_NAME_URL+'/getAjaxServer.php', // Replace with the actual path to your server-side script
        data: formData ,
        success: function (data) {
            if(data == "success"){
                Swal.fire({
                    title: 'Success!',
                    text: 'ลงข้อมูลยืนยันตนสำเร็จ!',
                    icon: 'success',
                    allowOutsideClick: false,
                    timer: 1500,
                }).then((result) => {
                  window.location.reload();
                });
            }
            else{
              var text = '';

              if (data == "er_mobile") text = 'เบอร์มือถือ เคยถูกใช้แล้ว โปรด Sign In เข้าสู่ระบบ';
              else if (data == "er_email") text = 'E-mail เคยถูกใช้แล้ว โปรด  Sign In เข้าสู่ระบบ';
              else if (data == "validate_mobile") text = 'รูปแบบ เบอร์มือถือ ไม่ถูกต้อง';
              else if (data == "validate_email") text = 'รูปแบบ E-mail ไม่ถูกต้อง';

              else if (data == "null_app") text = 'กรุณาเลือก application แหล่งที่ซื้อ';
              else if (data == "null_email") text = 'กรุณากรอก E-mail ห้ามเป็นค่าว่าง';
              else if (data == "null_mobile") text = 'กรุณากรอก เบอร์มือถือ ห้ามเป็นค่าว่าง';
              else if (data == "null_fullName") text = 'กรุณากรอก ชื่อ-นามสกุล ห้ามเป็นค่าว่าง';
              else if (data == "null_subdistricts") text = 'กรุณาเลือก แขวง/ตำบล ห้ามเป็นค่าว่าง';
              else if (data == "null_districts") text = 'กรุณาเลือก เขต/อำเภอ ห้ามเป็นค่าว่าง';
              else if (data == "null_province") text = 'กรุณาเลือก จังหวัด ห้ามเป็นค่าว่าง';
              else text = 'กรุณากรอกข้อมูลให้ครบถ้วน Insert failed!';

              Swal.fire({
                  title: 'Error!',
                  text: text,
                  icon: 'error',
                  allowOutsideClick: false,
              });
            }
              checker_textboxMobile($("#mobile").val(), "check_mobile");
              checker_textboxEmail($("#email").val(), "check_email");

              checkRepetitivEmailSubmit($("#email").val(), "check_email");
              checkRepetitiveMobileSubmit($("#mobile").val(), "check_mobile");
        }
    });

}

function getCustomerQrcodeByMember() {

    var url = new URL(window.location.href);
    var secretCode = url.searchParams.get("secret_code");

    var formData = {
          appByMember         : $("#appByMember").val(),
          ipinfoData          : $("#ipinfoData").val(),
          accept              : $("#accept").val(),
          secret_code         : secretCode,
          status              : $("#status").val(),
          secretCodeByMember  : "secretCodeByMember",
        };

    $.ajax({
        type: 'POST',
        url: HOST_NAME_URL+'/getAjaxServer.php', // Replace with the actual path to your server-side script
        data: formData ,
        success: function (data) {
            if(data == "success"){
                Swal.fire({
                    title: 'Success!',
                    text: 'ลงข้อมูลยืนยันตนสำเร็จ!',
                    icon: 'success',
                    allowOutsideClick: false,
                    timer: 1500,
                }).then((result) => {
                  window.location.reload();
                });
            }else{
              Swal.fire({
                  title: 'Warning!',
                  text: 'คุณเคยทำการยืนยันสินค้านี้ไปล้ว',
                  icon: 'warning',
                  allowOutsideClick: false,
              });
            }
        }
    });
}


function getEditProfile() {

    var formData = {
          fullName        : $("#fullName").val(),
          province        : $("#province").val(),
          districts       : $("#districts").val(),
          subdistricts    : $("#subdistricts").val(),
          getEditProfile  : "getEditProfile",
        };

    $.ajax({
        type: 'POST',
        url: HOST_NAME_URL+'/getAjaxServer.php', // Replace with the actual path to your server-side script
        data: formData ,
        success: function (data) {
            if(data == "success"){
                Swal.fire({
                    title: 'Success!',
                    text: 'เปลี่ยนแปลงข้อมูลโปรไฟล์สำเร็จ!',
                    icon: 'success',
                    allowOutsideClick: false,
                    timer: 1500,
                }).then((result) => {
                  window.location.reload();
                });
            }else if(data == "failed"){
              Swal.fire({
                  title: 'Error!',
                  text: 'พบข้อผิดพลาด!',
                  icon: 'error',
                  allowOutsideClick: false,
                  timer: 1500,
              });
            }else{
              Swal.fire({
                  title: 'Warning!',
                  text: 'กรุณากรอกข้อมูลให้ครบถ้วน Insert failed!',
                  icon: 'warning',
                  allowOutsideClick: false,
              });
            }

        }
    });

}



/////////////////////////////////////////
function checker_textbox(key,value) {

  if (key !== 'mobile' && key !== 'email') {
    if (value === null || value === undefined || value.trim() === '') {
        $("#check_" + key)
            .removeClass('text-success')
            .addClass('text-danger')
            .html('<i class="bi bi-x-circle-fill"></i> This field is required and cannot be empty')
            .show();
        return;
    } else {
        $("#check_" + key)
            .removeClass('text-danger')
            .addClass('text-success')
            .html('<i class="bi bi-check-circle-fill"></i> Passed inspection')
            .show();
    }
  }
}

function checker_textboxEmail(value,key) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let email = value;

    if (email === null || email === undefined || email.trim() === '') {
        $(`#${key}`).removeClass('text-success').addClass('text-danger').html(alertRequired).show();
    }
    else if (emailPattern.test(email)) {
        $(`#${key}`).removeClass('text-danger').addClass('text-success').html(alertPassed).show();
    } else {
        $(`#${key}`).removeClass('text-success').addClass('text-danger').html(alertEmailFormat).show();
    }
}


function checker_textboxMobile(value,key) {
    var thaiMobileFormat = /^(0[0-9]\d{8}|0[0-9]\d{2}-\d{3}-\d{4}|0[0-9]\d{2} \d{3} \d{4})$/;
    let formattedNumber = cleanedInput.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');

    let mobile = value;

    if (mobile === null || mobile === undefined || mobile.trim() === '') {
        $(`#${key}`)
            .removeClass('text-success')
            .addClass('text-danger')
            .html(alertRequired)
            .show();
    } else if (thaiMobileFormat.test(value)) {
        let cleanedInput = mobile.replace(/\D/g, '');  // Remove non-digit characters
        let formattedNumber = cleanedInput.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        mobile = formattedNumber; // Update the input value with the formatted number
        $(`#${key}`)
            .removeClass('text-danger')
            .addClass('text-success')
            .html(alertPassed)
            .show();
    } else {
        $(`#${key}`)
            .removeClass('text-success')
            .addClass('text-danger')
            .html(alertMobileFormat)
            .show();
    }
}

//////////////////////////////////////////////////

function shuffleString(inputString) {
  const array = inputString.split('');
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array.join('');
}

function generateRandomPassword() {
  const lengthChar = 2;
  const length = 8;
  const lowerCharset = "abcdefghijklmnopqrstuvwxyz";
  const upperCharset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  const numberCharset = "0123456789";
  const specialCharset = "!@#$%^*";

  const charset = lowerCharset + upperCharset + numberCharset + specialCharset;

  let password = "";

  for (let i = 0; i < lengthChar; i++) {
    const lowerIndex = Math.floor(Math.random() * lowerCharset.length);
    password += lowerCharset.charAt(lowerIndex);
  }
  for (let i = 0; i < lengthChar; i++) {
    const upperIndex = Math.floor(Math.random() * upperCharset.length);
    password += upperCharset.charAt(upperIndex);
  }
  for (let i = 0; i < lengthChar; i++) {
    const numberIndex = Math.floor(Math.random() * numberCharset.length);
    password += numberCharset.charAt(numberIndex);
  }
  for (let i = 0; i < lengthChar; i++) {
    const specialIndex = Math.floor(Math.random() * specialCharset.length);
    password += specialCharset.charAt(specialIndex);
  }

  return shuffleString(password);
}

function setRandomPasswordInInput() {
  const newPasswordInput = $("#newPassword");
  const confirmPasswordInput = $("#confirmPassword");
  const randomPassword = generateRandomPassword();
  newPasswordInput.val(randomPassword);
  confirmPasswordInput.val(randomPassword);

  newPasswordInput.prop('type', 'text');
  newPasswordInput.removeClass('bi-eye-slash').addClass('bi-eye');
  confirmPasswordInput.prop('type', 'text');
  confirmPasswordInput.removeClass('bi-eye-slash').addClass('bi-eye');

  passwordStrength();

  if($("#confirmPassword").val() !== ''){
      checkingConfirmPassword();
  }
}


function togglePassword(button) {
    var passwordField = button.previousElementSibling;
    var passwordIcon = button.querySelector('i');

    if (passwordField.type === 'password') {
      passwordField.type = 'text';
      passwordIcon.classList.remove('bi-eye-slash');
      passwordIcon.classList.add('bi-eye');
    } else {
      passwordField.type = 'password';
      passwordIcon.classList.remove('bi-eye');
      passwordIcon.classList.add('bi-eye-slash');
    }
}

function passwordStrength() {

    var password = $("#newPassword").val();
    $("#label_newPassword").show();

    const thaiPattern = /[ก-ฮ]/;
    const lowerPattern = /[a-z]/;
    const upperPattern = /[A-Z]/;
    const numberPattern = /[0-9]/;
    const specialPattern = /[!@#$%^&*_-]+/;
    var score = 0;

    if (lowerPattern.test(password)) score++;
    if (upperPattern.test(password)) score++;
    if (numberPattern.test(password)) score++;
    if (specialPattern.test(password)) score++;

    if (password.trim() === '') score = 0;
    if (thaiPattern.test(password)) score = -1;

    var $statusPassword = $("#label_statusPassword");
    var $progressbar = $("#progressbar");

    if (score === -1) {
      $statusPassword.attr("class", "text-danger").html('<i class="bi bi-x-circle text-danger"></i> ห้ามภาษาไทย').show();
      $progressbar.css("width", "0").attr("aria-valuenow", 0);
    } else if (score >= 1 && score <= 4) {
      const percentage = score * 25;
      const statusClass = `${score === 4 ? 'success' : (score === 3 ? 'primary' : (score === 2 ? 'warning' : 'danger'))}`;

      $statusPassword.attr("class", `text-${statusClass}`).html(score === 4 ? 'ยากมาก' : (score === 3 ? 'ยาก' : (score === 2 ? 'ธรรมดา' : 'ง่ายเกินไป'))).show();
      $progressbar.css("width", `${percentage}%`).attr("aria-valuenow", percentage).attr("class", `bg-${statusClass}`);
    } else {
      $statusPassword.attr("class", "text-danger").html('<i class="bi bi-x-circle text-danger"></i> ผิดพลาด').show();
      $progressbar.css("width", "0%").attr("aria-valuenow", 0);
    }

}

////////////////////////////////////////////////////

function checkRepetitiveMobileSubmit(input, labelId) {

  let mobile = input;

  var formData = {
      mobile                : mobile,
      checkRepetitiveMobile : "checkRepetitiveMobile",
  };

  $.ajax({
      type: 'POST',
      url: HOST_NAME_URL+'/getAjaxServer.php',
      data: formData ,
      success: function (data) {
        if (data) {
          $(`#${labelId}`).removeClass('text-success').addClass('text-danger')
            .html('<i class="bi bi-x-circle-fill"></i> <b>เบอร์มือถือ</b> เคยถูกใช้แล้ว โปรด <a class="text-light" href="#" data-bs-toggle="modal" data-bs-target="#modalLogin"><i class="bi bi-shield-lock"></i> <span>Sign In</span></a> เข้าสู่ระบบ').show();
        }
      }
  });

}

function checkRepetitivEmailSubmit(input, labelId) {

  let email = input;

  var formData = {
      email                : email,
      checkRepetitivEmail : "checkRepetitivEmail",
  };

  $.ajax({
      type: 'POST',
      url: HOST_NAME_URL+'/getAjaxServer.php',
      data: formData ,
      success: function (data) {
        if (data) {
          $(`#${labelId}`).removeClass('text-success').addClass('text-danger')
            .html('<i class="bi bi-x-circle-fill"></i> <b>E-mail</b> เคยถูกใช้แล้ว โปรด <a class="text-light" href="#" data-bs-toggle="modal" data-bs-target="#modalLogin"><i class="bi bi-shield-lock"></i> <span>Sign In</span></a> เข้าสู่ระบบ').show();
        }
      }
  });

}


function checkRepetitiveMobile(input, labelId) {

  let mobile = input.value;

  var formData = {
      mobile                : mobile,
      checkRepetitiveMobile : "checkRepetitiveMobile",
  };

  $.ajax({
      type: 'POST',
      url: HOST_NAME_URL+'/getAjaxServer.php',
      data: formData ,
      success: function (data) {
        if (data) {
          $(`#${labelId}`).removeClass('text-success').addClass('text-danger')
            .html('<i class="bi bi-x-circle-fill"></i> <b>เบอร์มือถือ</b> เคยถูกใช้แล้ว โปรด <a class="text-light" href="#" data-bs-toggle="modal" data-bs-target="#modalLogin"><i class="bi bi-shield-lock"></i> <span>Sign In</span></a> เข้าสู่ระบบ').show();
        }
      }
  });

}

function checkRepetitivEmail(input, labelId) {

  let email = input.value;

  var formData = {
      email                : email,
      checkRepetitivEmail : "checkRepetitivEmail",
  };

  $.ajax({
      type: 'POST',
      url: HOST_NAME_URL+'/getAjaxServer.php',
      data: formData ,
      success: function (data) {
        if (data) {
          $(`#${labelId}`).removeClass('text-success').addClass('text-danger')
            .html('<i class="bi bi-x-circle-fill"></i> <b>E-mail</b> เคยถูกใช้แล้ว โปรด <a class="text-light" href="#" data-bs-toggle="modal" data-bs-target="#modalLogin"><i class="bi bi-shield-lock"></i> <span>Sign In</span></a> เข้าสู่ระบบ').show();
        }
      }
  });

}


///////////////////////////////////////////////////////////////////
/////////////// Ajax combo selects จังหวัด อำเภอ ตำบล //////////////
//////////////////////////////////////////////////////////////////
function getDistricts() {
    var provinceId = $("#province").val();
    $("#districts").val(null);
    $("#subdistricts").val(null);
    $.ajax({
        type: 'GET',
        url: HOST_NAME_URL+'/getAjaxServer.php', // Replace with the actual path to your server-side script
        data: {
            provinceId: provinceId
        },
        success: function (data) {
            $('#districts_box').show();
            $('#districts').html(data);
            $('#subdistricts_box').hide();
        }
    });
}

function getSubdistricts() {
    var districtsId = $("#districts").val(); // Corrected variable name
    $("#subdistricts").val(null);
    $.ajax({
        type: 'GET',
        url: HOST_NAME_URL+'/getAjaxServer.php', // Replace with the actual path to your server-side script
        data: {
            districtsId: districtsId
        },
        success: function (data) {
            $('#subdistricts_box').show();
            $('#subdistricts').html(data);
            // Optionally trigger the next dropdown update here if needed
        }
    });
}
