<section class="pc-container">
  <div class="pc-content">
    <!-- [ Main Content ] start -->
    <div class="row">
      <div class="col-sm-4">
          <!-- Basic Inputs -->
          <div class="card">
              <div class="card-header">
                  <h5>Add QR Code</h5>
              </div>
              <div class="card-body">
                  <div class="row align-items-center">
                      <div class="col-8">
                          <h4 class="text-success">1 <i class="fas fa-exchange-alt text-success f-18"></i> <span id="QrcodeCountShow"><?=number_format(10);?></span></h4>
                          <h6 class="text-muted m-b-0">Total QR Code rows</h6>
                      </div>
                      <div class="col-4 text-end"><i class="material-icons-two-tone f-50">qr_code</i>
                      </div>
                  </div>
              </div>
              <div class="card-body">
                <div class="row row-cols-2 row-cols-md-2 row-cols-lg-2 g-2">
                  <div class="col">
                      <div class="btn btn-outline-dark card p-3 d-flex flex-row align-items-center justify-content-between shadow-none border border-secondary-subtle mb-0" onclick="setValue('500')">
                          <h3 class="mb-0">500</h3>
                          <p class="text-muted d-flex align-items-center mb-0"><i class="material-icons-two-tone">qr_code</i></p>
                      </div>
                    </div>
                    <div class="col">
                      <div class="btn btn-outline-dark card p-3 d-flex flex-row align-items-center justify-content-between shadow-none border border-secondary-subtle mb-0" onclick="setValue('1,000')">
                          <h3 class="mb-0">1,000</h3>
                          <p class="text-muted d-flex align-items-center mb-0"><i class="material-icons-two-tone">qr_code</i></p>
                      </div>
                    </div>
                    <div class="col">
                      <div class="btn btn-outline-dark card p-3 d-flex flex-row align-items-center justify-content-between shadow-none border border-secondary-subtle mb-0" onclick="setValue('5,000')">
                          <h3 class="mb-0">5,000</h3>
                          <p class="text-muted d-flex align-items-center mb-0"><i class="material-icons-two-tone">qr_code</i></p>
                      </div>
                    </div>
                    <div class="col">
                      <div class="btn btn-outline-dark card p-3 d-flex flex-row align-items-center justify-content-between shadow-none border border-secondary-subtle mb-0" onclick="setValue('10,000')">
                          <h3 class="mb-0">10,000</h3>
                          <p class="text-muted d-flex align-items-center mb-0"><i class="material-icons-two-tone">qr_code</i></p>
                      </div>
                    </div>
                </div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label class="form-label" for="multiple-addons">Count QR Code</label>
                  <div class="input-group">
                    <input type="text" id="addqrcode" name="addqrcode" class="formatted-number form-control" placeholder="0">
                    <span class="input-group-text">Rows</span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-label" for="product_id">Select Product</label>
                  <div class="input-group">
                    <span class="input-group-text">
                      <img class="rounded-circle" src="assets/images/no_image.jpg" id="productImage" alt="Product Image" style="max-width: 40px; height: auto;">
                    </span>
                    <select class="form-select" id="product_id" name="product_id" onchange="displayProductImage()">
                      <option value="" selected="selected" data-img="assets/images/no_image.jpg">==== Select Product ====</option>
                      <?php
                        $stmt = $db->products_list();
                        $SelectProductList = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($SelectProductList as $SelectProduct) {
                          echo '<option value="' . $SelectProduct['id'] . '" data-img="' . $SelectProduct['product_image'] . '">' . $SelectProduct['product_name'] . '</option>';
                        }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="modal" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
                  <div class="modal-dialog-centered modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <h3><center>⚠️ Please do not close.</center></h3><br>
                        <div class="progress" style="display: none;">
                          <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Loading..." aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading... 0%</div>
                        </div>
                        <p><i class="fas fa-spinner fa-spin"></i> Loading...</p>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

              <div class="card-footer pt-0">
                <button type="submit" class="btn btn-primary me-2" name="submitQrcode" id="submitQrcode" value="submitQrcode" onclick="postQrcode();">
                  <i class="fas fa-qrcode"></i> Add QR Code
                </button>
              </div>
          </div><!-- HTML Input Types -->
      </div>
      <!-- Column Selectors table start -->
      <div class="col-sm-8">
        <div class="card">
          <div class="card-header">
              <h5>QR Code List</h5>
          </div>
          <div class="card-header">
            <div class="row row-cols-md-auto lign-items-center">

              <div class="form-group col-md-4">
                <div class="input-group">
                  <span class="input-group-text">ID</span>
                  <input type="text" id="qrcode_start" name="qrcode_start" class="formatted-number form-control" placeholder="0" value="0">
                  <span class="input-group-text">Start</span>
                </div>
              </div>

              <div class="form-group col-md-4">
                <div class="input-group">
                  <span class="input-group-text">ID</span>
                  <input type="text" id="qrcode_end" name="qrcode_end" class="formatted-number form-control" placeholder="0" value="0">
                  <span class="input-group-text">End</span>
                </div>
              </div>

                <div class="form-group col-md-4">
                  <select class="form-select" id="qr_product_id" name="qr_product_id">
                    <option value="<?php echo $db->products_list_id(); ?>" selected="selected">📦 All Product</option>
                    <?php
                      $stmt = $db->products_list();
                      $SelectProductList = $stmt->fetchAll(PDO::FETCH_ASSOC);

                      foreach ($SelectProductList as $SelectProduct) {
                        echo '<option value="' . $SelectProduct['id'] . '">' . $SelectProduct['product_name'] . '</option>';
                      }
                    ?>
                  </select>
                </div>

                <div class="form-group col-md-4">
                  <select class="form-select" id="status" name="status">
                    <option value="0,1" selected="selected">🖨️ Status</option>
                    <option value="0">[0] ยังไม่ได้พิมพ์</option>
                    <option value="1">[1] พิมพ์แล้ว</option>
                  </select>
                </div>

                <div class="form-group col-md-4">
                  <select class="form-select" id="activate" name="activate">
                    <option value="0,1" selected="selected">🙋🏻‍♂️ Activate</option>
                    <option value="0">[0] ยังไม่ได้สแกน</option>
                    <option value="1">[1] สแกนแล้ว</option>
                  </select>
                </div>

                <div class="form-group col-md-2">
                  <button style="width:100%" type="submit" class="btn btn-primary me-2" name="SearchQRCode" id="SearchQRCode" value="SearchQRCode" onclick="postSearchQRCodeList();">
                    <i class="fas fa-search"></i> Search</button>
                </div>
                <div class="form-group col-md-2">
                  <button style="width:100%" type="submit" class="btn btn-primary" name="RefreshQRCode" id="RefreshQRCode" value="RefreshQRCode" onclick="RefreshQRCode()">
                  <i class="ti ti-refresh"></i> Reset</button>
                </div>
            </div>
          </div>
          <div class="card-body">
            <div class="dt-responsive table-responsive">
              <table id="cbtn-selectors" class="table table-striped table-bordered nowrap">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>ID Qr Code</th>
                    <th>Secret</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Activate</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $stmtQR = $db->qrcode_list_first();
                  $QRCodeListstmt = $stmtQR->fetchAll(PDO::FETCH_ASSOC);
                  foreach ($QRCodeListstmt as $QRCodeTd) {
                    echo
                    '<tr>' .
                        '<td>' . number_format($QRCodeTd['id']) . '</td>' .
                        '<td>' . $QRCodeTd['product_sku'].$QRCodeTd['product_id'].$QRCodeTd['id_qrcode'] . '</td>' .
                        '<td>' . $QRCodeTd['secret'] . '</td>' .
                        '<td>' . $QRCodeTd['product_name'] . '</td>' . // เปลี่ยนเป็น product_name แทน product_id
                        '<td>' . $QRCodeTd['status'] . '</td>' .
                        '<td>' . $QRCodeTd['activate'] . '</td>' .
                    '</tr>';
                  }
                   ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div><!-- Column Selectors end -->
    </div><!-- [ Main Content ] end -->
  </div>
</section>

<script>
  function displayProductImage() {
    var productSelect = document.getElementById('product_id');
    var selectedProduct = productSelect.options[productSelect.selectedIndex];

    var productImage = document.getElementById('productImage');
    var imgUrl = selectedProduct.getAttribute('data-img');

    productImage.src = imgUrl;
    productImage.style.display = 'block';
  }
</script>

<script>
  $(document).ready(function() {
    // เรียกใช้ DataTables
    $('#cbtn-selectors').DataTable({
        lengthMenu: [
            [20, 100, 150, -1],
            ['20', '100', '150', 'Show all']
        ],
        order: [[0, 'desc']] // ให้คอลัมน์ที่มี index 0 (ID) เรียงลำดับจากมากไปน้อย (descending order)
    });
  });
</script>
<!-- [Page Specific JS] end -->

<script>
function setValue(value) {
  document.getElementById('addqrcode').value = value;
}
</script>
