<?php
ob_start();
session_start();

require_once '../../../functions_emicon/config.inc.php';
require_once '../../../functions_emicon/functions.php';
require_once '../../../functions_emicon/vendor/autoload.php';

date_default_timezone_set('Asia/Bangkok');

// สร้างอ็อบเจ็กต์ Database
$db = (new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST))->connect();

/////////////////////////////////////////////////////
////////////////// Funtions Record //////////////////
/////////////////////////////////////////////////////
function record_system_list($dateSearch){

    global $db;
    $date = isset($dateSearch) ? $dateSearch : date("Y-m-d");

    $sql = "SELECT rs.*, ua.username
          FROM record_system rs
          JOIN user_account ua ON rs.user_id = ua.id
          WHERE rs.record_date = '$date'
          ORDER BY rs.id DESC";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $RecordSystem = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $RecordSystem;
}

/////////////////////////////////////////////////////
///////////////// Record System List ////////////////
/////////////////////////////////////////////////////
if (isset($_POST['record_system_list'])) {

  $RecordSystem = record_system_list($_POST['dateSearch']);

  // $date = isset($_POST['dateSearch']) ? $_POST['dateSearch'] : date("Y-m-d");
  //
  // $sql = "SELECT rs.*, ua.username
  //       FROM record_system rs
  //       JOIN user_account ua ON rs.user_id = ua.id
  //       WHERE rs.record_date = '$date'
  //       ORDER BY rs.id DESC";
  //
  // $stmt = $db->prepare($sql);
  // $stmt->execute();
  //
  // $RecordSystem = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo '<table id="dbtable-record" class="table table-striped table-bordered nowrap">
      <thead>
        <tr>
          <th>ID</th>
          <th><i class="bi bi-film"></i></th>
          <th>ORDER ID</th>
          <th>DATE</th>
          <th>TIME</th>
          <th>USER</th>
        </tr>
      </thead>
      <tbody>';
      foreach ($RecordSystem as $result) {
          echo'
            <tr>
              <td>'.$result['id'].'</td>
              <td><a target="_blank" href="https://drive.google.com/open?id='.$result['record_name'].'"><i class="bi bi-cloud-arrow-down"></i></a></td>
              <td>'.$result['order_id'].'</td>
              <td>'.$result['record_date'].'</td>
              <td>'.$result['record_time'].'</td>
              <td>'.$result['username'].'</td>
            </tr>
          ';
      }
  echo '</tbody>'.
      '</table>';
}

/////////////////////////////////////////////////////
////////////// Upload Vdo Google Drive //////////////
/////////////////////////////////////////////////////

if (isset($_POST['uploadVdoGoogleDrive'])) {

    $recordName = file_get_contents($_FILES['record_name']['tmp_name']);

    $client = new Google\Client();
    $client->setClientId(API_GOOGLE_CLIENT_ID);
    $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URL);
    $client->addScope("https://www.googleapis.com/auth/drive");
    $service = new Google\Service\Drive($client);

    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code'], $_SESSION['code_verifier']);
        $client->setAccessToken($token);

        $_SESSION['id_token_token'] = $token;

        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
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

    // ตรวจสอบการส่งข้อมูล access token ถูกต้อง
    if ($client->getAccessToken()) {

        $name = date('YmdHis') . "_" . generateRandomString(5);
        $recordName = FRONT_END_DOCUMENT_ROOT . "/temp_download/" . $name . ".webm";
        move_uploaded_file($_FILES["record_name"]["tmp_name"], $recordName);

        $file_data = file_get_contents($recordName);
        $folderId = API_GOOGLE_DRIVE_FOLDER_ID;

        $fileMetadata = new Google\Service\Drive\DriveFile([
                            'name'    => $name.'.webm',
                            'parents' => [$folderId],
                        ]);

        $file = $service->files->create(
          $fileMetadata,
            array(
              'data'        => $file_data,
              'mimeType'    => 'video/webm',
              'uploadType'  => 'media'
            )
        );
      }

      if ($file) {
        unlink($recordName);

        $postData = array(
          'record_name' => $file->id,
          'order_id'    => htmlspecialchars($_POST['order_id']),
          'record_date' => date('Y-m-d'),
          'record_time' => date('H:i:s'),
          'user_id'     => $_SESSION['user_id'],
        );

        $keysString = implode(",", array_keys($postData));
        $valuesBindParam = ":" . implode(",:", array_keys($postData));

        $sql = "INSERT INTO record_system ($keysString) VALUES ($valuesBindParam)";
        $result = $db->prepare($sql);

        foreach ($postData as $key => $value) {
          $result->bindParam(":$key", $postData[$key]);
        }

        $result->execute();

        $status  = 'success';
      }else{
        $status  = 'false';
      }

      echo $status;
}
?>
