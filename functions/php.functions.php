<?php

require_once 'php.config.inc.php';

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

    public function generateRandomStringUpper($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
class Authentication {
    private $db;
    private $addOn;

    public function __construct() {
        $this->db = (new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST))->connect();
        $this->addOn = new AddOn();
    }

    // ============================== //
    // Function - Check Login //
    // ============================== //

    public function check_pageLogin() {

        if (isset($_SESSION['user_info']['member_id']) && isset($_SESSION['user_info']['token'])) {

            $member_id = $_SESSION['user_info']['member_id'];
            $token = $_SESSION['user_info']['token'];

            // ทำการเลือกข้อมูลจากตาราง auth_member
            $sql = "SELECT * FROM `auth_member` WHERE `token` = :token AND `id` = :id";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindValue(":id", $member_id, PDO::PARAM_INT);
            $stmt->bindValue(":token", $token, PDO::PARAM_STR);
            $stmt->execute();
    
            $result = $this->addOn->useFetchAll($stmt);
            
            // ตรวจสอบผลลัพธ์และเปลี่ยนเส้นทาง
            if ($result === null || empty($result)) {
                header("Refresh:0; url=logout.php");
                exit;
            }else{
                header("Refresh:0; url=index");
                exit;
            }

        } 
    }

    public function check_indexLogin() {

        if (isset($_SESSION['user_info']['member_id']) && isset($_SESSION['user_info']['token'])) {

            $member_id = $_SESSION['user_info']['member_id'];
            $token = $_SESSION['user_info']['token'];

            // ทำการเลือกข้อมูลจากตาราง auth_member
            $sql = "SELECT * FROM `auth_member` WHERE `token` = :token AND `id` = :id";
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $member_id, PDO::PARAM_INT);
            $stmt->bindValue(":token", $token, PDO::PARAM_STR);
            $stmt->execute();

            $result = $this->addOn->useFetchAll($stmt);
            
            // ตรวจสอบผลลัพธ์และเปลี่ยนเส้นทาง
            if ($result === null || empty($result)) {
                header("Refresh:0; url=logout.php");
                exit;
            }
            
        } else{
            header("Refresh:0; url=logout.php");
            exit;
        }
    }


    // ============================== //
    // Function - Regiter User //
    // ============================== //

    public function check_EmailRegiter($email) {

        // ทำการเลือกข้อมูลจากตาราง auth_member
        $sql = "SELECT * FROM `auth_member` WHERE `email` = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $this->addOn->useFetchAll($stmt);

        return $result;

    }

}
// End Class Oauth

?>
