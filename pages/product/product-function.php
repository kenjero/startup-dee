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

  $sql = "SELECT * FROM `product_system` WHERE `member_id` = :member_id ORDER BY id DESC";
  $stmt = $db->prepare($sql);
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
    $tbody .= '<tr id="tr'.$result['product_code'].'">
                  <td><input type="checkbox" class="form-check-input input-light-primary" name="checkItem[]" id="checkItem" value="'.$result['product_code'].'"></td>
                  <td>'.$count--.'</td>
                  <td>
                    <a href="https://drive.google.com/thumbnail?id='.$result['product_image'].'" target="_blank">
                      <img src="https://drive.google.com/thumbnail?id='.$result['product_image'].'&sz=w50" class="me-3">
                    </a>
                  </td>
                  <td>'.$result['product_name'].'</td>
                  <td>'.$result['product_sku'].'</td>
                  <td>
                    <a href="#" class="avtar avtar-xs btn-link-success btn-pc-default" onclick="showModalEditProduct(\''.$result['product_code'].'\')"><i class="ti ti-edit-circle f-18"></i></a>
                    <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default" onclick="deleteProduct(\''.$result['product_code'].'\')"><i class="ti ti-trash f-18"></i></a>
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

    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName    = $_FILES['image']['name'];
    $fileSize    = $_FILES['image']['size'];
    $fileType    = $_FILES['image']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $allowedMimeTypes = array('image/jpeg', 'image/png', 'image/gif');

    $nameRandom = "image_product_" . date('YmdHis') . "_" . $addOn->generateRandomToken(5);

    $errormsg = [];
    $regexQrName = $addOn->regexTextbox(1, 10);
    $regexSku    = $addOn->regexTextbox(1, 3);

    ///////////// Error ProductName /////////////
    if ($_POST['productName'] == "") {
      $errormsg['errorProductName'] = '<i class="fa fa-times-circle"></i> Product name can\'t be blank.';
    }

    ///////////// Error Image /////////////
    if($imageTempName == ""){
      $errormsg['errorImage'] = '<i class="fa fa-times-circle"></i> Image can\'t be blank.';
    }
    else if (!in_array($fileType, $allowedMimeTypes) || !in_array($fileExtension, $allowedfileExtensions)) {
      $errormsg['errorImage'] = '<i class="fa fa-times-circle"></i> Image File is not valid.';
    }
    
    ///////////// Error QR-Name /////////////
    if ($_POST['qrName'] == "") {
      $errormsg['errorQrName'] = '<i class="fa fa-times-circle"></i> QR-Code Product can\'t be blank.';
    }
    else if (!preg_match($regexQrName, $_POST['qrName'])) {
      $errormsg['errorQrName'] = '<i class="fa fa-times-circle"></i> QR-Code Product input is invalid.';
    }

    ///////////// Error QR-Name /////////////
    if ($_POST['sku'] == "") {
      $errormsg['errorSku'] = '<i class="fa fa-times-circle"></i> SKU can\'t be blank.';
    }
    else if (!preg_match($regexSku, $_POST['sku'])) {
      $errormsg['errorSku'] = '<i class="fa fa-times-circle"></i> SKU input is invalid.';
    }

    $sql = "SELECT * FROM `product_system` WHERE `product_sku` = :product_sku AND `member_id` = :member_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":product_sku", $_POST['sku'], PDO::PARAM_STR);
    $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
    $stmt->execute();
    $stmtSku = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmtSku !== false) {
        $errormsg['errorSku'] = '<i class="fa fa-times-circle"></i> SKU is duplicate. '. $stmtSku['product_sku'];
    }

    if ($errormsg != null) {
      $status = [
        'status'   => "false",
      ];

      $response = array_merge($errormsg, $status);
      echo json_encode($response);
      exit;
    }
    
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
                'status' => "false",
                'message' => '<i class="fab fa-google-drive me-2"></i> Can\'t connect Google Drive.',
            ];
            echo json_encode($status);
            exit;
        }
    }

    $fileMetadata = new Google\Service\Drive\DriveFile([
      'name'    => $nameRandom,
      'parents' => $result['folderCode'] !== "" ? [$result['folderCode']] : [],
    ]);


    $file = $service->files->create(
        $fileMetadata,
        array(
            'data'       => $imageData,
            'mimeType'   => 'image/jpeg',
            'uploadType' => 'multipart',
        )
    );

    $permission = new Google\Service\Drive\Permission([
      'type'  => 'anyone',
      'value' => 'anyone',
      'role'  => 'reader',
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
 

if ($_POST['method'] == "editProduct") {

  if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $imageName     = $_FILES['image']['name'];
    $imageTempName = $_FILES['image']['tmp_name'];

    $fileTmpPath   = $_FILES['image']['tmp_name'];
    $fileName      = $_FILES['image']['name'];
    $fileSize      = $_FILES['image']['size'];
    $fileType      = $_FILES['image']['type'];
    $fileNameCmps  = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $allowedMimeTypes = array('image/jpeg', 'image/png', 'image/gif');

    $imageData = file_get_contents($imageTempName);

    $nameRandom = "image_product_" . date('YmdHis') . "_" . $addOn->generateRandomToken(5);

     ///////////// Error Image /////////////
    if ($imageTempName != "" && !in_array($fileType, $allowedMimeTypes) || !in_array($fileExtension, $allowedfileExtensions)) {
      $errormsg['errorImage'] = '<i class="fa fa-times-circle"></i> Image File is not valid.';
    }
    
  }
  $errormsg = [];
  $postData = array();
  $regexQrName = $addOn->regexTextbox(1, 10);
  $regexSku    = $addOn->regexTextbox(1, 3);

  $product_code = $_POST['id'];

  if ($product_code  == "") {
    $errormsg['errorProductName'] = '<i class="fa fa-times-circle"></i> There\'s something wrong..';
  }

  ///////////// Error ProductName /////////////
  if ($_POST['productName'] == "") {
    $errormsg['errorProductName'] = '<i class="fa fa-times-circle"></i> Product name can\'t be blank.';
  }

  ///////////// Error QR-Name /////////////
  if ($_POST['qrName'] == "") {
    $errormsg['errorQrName'] = '<i class="fa fa-times-circle"></i> QR-Code Product can\'t be blank.';
  }
  else if (!preg_match($regexQrName, $_POST['qrName'])) {
    $errormsg['errorQrName'] = '<i class="fa fa-times-circle"></i> QR-Code Product input is invalid.';
  }

  ///////////// Error QR-Name /////////////
  if ($_POST['sku'] == "") {
    $errormsg['errorSku'] = '<i class="fa fa-times-circle"></i> SKU can\'t be blank.';
  }
  else if (!preg_match($regexSku, $_POST['sku'])) {
    $errormsg['errorSku'] = '<i class="fa fa-times-circle"></i> SKU input is invalid.';
  }

  $sql = "SELECT * FROM `product_system` WHERE `product_code` != :product_code AND `product_sku` = :product_sku AND `member_id` = :member_id";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(":product_code", $product_code , PDO::PARAM_STR);
  $stmt->bindValue(":product_sku", $_POST['sku'], PDO::PARAM_STR);
  $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
  $stmt->execute();
  $stmtSku = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($stmtSku !== false) {
      $status = [
        'status' => "false",
      ];
      $errormsg['errorSku'] = '<i class="fa fa-times-circle"></i> SKU is duplicate. '. $stmtSku['product_sku'];
  }

  if ($errormsg != null) {
    $response = array_merge($errormsg, $status);
    
    echo json_encode($response);
    exit;
  }

  if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
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

    $service = new Google\Service\Drive($client);

    $sql = "SELECT * FROM `product_system` WHERE `product_code` = :product_code AND `member_id` = :member_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":product_code", $product_code, PDO::PARAM_STR);
    $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
    $stmt->execute();
    $stmtProduct = $addOn->useFetchAll($stmt);;

    try {
      $service->files->delete($stmtProduct['product_image']);
      $status['delete'] = "success";
    } catch (Google\Service\Exception $e) {
      $status['delete'] = "error";
    }

    $fileMetadata = new Google\Service\Drive\DriveFile([
      'name'    => $nameRandom,
      'parents' => $result['folderCode'] !== "" ? [$result['folderCode']] : [],
    ]);


    $file = $service->files->create(
        $fileMetadata,
        array(
            'data'       => $imageData,
            'mimeType'   => 'image/jpeg',
            'uploadType' => 'multipart',
        )
    );

    $permission = new Google\Service\Drive\Permission([
      'type'  => 'anyone',
      'value' => 'anyone',
      'role'  => 'reader',
    ]);

    $service->permissions->create($file->id, $permission);

    $postData['product_image'] = $file->id;
  }

  $postData['product_name']     = htmlspecialchars($_POST['productName']);
  $postData['product_name_qr']  = htmlspecialchars($_POST['qrName']);
  $postData['product_sku']      = htmlspecialchars($_POST['sku']);
  $postData['product_lastEdit'] = date('Y-m-d H:i:s');

  try {
    
      $keysString = implode(", ", array_map(function($key) {
        return "$key = :$key";
      }, array_keys($postData)));
    
      $sql = "UPDATE product_system SET $keysString WHERE `product_code` = :product_code AND `member_id` = :member_id";
      $stmt = $db->prepare($sql);
    
      foreach ($postData as $key => $value) {
        $stmt->bindValue(":$key", $value);
      }
      $stmt->bindValue(":product_code", $product_code, PDO::PARAM_STR);
      $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
    
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


if ($_POST['method'] == "showModalAddProduct") {

  $modal = '
            <div class="modal fade modal-animate" id="addProduct" data-bs-backdrop="static" tabindex="-1" aria-hidden="true"> 
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">📦 Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalAdd()"></button>
                  </div>
                
                  <div class="modal-body">
            ';

  $check_auth_google = $auth->check_EmailGoogleAPI($_SESSION['user_info']['member_id']);
  if (empty($check_auth_google)) {
    $modal .= ' 
                    <button type="button" class="w-100 btn mt-2 btn-light text-dark" onclick="window.location.href=\'setting_google\'">
                      <img class="me-2 pb-1" src="assets/images/authentication/google_drive.png" width="16" alt="img">
                      <span class="d-sm-inline-block">Setting Google Drive!!</span>
                    </button>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" id="close">Close</button>
                    </div>
              ';
  } else {
    $modal .= '     <div class="form-group mb-3">
                      <label class="form-label">Product Name</label>
                      <input type="text" class="form-control" id="productName" placeholder="Enter product name">
                      <small class="form-text" id="errorProductName" style="display: none;"></small>
                    </div>

                    <div class="form-group mb-3">
                      <label class="form-label">Image</label>
                      <div class="input-group">
                        <input type="file" id="image" class="form-control" accept="image/*">
                        <label class="input-group-text" for="image"><i class="fab fa-google-drive me-2"></i> Upload</label>
                      </div>
                      <small class="form-text" id="errorImage" style="display: none;"></small>
                    </div>

                    <div class="form-group mb-3">
                      <label class="form-label">Product name in QR-Code</label>
                      <small class="form-text text-muted">[ Max 10 character a-Z & 0-9]</small>
                      <input type="text" class="form-control" id="qrName" placeholder="Enter product name in QR-Code" maxlength="10">
                      <small class="form-text" id="errorQrName" style="display: none;"></small>
                    </div>

                    <div class="form-group mb-3">
                      <label class="form-label">SKU</label> 
                      <small class="form-text text-muted">[ Max 3 character a-Z & 0-9]</small>
                      <input type="text" class="form-control" id="sku" placeholder="Enter SKU" maxlength="3">
                      <small class="form-text" id="errorSku" style="display: none;"></small>
                    </div>

                    <div class="progress" id="uploadProgress" style="height: 2px; display: none;">
                      <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="form-text text-muted text-end" id="progressText" style="display: none;">
                      <i class="fa fa-spinner fa-pulse fa-fw"></i>
                      <span class="sr-only">Loading...</span> 
                      <span id="progress-text">0%</span> Loading...
                    </small>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="closeModalAdd()" id="close">Close</button>
                      <button type="button" class="btn btn-outline-primary" onclick="addProduct()" id="add">Add Product</button>
                    </div>
              ';
  }

    $modal .= '     

                  </div>
                </div>
              </div>
            </div>';

  $result = [
    'modal' => $modal,
  ];

  echo json_encode($result);

}


if ($_POST['method'] == "showModalEditProduct") {

  $product_code = $_POST['id'];
  $sql = "SELECT * FROM `product_system` WHERE `product_code` = :product_code AND `member_id` = :member_id";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(":product_code", $product_code, PDO::PARAM_STR);
  $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
  $stmt->execute();
  $result = $addOn->useFetchAll($stmt);

  $modal = '
            <div class="modal fade modal-animate" id="editProduct" data-bs-backdrop="static" tabindex="-1" aria-hidden="true"> 
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">📦 Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalEdit()"></button>
                  </div>
                
                  <div class="modal-body">
            ';

  $check_auth_google = $auth->check_EmailGoogleAPI($_SESSION['user_info']['member_id']);
  if (empty($check_auth_google)) {
    $modal .= ' 
                    <button type="button" class="w-100 btn mt-2 btn-light text-dark" onclick="window.location.href=\'setting_google\'">
                      <img class="me-2 pb-1" src="assets/images/authentication/google_drive.png" width="16" alt="img">
                      <span class="d-sm-inline-block">Setting Google Drive!!</span>
                    </button>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" id="close">Close</button>
                    </div>
              ';
  } else {
    $modal .= '     <div class="form-group mb-3">
                      <label class="form-label">Product Name</label>
                      <input type="hidden" class="form-control" id="id" value="'.$result['product_code'].'">
                      <input type="text" class="form-control" id="productName" placeholder="Enter product name" value="'.$result['product_name'].'">
                      <small class="form-text" id="errorProductName" style="display: none;"></small>
                    </div>

                    <div class="form-group mb-3">
                      <label class="form-label">Image</label>
                      <div class="input-group">
                        <a href="https://drive.google.com/thumbnail?id='.$result['product_image'].'" target="_blank">
                          <img src="https://drive.google.com/thumbnail?id='.$result['product_image'].'&sz=w50" class="me-3">
                        </a>
                        <input type="file" id="image" class="form-control" accept="image/*">
                        <label class="input-group-text" for="image"><i class="fab fa-google-drive me-2"></i> Upload</label>
                      </div>
                      <small class="form-text" id="errorImage" style="display: none;"></small>
                    </div>

                    <div class="form-group mb-3">
                      <label class="form-label">Product name in QR-Code</label>
                      <small class="form-text text-muted">[ Max 10 character a-Z & 0-9]</small>
                      <input type="text" class="form-control" id="qrName" placeholder="Enter product name in QR-Code" maxlength="10" value="'.$result['product_name_qr'].'">
                      <small class="form-text" id="errorQrName" style="display: none;"></small>
                    </div>

                    <div class="form-group mb-3">
                      <label class="form-label">SKU</label> 
                      <small class="form-text text-muted">[ Max 3 character a-Z & 0-9]</small>
                      <input type="text" class="form-control" id="sku" placeholder="Enter SKU" maxlength="3" value="'.$result['product_sku'].'">
                      <small class="form-text" id="errorSku" style="display: none;"></small>
                    </div>

                    <div class="progress" id="uploadProgress" style="height: 2px; display: none;">
                      <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="form-text text-muted text-end" id="progressText" style="display: none;">
                      <i class="fa fa-spinner fa-pulse fa-fw"></i>
                      <span class="sr-only">Loading...</span> 
                      <span id="progress-text">0%</span> Loading...
                    </small>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="closeModalEdit()" id="close">Close</button>
                      <button type="button" class="btn btn-outline-primary" onclick="editProduct()" id="add">Edit Product</button>
                    </div>
              ';
  }

    $modal .= '     

                  </div>
                </div>
              </div>
            </div>';

  $result = [
    'modal' => $modal,
  ];

  echo json_encode($result);

}


if ($_POST['method'] == "deleteProduct") {

  $product_code = $_POST['id'];

  $client = new Google\Client();
  $client->setClientId(API_GOOGLE_CLIENT_ID);
  $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
  $client->setRedirectUri(API_GOOGLE_DRIVE_CALLBACK_URL);
  $client->addScope("https://www.googleapis.com/auth/drive");

  // Retrieve token from the database
  $sql = "SELECT * FROM `auth_google` WHERE `member_id` = :member_id";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
  $stmt->execute();
  $result = $addOn->useFetchAll($stmt);;

  if (!$result) {
      $status = ['status' => "error auth google",];
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
          $status = ['status' => "Error google drive",];
          echo json_encode($status);
          exit;
      }
  }

  $service = new Google\Service\Drive($client);

  $sql = "SELECT * FROM `product_system` WHERE `product_code` = :product_code AND `member_id` = :member_id";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(":product_code", $product_code, PDO::PARAM_STR);
  $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
  $stmt->execute();
  $stmtProduct = $addOn->useFetchAll($stmt);;

  try {
    $service->files->delete($stmtProduct['product_image']);
    $status['delete'] = "success";
  } catch (Google\Service\Exception $e) {
    $status['delete'] = "error";
  }

  try {
      $sql = "DELETE FROM product_system WHERE `product_code` = :product_code AND `member_id` = :member_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":product_code", $product_code, PDO::PARAM_STR);
      $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
      $stmt->execute();
      $status = ['status' => "success",];
  } catch (Exception $e) {
      $status = ['status' => "error",];
  }

  echo json_encode($status);
}


?>
