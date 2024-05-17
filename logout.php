<?php
// เริ่ม session
session_start();

ini_set('display_errors', 0);
error_reporting(~0);

unset($_SESSION['user_info']);
session_unset();

header("Refresh:0; url=login.php");
exit;
?>
