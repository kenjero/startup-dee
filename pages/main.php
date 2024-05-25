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

    ///// record system /////
    if ($pages === "record") {
        $title = "Recode Vdo System";
        $description = "This is the Recode Vdo System page description.";
        include 'pages/record/record.php';
    }
    if ($pages === "setting_record") {
        $title = "Record Vdo Setting";
        $description = "This is the Recode Vdo System page description.";
        include 'pages/record/setting_record.php';
    }

    ///// qrcode system /////
    if ($pages === "qrcode") {
        $title = "QR-Code System";
        $description = "This is the Recode Vdo System page description.";
        include 'pages/qrcode/qrcode.php';
    }
    if ($pages === "setting_qrcode") {
        $title = "QR-Code Setting";
        $description = "This is the Recode Vdo System page description.";
        include 'pages/qrcode/setting_qrcode.php';
    }

    ///// product system /////
    if ($pages === "products") {
        $title = "Products System";
        $description = "This is the Recode Vdo System page description.";
        include 'pages/product/product.php';
    }

?>