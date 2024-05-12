<?php
ob_start();
session_start();

require_once '../funtions_emicon/config.inc.php';
require_once '../funtions_emicon/functions.php';

date_default_timezone_set('Asia/Bangkok');

$db = new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST);

header('Content-type: application/json; charset=utf-8');

///////// Test Api /////////
if (isset($_POST['metode']) && $_POST['metode'] == "TestAPI") {

  $Data_System = [
      'status' => 'success',
  ];

  echo json_encode($Data_System);
  exit();
}


///////// Loggedin Api /////////
if (isset($_POST['metode']) && $_POST['metode'] == "loggedinApi") {
  // Access the posted data

    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $postData = array(
            'username'  => htmlspecialchars($_POST['username']),
            'password'  => htmlspecialchars($_POST['password']),
        );

        $jsonData = json_encode($postData);
        $Data_System = $db->login_system_API($jsonData);

        // Return JSON response
        echo json_encode($Data_System);
        exit(); // Terminate the script after sending the response
    }
}

///////// QRcodeCreateApi /////////
if (isset($_POST['metode']) && $_POST['metode'] == "QRcodeCreateApi") {
    // Access the posted data
    if (!empty($_POST['Qrcode_Start']) && !empty($_POST['Qrcode_End']) && !empty($_POST['productID'])) {
        $postData = array(
            'Qrcode_Start' => htmlspecialchars($_POST['Qrcode_Start']),
            'Qrcode_End'   => htmlspecialchars($_POST['Qrcode_End']),
            'productID'    => htmlspecialchars($_POST['productID']),
        );

        $jsonData = json_encode($postData);

        // Assuming $db is an instance of your database class
        $Data_System = $db->Qrcode_Create_system_API($jsonData);

        // Return JSON response
        echo json_encode($Data_System);
        exit(); // Terminate the script after sending the response
    }
}

///////// QRcodeCreateApi /////////
if (isset($_POST['metode']) && $_POST['metode'] == "ListProductAPI") {

    $Data_System = $db->List_Product_API();
    echo json_encode($Data_System);
    exit(); // Terminate the script after sending the response
}

$responseData = [
    'status' => 'error',
    'message' => 'The information is correct.'
];
$jsonSystem = json_encode($responseData, JSON_PRETTY_PRINT);
echo $jsonSystem;
