<?php
require_once 'php.config.inc.php';

$db    = (new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST))->connect();
$oauth = new Oauth();
$addOn = new AddOn();

// Start Class Database
Class Database {
    // ============================== //
    // เชื่อต่อฐานข้อมูล แบบ PDO //
    // ============================== //
    private $charset = 'UTF8';
    private $user;
    private $password;
    private $database;
    private $host;

    // กำหนดค่าเริ่มต้นในการเชื่อมต่อฐานข้อมูล
    public function __construct($user, $password, $database, $host) {
        $this->user     = $user;
        $this->password = $password;
        $this->database = $database;
        $this->host     = $host;
    }

    // เชื่อมต่อฐานข้อมูล
    public function connect() {
        try {
            $pdo = new PDO("mysql:host=$this->host; dbname=$this->database; charset=$this->charset", $this->user, $this->password);
            // ตั้งค่าการแสดงข้อผิดพลาดในรูปแบบของ exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch(PDOException $e) {
            // จัดการข้อผิดพลาดเมื่อเชื่อมต่อฐานข้อมูลล้มเหลว
            echo "การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage();
            return null; // หรือสามารถจัดการข้อผิดพลาดต่อไปได้ตามความเหมาะสม
        }
    }
}
// End Class Database

// Start Class AddOn
Class AddOn {

    public function useFetchAll($stmt) {
        if ($stmt->rowCount() > 1) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($stmt->rowCount() === 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $result = null;
        }
        return $result;
    }

    public function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
// End Class AddOn

// Start Class Oauth
class Oauth {
    private $db;
    private $addOn;

    public function __construct() {
        $this->db = (new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST))->connect();
        $this->addOn = new AddOn();
    }

    // ============================== //
    // Function - Auth Google //
    // ============================== //
    public function auth_google() {
        try {
            // ทำการเลือกข้อมูลจากตาราง auth_google
            $sql = "SELECT * FROM `auth_google` WHERE `id` = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":id", 1, PDO::PARAM_INT);
            $stmt->execute();
            $result = $this->addOn->useFetchAll($stmt);
            return $result;
            
        } catch(PDOException $e) {
            // จัดการข้อผิดพลาดเมื่อส่งคำสั่ง SQL ล้มเหลว
            echo "การเรียกข้อมูลผู้ใช้ล้มเหลว: " . $e->getMessage();
            return null;
        }
    }
}
// End Class Oauth


?>
