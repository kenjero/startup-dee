<?PHP
  ob_start();
  session_start();

  require_once '../functions_emicon/config.inc.php';
  require_once '../functions_emicon/functions.php';
  require_once '../functions_emicon/vendor/autoload.php';
?>

<?PHP
if ($_GET['state'] == 'google_login') {
    $client = new Google\Client();
    $client->setClientId(API_GOOGLE_CLIENT_ID);
    $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(REDIRECT_URL);

    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code'], $_SESSION['google_code_verifier']);
        $client->setAccessToken($token);
        $_SESSION['id_google_token'] = $token;
        header('Location: ' . filter_var(REDIRECT_URL, FILTER_SANITIZE_URL));
    }

    if (!empty($_SESSION['id_google_token'])) {
        $client->setAccessToken($_SESSION['id_token_token']);
        if ($client->isAccessTokenExpired()) {
            unset($_SESSION['id_google_token']);
        }
    } else {
        $_SESSION['google_code_verifier'] = $client->getOAuth2Service()->generateCodeVerifier();
        $authUrl = $client->createAuthUrl();
        
    }
    if ($client->getAccessToken()) {
        $_SESSION['Profile_Google'] = $client->verifyIdToken();
    }
}
?>


<?PHP

use TyperEJ\LineLogin\Login;

if ($_GET['state'] == 'line_login') {

    $login = new Login(LINE_CHANNEL_ID, LINE_CHANNEL_SECRET); 
    $_SESSION['Profile_Line'] = $user = $login->requestToken($_GET['code']);
}
?>

<script>window.close();</script>
