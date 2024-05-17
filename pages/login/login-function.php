<?php
require_once '../../functions/php.config.inc.php';
require_once '../../functions/php.functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once '../../vendor/autoload.php';

$db    = (new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST))->connect();

$addOn = new AddOn();
$auth = new Authentication();

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



// ====================================== //
// === Ajax Function - Create Account === //
// ====================================== //

if ($_POST['method'] == "changeCreateAccount") {

    $loginHeader = 'Create Account';

    $loginBody =   '
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-text">✉️</span>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text">📨</span>
                                <input type="text" class="form-control" name="otp" id="otp" placeholder="OTP">
                                <button class="btn btn-dark" onclick="requestOTP()" id="requestOTP">Request</button>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text">🔑</span>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                <button class="input-group-text" onclick="togglePassword()">
                                    <i class="ti ti-eye-off" id="passwordIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text">🔑</span>
                                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password">
                                <button class="input-group-text" onclick="toggleConfirmPassword()">
                                    <i class="ti ti-eye-off" id="confirmPasswordIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary" name="loggedin" id="loggedin" value="loggedin" onclick="postLogin()">Sign up</button>
                        </div>
                    ';

    $loginFooter =  ' 
                        <h6 class="f-w-500 mb-0">Already have an account?</h6>
                        <a href="#" class="link-primary" onclick="changeCreateAccount()">Sign in</a>
                    ';

    $result = [
        'loginHeader' => $loginHeader,
        'loginBody'   => $loginBody,
        'loginFooter' => $loginFooter,
    ];

    echo json_encode($result);
    exit;
}


if ($_POST['method'] == "sandOTP") {

    $email = $_POST['email'];

    $emailRegiter = $auth->check_EmailRegiter($email);

    if ($emailRegiter === null || empty($emailRegiter)) {
       
        $otp = $addOn->generateRandomStringUpper(6);

        $dataArray = [
            "email"         => $email,
            "otp"           => $otp,
            "verifiedEmail" => 0,
        ];

        $keysString = implode(", ", array_keys($dataArray));
        $valuesBindParam = ":" . implode(", :", array_keys($dataArray));
        $sql = "INSERT INTO auth_member ($keysString) VALUES ($valuesBindParam)";
        $stmt = $db->prepare($sql);

        foreach ($dataArray as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $authMember = $stmt->execute();

        try {
            // การตั้งค่าผู้ส่ง
            $mail = new PHPMailer();
            $mail->isSMTP();                                      // ตั้งค่าให้ใช้ SMTP
            $mail->SMTPDebug  = SMTP::DEBUG_SERVER;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Host       = MAIL_SMTP_SERVER;                 // กำหนด SMTP server
            $mail->SMTPAuth   = true;                             // เปิดการตรวจสอบ SMTP
            $mail->Username   = MAIL_USERNAME;                    // SMTP username
            $mail->Password   = MAIL_PASSWORD;                    // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      // เปิดการเข้ารหัส TLS; ใช้ `PHPMailer::ENCRYPTION_SMTPS` สำหรับ SSL
            $mail->Port       = MAIL_PORT_TCP;                    // พอร์ต TCP สำหรับการเชื่อมต่อ
        
            // ผู้รับ
            $mail->setFrom(MAIL_USERNAME, 'Mailer');
            $mail->addAddress($email, 'Recipient Name');
            $mail->addReplyTo(MAIL_USERNAME, 'REGITER BY EMAIL : '.$email);
        
            // เนื้อหาอีเมล
            $mail->isHTML(true);                                   // กำหนดให้ส่งเป็นอีเมล HTML
            $mail->Subject = 'Your OTP Code By Startup Dee';
            $mail->Body = "Your OTP code is <b>$otp</b>. Please use this code to verify your account.";
            /* $mail->msgHTML(file_get_contents('contents.html'), __DIR__); */
            $mail->AltBody = "Your OTP code is $otp. Please use this code to verify your account.";

            $mail->send();

            $result =   [
                            'status'    => "success",
                        ];

        } catch (Exception $e) {
            $result =   [
                'status'    => "failed",
            ];
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $result =   [
            'status'    => "repeatedly",
        ];
    }

    echo json_encode($result);
    exit;

}

?>