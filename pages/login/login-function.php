<?php
require_once '../../functions/php.config.inc.php';
require_once '../../functions/php.functions.php';

$db    = (new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST))->connect();

// ====================================== //
// ==== Ajax Function - login system ==== //
// ====================================== //

if ($_POST['method'] == "authenticate") { 

    $resul = array();

    if (!isset($_POST['username']) && !isset($_POST['password'])) {
        
        $postData = array(
          'username'  => htmlspecialchars($_POST['username']),
          'password'  => htmlspecialchars($_POST['password']),
        );

        $sql = "SELECT * FROM `auth_member` WHERE `username` = :username";

        $statement = $db->prepare($sql);
        $statement->bindParam(':username', $postData['username'], PDO::PARAM_STR);
        $statement->execute();

        $sql_user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($sql_user && password_verify($postData['password'], $sql_user['password'])) {
            $result = [
                        'status' => "success",
                        'message' => "Login success",
                        'result' => [
                            'user_id'   => $sql_user['id'],
                            'username'  => $sql_user['username'],
                            'position'  => $sql_user['position'],
                        ]
                    ];

            $_SESSION['logged_in'] = session_id();
            $_SESSION['position']  = $login_system['user_info']['position'];
            $_SESSION['username']  = $login_system['user_info']['username'];
            $_SESSION['user_id']   = $login_system['user_info']['user_id'];
            
        } else {
            $result = [
                        'status'  => "failed",
                        'message' => "Invalid password",
                    ];
        }
    } else {
        if (isset($_POST['username'])) {
            $result = [
                'status'    => "failed",
                'message'   => "The username cannot be empty.",
            ];
        }
        else if (isset($_POST['password'])) {
            $result = [
                'status'    => "failed",
                'message' => "The password cannot be empty.",
            ];
        }
        else {
            $result = [
                'status'    => "failed",
                'message' => "The username & password cannot be empty.",
            ];
        }
    }

    echo json_encode($result);
    exit;
}

?>