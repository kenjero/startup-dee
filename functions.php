<?php

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomUpper($length) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function Number_String($numberString) {
  $String = htmlspecialchars($numberString);
  $number = intval(str_replace(",", "", $String));
  return $number;
}


function get_client_ip() {
     $ipaddress = '';
     if ($_SERVER['HTTP_CLIENT_IP'])
         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
     else if($_SERVER['HTTP_X_FORWARDED_FOR'])
         $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
     else if($_SERVER['HTTP_X_FORWARDED'])
         $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
     else if($_SERVER['HTTP_FORWARDED_FOR'])
         $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
     else if($_SERVER['HTTP_FORWARDED'])
         $ipaddress = $_SERVER['HTTP_FORWARDED'];
     else if($_SERVER['REMOTE_ADDR'])
         $ipaddress = $_SERVER['REMOTE_ADDR'];
     else
         $ipaddress = 'UNKNOWN';

	return $ipaddress;
}

function isMobileCheck(){
    $isMobile = false;
    $op = strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE']);
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    $ac = strtolower($_SERVER['HTTP_ACCEPT']);
    $ip = $_SERVER['REMOTE_ADDR'];

    $isMobile = strpos($ac, 'application/vnd.wap.xhtml+xml') !== false
            || $op != ''
            || strpos($ua, 'sony') !== false
            || strpos($ua, 'symbian') !== false
            || strpos($ua, 'nokia') !== false
            || strpos($ua, 'samsung') !== false
            || strpos($ua, 'mobile') !== false
            || strpos($ua, 'windows ce') !== false
            || strpos($ua, 'epoc') !== false
            || strpos($ua, 'opera mini') !== false
            || strpos($ua, 'nitro') !== false
            || strpos($ua, 'j2me') !== false
            || strpos($ua, 'midp-') !== false
            || strpos($ua, 'cldc-') !== false
            || strpos($ua, 'netfront') !== false
            || strpos($ua, 'mot') !== false
            || strpos($ua, 'up.browser') !== false
            || strpos($ua, 'up.link') !== false
            || strpos($ua, 'audiovox') !== false
            || strpos($ua, 'blackberry') !== false
            || strpos($ua, 'ericsson,') !== false
            || strpos($ua, 'panasonic') !== false
            || strpos($ua, 'philips') !== false
            || strpos($ua, 'sanyo') !== false
            || strpos($ua, 'sharp') !== false
            || strpos($ua, 'sie-') !== false
            || strpos($ua, 'portalmmm') !== false
            || strpos($ua, 'blazer') !== false
            || strpos($ua, 'avantgo') !== false
            || strpos($ua, 'danger') !== false
            || strpos($ua, 'palm') !== false
            || strpos($ua, 'series60') !== false
            || strpos($ua, 'palmsource') !== false
            || strpos($ua, 'pocketpc') !== false
            || strpos($ua, 'smartphone') !== false
            || strpos($ua, 'rover') !== false
            || strpos($ua, 'ipaq') !== false
            || strpos($ua, 'au-mic,') !== false
            || strpos($ua, 'alcatel') !== false
            || strpos($ua, 'ericy') !== false
            || strpos($ua, 'up.link') !== false
            || strpos($ua, 'vodafone/') !== false
            || strpos($ua, 'wap1.') !== false
            || strpos($ua, 'wap2.') !== false;
        return $isMobile;
}

function isDotTypeimage($fileType){

  switch ($fileType) {
      case 'image/jpeg':
          $dotType = ".jpeg";
          break;
      case 'image/jpg':
          $dotType = ".jpg";
          break;
      case 'image/png':
          $dotType = ".png";
          break;
      default:
          $dotType = ".jpg";
          break;
  }
  return $dotType;

}

function copyImage($sourcePath, $destinationPath) {
    // Read the contents of the source image
    $imageData = file_get_contents($sourcePath);

    // Write the contents to the destination image file
    file_put_contents($destinationPath, $imageData);

    return file_exists($destinationPath);
}

////////////////////////////////////////////////////////////

function validateFormat($data, $type) {

    if($type == "mobile"){
        $pattern = '/^0[0-9]\d{8}$/';
        return preg_match($pattern, $data) === 1;
    }

    if($type == "email"){
        return filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
    }

    if($type == "user"){
        $validate = '/^[a-zA-Z0-9]+$/';
        return preg_match($validate, $data) === 1;
    }
}


function statusCustomerQRCode ($status) {

  if($status == 1){
    $result = '<span class="bi bi-check-circle-fill text-success"> ของแท้ 100%</span>';
  }
  else if($status == 2){
    $result = '<span class="bi bi-exclamation-triangle-fill text-warning"> เคยสแกนแล้ว</span>';
  }
  else{
    $result = '<span class="bi bi-x-circle text-danger"> ของปลอม</span>';
  }

  return $result;

}


function statustypeApp($typeApp) {

  if($typeApp == "tk"){
    $result = '<span class="m-1 badge bg-dark"><i class="bi bi-tiktok"></i> Tiktok</span>';
  }
  else if($typeApp == "fb"){
    $result = '<span class="m-1 badge bg-primary"><i class="bi bi-facebook"></i> Facebook</span>';
  }
  else if($typeApp == "sp"){
    $result = '<span class="m-1 badge bg-danger"><i class="bi bi-bag"></i> Shopee</span>';
  }
  else if($typeApp == "lz"){
    $result = '<span class="m-1 badge bg-info"><i class="bi bi-bag-heart"></i> Lazada</span>';
  }
  else{
    $result = '<span class="m-1 badge bg-secondary"><i class="bi bi-basket2-fill"></i> อื่นๆ</span>';
  }

  return $result;

}


function readVideoChunk($handle, $chunkSize)
{
    $byteCount = 0;
    $giantChunk = "";
    while (!feof($handle)) {
        $chunk = fread($handle, 8192);
        $byteCount += strlen($chunk);
        $giantChunk .= $chunk;
        if ($byteCount >= $chunkSize) {
            return $giantChunk;
        }
    }
    return $giantChunk;
}

Class Database {

  // ====================================================== //
  // =================== Back-End ========================= //
	// ====================================================== //

	// ====================================================== //
	// เชื่อต่อฐานข้อมูล แบบ PDO //
	// ====================================================== //
	private $charset = 'UTF8';

    public function __construct($user, $password, $database, $host) {
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->host = $host;
    }

    public function connect() {
        return new PDO("mysql::host=$this->host; dbname=$this->database", $this->user, $this->password,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $this->charset"));
    }

    // ====================================================== //
    // products_list //
    // ====================================================== //

    public function products_list() {
      $db = $this->connect();
      $result = $db->prepare("SELECT * FROM product ORDER BY id DESC");
      $result->execute();
      return $result;
    }

    public function products_select($product_id) {
      $db = $this->connect();
      $result = $db->prepare("SELECT * FROM product WHERE id = :product_id");

      $result->bindParam(':product_id', $product_id, PDO::PARAM_INT);
      $result->execute();

      return $result;
    }

    public function products_sku_edit_check($product_sku,$product_id) {
      $db = $this->connect();
      $statement = $db->prepare("SELECT * FROM product WHERE product_sku = :product_sku AND id <> :product_id");

      $statement->bindParam(':product_sku', $product_sku, PDO::PARAM_STR);
      $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
      $statement->execute();

      return $statement->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function products_sku_check($product_sku) {
      $db = $this->connect();
      $statement = $db->prepare("SELECT * FROM product WHERE product_sku = :product_sku");

      $statement->bindParam(':product_sku', $product_sku, PDO::PARAM_STR);
      $statement->execute();

      return $statement->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function products_list_id() {
      $db = $this->connect();
      $result = $db->prepare("SELECT id FROM product ORDER BY id ASC");
      $result->execute();
      $ids = [];

      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $ids[] = $row['id'];
      }

      $idsString = implode(',', $ids);
      return $idsString;
    }

    public function products_add($jsonData) {
      $db = $this->connect();

      $dataArray = json_decode($jsonData, true);
      $keysString = implode(",", array_keys($dataArray));
      $valuesBindParam = ":" . implode(",:", array_keys($dataArray));

      $db = $this->connect();
      $sql = "INSERT INTO product ($keysString) VALUES ($valuesBindParam)";
      $result = $db->prepare($sql);

      foreach ($dataArray as $key => $value) {
          $result->bindParam(":$key", $dataArray[$key]);
      }

      $result->execute();

      return $result;
    }

    public function products_edit($jsonData) {
        $db = $this->connect();

        $dataArray = json_decode($jsonData, true);
        $updateSetString = implode(", ", array_map(function($key) {
              return "$key = :$key";
        }, array_keys($dataArray)));

        $db = $this->connect();
        $sql = "UPDATE product SET $updateSetString WHERE id = :id";
        $result = $db->prepare($sql);

        foreach ($dataArray as $key => $value) {
            $result->bindParam(":$key", $dataArray[$key]);
        }

        $result->execute();

        return $result;
    }

    public function deleteProduct($jsonData) {
      $db = $this->connect();
      $dataArray = json_decode($jsonData, true);

      $sql = "SELECT * FROM product WHERE id = :id";
      $result = $db->prepare($sql);
      $result->bindParam(':id', $dataArray['id'], PDO::PARAM_INT);
      $result->execute();

      $product = $result->fetch(PDO::FETCH_ASSOC);
      $originalFilename  = FRONT_END_DOCUMENT_ROOT.$product['product_image'];
      unlink($originalFilename);

      $sql = "DELETE FROM product WHERE id = :id";

      $result = $db->prepare($sql);
      $result->bindParam(':id', $dataArray['id'], PDO::PARAM_INT);
      $result->execute();

      if ($result->rowCount() > 0) {
        return true;
      } else {
        return false;
      }
    }

    public function MAXProductId() {
      $db = $this->connect();
      $sql = "SELECT MAX(id) AS max_id FROM product";
      $stmt = $db->prepare($sql);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['max_id'];
    }

    public function copyProductByCheckbox($postData) {
      $db = $this->connect();
      $sql = "SELECT * FROM product WHERE id = :id";
      $result = $db->prepare($sql);
      $result->bindParam(':id', $postData, PDO::PARAM_INT);
      $result->execute();

      $product = $result->fetch(PDO::FETCH_ASSOC);
      $MAXProductId = $this->MAXProductId();

      if($product){
          $secrets = generateRandomUpper(3);
          while ($this->products_sku_check($secrets)) {
            $secrets = generateRandomUpper(3);
          }

          $originalFilename  = FRONT_END_DOCUMENT_ROOT.$product['product_image'];
          $dotTypeFilename = pathinfo($originalFilename , PATHINFO_EXTENSION);
          $newImageFilename = $secrets.'.'.$dotTypeFilename;
          $destinationImagePath = FRONT_END_DOCUMENT_ROOT.FRONT_END_IMAGE_PRODUCT_PATH.$newImageFilename;


          $dataArray = array(
              'product_image'    => FRONT_END_IMAGE_PRODUCT_PATH.$newImageFilename,
              'product_name'     => $product['product_name'],
              'product_name_qr'  => $product['product_name_qr'],
              'product_sku'      => $secrets,
          );

          $keysString = implode(",", array_keys($dataArray));
          $valuesBindParam = ":" . implode(",:", array_keys($dataArray));

          $insertSql = "INSERT INTO product ($keysString) VALUES ($valuesBindParam)";
          $insertResult = $db->prepare($insertSql);

          foreach ($dataArray as $insertKey => $insertValue) {
              $insertResult->bindParam(":$insertKey", $dataArray[$insertKey]);
          }
          $returnSql = $insertResult->execute();

          copyImage($originalFilename, $destinationImagePath);

          return $dataArray;
      }

    }

    public function deleteProductByCheckbox($postData) {
      $db = $this->connect();

      $sql = "SELECT * FROM product WHERE id = :id";
      $result = $db->prepare($sql);
      $result->bindParam(':id', $postData, PDO::PARAM_INT);
      $result->execute();

      $product = $result->fetch(PDO::FETCH_ASSOC);
      $originalFilename  = FRONT_END_DOCUMENT_ROOT.$product['product_image'];
      unlink($originalFilename);

      $sql = "DELETE FROM product WHERE id = :id";
      $result = $db->prepare($sql);
      $result->bindParam(':id', $postData, PDO::PARAM_INT);
      $result->execute();

      if ($result->rowCount() > 0) {
        return true;
      } else {
        return false;
      }
    }

    // public function copyProductByCheckbox($postData) {
    //   $db = $this->connect();
    //
    //   foreach ($dataArray as $key => $value) {
    //
    //       $sql = "SELECT * FROM product WHERE id = :id";
    //       $result = $db->prepare($sql);
    //       $result->bindParam(':id', $value, PDO::PARAM_INT);
    //       $result->execute();
    //
    //       $products = $result->fetchAll(PDO::FETCH_ASSOC);
    //       $dataArray = json_decode($products, true);
    //       $keysString = implode(",", array_keys($dataArray));
    //       $valuesBindParam = ":" . implode(",:", array_keys($dataArray));
    //
    //       foreach ($products as $key => $value) {
    //             $cleanKey = preg_replace('/\[.*?\]/', '', $key);
    //             $sql = "INSERT INTO product ($keysString) VALUES ($valuesBindParam)";
    //             $result = $db->prepare($sql);
    //             foreach ($dataArray as $key => $value) {
    //                 $result->bindParam(":$key", $dataArray[$key]);
    //             }
    //             $result->execute();
    //       }
    //   }
    //
    //   if ($result->rowCount() > 0) {
    //     return true;
    //   } else {
    //     return false;
    //   }
    // }

    public function List_Product_API() {
        $db = $this->connect();
        $result = $db->prepare("SELECT id, product_name, product_name_qr, product_sku FROM product ORDER BY id ASC");
        $result->execute();

        $products = $result->fetchAll(PDO::FETCH_ASSOC);

        return $products;
    }



    // ====================================================== //
    // user_account แบบ login user //
    // ====================================================== //

    public function login_system($jsonData) {

        $db = $this->connect();
        $dataArray = json_decode($jsonData, true);
        $sql = "SELECT * FROM user_account WHERE username = :username";

        $statement = $db->prepare($sql);
        $statement->bindParam(':username', $dataArray['username'], PDO::PARAM_STR);
        $statement->execute();

        $sql_user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($sql_user && password_verify($dataArray['password'], $sql_user['password'])) {
            $result = [
                        'status' => 'success',
                        'user_info' => [
                            'user_id' => $sql_user['id'],
                            'username' => $sql_user['username'],
                        ]
                      ];
        } else {
            $result = [
                        'status' => 'failed',
                        'user_info' => 'Invalid credentials',
                      ];
        }

        return $result;
    }

    public function login_system_API($jsonData) {

        $db = $this->connect();
        $dataArray = json_decode($jsonData, true);
        $sql = "SELECT * FROM user_account WHERE username = :username";

        $statement = $db->prepare($sql);
        $statement->bindParam(':username', $dataArray['username'], PDO::PARAM_STR);
        $statement->execute();

        $sql_user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($sql_user && password_verify($dataArray['password'], $sql_user['password'])) {
            $result = [
                        'status' => 'success',
                        'user_info' => [
                            'user_id' => $sql_user['id'],
                            'username' => $sql_user['username'],
                        ]
                      ];

        } else {
            $result = [
                        'status' => 'failed',
                        'user_info' => 'Invalid credentials',
                      ];
        }
        return $result;
    }

    //////////// Add User Login ////////////
    public function Add_User_Login($jsonData) {
        // เชื่อมต่อกับฐานข้อมูล
        $db = $this->connect();

        $dataArray = json_decode($jsonData, true);
        $keysString = implode(",", array_keys($dataArray));
        $valuesBindParam = ":" . implode(",:", array_keys($dataArray));

        $db = $this->connect();
        $sql = "INSERT INTO user_account ($keysString) VALUES ($valuesBindParam)";
        $stmt = $db->prepare($sql);

        foreach ($dataArray as $key => $value) {
            $stmt->bindParam(":$key", $dataArray[$key]);
        }

        $result = $stmt->execute();
        return $result;
    }

    // ============================= //
    //  Qrcode //
    // ============================ //

    //////////// Add Qrcode ////////////
    public function add_qrcode($jsonData) {
    $db = $this->connect();
    $dataArray = json_decode($jsonData, true);

    $count = $dataArray['addqrcode'];
    $product_id = $dataArray['product_id'];

    try {
        $db->beginTransaction();

        $sql = 'SELECT MAX(id_qrcode) AS max_id FROM qrcode_system WHERE product_id = :product_id';
        $statement = $db->prepare($sql);
        $statement->bindParam(':product_id', $product_id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $max_id = $result['max_id'];

        $secrets = array();
        for ($i = 0; $i < $count; $i++) {
            $max_id++; // เพิ่มค่า id_qrcode จากค่าล่าสุดของ product_id นั้น ๆ
            $secrets[] = generateRandomString(6);

            $sql = 'INSERT INTO qrcode_system (id_qrcode, secret, product_id, status, activate)
            VALUES (:id_qrcode, :secret, :product_id, :status, :activate)';
            $statement = $db->prepare($sql);
            $statement->bindParam(':id_qrcode', $max_id, PDO::PARAM_INT);
            $statement->bindParam(':secret', $secrets[$i], PDO::PARAM_STR);
            $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $statement->bindValue(':status', 0, PDO::PARAM_INT);
            $statement->bindValue(':activate', 0, PDO::PARAM_INT);
            $statement->execute();
        }

        $db->commit();
        return true;
    } catch (PDOException $e) {
        $db->rollBack();
        // Log or handle the exception
        return false;
    }
  }

  //////////// Qrcode Create System API ////////////
  public function Qrcode_Create_system_API($jsonData) {

    $db = $this->connect();
    $dataArray = json_decode($jsonData, true);

    $sql = "SELECT qs.id, qs.id_qrcode, qs.secret, qs.product_id, p.product_name, p.product_name_qr, p.product_sku, qs.status, qs.activate
            FROM qrcode_system qs
            JOIN product p ON qs.product_id = p.id
            WHERE qs.product_id = :productID
            AND qs.id_qrcode BETWEEN :Qrcode_Start AND :Qrcode_End
            ORDER BY qs.id ASC";

    $statement = $db->prepare($sql);
    $statement->bindParam(':productID', $dataArray['productID'],PDO::PARAM_INT);
    $statement->bindParam(':Qrcode_Start', $dataArray['Qrcode_Start'],PDO::PARAM_INT);
    $statement->bindParam(':Qrcode_End', $dataArray['Qrcode_End'],PDO::PARAM_INT);
    $statement->execute();

    $sql_user = $statement->fetchAll(PDO::FETCH_ASSOC);

    // ตรวจสอบว่ามีข้อมูลหรือไม่ก่อนที่จะดำเนินการ
    if ($sql_user) {
      foreach ($sql_user as $record) {
        $result[] = [
            'id' => $record['id'],
            'id_qrcode' => $record['id_qrcode'],
            'secret' => $record['secret'],
            'product_id' => $record['product_id'],
            'product_name' => $record['product_name'],
            'product_name_qr' => $record['product_name_qr'],
            'product_sku' => $record['product_sku'],
            'status' => $record['status'],
            'activate' => $record['activate'],
        ];
      }
    } else {
      // ถ้าไม่มีข้อมูลในผลลัพธ์
      $result = [];
    }

    return $result;
  }



    //////////// List Qrcode ////////////
    public function qrcode_list($jsonData) {
      $db = $this->connect();
      $dataArray = json_decode($jsonData, true);

      // แปลง number X0,000 เป็น X0000
      $QRCode_Start = intval(str_replace(",", "", $dataArray['QRCode_Start']));
      $QRCode_End = intval(str_replace(",", "", $dataArray['QRCode_End']));

      $result = $db->prepare("SELECT qs.id, qs.id_qrcode, qs.secret, qs.product_id, p.product_name, p.product_sku, qs.status, qs.activate
                              FROM qrcode_system qs
                              JOIN product p ON qs.product_id = p.id
                              WHERE qs.product_id IN (:productID)
                              AND qs.activate IN (:QRActivate)
                              AND qs.status IN (:QRStatus)
                              AND qs.id_qrcode BETWEEN :QRCode_Start AND :QRCode_End
                              ORDER BY qs.id DESC
                              ");

      $result->bindParam(':QRCode_Start', $QRCode_Start, PDO::PARAM_INT);
      $result->bindParam(':QRCode_End', $QRCode_End, PDO::PARAM_INT);

      $result->bindParam(':productID', $dataArray['QRProductID'], PDO::PARAM_INT);

      $result->bindParam(':QRActivate', $dataArray['QRActivate'], PDO::PARAM_INT);
      $result->bindParam(':QRStatus', $dataArray['QRStatus'], PDO::PARAM_INT);

      $result->execute();
      return $result;
    }

    //////////// List 100 Qrcode ////////////
    public function qrcode_list_first() {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT qs.id, qs.id_qrcode, qs.secret, qs.product_id, p.product_name, p.product_sku, qs.status, qs.activate
                      FROM qrcode_system qs
                      JOIN product p ON qs.product_id = p.id
                      ORDER BY qs.id DESC
                      LIMIT 100");
        $stmt->execute();
        return $stmt; // รีเทิร์น Statement Object ที่เก็บผลลัพธ์จากการ execute
    }

    //////////// Qrcode Count ////////////
    public function qrcode_count() {
      $db = $this->connect();

      try {
          $query = "SELECT COUNT(*) as count FROM qrcode_system";
          $statement = $db->prepare($query);
          $statement->execute();

          $result = $statement->fetch(PDO::FETCH_ASSOC);

          if ($result) {
              return $result['count']; // จำนวนแถวทั้งหมดในตาราง
          }
          return 0; // หากไม่พบข้อมูลในตาราง
      } catch (PDOException $e) {
          // จัดการข้อผิดพลาดที่เกิดขึ้น
          // เช่น การเชื่อมต่อฐานข้อมูลผิดพลาด
          return -1; // หรือค่าอื่นๆ เพื่อบอกว่ามีข้อผิดพลาดเกิดขึ้น
      }
    }

  // ====================================================== //
  // =================== Front-End ======================== //
	// ====================================================== //

  // ====================================================== //
	// Change Password //
	// ====================================================== //

  public function checkOldPassword($jsonData) {

      $db = $this->connect();
      $dataArray = json_decode($jsonData, true);

      $sql = "SELECT * FROM customer_member WHERE email = :email";

      $statement = $db->prepare($sql);
      $statement->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
      $statement->execute();

      $sql_user = $statement->fetch(PDO::FETCH_ASSOC);

      if ($sql_user && password_verify($dataArray['oldPassword'], $sql_user['password'])) {
          $result = true;
      } else {
          $result = false;
      }

      return $result;

  }

  public function changePassword($jsonData) {
      $db = $this->connect();
      $dataArray = json_decode($jsonData, true);
      $sql = "SELECT * FROM customer_member WHERE email = :email";

      $statement = $db->prepare($sql);
      $statement->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
      $statement->execute();

      $sql_user = $statement->fetch(PDO::FETCH_ASSOC);

      if ($sql_user && password_verify($dataArray['oldPassword'], $sql_user['password'])) {
          $sql = "UPDATE customer_member SET password = :password WHERE email = :email";
          $stmt = $db->prepare($sql);
          $hashedNewPassword = password_hash($dataArray['newPassword'], PASSWORD_DEFAULT);
          $stmt->bindParam(":password", $hashedNewPassword);
          $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
          $stmt->execute();

          $result = true;
      } else {
          $result = false;
      }

      return $result;
  }

  // ====================================================== //
  // Change Image File //
  // ====================================================== //
  public function changeImageFile($jsonData) {

    $db = $this->connect();
    $dataArray = json_decode($jsonData, true);
    $sql = "UPDATE customer_member SET image = :image WHERE email = :email";

    $statement = $db->prepare($sql);
    $statement->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
    $statement->bindParam(':image', $dataArray['base64Image'], PDO::PARAM_LOB);

    $sql_user = $statement->execute();

    if ($sql_user) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
  }

  public function showImageFile() {

    $db = $this->connect();
    $sql = "SELECT * FROM customer_member WHERE email = :email";

    $statement = $db->prepare($sql);
    $statement->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
    $statement->execute();

    $sql_user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($sql_user) {
        $result = $sql_user['image'];
    }else{
        $result = 'img/user/unknown_person.jpg';
    }

    return $result;
  }

  // ====================================================== //
  // Sign In system //
  // ====================================================== //
  public function signIn_system($jsonData) {

      $db = $this->connect();
      $dataArray = json_decode($jsonData, true);
      $sql = "SELECT * FROM customer_member WHERE email = :email";

      $statement = $db->prepare($sql);
      $statement->bindParam(':email', $dataArray['email'], PDO::PARAM_STR);
      $statement->execute();

      $sql_user = $statement->fetch(PDO::FETCH_ASSOC);

      if ($sql_user && password_verify($dataArray['password'], $sql_user['password'])) {
          $_SESSION['signed_in']  = true;
          $_SESSION['member_id']  = $sql_user['id'];
          $_SESSION['email']      = $sql_user['email'];
          $_SESSION['fullname']   = $sql_user['fullname'];

          $result = true;
      } else {
          $result = false;
      }

      return $result;
  }

  public function CustomerMemberDetail() {
    $db = $this->connect();

    try {
        $sql = "SELECT * FROM customer_member WHERE email = :email";
        $statement = $db->prepare($sql);
        $statement->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);

        if ($statement->execute()) {
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return false;
    }
  }

  public function CustomerMember_ID() {
    $db = $this->connect();

    try {
        $sql = "SELECT id FROM customer_member WHERE email = :email";
        $statement = $db->prepare($sql);
        $statement->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);

        if ($statement->execute()) {
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result['id'];
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return false;
    }
  }

  public function CustomerQRCodeListByUser() {
    $db = $this->connect();

    $customer_id = $this->CustomerMember_ID();

    try {
        $sql = "SELECT cq.id, cq.id_qrcode, p.product_name, p.product_sku, cq.status, cq.typeApp
                FROM customer_qrcode cq
                JOIN product p ON cq.product_id = p.id
                WHERE cq.customer_id = :customer_id
                ORDER BY cq.id DESC";

        $statement = $db->prepare($sql);
        $statement->bindParam(':customer_id', $customer_id, PDO::PARAM_STR);

        if ($statement->execute()) {
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return false;
    }
}

  // ====================================================== //
	// check Repetitive - เช็คค่าซ้ำ //
	// ====================================================== //

  public function checkRepetitiveMobile($jsonData) {

      $db = $this->connect();
      $dataArray = json_decode($jsonData, true);

      try {
          $sql = "SELECT * FROM customer_member WHERE mobile = :mobile";
          $statement = $db->prepare($sql);
          $statement->bindParam(':mobile', $dataArray['mobile']);

          if ($statement->execute()) {
              $result = $statement->fetch(PDO::FETCH_ASSOC);
              return ($result !== false);
          } else {
              return false;
          }
      } catch (PDOException $e) {
          return false;
      }
  }

  public function checkRepetitivEmail($jsonData) {

    $db = $this->connect();
    $dataArray = json_decode($jsonData, true);

    try {
        $sql = "SELECT * FROM customer_member WHERE email = :email";
        $statement = $db->prepare($sql);
        $statement->bindParam(':email', $dataArray['email']);

        if ($statement->execute()) {
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return ($result !== false);
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return false;
    }

  }


	// ====================================================== //
	// Select(List/Menu) จังหวัด อำเภอ ตำบล รหัสไปรษณีย์ //
	// ====================================================== //
  public function getProvinces() {
    $db = $this->connect();
    $result = $db->prepare("SELECT * FROM address_provinces ORDER BY id ASC");
    $result->execute();
    return $result;
  }

  public function ListMenu_GetDistricts($province_id) {
    $db = $this->connect();
    $result = $db->prepare("SELECT * FROM address_districts WHERE province_id = :province_id ORDER BY id ASC");
    $result->bindParam(':province_id', $province_id, PDO::PARAM_INT);
    $result->execute();
    return $result;
}

  public function ListMenu_GetSubdistricts($district_id) {
    $db = $this->connect();
    $result = $db->prepare("SELECT * FROM address_subdistricts WHERE district_id = :district_id ORDER BY id ASC");
    $result->bindParam(':district_id', $district_id, PDO::PARAM_INT);
    $result->execute();
    return $result;
  }

  // ====================================================== //
	// Add Customer //
	// ====================================================== //
  public function AddCustomer($jsonData) {
      $dataArray = json_decode($jsonData, true);
      $keysString = implode(",", array_keys($dataArray));
      $valuesBindParam = ":" . implode(",:", array_keys($dataArray));

      $db = $this->connect();
      $sql = "INSERT INTO customer_member ($keysString) VALUES ($valuesBindParam)";
      $stmt = $db->prepare($sql);

      foreach ($dataArray as $key => $value) {
          $stmt->bindParam(":$key", $dataArray[$key]);
      }

      $result = $stmt->execute();
      return $result;
  }


  // ====================================================== //
	// Chack Secret QRCode //
	// ====================================================== //


  public function CheckSecretQRCode($URL_QrcodeID,$URL_ProductID,$URL_Secret) {
      $db = $this->connect();

      $result = $db->prepare("SELECT * FROM qrcode_system
                              WHERE id_qrcode = :id_qrcode
                              AND product_id = :product_id
                              AND secret = :secret
                              ");

      $result->bindParam(':id_qrcode',  $URL_QrcodeID, PDO::PARAM_INT);
      $result->bindParam(':product_id', $URL_ProductID, PDO::PARAM_INT);
      $result->bindParam(':secret',     $URL_Secret, PDO::PARAM_STR);

      $result->execute();

      return $result;
  }

  public function CheckSecretCustomerQRCode($URL_QrcodeID,$URL_ProductID,$URL_Secret) {
      $db = $this->connect();

      $result = $db->prepare("SELECT * FROM customer_qrcode
                              WHERE id_qrcode = :id_qrcode
                              AND product_id = :product_id
                              AND secret = :secret
                              ");

      $result->bindParam(':id_qrcode',  $URL_QrcodeID, PDO::PARAM_INT);
      $result->bindParam(':product_id', $URL_ProductID, PDO::PARAM_INT);
      $result->bindParam(':secret',     $URL_Secret, PDO::PARAM_STR);

      $result->execute();

      return $result;
  }

  public function addCustomerQrcode($jsonData) {
      // เชื่อมต่อกับฐานข้อมูล
      $db = $this->connect();

      $mobile = str_replace('-', '',  $jsonData['customer_member']['mobile']);
      $email = $jsonData['customer_member']['email'];

      $sql = "SELECT * FROM customer_member WHERE mobile = :mobile";
      $statement = $db->prepare($sql);
      $statement->bindParam(':mobile', $mobile);
      $statement->execute();  // Execute the query
      $resultMobile = $statement->fetch(PDO::FETCH_ASSOC);

      $sql = "SELECT * FROM customer_member WHERE email = :email";
      $statement = $db->prepare($sql);
      $statement->bindParam(':email', $email);
      $statement->execute();  // Execute the query
      $resultEmail = $statement->fetch(PDO::FETCH_ASSOC);

      if ($resultMobile) {
          return 'er_mobile';
      }
      elseif ($resultEmail) {
          return 'er_email';
      }
      elseif (!validateFormat($jsonData['customer_member']['email'], "email")) {
          return 'validate_email';
      }
      elseif (!validateFormat($jsonData['customer_member']['mobile'], "mobile")) {
          return 'validate_mobile';
      }
      elseif (empty($jsonData['customer_qrcode']['typeApp'])) {
          return 'null_app';
      }
      elseif (empty($jsonData['customer_member']['email'])) {
          return 'null_email';
      }
      elseif (empty($jsonData['customer_member']['mobile'])) {
          return 'null_mobile';
      }
      elseif (empty($jsonData['customer_member']['fullname'])) {
          return 'null_fullName';
      }
      elseif (empty($jsonData['customer_member']['subdistricts'])) {
          return 'null_subdistricts';
      }
      elseif (empty($jsonData['customer_member']['district'])) {
          return 'null_districts';
      }
      elseif (empty($jsonData['customer_member']['province'])) {
          return 'null_province';
      }
      else {
        $update = "UPDATE qrcode_system SET activate = 1
         WHERE id_qrcode = :id_qrcode
         AND product_id = :product_id
         AND secret = :secret";

        $stmt = $db->prepare($update);
        $stmt->bindParam(':id_qrcode', $jsonData['customer_qrcode']['id_qrcode'], PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $jsonData['customer_qrcode']['product_id'], PDO::PARAM_INT);
        $stmt->bindParam(':secret', $jsonData['customer_qrcode']['secret'], PDO::PARAM_STR);

        $stmt->execute();

        $dataArray = $jsonData["customer_member"];
        $keysString = implode(",", array_keys($dataArray));
        $valuesBindParam = ":" . implode(",:", array_keys($dataArray));

        $sql = "INSERT INTO customer_member ($keysString) VALUES ($valuesBindParam)";

        $stmt = $db->prepare($sql);
        foreach ($dataArray as $key => $value) {
            $stmt->bindParam(":$key", $dataArray[$key]);
        }
        $stmt->execute();

        $lastInsertedId = $db->lastInsertId();

        /////////////////////////////////////////////////////////////////////////

        $dataArray = $jsonData["customer_qrcode"];
        $keysString = implode(",", array_keys($dataArray));
        $valuesBindParam = ":" . implode(",:", array_keys($dataArray));

        $sql = "INSERT INTO customer_qrcode (customer_id, $keysString)
                VALUES (:customer_id, $valuesBindParam)";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(":customer_id",  $lastInsertedId, PDO::PARAM_INT);
        foreach ($dataArray as $key => $value) {
            $stmt->bindParam(":$key", $dataArray[$key]);
        }

        $result = $stmt->execute();

        return 'success';
      }

  }


  public function addCustomerQrcodeByMember($jsonData) {
      $db = $this->connect();

      $customer_id = $this->CustomerMember_ID();

      $sqlCheck = "SELECT * FROM customer_qrcode
                   WHERE customer_id = :customer_id
                   AND id_qrcode = :id_qrcode
                   AND product_id = :product_id
                   AND secret = :secret";

      $stmtCheck = $db->prepare($sqlCheck);
      $stmtCheck->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
      $stmtCheck->bindParam(':id_qrcode', $jsonData['customer_qrcode']['id_qrcode'], PDO::PARAM_INT);
      $stmtCheck->bindParam(':product_id', $jsonData['customer_qrcode']['product_id'], PDO::PARAM_INT);
      $stmtCheck->bindParam(':secret', $jsonData['customer_qrcode']['secret'], PDO::PARAM_STR);
      $stmtCheck->execute();

      $PostSpam = $stmtCheck->fetch(PDO::FETCH_ASSOC);

      // If the record exists, return 'failed'
      if ($PostSpam) {
           return 'failed';
      } else {
          $update = "UPDATE qrcode_system SET activate = 1
           WHERE id_qrcode = :id_qrcode
           AND product_id = :product_id
           AND secret = :secret";

          $stmt = $db->prepare($update);
          $stmt->bindParam(':id_qrcode', $jsonData['customer_qrcode']['id_qrcode'], PDO::PARAM_INT);
          $stmt->bindParam(':product_id', $jsonData['customer_qrcode']['product_id'], PDO::PARAM_INT);
          $stmt->bindParam(':secret', $jsonData['customer_qrcode']['secret'], PDO::PARAM_STR);

          $stmt->execute();

          // If the record doesn't exist, proceed with insertion
          $dataArray = $jsonData["customer_qrcode"];
          $keysString = implode(",", array_keys($dataArray));
          $valuesBindParam = ":" . implode(",:", array_keys($dataArray));

          $sqlInsert = "INSERT INTO customer_qrcode (customer_id, $keysString)
                        VALUES (:customer_id, $valuesBindParam)";

          $stmtInsert = $db->prepare($sqlInsert);
          $stmtInsert->bindParam(":customer_id",  $customer_id, PDO::PARAM_INT);

          foreach ($dataArray as $key => $value) {
              $stmtInsert->bindParam(":$key", $dataArray[$key]);
          }

          // Execute the insertion query
          $result = $stmtInsert->execute();

          // Return 'success' or any appropriate response
          return "success";
      }
  }

  public function getEditProfile($jsonData) {
      $db = $this->connect();
      $dataArray = json_decode($jsonData, true);

      $sql = "UPDATE customer_member SET
                  fullname = :fullname,
                  province = :province,
                  district = :district,
                  subdistricts = :subdistricts
              WHERE email = :email";

      $stmt = $db->prepare($sql);

      $stmt->bindParam(':fullname', $dataArray['fullname'], PDO::PARAM_STR);
      $stmt->bindParam(':province', $dataArray['province'], PDO::PARAM_INT);
      $stmt->bindParam(':district', $dataArray['district'], PDO::PARAM_INT);
      $stmt->bindParam(':subdistricts', $dataArray['subdistricts'], PDO::PARAM_INT);
      $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);

      $result = $stmt->execute();

      if ($result) {
          return true;
      } else {
          return false;
      }
  }



}// end Class
?>
