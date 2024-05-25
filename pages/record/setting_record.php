<section class="pc-container">
  <div class="pc-content">
    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- Column Selectors table start -->
      <div class="col-sm-5">
        <div class="card">
          <div class="card-header">
          <i class="fab fa-google-drive me-2"></i> Connect Google Drive!!
          </div>

          <div class="card-body">

            <div id="connectGoogle"></div>

            <div class="input-group mt-3">
              <span class="input-group-text"> E-mail : </span>
              <input type="text" id="email" class="form-control" readonly>
            </div>

            <div class="input-group mt-3">
              <span class="input-group-text"> Name : </span>
              <input type="text" id="name" class="form-control" readonly>
            </div>

            <div class="input-group mt-3">
              <span class="input-group-text"><i class="fab fa-google-drive"></i></span>
              <input type="text" id="folder" class="form-control">
              <button type="submit" class="input-group-text btn btn-light" id="submitFolder" onclick="submitFolder()">OK</button>
            </div>
            <small class="form-text text-muted">https://drive.google.com/drive/folders/ <code><< Folders code>></code></small>

          </div>

          <div class="card-footer border-top"></div>

        </div>
      </div>
      <!-- Column Selectors end -->

      <!-- Column Selectors table start -->
      <div class="col-sm-7">
        <div class="card">
          <div class="card-header">
            <h5><i class="fas fa-folder me-2"></i>  Google Drive</h5>
          </div>
          
          <div class="card-body">
            <div id="fileGoogleDrive"></div>
          </div>

          <div class="card-footer border-top"></div>
        </div>
      </div>
      <!-- Column Selectors end -->

    </div><!-- [ Main Content ] end -->
  </div>
</section>
<!-- [Page Specific JS] end -->

<script src="pages/record/setting_record-javascript.js"></script>


