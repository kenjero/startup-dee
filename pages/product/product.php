<section class="pc-container">
  <div class="pc-content">
    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- Column Selectors table start -->
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
              <h5>Products List</h5>
          </div>
          <div class="card-header">
            <div class="row row-cols-md-auto lign-items-center">

              <div class="col-md-7">
                <div class="input-group input-group-sm">
                  <button class="input-group-text btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Product Name</button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" onclick="selectProductName();">Product Name</a></li>
                    <li><a class="dropdown-item" onclick="selectProductCode();">Product Code</a></li>
                  </ul>
                  <input type="hidden" class="form-control" id="selectSearch">
                  <input type="text" class="form-control" id="textSearch">
                  <button type="button" class="input-group-text btn btn-dark" onclick="search_record();"><i class="fa fa-search fs-6 me-1"></i> Search</button>
                  <button type="button" class="input-group-text btn btn-dark ms-3"onclick="search_record_all();"><i class="fa fa-search fs-6 me-1"></i> Search All</button>
                </div>
              </div>
              <div class="col-md-5 text-end">
                <button type="submit" class="btn btn-sm btn-light-success" data-bs-toggle="modal" data-bs-target="#addProduct"><i class="fas fa-plus fs-6 me-1"></i> Add Product <i class="fas fa-box fs-6 ms-1"></i> </button>
              </div>

            </div>
          </div>

          <div class="card-body">
            
            <div class="dt-responsive table-responsive">
              <table id="dbtable-products" class="table table-striped table-bordered nowrap">
              </table>
            </div>

            <div class="text-end mt-3">
              <button class="btn btn-sm btn-light-primary" type="button" id="btnCheckAll-Maid" onclick="btnCheckAll()"> 
                <i class="fas fa-check-square mr-1"></i> Select All </span>
              </button> 
              <button class="btn btn-sm btn-light-danger" type="button" id="delete" onclick="btnDeleteAll()"> 
                <i class="fas fa-trash-alt mr-1"></i> Delete </span>
              </button>
            </div>

          </div>

        </div>
      </div>
      <!-- Column Selectors end -->

    </div>
    <!-- [ Main Content ] end -->
  </div>
</section>
<!-- [Page Specific JS] end -->

<div class="modal fade modal-animate" id="addProduct" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">📦 Add Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="form-group mb-3">
          <label class="form-label">Product Name</label>
          <input type="text" class="form-control" id="productName" placeholder="Enter product name">
        </div>

        <div class="form-group mb-3">
          <label class="form-label">Image</label>
          <div class="input-group">
            <input type="file" id="image" class="form-control" accept="image/*">
            <label class="input-group-text" for="image"><i class="fab fa-google-drive me-2"></i> Upload</label>
          </div>
          <small class="form-text text-muted">Recommended size : 1080 x 1080</small>
        </div>

        <div class="form-group mb-3">
          <label class="form-label">Product name in QR-Code</label>
          <input type="text" class="form-control" id="qrName" placeholder="Enter product name in QR-Code">
          <small class="form-text text-muted">Max 10 character</small>
        </div>

        <div class="form-group mb-3">
          <label class="form-label">SKU</label>
          <input type="text" class="form-control" id="sku" placeholder="Enter SKU">
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-outline-primary" onclick="addProduct()">Add Product</button>
      </div>

    </div>
  </div>
</div>


<script src="pages/product/product-javascript.js"></script>