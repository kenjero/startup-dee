<section class="pc-container">
  <div class="pc-content">
    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- Column Selectors table start -->
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
          
            <div class="row">
              <div class="col-6 text-start align-middle">
                <h5>Products List</h5>
              </div>

              <div class="col-6 text-end">
                <button type="button" class="btn btn-sm btn-light-success" onclick="showModalAddProduct()">
                  <i class="fas fa-plus fs-6 me-1"></i> Add Product <i class="fas fa-box fs-6 ms-1"></i>
                </button>
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


<script src="pages/product/product-javascript.js"></script>