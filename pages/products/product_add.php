<div class="pc-container">
  <div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center justify-content-between">
          <div class="col-sm-auto">
            <div class="page-header-title">
              <h5 class="mb-0">Add New Product</h5>
            </div>
          </div>
          <div class="col-sm-auto">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="javascript: void(0)">E-commerce</a></li>
              <li class="breadcrumb-item" aria-current="page">Add New Product</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row">
      <!-- [ sample-page ] start -->
      <div class="col-xl-6">
        <div class="card">
          <div class="card-header">
            <h5>Add Product</h5>
          </div>
          <div class="card-body">
            <formenctype="multipart/form-data">
              <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Product Name" required>
              </div>
              <div class="form-group">
                <label class="form-label">Product Name QR_CODE</label>
                <input type="text" class="form-control" id="product_name_qr" name="product_name_qr" placeholder="Enter Product QR_CODE"
                oninput="convertToUpperCase('product_name_qr')" maxlength="15"
                required>
              </div>
              <div class="form-group">
                <label class="form-label">Product SKU</label>
                <input type="text" class="form-control" id="product_sku" name="product_sku" placeholder="Enter Product SKU" maxlength="3"
                oninput="convertToUpperCase('product_sku')" required>
                <label class="form-label fz-12 text-muted mt-2" text->0-9, A-Z Uppercase letters and 3 Character</label>
              </div>
              <div class="form-group">
                <label class="form-label">Product Image</label>
                <div class="input-group">
                  <input type="file" class="form-control" id="product_image" name="product_image">
                  <label class="input-group-text" for="product_image">Upload</label>
                </div>
              </div>
            </form>
          </div>
          <div class="card">
            <div class="card-body text-end btn-page">
              <button class="btn btn-light-primary me-2" name="submitAddProduct" id="submitAddProduct" value="submitQrcode" onclick="addProduct();">
                <i class="fas fa-save"></i> Save product 
              </button>
            </div>
          </div>

        </div>
      </div>

      <div class="col-xl-6">
        <div class="card">
          <div class="card-header">
            <h5>Product details</h5>
          </div>
          <div class="card-body">

            <table class="table">
              <tr>
                  <th>Product Image</th>
                  <th><img src="assets/images/products/product_unknown.jpg" width="150" height="150" alt="logo img"></th>
              </tr>
              <tr>
                  <th>Product Name</th>
                  <th></th>
              </tr>
              <tr>
                  <th>Product Name QR_CODE</th>
                  <td></td>
              </tr>
              <tr>
                  <th>Product SKU</th>
                  <td></td>
              </tr>
              <!-- เพิ่มแถวตามจำนวนสินค้าที่ต้องการแสดง -->
          </table>

        </div>
      </div>

    </div><!-- [ Main Content ] end -->

  </div>
</div><!-- [ Main Content ] end -->
