<?PHP
    ///////////////////////////
    ////// Default pages //////
    ///////////////////////////

    if (!isset($_GET['pages']) || $_GET['pages'] === 'index' || $_GET['pages'] === '') {
        $pages = "home"; 
    }else{
        $pages = $_GET['pages'];
    }

    ///////////////////////////
    ///// Condition pages /////
    ///////////////////////////
    if ($pages === "home") {
        $title = "Home Page";
        $description = "This is the dashboard page description.";
        include 'pages/home/home.php';
    }

    if ($pages === "record") {
        $title = "Recode Vdo System";
        $description = "This is the Recode Vdo System page description.";
        include 'pages/record/record.php';
    }
    if ($pages === "setting_record") {
        $title = "Setting Record Vdo System";
        $description = "This is the Recode Vdo System page description.";
        include 'pages/record/setting_record.php';
    }

    if ($pages === "qrcode") {
        $title = "Recode Vdo System";
        $description = "This is the Recode Vdo System page description.";
        include 'pages/qrcode/qrcode.php';
    }

    if ($pages === "tiktok") {
        $title = "tiktok Vdo System";
        $description = "This is the Recode Vdo System page description.";
        include 'pages/qrcode/qrcode.php';
    }

?>