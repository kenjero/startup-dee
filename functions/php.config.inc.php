<?PHP

    ob_start();
    session_start();

    //////////////////////////////////
    ///////// Setting Server /////////
    //////////////////////////////////

    /* ini_set('display_errors', 0); */
    /* error_reporting(~0); */

    ini_set('memory_limit', '1024M');
    ini_set('default_charset', 'UTF-8');

    date_default_timezone_set('Asia/Bangkok');

    //////////////////////////////////
    //////// Connect database ////////
    //////////////////////////////////

    define("DB_HOST","localhost");
    define("DB_USER","root");
    define("DB_NAME","startupdee");
    define("DB_PASS","");

    //////////////////////////////////
    //////////// Url Ajax ////////////
    //////////////////////////////////

    define("HOST_NAME_URL", "http://localhost/startupdee/");
    define("SERVER_NAME", $_SERVER['SERVER_NAME']."/startupdee/");

    define("FRONT_END_DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']."/startupdee/");
    define("FRONT_END_IMAGE_PRODUCT_PATH", "assets/images/products/");

    define("BACK_END_DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']."/startupdee");
    define("BACK_END_IMAGE_PRODUCT_ROOt", "img");

    //////////////////////////////////
    /////////// API Google ///////////
    //////////////////////////////////

    // API Google
    define("API_GOOGLE_CLIENT_ID","381615817828-01u4v5a4qvmqf2ar879lqaqrkk7ghn7j.apps.googleusercontent.com"); // ClientId API GOOGLE
    define("API_GOOGLE_CLIENT_SECRET","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"); // ClientSecret API GOOGLE
    define("API_GOOGLE_DRIVE_FOLDER_ID","13hjPbMtyNw4s_6u6YhqVctDZnyHzwJXb"); //ID โฟลเดอร์ Google Drive
    // API Google Callback
    define("GOOGLE_CALLBACK_URL", HOST_NAME_URL."callbacks/google/callback_google.php");

    //////////////////////////////////
    //////////// API Line ////////////
    //////////////////////////////////
    // API LINE
    define("LINE_CHANNEL_ID","2003369350");
    define("LINE_CHANNEL_SECRET","c5be5da90cd1ef56a50efe06050809b6");

    


?>
