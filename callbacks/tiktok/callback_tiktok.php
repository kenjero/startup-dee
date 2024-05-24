<?PHP
    require_once '../../functions/php.config.inc.php';
    require_once '../../functions/php.functions.php';
    require_once '../../vendor/autoload.php';

    $db = (new Database())->connect();
    use GuzzleHttp\Client;

    function getAccessToken($clientId, $clientSecret, $code, $redirectUri) {
        $client = new Client();
    
        $response = $client->request('POST', 'https://open-api.tiktok.com/oauth/access_token', [
            'form_params' => [
                'client_key'    => $clientId,
                'client_secret' => $clientSecret,
                'code'          => $code,
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => $redirectUri
            ]
        ]);
    
        $data = json_decode($response->getBody(), true);
        return $data;
    }

    // Parameters from your app settings
    $clientId     = API_TIKTOK_CLIENT_KEY;
    $clientSecret = API_TIKTOK_CLIENT_SECRET;
    $redirectUri  = API_TIKTOK_CALLBACK_URL;

    // Authorization code from TikTok OAuth
    $code = $_GET['code'];
    
    $tokenData = getAccessToken($clientId, $clientSecret, $code, $redirectUri);
    
    if (isset($tokenData['data']['access_token'])) {
        $accessToken = $tokenData['data']['access_token'];
        echo "Access Token: " . $accessToken;
    } else {
        echo "Failed to get access token.";
    }
    
    /* echo '<script>window.close();</script>'; */

?>


