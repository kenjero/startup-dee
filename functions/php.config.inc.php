﻿<?PHP

    //////////////////////////////////
    ///////// Setting Server /////////
    //////////////////////////////////

    ob_start();
    session_start();

    /* ini_set('display_errors', 0); */
    /* error_reporting(~0); */

    ini_set('memory_limit'    , '1024M');
    ini_set('default_charset' , 'UTF-8');

    date_default_timezone_set('Asia/Bangkok');

    //////////////////////////////////
    //////// Connect database ////////
    //////////////////////////////////

    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_NAME", "emicon_system");
    define("DB_PASS", "");

    //////////////////////////////////
    //////////// Url Ajax ////////////
    //////////////////////////////////

    define("HOST_NAME_URL"                , "http://localhost/startup-dee/");
    define("SERVER_NAME"                  , $_SERVER['SERVER_NAME']."/startup-dee/");

    define("FRONT_END_DOCUMENT_ROOT"      , $_SERVER['DOCUMENT_ROOT']."/startup-dee/");
    define("FRONT_END_IMAGE_PRODUCT_PATH" , "assets/images/products/");

    define("BACK_END_DOCUMENT_ROOT"       , $_SERVER['DOCUMENT_ROOT']."/startup-dee/");
    define("BACK_END_IMAGE_PRODUCT_ROOt"  , "img");

    //////////////////////////////////
    /////////// phpMailer ////////////
    //////////////////////////////////

    define("EMAIL_SMTP_SERVER"  , 'smtp.gmail.com');
    define("EMAIL_USERNAME"     , 'kenjerorom01@gmail.com');
    define("EMAIL_PASSWORD"     , 'cgojbuonynarpjug');
    define("EMAIL_PORT_TCP"     , '587');
    define("EMAIL_FROM_ADDRESS" , 'Startup-Dee@gmail.com');
    define("EMAIL_FROM_NAME"    , 'Startup-Dee.com');

    //////////////////////////////////
    /////////// API Google ///////////
    //////////////////////////////////

    // API Google
    define("API_GOOGLE_CLIENT_ID"          , "381615817828-01u4v5a4qvmqf2ar879lqaqrkk7ghn7j.apps.googleusercontent.com"); // ClientId API GOOGLE
    define("API_GOOGLE_CLIENT_SECRET"      , "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"); // ClientSecret API GOOGLE
    define("API_GOOGLE_CALLBACK_URL"       ,  HOST_NAME_URL."callbacks/google/callback_google.php");
    define("API_GOOGLE_DRIVE_FOLDER_ID"    , "13hjPbMtyNw4s_6u6YhqVctDZnyHzwJXb"); //ID โฟลเดอร์ Google Drive
    define("API_GOOGLE_DRIVE_CALLBACK_URL" ,  HOST_NAME_URL."callbacks/google/callback_google_drive.php");
    

    //////////////////////////////////
    //////////// API Line ////////////
    //////////////////////////////////

    define("API_LINE_CHANNEL_ID"        ,"2003369350");
    define("API_LINE_CHANNEL_SECRET"    ,"c5be5da90cd1ef56a50efe06050809b6");
    define("API_LINE_CALLBACK_URL"      , HOST_NAME_URL."callbacks/line/callback_line.php");

    //////////////////////////////////
    /////////// API TikTok ///////////
    //////////////////////////////////

    define("API_TIKTOK_CLIENT_KEY"    , "awhbsal49hmgtpwt");
    define("API_TIKTOK_CLIENT_SECRET" , "s0nLM0zKBzmPfZqhNmdIpvUNIkVYhh6Y");
    /* define("API_TIKTOK_SCOPES"     , "product.list,product.detail,order.list,order.detail,product.add,product.update,product.delete"); */
    define("API_TIKTOK_SCOPES"        , "user.info");
    define("API_TIKTOK_CALLBACK_URL"  , HOST_NAME_URL."callbacks/tiktok/callback_tiktok.php");
    define("API_TIKTOK_RIGHTS_URL"    , "https://www.tiktok.com/auth/authorize/?client_key=".API_TIKTOK_CLIENT_KEY."&response_type=code&scope=".API_TIKTOK_SCOPES."&redirect_uri=".API_TIKTOK_CALLBACK_URL);


?>
