///////////////////////////////
/////// Setting Product ///////
///////////////////////////////

function table_product() {

    var typeSearch = $("#selectSearch").val();
    var textSearch = $("#textSearch").val();

    var formData = {
        typeSearch : typeSearch,
        textSearch : textSearch,
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
            }, 500);

           

        },
        error: function (error) {
            console.error('Error uploading video to server:', error);
        }
    });
}

function selectProductName() {
    $("#selectSearch").val('productName');
}

function selectProductCode() {
    $("#selectSearch").val('productCode');
}


function addProduct() {
    var productName  = $("#productName");
    var image        = $("#image");
    var qrName       = $("#qrName");
    var sku          = $("#sku");

    var formData = new FormData();
    formData.append('productName', productName.val());
    formData.append('image', image[0].files[0]);
    formData.append('qrName', qrName.val());
    formData.append('sku', sku.val());
    formData.append('method', 'addProduct');

    productName.addClass("is-perload");
    image.addClass("is-perload");
    qrName.addClass("is-perload");
    sku.addClass("is-perload");

    $.ajax({
        type: 'POST',
        url: JSON_HOST_NAME_URL + 'product/product-function.php',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            var jsonData = JSON.parse(response); 
            console.log(jsonData);

            productName.removeClass("is-perload");
            image.removeClass("is-perload");
            qrName.removeClass("is-perload");
            sku.removeClass("is-perload");

            setTimeout(function() {
                $('#addProduct').modal('hide');
            }, 1000);

        },
        error: function (error) {
            console.error('Error uploading video to server:', error);
        }
    });
}


window.onload = function() {
    table_product();
};
