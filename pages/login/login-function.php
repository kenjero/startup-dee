<?php
require_once '../../functions/php.config.inc.php';
require_once '../../functions/php.functions.php';
require_once '../../vendor/autoload.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail  = new PHPMailer(true);
$db    = (new Database())->connect();
$addOn = new AddOn();
$auth  = new Authentication();

// ====================================== //
// ==== Ajax Function - login system ==== //
// ====================================== //

if ($_POST['method'] == "changeLoginAccount") {

    $loginHeader = 'Loging Account';

    $loginBody =   '
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-text">✉️</span>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
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
                        <div class="d-grid mt-3 mb-2">
                                <button type="button" class="btn btn-primary" name="loggedin" id="loggedin" value="loggedin" onclick="postLogin()">Login</button>
                        </div>
                        <a href="#" class="link-primary" onclick="changeForgotPassword()">Forgot your password?</a> 
                    ';

    $loginFooter =  ' 
                        <h6 class="f-w-500 mb-0">Don&apos;t have an Account?</h6>
                        <a href="#" class="link-primary" onclick="changeCreateAccount()">Create Account</a>
                    ';

    $result = [
        'loginHeader' => $loginHeader,
        'loginBody'   => $loginBody,
        'loginFooter' => $loginFooter,
    ];

    echo json_encode($result);
    exit;
}


if ($_POST['method'] == "postLogin") {

    if (!empty($_POST['email']) && !empty($_POST['password'])) {

        $email    = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        $sql = "SELECT * FROM `auth_member` WHERE `email` = :email AND `type` = :type ";

        $statement = $db->prepare($sql);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':type', "RG", PDO::PARAM_STR);
        $statement->execute();
        $auth_member = $statement->fetch(PDO::FETCH_ASSOC);

        if ($auth_member && password_verify($password, $auth_member['password'])) {
            $result = ['status' => "success"];

            $token = $addOn->generateRandomToken(363);

            $sqlAuthMember = "UPDATE `auth_member` SET `token` = :token, `online` = :online WHERE `email` = :email AND `type` = :type ";
            $stmtMember = $db->prepare($sqlAuthMember);
            $stmtMember->bindValue(':token'  , $token , PDO::PARAM_STR);
            $stmtMember->bindValue(':online' , 1      , PDO::PARAM_INT);
            $stmtMember->bindValue(':type'   , "RG"   , PDO::PARAM_STR);
            $stmtMember->bindValue(':email'  , $email , PDO::PARAM_STR);
            $stmtMember->execute();

            $_SESSION['user_info']  =   [
                                            'logged_in' => session_id(),
                                            'token'     => $token,
                                            'email'     => $auth_member['email'],
                                            'member_id' => $auth_member['id'],
                                            'picture'   => $auth_member['picture'],
                                            'name'      => $auth_member['name'],
                                            'type'      => $auth_member['type'],
                                        ];
        }else{
            $result = [
                'status' => "failed",
                'message' => "Login failed.",
            ];
        }
    } else {
        if (empty($_POST['email'])) {
            $result = [
                'status'    => "failed",
                'message'   => "The email cannot be empty.",
            ];
        }
        else if (empty($_POST['password'])) {
            $result = [
                'status'    => "failed",
                'message' => "The password cannot be empty.",
            ];
        }
        else {
            $result = [
                'status'    => "failed",
                'message' => "The email & password cannot be empty.",
            ];
        }
    }

    echo json_encode($result);
}

// ====================================== //
// ====== Forgot & Change Password ====== //
// ====================================== //

if ($_POST['method'] == "changeForgotPassword") {

    $loginHeader = 'Forgot Password';

    $loginBody =   '
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-text">✉️</span>
                                <input type="email" class="form-control" name="email" id="email" oninput="checkEmailForgotPassword()" placeholder="Email">
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary" name="forgotPassword" id="forgotPassword" value="forgotPassword" onclick="postForgotPasswordt()">Forgot Password</button>
                        </div>
                    ';

    $loginFooter =  ' 
                        <h6 class="f-w-500 mb-0">Return to login page?</h6>
                        <a href="#" class="link-primary" onclick="changeLoginAccount()">Sign in</a>
                    ';

    $result = [
        'loginHeader' => $loginHeader,
        'loginBody'   => $loginBody,
        'loginFooter' => $loginFooter,
    ];

    echo json_encode($result);
    exit;
}

if ($_POST['method'] == "checkEmailForgotPassword") {

    $email = $_POST['email'];

    $verifiedEmail = $auth->check_EmailRegiter($email,1);

    if ($verifiedEmail === null || empty($verifiedEmail)) {
        $result = ['status' => "invalid"];
    } else {
        $result = ['status' => "valid"];
    }

    echo json_encode($result);
}

if ($_POST['method'] == "postForgotPasswordt") {

    $email = $_POST['email'];

    if ($auth->canSendEmail($email) === false) {
        echo json_encode(['status' => 'too_frequent']);
        exit;
    }

    $verifiedEmail = $auth->check_EmailRegiter($email,1);

    if ($verifiedEmail === null || empty($verifiedEmail)) {
        $result = ['status' => "missing"];
        exit;
    }

    $ccp = $addOn->generateRandomStringLowerUpper(15);

    $dataArray = [
        "codeChangPassword" => $ccp,
    ];

    $setString = implode(", ", array_map(function($key) {
        return "$key = :$key";
    }, array_keys($dataArray)));

    $sql = "UPDATE `auth_member` SET $setString WHERE `email` = :email AND `type` = :type";
    $stmt = $db->prepare($sql);

    foreach ($dataArray as $key => $value) {
        $stmt->bindParam(":$key", $dataArray[$key]);
    }

    $stmt->bindValue(":email" , $email , PDO::PARAM_STR);
    $stmt->bindValue(":type"  , "RG"   , PDO::PARAM_STR);

    $authMember = $stmt->execute();

    // การตั้งค่าผู้ส่ง - Server settings
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = EMAIL_SMTP_SERVER;                      // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = EMAIL_USERNAME;                         // SMTP username (your Gmail address)
    $mail->Password   = EMAIL_PASSWORD;                         // SMTP password (your Gmail password or App Password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = EMAIL_PORT_TCP;                         // TCP port to connect to (TLS port)

    // Recipients
    $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);        // Set the sender's email address and name
    $mail->addAddress($email, 'Recipient Name');                // Add a recipient (name is optional)

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = 'Password Change Request By Startup Dee';

    $callback    = HOST_NAME_URL.'login.php?email='.$email.'&'.'code='.$ccp;
    $mail->Body  = '<h2>Password Change Request</h2>
                    <p>We received a request to reset your password. Please click the button below to change your password:</p>
                    <a href="' . $callback . '" type="button" target="_blank">Change Password</a>
                    <p>If you did not request a password change, please ignore this email.</p>
                    <p>Thank you!</p>';
                    
    $mail->AltBody = '<p>We received a request to reset your password. Please click the button below to change your password:</p>
                        <a href="' . $callback . '" target="_blank">Change Password</a>';

    // Send the email
    if ($mail->send()) {
        $auth->updateLastSentTime($email);
        $result =   ['status' => "success"];
    } else {
        $result =   ['status' => "failed"];
    }

    echo json_encode($result);
    exit;

}

// ====================================== //
// === Ajax Function - Reset Password === //
// ====================================== //

if ($_POST['method'] == "changeResetPassword") {

    $loginHeader = 'Reset Password';

    $loginBody =   '
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text">🔑</span>
                                <input type="password" class="form-control" name="password" id="password" oninput="validatePassword()" placeholder="Password">
                                <button class="input-group-text" onclick="togglePassword()">
                                    <i class="ti ti-eye-off" id="passwordIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text">🔑</span>
                                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" oninput="validateConfirmPassword()" placeholder="Confirm Password">
                                <button class="input-group-text" onclick="toggleConfirmPassword()">
                                    <i class="ti ti-eye-off" id="confirmPasswordIcon"></i>
                                </button>
                                <small class="mt-2">Least 6 characters, contain uppercase and lowercase letters, a digit.</small>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary" name="resetPassword" id="resetPassword" value="resetPassword" onclick="postResetPassword()">Reset Password</button>
                        </div>
                    ';

    $loginFooter =  ' 
                        <h6 class="f-w-500 mb-0">Return to login page?</h6>
                        <a href="#" class="link-primary" onclick="changeLoginAccount()">Sign in</a>
                    ';

    $result = [
        'loginHeader' => $loginHeader,
        'loginBody'   => $loginBody,
        'loginFooter' => $loginFooter,
    ];

    echo json_encode($result);
    exit;
}

if ($_POST['method'] == "postResetPassword") {

    $email           = $_POST['email'];
    $code            = $_POST['code'];
    $password        = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (isset($email) && isset($code) && $auth->check_ResetPassword($email,$code)) {

        $dataArray = [
            "codeChangPassword" => '',
            'password'          => password_hash($password, PASSWORD_DEFAULT),
            'last_sent_time'    => NULL,
        ];

        $setString = implode(", ", array_map(function($key) {
            return "$key = :$key";
        }, array_keys($dataArray)));

        $sql = "UPDATE `auth_member` SET $setString WHERE `email` = :email AND `type` = :type AND `codeChangPassword` = :code";
        $stmt = $db->prepare($sql);

        foreach ($dataArray as $key => $value) {
            $stmt->bindParam(":$key", $dataArray[$key]);
        }
        $stmt->bindValue(":email" , $email , PDO::PARAM_STR);
        $stmt->bindValue(":code"  , $code  , PDO::PARAM_STR);
        $stmt->bindValue(":type"  , "RG"   , PDO::PARAM_STR);
        $resetPassword = $stmt->execute();

        if($resetPassword) {
            $result =   ['status' => "success"];
        } else {
            $result =   ['status' => "error"];
        }

    } else {
         $result =   ['status' => "failed"];
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
                                <input type="email" class="form-control" name="email" id="email" oninput="checkEmail()" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text">📨</span>
                                <input type="text" class="form-control" name="otp" id="otp" oninput="checkOTP()" placeholder="OTP" disabled>
                                <button class="btn btn-dark" onclick="requestOTP()" id="btnOTP">Send OTP</button>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text">🔑</span>
                                <input type="password" class="form-control" name="password" id="password" oninput="validatePassword()" placeholder="Password">
                                <button class="input-group-text" onclick="togglePassword()">
                                    <i class="ti ti-eye-off" id="passwordIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text">🔑</span>
                                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" oninput="validateConfirmPassword()" placeholder="Confirm Password">
                                <button class="input-group-text" onclick="toggleConfirmPassword()">
                                    <i class="ti ti-eye-off" id="confirmPasswordIcon"></i>
                                </button>
                                <small class="mt-2">Least 6 characters, contain uppercase and lowercase letters, a digit.</small>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary" name="register" id="register" value="register" onclick="postRegister()">Sign up</button>
                        </div>
                    ';

    $loginFooter =  ' 
                        <h6 class="f-w-500 mb-0">Already have an account?</h6>
                        <a href="#" class="link-primary" onclick="changeLoginAccount()">Sign in</a>
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

    if ($auth->canSendEmail($email) === false) {
        echo json_encode(['status' => 'too_frequent']);
        exit;
    }

    $verifiedEmail = $auth->check_EmailRegiter($email,1);

    if ($verifiedEmail === null || empty($verifiedEmail)) {

        $notVerifiedEmail = $auth->check_EmailRegiter($email,0);
        $otp = $addOn->generateRandomStringUpper(6);

        if ($notVerifiedEmail === null || empty($notVerifiedEmail)) {

            $dataArray = [
                "email"         => $email,
                "otp"           => $otp,
                "verifiedEmail" => 0,
                "type"          => "RG",
            ];
            
            $keysString = implode(", ", array_keys($dataArray));
            $valuesBindParam = ":" . implode(", :", array_keys($dataArray));
            $sql = "INSERT INTO `auth_member` ($keysString) VALUES ($valuesBindParam)";
            $stmt = $db->prepare($sql);

            foreach ($dataArray as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

        } else {

            $dataArray = [
                "otp"           => $otp,
                "verifiedEmail" => 0,
            ];

            $setString = implode(", ", array_map(function($key) {
                return "$key = :$key";
            }, array_keys($dataArray)));
    
            $sql = "UPDATE `auth_member` SET $setString WHERE `email` = :email AND `type` = :type";
            $stmt = $db->prepare($sql);
    
            foreach ($dataArray as $key => $value) {
                $stmt->bindParam(":$key", $dataArray[$key]);
            }
            
            $stmt->bindValue(":email" , $email , PDO::PARAM_STR);
            $stmt->bindValue(":type"  , "RG"   , PDO::PARAM_STR);

        }

        $authMember = $stmt->execute();

        // การตั้งค่าผู้ส่ง - Server settings
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = EMAIL_SMTP_SERVER;                      // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = EMAIL_USERNAME;                         // SMTP username (your Gmail address)
        $mail->Password   = EMAIL_PASSWORD;                         // SMTP password (your Gmail password or App Password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = EMAIL_PORT_TCP;                         // TCP port to connect to (TLS port)

        // Recipients
        $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);        // Set the sender's email address and name
        $mail->addAddress($email, 'Recipient Name');                // Add a recipient (name is optional)

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Your OTP Code By Startup Dee';
        $mail->Body    = 'Your OTP code is <b>' . $otp . '</b>. Please use this code to verify your account.';
        $mail->AltBody = 'Your OTP code is ' . $otp . '. Please use this code to verify your account.';

        // Send the email
        if ($mail->send()) {
            $auth->updateLastSentTime($email);
            $result =   ['status' => "success"];
        } else {
            $result =   ['status' => "failed"];
        }

    } else {
        $result = ['status' => "repeatedly"];
    }

    echo json_encode($result);
    exit;

}


if ($_POST['method'] == "checkEmail") {

    $email = $_POST['email'];

    $verifiedEmail = $auth->check_EmailRegiter($email,1);

    if ($verifiedEmail === null || empty($verifiedEmail)) {
        $result = ['status' => "valid"];
    } else {
        $result = ['status' => "invalid"];
    }

    echo json_encode($result);
}


if ($_POST['method'] == "checkOTP") {

    $dataArray = [
        'email' => $_POST['email'],
        'otp'   => $_POST['otp'],
    ];
    $checkOTP = $auth->check_OTP($dataArray);

    if ($checkOTP === null || empty($checkOTP)) {
        $result = ['status' => "invalid"];
    } else {
        $result = ['status' => "valid"];
    }

    echo json_encode($result);
}

if ($_POST['method'] == "postRegister") {

    $email    = $_POST['email'];
    $otp      = $_POST['otp'];
    $password = $_POST['password'];

    $verifiedEmail = $auth->check_EmailRegiter($email, 1);

    $dataArrayOTP = [
        'email' => $email,
        'otp'   => $otp,
    ];

    $checkOTP = $auth->check_OTP($dataArrayOTP);

    if ($checkOTP !== null && $verifiedEmail === null ) {
        $dataArray = [
            'otp'            => $otp,
            'password'       => password_hash($password, PASSWORD_DEFAULT),
            'verifiedEmail'  => 1,
            'picture'        => 'assets/images/user/no-picture.jpg',
            'OTP'            => '', 
            'last_sent_time' => NULL,
        ];

        $setString = implode(", ", array_map(function($key) {
            return "$key = :$key";
        }, array_keys($dataArray)));

        $sql = "UPDATE `auth_member` SET $setString WHERE `email` = :email AND `type` = :type";
        $stmt = $db->prepare($sql);

        foreach ($dataArray as $key => $value) {
            $stmt->bindParam(":$key", $dataArray[$key]);
        }

        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":type", "RG", PDO::PARAM_STR);

        $update = $stmt->execute();

        if ($update) {
            $result =   ['status' => "success"];
        }else{
            $result =   ['status' => "error"];
        }
    }else{
        $result =   ['status' => "failed"];
    }

    echo json_encode($result);
}

?>