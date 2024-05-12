<section class="pc-container">
  <div class="pc-content">
    <!-- [ Main Content ] start -->
    <div class="row">
      <!-- Column Selectors table start -->
      <div class="col-sm-5">
        <div class="card">
          <div class="card-header">
              <h5><i class="bi bi-camera-reels"></i> เปิดกล้อง Webcam </h5>
          </div>

          <?php
            $client = new Google\Client();
            $client->setClientId(API_GOOGLE_CLIENT_ID);
            $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
            $client->setRedirectUri(REDIRECT_URL);
            $client->addScope("https://www.googleapis.com/auth/drive");
            $service = new Google\Service\Drive($client);

            if (isset($_GET['code'])) {
                $token = $client->fetchAccessTokenWithAuthCode($_GET['code'], $_SESSION['code_verifier']);
                $client->setAccessToken($token);

                $_SESSION['id_token_token'] = $token;
                header('Location: ' . filter_var(REDIRECT_URL, FILTER_SANITIZE_URL));
            }
            // set the access token as part of the client
            if (!empty($_SESSION['id_token_token'])) {
                $client->setAccessToken($_SESSION['id_token_token']);
                if ($client->isAccessTokenExpired()) {
                    unset($_SESSION['id_token_token']);
                }
            } else {
                $_SESSION['code_verifier'] = $client->getOAuth2Service()->generateCodeVerifier();
                $authUrl = $client->createAuthUrl();
            }
           ?>

          <div class="card-body">
            <button id="record-btn" onclick="toggleRecording()" type="button" class="btn btn-sm btn-light-primary" tabindex="0" aria-controls="cbtn-selectors"><span id="record-text"><i class="bi bi-play-fill"></i> Record</span></button>
            <!-- <button id="save-btn" onclick="saveVideo()" type="button" class="btn btn-sm btn-light-secondary" name="bt-select-all" id="bt-select-all"><i class="icon feather icon-check-square"></i> Save Video</button> -->

            <?php if (isset($authUrl)) : ?>

              <script>window.onload = stopWebcam;</script>

              <div id="container"> 
                  <div class="alert alert-danger" role="alert">
                    <a class='login' href='#' onclick="googleAuthAPI('<?=$authUrl?>')"><i class="fab fa-google-drive"></i> Connect Google Drive!!</a>
                  </div>
              </div>
            <?php else : ?>
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
            <?php endif ?>

            <div class="input-group">
              <span class="input-group-text"><i class="fi fi-ts-scanner-gun"></i></span>
              <input type="text" id="orderid" name="orderid" class="form-control">
              <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
            </div>

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
                <input class="form-control" id="dateSearch" type="date" value="<?=date('Y-m-d')?>">
              </div>

              <div class="col-md-2">
                <button type="submit" class="btn btn-primary" name="search" id="search" value="search" onclick="record_system();">
                  <i class="fas fa-search"></i> Search</button>
              </div>

            </div>
          </div>
          <div class="card-body">
            <div class="dt-responsive table-responsive">

              <table id="dbtable-record" class="table table-striped table-bordered nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>VDO</th>
                      <th>ORDER ID</th>
                      <th>DATE</th>
                      <th>TIME</th>
                      <th>USER</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
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

<script src="pages/record/ajax-record.js"></script>
