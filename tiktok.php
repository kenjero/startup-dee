<?php
    ob_start();
    session_start();
    require_once 'vendor/autoload.php';

    $client_key = 'awhbsal49hmgtpwt';
    $client_secret = 's0nLM0zKBzmPfZqhNmdIpvUNIkVYhh6Y';
    $redirect_uri = 'https://www.sarasinlab.com/tiktok.php';

    use gimucco\TikTokLoginKit\Connector;

    $_TK = new Connector($client_key, $client_secret, $redirect_uri); 

    if (Connector::receivingResponse()) {
        try {
            $token = $_TK->verifyCode($_GET[Connector::CODE_PARAM]);
            $user = $_TK->getUser();
            var_dump($user);

        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();  
            echo '<br /><a href="?">Retry</a>';
        }
    } else { 
        echo '<a href="'.$_TK->getRedirect().'">Log in with TikTok</a>';
    }

?>