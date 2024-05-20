<section class="pc-container">
  <div class="pc-content">
    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- Column Selectors table start -->
      <div class="col-sm-5">
        <div class="card">
          <div class="card-header">
            <i class="ph-duotone ph-webcam fs-3 me-2"></i> เปิดกล้อง Webcam
          </div>

          <div class="card-body">
            <?php 
            /* unset($_SESSION['id_token_token_drive']);
            unset($_SESSION['code_verifier_drive']); */

            $check_auth_google = $auth->check_EmailGoogleAPI($_SESSION['user_info']['member_id']);
            if (empty($check_auth_google)) : ?>

              <div id="container"> 
                  <div class="alert alert-danger" role="alert">
                    <a class='login' href='#' onclick="googleAuthAPI('<?=API_GOOGLE_DRIVE_CALLBACK_URL?>')"><i class="fab fa-google-drive"></i> Connect Google Drive!!</a>
                  </div>
              </div>
            <?php else : ?>

            <button id="record-btn" onclick="toggleRecording()" type="button" class="btn btn-sm btn-light-primary" tabindex="0" aria-controls="cbtn-selectors"><span id="record-text"><i class="bi bi-play-fill"></i> Record</span></button>
            <div id="container">

              <video id="webcam" autoplay></video>

              <div id="recording-loader">
                <div class="progress mb-4" id="progress-bar-container">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-'+bgcolor+'" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span id="process-number"></span>
                  </div>
                </div>
              </div>

              <div id="recording-error">
                <div class="alert alert-secondary" role="alert"><i class="bi bi-camera-video-off"></i> ไม่สามารถเปิดกล้องได้</div>
              </div>

              <div id="recording-indicator">
                <span class="badge text-bg-danger rounded-pill" id="recording-badge">
                  <i class="bi bi-record-circle"></i> Recording
                  <span id="timer">00:00:00</span>
                </span>
                <span class="badge bg-light-subtle text-light-emphasis rounded-pill"><i class="bi bi-mic-mute"></i></span>
              </div>
            </div>

            <div class="input-group">
              <span class="input-group-text"><i class="fi fi-ts-scanner-gun"></i></span>
              <input type="text" id="orderid" name="orderid" oninput="checkOrderID()" class="form-control">
              <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
            </div>

            <?php endif ?>

          </div>
        </div>
      </div>
      <!-- Column Selectors end -->

      <!-- Column Selectors table start -->
      <div class="col-sm-7">
        <div class="card">
          <div class="card-header">
              <h5>Record List</h5>
          </div>
          <div class="card-header">
            <div class="row row-cols-md-auto lign-items-center">

              <div class="col-md-6">
                <div class="input-group date">
                  <input type="text" class="form-control" placeholder="Select date" id="dateSearch" value="<?=date('Y-m-d')?>">
                  <span class="input-group-text" id="iconDatepicker"><i class="fas fa-calendar-alt"></i></span>
                </div>
              </div>

              <div class="col-md-6">
                <button type="submit" class="btn btn-primary" name="search" id="search" value="search" onclick="record_system();">
                  <i class="fas fa-search"></i> Search</button>
              </div>

            </div>
          </div>
          <div class="card-body">
            <div class="dt-responsive table-responsive">

              <table id="dbtable-record" class="table table-striped table-bordered nowrap">
              </table>

            </div>
          </div>
        </div>
      </div>
      <!-- Column Selectors end -->

    </div><!-- [ Main Content ] end -->
  </div>
</section>
<!-- [Page Specific JS] end -->

<script src="pages/record/record-javascript.js"></script>

<?php 
if (empty($check_auth_google)) {
  echo '<script>window.onload = stopWebcam;</script>';
}
?>


