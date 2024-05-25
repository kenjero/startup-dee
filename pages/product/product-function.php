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
if ($_POST['method'] == "table_product") {

  
  $textSearch = $_POST['textSearch'];
  $typeSearch = $_POST['typeSearch'];
  
  if ($typeSearch == 'productName') {
    $sql = "SELECT * FROM `product_system` WHERE `product_name` = :textSearch AND `member_id` = :member_id ORDER BY id DESC";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':product_name' , $textSearch , PDO::PARAM_STR);
  }
  else if ($typeSearch == 'productCode') {
    $sql = "SELECT * FROM `product_system` WHERE `product_code` = :textSearch AND `member_id` = :member_id ORDER BY id DESC";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':product_code' , $textSearch , PDO::PARAM_STR);
  }
  else {
    $sql = "SELECT * FROM `product_system` WHERE `member_id` = :member_id ORDER BY id DESC";
    $stmt = $db->prepare($sql);
  }
 
  $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
  $stmt->execute();
  $productSystem = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $thead = '<thead>
              <tr>
                <th><input class="form-check-input input-light-primary" id="checkAll" type="checkbox" onclick="checkAll()"></th>
                <th>ID</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>SKU</th>
                <th>Action</th>
              </tr>
            </thead>
            ';


  $tbody = '<tbody>';
  $count = count($productSystem);
  foreach ($productSystem as $result) {
    $tbody .= '<tr id="tr'.$result['id'].'">
                  <td><input type="checkbox" class="form-check-input input-light-primary" name="checkItem[]"  value="'.$result['id'].'"></td>
                  <td>'.$count--.'</td>
                  <td><img src="https://drive.google.com/thumbnail?id=' . $result['product_image'] . '&sz=w40"></td>
                  <td>'.$result['product_name'].'</td>
                  <td>'.$result['product_sku'].'</td>
                  <td>
                    <a class="mr-3" href="#" onclick="copyProduct('.$result['id'].')"><i class="icon feather f-16 icon-copy text-warning"></i></a>
                    <a class="mr-3" href="product_edit?productID='.$result['id'].'"><i class="icon feather icon-edit f-16 text-success"></i></a>
                    <a class="mr-3" href="#" onclick="deleteProduct('.$result['id'].')"><i class="feather icon-trash-2 f-16 text-danger"></i></a>
                  </td>
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


if ($_POST['method'] == "addProduct") {

    $imageName = $_FILES['image']['name'];
    $imageTempName = $_FILES['image']['tmp_name'];
    $imageData = file_get_contents($imageTempName);

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
            'status' => "false",
            'message' => '<i class="fab fa-google-drive me-2"></i> You haven\'t connected to Google Drive yet. Please connect to proceed.',
        ];
        echo json_encode($status);
        exit;
    }

    $id_token_token_drive = [
        "access_token" => $result['access_token'],
        "expires_in" => $result['expires_in'],
        "refresh_token" => $result['refresh_token'],
        "scope" => $result['scope'],
        "token_type" => $result['token_type'],
        "id_token" => $result['id_token'],
        "created" => $result['created'],
    ];

    $client->setAccessToken($id_token_token_drive);

    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $id_token_token_drive = $client->getAccessToken();
        } else {
            $status = [
                'status' => "false",
                'message' => '<i class="fab fa-google-drive me-2"></i> Can\'t connect Google Drive.',
            ];
            echo json_encode($status);
            exit;
        }
    }

    $fileMetadata = new Google\Service\Drive\DriveFile([
        'name' => $imageName,
        'parents' => isset($result['folderCode']) ? [$result['folderCode']] : [],
    ]);

    $file = $service->files->create(
        $fileMetadata,
        array(
            'data' => $imageData,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart',
        )
    );

    $permission = new Google\Service\Drive\Permission([
      'type' => 'anyone',
      'value' => 'anyone',
      'role' => 'reader',
    ]);

    $service->permissions->create($file->id, $permission);

    $product_code = $_SESSION['user_info']['member_id'] . "_" . date('YmdHis') . "_" . bin2hex(random_bytes(5));

    $postData = array(
        'product_image'    => $file->id,
        'product_code'     => $product_code,
        'product_name'     => htmlspecialchars($_POST['productName']),
        'product_name_qr'  => htmlspecialchars($_POST['qrName']),
        'product_sku'      => htmlspecialchars($_POST['sku']),
        'product_create'   => date('Y-m-d H:i:s'),
        'product_lastEdit' => date('Y-m-d H:i:s'),
        'member_id'        => $_SESSION['user_info']['member_id'],
    );

    try {
        $keysString = implode(",", array_keys($postData));
        $valuesBindParam = ":" . implode(",:", array_keys($postData));

        $sql = "INSERT INTO product_system ($keysString) VALUES ($valuesBindParam)";
        $stmt = $db->prepare($sql);

        foreach ($postData as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();

        $status = [
            'status' => "success",
        ];
    } catch (Exception $e) {
        $status = [
            'status' => "false",
            'message' => $e->getMessage(),
        ];
    }

    echo json_encode($status);
}


?>
