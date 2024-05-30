<?php
require_once '../../functions/php.config.inc.php';
require_once '../../functions/php.functions.php';
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$db    = (new Database())->connect();
$addOn = new AddOn();
$auth  = new Authentication();

/////////////////////////////////////////////////////
///////////////// Record System List ////////////////
/////////////////////////////////////////////////////

if ($_POST['method'] == "showModalLazadaOrder") {

  $modal = '
            <div class="modal fade modal-animate" id="uploadLazadaOrder" data-bs-backdrop="static" tabindex="-1" aria-hidden="true"> 
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Lazada Order </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModalUploadLazadaOrder()"></button>
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
                      <label class="form-label text-start">Excel File</label>
                      <div class="input-group">
                        <input type="file" id="excel" class="form-control" accept=".xlsx, .xls, .csv">
                        <label class="input-group-text" for="excel"><i class="fab fa-google-drive me-2"></i> Upload</label>
                      </div>
                      <small class="form-text">File type [.csv], [.xlsx], [.xls] only</small>
                      <small class="form-text" id="errorExcel" style="display: none;"></small>
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
                      <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="closeModalUploadLazadaOrder()" id="close">Close</button>
                      <button type="button" class="btn btn-outline-primary" onclick="uploadExcel()" id="add">Upload Now</button>
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

if ($_POST['method'] == "uploadExcel") {
  $fileTmpPath = $_FILES['excel']['tmp_name'];
  $fileName = $_FILES['excel']['name'];
  $fileSize = $_FILES['excel']['size'];
  $fileType = $_FILES['excel']['type'];
  $fileNameCmps = explode(".", $fileName);
  $fileExtension = strtolower(end($fileNameCmps));

  $allowedfileExtensions = ['xlsx', 'xls', 'csv'];
  $allowedMimeTypes = [
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
      'application/vnd.ms-excel', // .xls
      'text/csv' // .csv
  ];

  $nameRandom = "Lazada_order_" . date('Y_m_d_H_i_s') . "_" . $addOn->generateRandomToken(5);

  $errormsg = [];

  if ($fileTmpPath == "") {
      $errormsg['errorExcel'] = '<i class="fa fa-times-circle"></i> File can\'t be blank.';
  } elseif (!in_array($fileType, $allowedMimeTypes) || !in_array($fileExtension, $allowedfileExtensions)) {
      $errormsg['errorExcel'] = '<i class="fa fa-times-circle"></i> File is not valid.';
  }

  if (!empty($errormsg)) {
      $status = ['status' => "false"];
      echo json_encode(array_merge($errormsg, $status));
      exit;
  }

  try {

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestColumn = $worksheet->getHighestColumn();
    $highestRow = $worksheet->getHighestRow();
    
    // Read headers
    $headers = $worksheet->rangeToArray('A1:' . $highestColumn . '1')[0];
    
    // Add member_id to headers
    $headers[] = 'member_id';
    
    // Prepare SQL statement
    $columnsString = implode(", ", array_map(function($header) {
        return "`" . strtolower($header) . "`";
    }, $headers));
    $placeholders = implode(", ", array_map(function($header) {
        return ":" . strtolower($header);
    }, $headers));
    
    $sql = "INSERT INTO order_lazada ($columnsString) VALUES ($placeholders)";
    $stmt = $db->prepare($sql);
    
    $dateTimeColumns = ['createtime', 'updatetime', 'rtssla', 'ttssla', 'delivereddate', 'promisedshippingtime', 'flexibledeliverytime'];

    for ($row = 2; $row <= $highestRow; $row++) {
      $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row)[0];
      $rowArray = array_combine(array_slice($headers, 0, -1), $rowData);
  
      // Add member_id to row data
      $rowArray['member_id'] = $_SESSION['user_info']['member_id'];
  
      // Bind values to the statement
      foreach ($rowArray as $key => $value) {
          if (in_array(strtolower($key), $dateTimeColumns)) {
              $date = DateTime::createFromFormat('d M Y H:i', $value);
              if ($date) {
                  $value = $date->format('Y-m-d H:i:s');
              }
          }
          $stmt->bindValue(':' . strtolower($key), $value);
      }
      
      $stmt->execute();
    }
    
    $status = ['status' => "success"];

  } catch (Exception $e) {
      $status = [
          'status' => "false",
          'message' => $e->getMessage(),
      ];
  }

  echo json_encode($status);
}

if ($_POST['method'] == "table_lazadaOrder") {

  $search = isset($_POST['search']) && $_POST['search'] !== "" ? $_POST['search'] : date("Y-m-d")." to ".date("Y-m-d");
  $searchDates = explode(' to ', $search);

  $startDate = $searchDates[0];
  $endDate = isset($searchDates[1]) && !empty($searchDates[1]) ? $searchDates[1] : $startDate;

  $sql = "SELECT * FROM `order_lazada` WHERE `createTime` BETWEEN :startDate AND :endDate
          AND `member_id` = :member_id ORDER BY orderItemId DESC";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
  $stmt->bindValue(":startDate", $startDate, PDO::PARAM_STR);
  $stmt->bindValue(":endDate", $endDate.' 23:59:59', PDO::PARAM_STR);
  $stmt->execute();
  $lazadaOrder = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $thead = '<thead>
              <tr>
                <th><input class="form-check-input input-light-primary" id="checkAll" type="checkbox" onclick="checkAll()"></th>
                <th>ID</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Customer Name</th>
                <th>Update Time</th>
                <th><i class="ti ti-trash f-18"></i></th>
              </tr>
            </thead>';

  $tbody = '<tbody>';
  foreach ($lazadaOrder as $result) {
    $tbody .= '<tr id="tr' . htmlspecialchars($result['orderItemId']) . '">
                  <td><input type="checkbox" class="form-check-input input-light-primary" name="checkItem[]" id="checkItem" value="' . htmlspecialchars($result['orderItemId']) . '"></td>
                  <td>' . htmlspecialchars($result['orderItemId']) . '</td>
                  <td>' . htmlspecialchars($result['sellerSku']) . '</td>
                  <td>' . htmlspecialchars($result['paidPrice']) . '</td>
                  <td>' . htmlspecialchars($result['customerName']) . '</td>
                  <td>' . htmlspecialchars($result['createTime']) . '</td>
                  <td><a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default" onclick="deleteOrderLazada(\''.$result['orderItemId'].'\')"><i class="ti ti-trash f-18"></i></a></td>
                </tr>';
  }
  $tbody .= '</tbody>';

  // Assuming $addOn->trLoading(7) returns some additional HTML rows to display while loading
  $tload = $addOn->trLoading(7);

  $table = [
    'thead' => $thead,
    'tbody' => $tbody, 
    'tload' => $tload,
  ];

  echo json_encode($table);
}



if ($_POST['method'] == "deleteOrderLazada") {

  $orderItemId = $_POST['id'];

  try {
      $sql = "DELETE FROM `order_lazada` WHERE `orderItemId` = :orderItemId AND `member_id` = :member_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":orderItemId", $orderItemId, PDO::PARAM_STR);
      $stmt->bindValue(":member_id", $_SESSION['user_info']['member_id'], PDO::PARAM_INT);
      $stmt->execute();
      $status = ['status' => "success",];
  } catch (Exception $e) {
      $status = ['status' => "error",];
  }

  echo json_encode($status);
}

?>
