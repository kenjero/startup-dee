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

    ///////////////////////////
    ////// Order System  //////
    ///////////////////////////

    ///// Order Lazada /////
    if ($pages === "order_lazada") {
        $title = "Lazada order";
        $description = "This is the Lazada orderSystem page description.";
        include 'pages/order_lazada/order_lazada.php';
    }

    ///////////////////////////
    ///// Setting Google  /////
    ///////////////////////////
    if ($pages === "setting_google") {
        $title = "Google Setting";
        $description = "This is the Google System page description.";
        include 'pages/setting_google/setting_google.php';
    }

?>