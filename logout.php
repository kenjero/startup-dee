<?php
// เริ่ม session
session_start();
// ลบทุก session variables
// $_SESSION = array();
// ถอน session
// session_destroy();

unset($_SESSION['logged_in']);
unset($_SESSION['username']);
unset($_SESSION['user_id']); 

// ทำการ redirect ผู้ใช้กลับไปยังหน้า login หรือหน้าหลักของเว็บไซต์
header("Location: index.php");
exit;
?>
