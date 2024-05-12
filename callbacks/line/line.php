<?PHP
    require_once '../../functions/php.config.inc.php';
    require_once '../../functions/php.functions.php';
    require_once '../../vendor/autoload.php';

    $db = (new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST))->connect();
?>

<?PHP
use TyperEJ\LineLogin\Login;

if ($_GET['state'] == 'line_login') {

    $login = new Login(LINE_CHANNEL_ID, LINE_CHANNEL_SECRET); 
    $_SESSION['Profile_Line'] = $user = $login->requestToken($_GET['code']);
    $login = new Login(LINE_CHANNEL_ID, LINE_CHANNEL_SECRET); 
    $_SESSION['Profile_Line'] = $user = $login->requestToken($_GET['code']);
    $login = new Login(LINE_CHANNEL_ID, LINE_CHANNEL_SECRET); 
    $_SESSION['Profile_Line'] = $user = $login->requestToken($_GET['code']);
    $login = new Login(LINE_CHANNEL_ID, LINE_CHANNEL_SECRET); 
    $_SESSION['Profile_Line'] = $user = $login->requestToken($_GET['code']);
}
?>

<script>window.close();</script>
