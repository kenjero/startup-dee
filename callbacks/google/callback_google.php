<?PHP
    require_once '../../functions/php.config.inc.php';
    require_once '../../functions/php.functions.php';
    require_once '../../vendor/autoload.php';

    $db = (new Database())->connect();
?>

<?PHP
    $client = new Google\Client();
    $client->setClientId(API_GOOGLE_CLIENT_ID);
    $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(API_GOOGLE_CALLBACK_URL);
    $client->setScopes(['email', 'profile']);
    $client->addScope("https://www.googleapis.com/auth/drive");
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');

    if (!isset($_GET['code'])) {
        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        exit;
    } else {

        $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['accessToken'] = $accessToken;
        $setAccessToken = $_SESSION['accessToken']['access_token'];
    
        if ($client->isAccessTokenExpired()) {
            if (isset($_SESSION['accessToken']['refresh_token'])) {
                $_SESSION['accessToken'] = $client->fetchAccessTokenWithRefreshToken($_SESSION['accessToken']['refresh_token']);
            } else {
                echo "Refresh token not available.";
                exit;
            }
        }
        $code_verifier = $client->getOAuth2Service()->generateCodeVerifier();

        $client->setAccessToken($setAccessToken);
        $service = new Google\Service\Oauth2($client);
        $userInfo = $service->userinfo->get(); // ดึงข้อมูลผู้ใช้

        $getEmail         = $userInfo->getEmail();
        $getPicture       = $userInfo->getPicture();
        $getVerifiedEmail = $userInfo->getVerifiedEmail();
        $getName          = $userInfo->getname();
        $getLocale        = $userInfo->getLocale();

        $sql = "SELECT * FROM `auth_google` WHERE `email` = :email"; 
        
        $statement = $db->prepare($sql);
        $statement->bindParam(':email', $getEmail, PDO::PARAM_STR);
        $statement->execute();

        $checkEmail = $statement->fetch(PDO::FETCH_ASSOC);

        if(!$checkEmail){
            $dataArray = [
                "type"          => "GG",
                "token"         => $accessToken['access_token'],
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
                "member_id"     => $memberId,
                "email"         => $getEmail,
                "name"          => $getName,
                "picture"       => $getPicture,
                "verifiedEmail" => $getVerifiedEmail,
                "locale"        => $getLocale,
                "access_token"  => $accessToken['access_token'],
                "expires_in"    => $accessToken['expires_in'],
                "refresh_token" => $accessToken['refresh_token'],
                "scope"         => $accessToken['scope'],
                "token_type"    => $accessToken['token_type'],
                "id_token"      => $accessToken['id_token'],
                "created"       => $accessToken['created'],
                "code_verifier" => $code_verifier,
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
            // อัปเดต token และ status ในตาราง auth_member
            $sqlAuthMember = "UPDATE `auth_member` SET `token` = :token, `online` = :online WHERE `email` = :email  AND `type` = :type ";
            $stmtMember = $db->prepare($sqlAuthMember);
            $stmtMember->bindValue(':token' , $accessToken['access_token']  , PDO::PARAM_STR);
            $stmtMember->bindValue(':type'  , "GG"                          , PDO::PARAM_STR);
            $stmtMember->bindValue(':online', 1                             , PDO::PARAM_INT);
            $stmtMember->bindValue(':email' , $getEmail                     , PDO::PARAM_STR);
            $stmtMember->execute();
        }
        $sql = "SELECT am.* , `ag`.`id` AS google_id
                FROM `auth_member` AS am
                JOIN `auth_google` AS ag ON `am`.`id` = `ag`.`member_id`
                WHERE `am`.`email` = :email";

        $statement = $db->prepare($sql);
        $statement->bindValue(':email', $getEmail, PDO::PARAM_STR);
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
                                            'type'      => $sql_user['type'],
                                        ];
        }

        echo '<script>window.close();</script>';

    }
?>


