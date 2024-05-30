<section class="pc-container">
  <div class="pc-content">
    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- Column Selectors table start -->
      <div class="col-sm-12">
        <div class="card">

          <div class="card-header">
              <h5>Lazada order</h5>
          </div>

          <div class="card-header">
            <div class="row">

              <div class="col-md-6">
                <div class="input-group date">
                  <span class="input-group-text" id="iconDatepicker"><i class="fas fa-calendar-alt"></i></span>
                  <input type="text" class="form-control" placeholder="Select date" id="dateRangeSearch" style="background: none;"> 
                  <button type="submit" class="btn btn-dark" name="search" id="search" value="search" onclick="searchLazadaOrder();"><i class="fas fa-search"></i> Search</button>
                </div>
              </div>

              <div class="col-6 text-end">
                <button type="button" class="btn btn btn-light-success" onclick="showModalLazadaOrder()">
                  <i class="fas fa-file-excel me-1"></i> Upload File Lazada 
                </button>
              </div>

            </div>
          </div>

          <div class="card-body">
            
            <div class="dt-responsive table-responsive">
              <table id="dbtable-lazadaOrder" class="table table-striped table-bordered nowrap">
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

<script src="pages/order_lazada/order_lazada-javascript.js"></script>


