<section class="pc-container">
  <div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center justify-content-between">
          <div class="col-sm-auto">
            <div class="page-header-title">
              <h5 class="mb-0">List Products</h5>
            </div>
          </div>
          <div class="col-sm-auto">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="products">E-commerce</a></li>
              <li class="breadcrumb-item" aria-current="page">List Products</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- Column Selectors table start -->
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
              <h5>Products List</h5>
          </div>
          <div class="card-body">
            <div class="dt-responsive table-responsive">

              <table id="cbtn-selectors" class="table table-hover table-striped nowrap">
                <thead>
                  <tr>
                    <th width="2%"><input type="checkbox" id="select-all" class="checkbox"></th>
                    <th width="10%">ID</th>
                    <th width="20%">Image</th>
                    <th width="38%">Product</th>
                    <th width="15%">SKU</th>
                    <th width="15%">Action</th>
                  </tr>
                </thead>
                <tbody id="listProductTable">
                  <?php
                    $stmt = $db->products_list();
                    $ProductList = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($ProductList as $Product) {
                      echo
                      '<tr id="tb-productID-'.$Product['id'].'">' .
                          '<td><input type="checkbox" id="'.$Product['id'].'" value="'.$Product['id'].'" class="checkbox" ></td>' .
                          '<td>'.$Product['id'].'</label></td>' .
                          '<td> <img src="' . $Product['product_image'] . '" width="40"></td>' .
                          '<td>' . $Product['product_name'] . '</td>' .
                          '<td>' . $Product['product_sku'] . '</td>' .
                          '<td>' .
                            '<a class="mr-3" href="#" onclick="copyProduct('.$Product['id'].')"><i class="icon feather f-16 icon-copy text-warning"></i></a> ' .
                            '<a class="mr-3" href="product_edit?productID='.$Product['id'].'"><i class="icon feather icon-edit f-16 text-success"></i></a> ' .
                            '<a class="mr-3" href="#" onclick="deleteProduct('.$Product['id'].')"><i class="feather icon-trash-2 f-16 text-danger"></i></a>' .
                          '</td>' .
                      '</tr>';
                    }
                  ?>
                </tbody>
              </table>

              <div class="form-group">
                <button type="button" class="btn btn-sm btn-light-primary" tabindex="0" aria-controls="cbtn-selectors" onclick="window.location.href='product_add';"><span><i class="fas fa-plus-square"></i> Add Product</span></button>
                <button type="button" class="btn btn-sm btn-light-secondary" name="bt-select-all" id="bt-select-all" onclick="check_selectAll()"><i class="icon feather icon-check-square"></i> Select all</button>
                <button type="button" class="btn btn-sm btn-light-secondary" name="bt-unselect-all" id="bt-unselect-all" onclick="check_UnselectAll()"><i class="icon feather icon-x-square"></i> Unselect all</button>
                <button type="button" class="btn btn-sm btn-light-info" name="editProduct" id="editProduct" onclick="editProductByCheckbox()"><i class="icon feather icon-edit"></i> Edit</button>
                <button type="button" class="btn btn-sm btn-light-warning" name="copyProductAll" id="copyProductAll" onclick="copyProductByCheckbox()"><i class="icon feather icon-copy"></i> Copy</button>
                <button type="button" class="btn btn-sm btn-light-danger" name="deleteProductAll" id="deleteProductAll" onclick="deleteProductByCheckbox()"><i class="icon feather icon-trash-2"></i> Delete</button>
              </div>

            </div>
          </div>
        </div>
      </div><!-- Column Selectors end -->
    </div><!-- [ Main Content ] end -->
  </div>
</section>

<script>
  $(document).ready(function() {
    // เรียกใช้ DataTables
    var table = $('#cbtn-selectors').DataTable({
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

  });
</script>
<!-- [Page Specific JS] end -->
