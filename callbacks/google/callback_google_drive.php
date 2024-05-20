<?PHP
    require_once '../../functions/php.config.inc.php';
    require_once '../../functions/php.functions.php';
    require_once '../../vendor/autoload.php';

    $db    = (new Database())->connect();
    $addOn = new AddOn();
    $auth  = new Authentication();

    $client = new Google\Client();
    $client->setClientId(API_GOOGLE_CLIENT_ID);
    $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(API_GOOGLE_DRIVE_CALLBACK_URL);
    $client->setScopes(['email', 'profile']);
    $client->addScope("https://www.googleapis.com/auth/drive");
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
    $service = new Google\Service\Drive($client);

    if (!isset($_GET['code'])) {
        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        exit;
    }

    $memberId = $_SESSION['user_info']['member_id'];

    $check_auth_google = $auth->check_EmailGoogleAPI($memberId);

    if (empty($check_auth_google)) {
    
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

        $client->setAccessToken($setAccessToken);
        $service = new Google\Service\Oauth2($client);
        $userInfo = $service->userinfo->get(); // ดึงข้อมูลผู้ใช้

        $getEmail         = $userInfo->getEmail();
        $getPicture       = $userInfo->getPicture();
        $getVerifiedEmail = $userInfo->getVerifiedEmail();
        $getName          = $userInfo->getname();
        $getLocale        = $userInfo->getLocale();
        $code_verifier    = $client->getOAuth2Service()->generateCodeVerifier();

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

    }

    // Retrieve token from the database
    $memberId = $_SESSION['user_info']['member_id'];
    $sql = "SELECT * FROM `auth_google` WHERE `member_id` = :member_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":member_id", $memberId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        unset($_SESSION['id_token_token_drive']);
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
            unset($_SESSION['id_token_token_drive']);
        }
    }

    if ($client->getAccessToken()) {
        $_SESSION['id_token_token_drive'] = $client->getAccessToken();
        $_SESSION['code_verifier_drive']  = $client->getOAuth2Service()->generateCodeVerifier();
    }

    /* if (!empty($_SESSION['id_token_token_drive'])) {
        $client->setAccessToken($_SESSION['id_token_token_drive']);
        
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $_SESSION['id_token_token_drive'] = $client->getAccessToken();
            } else {
                unset($_SESSION['id_token_token_drive']);
            }
        }
    } else {
        $_SESSION['code_verifier_drive'] = $client->getOAuth2Service()->generateCodeVerifier();
        $authUrl = $client->createAuthUrl();
        header('Location: ' . $authUrl);
        exit();
    } */

  /*   echo json_encode($_SESSION['id_token_token_drive']) . "<br><br><br>";
    echo $_SESSION['code_verifier_drive'] . "<br><br><br>";

    try {
        $files = $service->files->listFiles(array())->getFiles();
        echo "Connection to Google Drive was successful. Here are the files:<br>";
        foreach ($files as $file) {
            echo $file->getName() . "<br>";
        }
    } catch (Exception $e) {
        echo 'Failed to connect to Google Drive: ' . $e->getMessage();
    }  */

  echo '<script>window.close();</script>';
?>