<?php
// เริ่ม session
session_start();

unset($_SESSION['logged_in']);
unset($_SESSION['username']);
unset($_SESSION['user_id']); 
session_unset();

/* header("http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/"); */
/* header("Refresh:0; url=login"); */
header('Location: login');
exit;
?>
