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
    if ($pages === "logout") {
        include 'pages/logout/logout.php';
    } 
    if ($pages === "login") {
        include 'pages/login/login.php';
    }
    if ($pages === "home") {
        $title = "Home Page";
        $description = "This is the dashboard page description.";
        include 'pages/home/home.php';
    } 

?>