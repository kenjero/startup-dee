<?PHP
  require_once 'functions/php.config.inc.php';
  require_once 'functions/php.functions.php';
  require_once 'vendor/autoload.php';

  $db = (new Database())->connect();
  
  // เริ่ม session
  session_start();

  $id = $_SESSION['user_info']['id'];

  $sqlAuthMember = "UPDATE `auth_member` SET `token` = :token, `online` = :online WHERE `id` = :id";
  $stmtMember = $db->prepare($sqlAuthMember);
  $stmtMember->bindValue(':token' , ''  , PDO::PARAM_STR);
  $stmtMember->bindValue(':id'    , $id , PDO::PARAM_INT);
  $stmtMember->bindValue(':online', 0   , PDO::PARAM_INT);
  $stmtMember->execute();
  
  ini_set('display_errors', 0);
  error_reporting(~0);

  unset($_SESSION['user_info']);
  session_unset();

  header("Refresh:0; url=login.php");
  exit;
?>
