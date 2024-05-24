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
if ($_POST['method'] == "record_system_list") {

  $dateSearch = $_POST['dateSearch'];

  $sql = "SELECT * FROM `record_system`
          WHERE `record_date` = :date AND `member_id` = :member_id
          ORDER BY id DESC";

  $member_id = $_SESSION['user_info']['member_id'];

  $stmt = $db->prepare($sql);
  $stmt->bindValue(':date'      , $dateSearch , PDO::PARAM_STR);
  $stmt->bindValue(':member_id' , $member_id  , PDO::PARAM_STR);
  $stmt->execute();

  $RecordSystem = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $thead = '<thead>
              <tr>
                <th>ID</th>
                <th><i class="bi bi-film"></i></th>
                <th>ORDER ID</th>
                <th>DATE</th>
                <th>TIME</th>
                <th><i class="fas fa-trash-alt"></i></th>
              </tr>
            </thead>';
  
  $tbody = '<tbody>';
  $count = count($RecordSystem);
  foreach ($RecordSystem as $result) {
  $tbody .= '<tr>
                <td>'.$count--.'</td>
                <td><a target="_blank" href="https://drive.google.com/open?id='.$result['record_name'].'"><i class="fab fa-google-drive"></i></a></td>
                <td>'.$result['order_id'].'</td>
                <td>'.$result['record_date'].'</td>
                <td>'.$result['record_time'].'</td>
                <td><a href="#" class="link-danger" title="Delete" id="delete" onclick="deleteRecord('.$result['id'].')"><i class="ti ti-trash f-16"></i></a></td>
              </tr>';
  }
  $tbody .= '</tbody>';

  $tload = $addOn->trLoading(6);

  $result = [
    'thead' => $thead,
    'tbody' => $tbody, 
    'tload' => $tload,
  ];

  echo json_encode($result);
}

/////////////////////////////////////////////////////
////////////// Upload Vdo Google Drive //////////////
/////////////////////////////////////////////////////

if ($_POST['method'] == "uploadVdoGoogleDrive") {

    $recordName = file_get_contents($_FILES['record_name']['tmp_name']);

    $client = new Google\Client();
    $client->setClientId(API_GOOGLE_CLIENT_ID);
    $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(API_GOOGLE_DRIVE_CALLBACK_URL);
    $client->addScope("https://www.googleapis.com/auth/drive");
    $service = new Google\Service\Drive($client);

    // Retrieve token from the database
    $memberId = $_SESSION['user_info']['member_id'];
    $sql = "SELECT * FROM `auth_google` WHERE `member_id` = :member_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":member_id", $memberId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $status = ['status' => "false",];
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
            $status = ['status' => "false",];
            exit;
        }
    }

    // ตรวจสอบการส่งข้อมูล access token ถูกต้อง
    if ($client->getAccessToken()) {

        $name = date('YmdHis') . "_" . $addOn->generateRandomToken(5);

        /*
        $recordName = FRONT_END_DOCUMENT_ROOT . "/temp_download/" . $name . ".webm";
        move_uploaded_file($_FILES["record_name"]["tmp_name"], $recordName);

        $file_data = file_get_contents($recordName);
        */

        $folderId = API_GOOGLE_DRIVE_FOLDER_ID;

        $fileMetadata = new Google\Service\Drive\DriveFile([
            'name'    => $name.'.webm',
            'parents' => [$folderId],
        ]);

        $file = $service->files->create(
          $fileMetadata,
            array(
              'data'        => file_get_contents($_FILES["record_name"]["tmp_name"]),
              'mimeType'    => 'video/webm',
              'uploadType'  => 'media'
            )
        );

        $postData = array(
          'record_name' => $file->id,
          'order_id'    => htmlspecialchars($_POST['order_id']),
          'record_date' => date('Y-m-d'),
          'record_time' => date('H:i:s'),
          'member_id'   => $_SESSION['user_info']['member_id'],
        );

        $keysString = implode(",", array_keys($postData));
        $valuesBindParam = ":" . implode(",:", array_keys($postData));

        $sql = "INSERT INTO record_system ($keysString) VALUES ($valuesBindParam)";
        $result = $db->prepare($sql);

        foreach ($postData as $key => $value) {
          $result->bindParam(":$key", $postData[$key]);
        }

        $result->execute();

        /* unlink($recordName); */

        $status = ['status' => "success",];
      }else{
        $status = ['status' => "false",];
      }

      echo json_encode($status);
}



if ($_POST['method'] == "deleteRecord") {

  $id = $_POST['id'];

  $client = new Google\Client();
  $client->setClientId(API_GOOGLE_CLIENT_ID);
  $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
  $client->setRedirectUri(API_GOOGLE_DRIVE_CALLBACK_URL);
  $client->addScope("https://www.googleapis.com/auth/drive");

  // Retrieve token from the database
  $memberId = $_SESSION['user_info']['member_id'];
  $sql = "SELECT * FROM `auth_google` WHERE `member_id` = :member_id";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(":member_id", $memberId, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$result) {
      $status = ['status' => "false",];
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
          $status = ['status' => "false",];
          exit;
      }
  }

  $service = new Google\Service\Drive($client);

  $sql = "SELECT * FROM `record_system` WHERE `id` = :id";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(":id", $id, PDO::PARAM_INT);
  $stmt->execute();
  $stmtRecord = $stmt->fetch(PDO::FETCH_ASSOC);

  try {
      $service->files->delete($stmtRecord['record_name']);

      $sql = "DELETE FROM record_system WHERE `id` = :id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":id", $id, PDO::PARAM_INT);
      $stmt->execute();
      $status = ['status' => "success",];
  } catch (Exception $e) {
      $status = ['status' => "error",];
  }

  echo json_encode($status);
}
?>
