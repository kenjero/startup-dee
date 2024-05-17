<?PHP
    require_once '../../functions/php.config.inc.php';
    require_once '../../functions/php.functions.php';
    require_once '../../vendor/autoload.php';

    $db = (new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST))->connect();
?>

<?PHP
    $client = new Google\Client();
    $client->setClientId(API_GOOGLE_CLIENT_ID);
    $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_CALLBACK_URL);
    $client->setScopes(['email', 'profile']);
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');

    if (!isset($_GET['code'])) {
        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        exit;
    } else {
        $access_token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['access_token'] = $access_token;
    
        if ($client->isAccessTokenExpired()) {
            if (isset($_SESSION['access_token']['refresh_token'])) {
                $refreshedToken = $client->fetchAccessTokenWithRefreshToken($_SESSION['access_token']['refresh_token']);
                $_SESSION['access_token'] = $refreshedToken;
            } else {
                echo "Refresh token not available.";
                exit;
            }
        }

        $client->setAccessToken($_SESSION['access_token']);
        $service = new Google\Service\Oauth2($client);
        $userInfo = $service->userinfo->get(); // ดึงข้อมูลผู้ใช้

        $getEmail         = $userInfo->getEmail();
        $getPicture       = $userInfo->getPicture();
        $getVerifiedEmail = $userInfo->getVerifiedEmail();
        $token            = $_SESSION['access_token']['access_token'];

        $sql = "SELECT * FROM `auth_google` WHERE `email` = :email"; 
        
        $statement = $db->prepare($sql);
        $statement->bindParam(':email', $getEmail, PDO::PARAM_STR);
        $statement->execute();

        $checkEmail = $statement->fetch(PDO::FETCH_ASSOC);

        if(!$checkEmail){
            $dataArray = [
                "token"         => $token,
                "email"         => $getEmail,
                "password"      => '',
                "picture"       => $getPicture,
                "verifiedEmail" => $getVerifiedEmail,
                
            ];

            $keysString = implode(", ", array_keys($dataArray));
            $valuesBindParam = ":" . implode(", :", array_keys($dataArray));
            $sql = "INSERT INTO auth_member ($keysString) VALUES ($valuesBindParam)";
            $stmt = $db->prepare($sql);

            foreach ($dataArray as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $authMember = $stmt->execute();
            $memberId = $db->lastInsertId();
            
            $dataArray = [
                "token"         => $token, // เก็บเฉพาะ access token
                "member_id"     => $memberId,
                "email"         => $getEmail,
                "name"          => $userInfo->getname(),
                "picture"       => $getPicture,
                "verifiedEmail" => $getVerifiedEmail,
                "locale"        => $userInfo->getLocale(),
            ];

            $keysString = implode(", ", array_keys($dataArray));
            $valuesBindParam = ":" . implode(", :", array_keys($dataArray));
            $sql = "INSERT INTO auth_google ($keysString) VALUES ($valuesBindParam)";
            $stmt = $db->prepare($sql);

            foreach ($dataArray as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            $authGoogle = $stmt->execute();

        }else{
            // อัปเดต token ในตาราง auth_google
            $sqlAuthGoogle = "UPDATE `auth_google` SET `token` = :token WHERE `email` = :email";
            $stmtGoogle = $db->prepare($sqlAuthGoogle);
            $stmtGoogle->bindParam(':token', $token, PDO::PARAM_STR);
            $stmtGoogle->bindParam(':email', $getEmail, PDO::PARAM_STR);
            $stmtGoogle->execute();

            // อัปเดต token และ status ในตาราง auth_member
            $sqlAuthMember = "UPDATE `auth_member` SET `token` = :token, `status` = :status WHERE `email` = :email";
            $stmtMember = $db->prepare($sqlAuthMember);
            $stmtMember->bindParam(':token', $token, PDO::PARAM_STR);
            $status = 1;
            $stmtMember->bindParam(':status', $status, PDO::PARAM_INT);
            $stmtMember->bindParam(':email', $getEmail, PDO::PARAM_STR);
            $stmtMember->execute();
        }
        $sql = "SELECT am.* , ag.id AS google_id
                FROM `auth_member` AS am
                JOIN `auth_google` AS ag ON `am`.`id` = `ag`.`member_id`
                WHERE `am`.`email` = :email AND `am`.`password` = ''";

        $statement = $db->prepare($sql);
        $statement->bindParam(':email', $getEmail, PDO::PARAM_STR);
        $statement->execute();

        $sql_user = $statement->fetch(PDO::FETCH_ASSOC);
        unset($_SESSION['user_info']);

        if ($sql_user) {
            $_SESSION['user_info']  =   [
                                            'logged_in' => session_id(),
                                            'token'     => $sql_user['token'],
                                            'email'     => $sql_user['email'],
                                            'member_id' => $sql_user['id'],
                                            'picture'   => $sql_user['picture'],
                                            'name'      => $sql_user['name'],
                                            'google_id' => $sql_user['google_id'],
                                        ];
        }

        echo '<script>window.close();</script>';

    }
?>


