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

        $sql = "SELECT am.*, ag.* AS customer_name
                FROM `auth_member` AS am
                JOIN `auth_google` AS ag ON `am.id` = `ag.member_id`
                WHERE ag.email = :
                ";
        
        $statement = $db->prepare($sql);
        $statement->bindParam(':username', $userInfo->getEmail(), PDO::PARAM_STR);
        $statement->execute();

        $sql_user = $statement->fetch(PDO::FETCH_ASSOC);
        
    
        /*
        
        
        $dataArray = [
            "token"  => $_SESSION['access_token']['access_token'], // เก็บเฉพาะ access token
            "email"         => $userInfo->getEmail(),
            "name"          => $userInfo->getname(),
            "picture"       => $userInfo->getPicture(),
            "verifiedEmail" => $userInfo->getVerifiedEmail(),
            "locale"        => $userInfo->getLocale(),
        ];
    
        $keysString = implode(", ", array_keys($dataArray));
        $valuesBindParam = ":" . implode(", :", array_keys($dataArray));
        $sql = "INSERT INTO auth_google ($keysString) VALUES ($valuesBindParam)";
        $stmt = $db->prepare($sql);
    
        foreach ($dataArray as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $Oauth = $stmt->execute(); */

        /* if ($Oauth) {
            echo '<script>window.close();</script>';
        } */
        
    }
?>


