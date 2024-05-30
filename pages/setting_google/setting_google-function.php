<?php
require_once '../../functions/php.config.inc.php';
require_once '../../functions/php.functions.php';
require_once '../../vendor/autoload.php';

$db    = (new Database())->connect();
$addOn = new AddOn();
$auth  = new Authentication();

/////////////////////////////////////////////////////
///////////////// Record System List ////////////////
/////////////////////////////////////////////////////
if ($_POST['method'] == "authGoogle") {

  $sql = "SELECT * FROM `auth_google` WHERE `member_id` = :member_id";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':member_id' , $_SESSION['user_info']['member_id']  , PDO::PARAM_INT);
  $stmt->execute();

  $authGoogle = $addOn->useFetchAll($stmt);

  $email  = $authGoogle['email'] ?? '';
  $name   = $authGoogle['name'] ?? '';
  $folder = $authGoogle['folderCode'] ?? '';

  $check_auth_google = $auth->check_EmailGoogleAPI($_SESSION['user_info']['member_id']);
  if (empty($check_auth_google)) {
    $google = ' 
                <button type="button" class="w-100 btn mt-2 btn-light" onclick="googleAuthAPI(\''.API_GOOGLE_DRIVE_CALLBACK_URL.'\')">
                  <img class="me-2 pb-1" src="assets/images/authentication/google_drive.png" width="16" alt="img">
                  <span class="d-sm-inline-block">Connect Google Drive!!</span>
                </button>
              ';
  } else {
    $google = ' 
                <button type="button" class="w-100 btn mt-2 btn-light text-danger" onclick="outGoogleAuthAPI()">
                  <img class="me-2 pb-1" src="assets/images/authentication/google_drive.png" width="16" alt="img">
                  <span class="d-sm-inline-block">Disconnect Google Drive!!</span>
                </button>
              ';
  }

  $result = [
    'email'  => $email ,
    'name'   => $name,
    'folder' => $folder,
    'google' => $google ,
  ];

  echo json_encode($result);
}

if ($_POST['method'] == "submitFolder") {

  $sql = "UPDATE `auth_google` SET `folderCode` = :folderCode WHERE `member_id` = :member_id";

  $folder    = $_POST['folder'];

  $stmt = $db->prepare($sql);
  $stmt->bindValue(':folderCode' , $folder    , PDO::PARAM_STR);
  $stmt->bindValue(':member_id'  , $_SESSION['user_info']['member_id'] , PDO::PARAM_INT);

  $stmt->execute();

  $authGoogle = $addOn->useFetchAll($stmt);

  if(!$authGoogle){
    $result = [
      'status' => "success",
      'folder' => $folder,
    ];
  }else{
    $result = [
      'status' => "false",
    ];
  }
  
  echo json_encode($result);
}


if ($_POST['method'] == "outGoogleAuthAPI") {

  try {
      $sql = "DELETE FROM auth_google WHERE member_id = :member_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':member_id', $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
      $stmt->execute();

      $status = ['status' => "success"];

  } catch (Exception $e) {
      $status = ['status' => "false", 'message' => $e->getMessage()];
  }

  echo json_encode($status);
  
}


if ($_POST['method'] == "fileGoogleDrive") {
  
  $client = new Google\Client();
  $client->setClientId(API_GOOGLE_CLIENT_ID);
  $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
  $client->setRedirectUri(API_GOOGLE_DRIVE_CALLBACK_URL);
  $client->addScope("https://www.googleapis.com/auth/drive");
  $service = new Google\Service\Drive($client);

  // Retrieve token from the database
  $sql = "SELECT * FROM `auth_google` WHERE `member_id` = :member_id";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
  $stmt->execute();
  $result = $addOn->useFetchAll($stmt);

  if (!$result) {
      $status = [
        'status'  => "false",
        'message' => '<i class="fab fa-google-drive me-2"></i> You haven\'t connected to Google Drive yet. Please connect to proceed.',
      ];
      echo json_encode($status);
      exit;
  }

  $id_token_token_drive = [
    "access_token"  => $result['access_token'],
    "expires_in"    => $result['expires_in'],
    "refresh_token" => $result['refresh_token'],
    "scope"         => $result['scope'],
    "token_type"    => $result['token_type'],
    "id_token"      => $result['id_token'],
    "created"       => $result['created'],
  ];

  $client->setAccessToken($id_token_token_drive);

  if ($client->isAccessTokenExpired()) {
      if ($client->getRefreshToken()) {
          $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
          $id_token_token_drive = $client->getAccessToken();
      } else {
          $status = [
            'status'  => "false",
            'message' => '<i class="fab fa-google-drive me-2"></i> Can\'t connect Google Drive.',
          ];
          echo json_encode($status);
          exit;
      }
  }

  if ($client->getAccessToken()) {
    $_SESSION['id_token_token_drive'] = $client->getAccessToken();
    $_SESSION['code_verifier_drive']  = $client->getOAuth2Service()->generateCodeVerifier();
  }

  try {

    if ($result['folderCode'] == null) {
      $optParams = array(
        'q' => "mimeType='video/webm'"
      );
      $url = 'https://drive.google.com/drive/my-drive';
    } else {
      $optParams = array(
        'q' => "mimeType='video/webm' and '" . $result['folderCode'] . "' in parents"
      );
      $url = 'https://drive.google.com/drive/folders/'.$result['folderCode'];
    }

    $files = $service->files->listFiles($optParams)->getFiles();

    $cardBody = '<p><small class="form-text text-muted"> Google Drive [*.webm] : <a class="pc-link" href="'.$url.'" target="_blank">'.$url.'</a></small></p>';

    if ($files == null) {

      $cardBody .=  '<div class="alert alert-dark text-center" role="alert">No data available in google drive [*.webm]</div>';

      $status = [
        'status'  => "success",
        'message' => $cardBody,
      ];

    } else {

      $cardBody .= '<div class="row i-main">';
      foreach ($files as $file) {
        $cardBody .=  ' 
                        <div class="col-2">
                          <div class="i-block">
                            <i class="fas fa-film"></i>
                          </div>
                          <small class="form-text text-center text-muted">'.$file->getName().'</small>
                        </div>
                      ';
      }
      $cardBody .= '</div>';

      $status = [
        'status'  => "success",
        'message' => $cardBody,
      ];

    }

  } catch (Exception $e) {
    $status = [
      'status'  => "false",
      'message' => $e->getMessage(),
    ];
  }

  echo json_encode($status);

}



?>
