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
    public function __construct() {
        $this->user     = DB_USER;
        $this->password = DB_PASS;
        $this->database = DB_NAME;
        $this->host     = DB_HOST;
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

    public function trLoading($colspan) {
        $tLoad  = '<tbody>';
        $tLoad .= ' <tr class="odd">';
        $tLoad .= '     <td valign="top" colspan="'.$colspan.'" class="dt-center">';
        $tLoad .= '         <span class="spinner-border spinner-border-sm" role="status"></span>  Loading...';
        $tLoad .= '     </td>';

        for($i = 2; $i <= $colspan; $i++){
            $tLoad .= ' <td style="display: none;"></td>';
        }

        $tLoad .= ' </tr>';  
        $tLoad .= '</tbody>';

        return $tLoad;
    }

    public function cardLoading() {
        $cardLoad  = '<div class="d-flex justify-content-center">';
        $cardLoad .= '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>';
        $cardLoad .= '</div>';

        return $cardLoad;
    }

    public function generateRandomString($length) {
        $numbers    = '0123456789';
        $lowercase  = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $special    = '!@#$%&*';
    
        $allCharacters = $numbers . $lowercase . $uppercase . $special;
        $allCharactersLength = strlen($allCharacters);
    
        // สร้างสตริงที่มีอย่างน้อยหนึ่งตัวจากแต่ละกลุ่ม
        $randomString = '';
        $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
        $randomString .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $randomString .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $randomString .= $special[rand(0, strlen($special) - 1)];
    
        // สร้างสตริงส่วนที่เหลือ
        for ($i = 4; $i < $length; $i++) {
            $randomString .= $allCharacters[rand(0, $allCharactersLength - 1)];
        }
    
        // สับเปลี่ยนสตริงเพื่อไม่ให้ตัวอักษรที่บังคับอยู่ในตำแหน่งเดิมเสมอ
        $randomString = str_shuffle($randomString);
    
        return $randomString;
    }

    public function generateRandomStringLowerUpper($length) {
        $numbers    = '0123456789';
        $lowercase  = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
        $allCharacters = $numbers . $lowercase . $uppercase;
        $allCharactersLength = strlen($allCharacters);
    
        // สร้างสตริงที่มีอย่างน้อยหนึ่งตัวจากแต่ละกลุ่ม
        $randomString = '';
        $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
        $randomString .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $randomString .= $uppercase[rand(0, strlen($uppercase) - 1)];
    
        // สร้างสตริงส่วนที่เหลือ
        for ($i = 3; $i < $length; $i++) {
            $randomString .= $allCharacters[rand(0, $allCharactersLength - 1)];
        }
    
        // สับเปลี่ยนสตริงเพื่อไม่ให้ตัวอักษรที่บังคับอยู่ในตำแหน่งเดิมเสมอ
        $randomString = str_shuffle($randomString);
    
        return $randomString;
    }

    public function generateRandomStringUpper($length) {
        $numbers = '0123456789';
        $uppercase  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $allCharacters = $numbers . $uppercase;
        
        $numbersLength = strlen($numbers);
        $lettersLength = strlen($uppercase);
        $allCharactersLength = strlen($allCharacters);
        
        $randomString = '';
        $randomString .= $numbers[rand(0, $numbersLength - 1)];   // เพิ่มตัวเลขอย่างน้อยหนึ่งตัว
        $randomString .= $uppercase[rand(0, $lettersLength - 1)]; // เพิ่มตัวอักษรอย่างน้อยหนึ่งตัว
    
        // สร้างสตริงส่วนที่เหลือ
        for ($i = 2; $i < $length; $i++) {
            $randomString .= $allCharacters[rand(0, $allCharactersLength - 1)];
        }
    
        // สับเปลี่ยนสตริงเพื่อไม่ให้ตัวเลขและตัวอักษรที่บังคับอยู่ในตำแหน่งเดิมเสมอ
        $randomString = str_shuffle($randomString);
    
        return $randomString;
    }

    public function generateRandomToken($length) {
        $numbers    = '0123456789';
        $lowercase  = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $special    = '-_.';
    
        $allCharacters = $numbers . $lowercase . $uppercase . $special;
        $allCharactersLength = strlen($allCharacters);
    
        // สร้างสตริงที่มีอย่างน้อยหนึ่งตัวจากแต่ละกลุ่ม
        $randomString = '';
        $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
        $randomString .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $randomString .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $randomString .= $special[rand(0, strlen($special) - 1)];
    
        // สร้างสตริงส่วนที่เหลือ
        for ($i = 4; $i < $length; $i++) {
            $randomString .= $allCharacters[rand(0, $allCharactersLength - 1)];
        }
    
        // สับเปลี่ยนสตริงเพื่อไม่ให้ตัวอักษรที่บังคับอยู่ในตำแหน่งเดิมเสมอ
        $randomString = str_shuffle($randomString);
    
        return $randomString;
    }

}
// End Class AddOn

// Start Class Oauth
class Authentication {
    private $db;
    private $addOn;

    public function __construct() {
        $this->db = (new Database())->connect();
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

    public function check_EmailRegiter($email,$verifiedEmail) {
        // ทำการเลือกข้อมูลจากตาราง auth_member
        $sql = "SELECT * FROM `auth_member` WHERE `email` = :email AND verifiedEmail = :verifiedEmail AND `type` = :type";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email"         , $email         , PDO::PARAM_STR);
        $stmt->bindValue(":verifiedEmail" , $verifiedEmail , PDO::PARAM_INT);
        $stmt->bindValue(":type"          , "RG"           , PDO::PARAM_STR);
        $stmt->execute();
        $result = $this->addOn->useFetchAll($stmt);
        return $result;
    }

    public function check_OTP($dataArray) {
        // ทำการเลือกข้อมูลจากตาราง auth_member
        $sql = "SELECT * FROM `auth_member` WHERE `email` = :email AND `OTP` = :OTP AND `type` = :type";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email" , $dataArray['email'] , PDO::PARAM_STR);
        $stmt->bindValue(":OTP"   , $dataArray['otp']   , PDO::PARAM_STR);
        $stmt->bindValue(":type"  , "RG"                , PDO::PARAM_STR);
        $stmt->execute();
        $result = $this->addOn->useFetchAll($stmt);
        return $result;
    }
    

    function canSendEmail($email) {
        $sql = "SELECT last_sent_time FROM auth_member WHERE email = :email AND `type` = :type";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email" , $email , PDO::PARAM_STR);
        $stmt->bindValue(":type"  , "RG"   , PDO::PARAM_STR);
        $stmt->execute();
        $lastSent = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($lastSent && (time() - strtotime($lastSent['last_sent_time']) < 60)) {
            // หากเวลาที่ส่งอีเมลครั้งล่าสุดน้อยกว่า 60 วินาที
            return false;
        }else{
            return true;
        }
    }
    
    function updateLastSentTime($email) {
        $sql = "UPDATE auth_member SET last_sent_time = NOW() WHERE email = :email AND `type` = :type";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email" , $email , PDO::PARAM_STR);
        $stmt->bindValue(":type"  , "RG"   , PDO::PARAM_STR);
        $stmt->execute();
    }


    public function check_ResetPassword($email,$code) {
        $sql = "SELECT * FROM `auth_member` WHERE `email` = :email AND `codeChangPassword` = :code AND `type` = :type";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email" , $email  , PDO::PARAM_STR);
        $stmt->bindValue(":code"   , $code   , PDO::PARAM_STR);
        $stmt->bindValue(":type"  , "RG"    , PDO::PARAM_STR);
        $stmt->execute();
        $result = $this->addOn->useFetchAll($stmt);

        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }

    public function check_EmailGoogleAPI($member_id) {
        $sql = "SELECT * FROM `auth_google` WHERE `member_id` = :member_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":member_id" , $member_id , PDO::PARAM_INT);
        $stmt->execute();
        $result = $this->addOn->useFetchAll($stmt);
        return $result;
    }


}
// End Class Oauth
?>
