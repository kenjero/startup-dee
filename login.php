<?PHP
    require_once 'functions/php.config.inc.php';
    require_once 'functions/php.functions.php';
    require_once 'vendor/autoload.php';

    /* echo json_encode($_SESSION['user_info']); */
    if (isset($_SESSION['user_info']['member_id']) && isset($_SESSION['user_info']['token'])) {
        $auth = new Authentication();
        $result = $auth->check_pageLogin();
    }
?>

<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Startup-Dee - Login</title>
    <meta charset="utf-8">
    <!-- [Meta] -->

    <script src="functions/javascript.config.js"></script>
    <script src="functions/javascript.functions.js"></script>
    <!-- [Google Font : Public Sans] icon -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    
    <!-- [Fonts Icons] -->
    <link href="assets/fonts-icons/css/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/fonts-icons/css/fontawesome.css" rel="stylesheet">
    <link href="assets/fonts-icons/css/material.css" rel="stylesheet">
    <link href="assets/fonts-icons/css/phosphor.css" rel="stylesheet">
    <link href="assets/fonts-icons/css/tabler-icons.min.css" rel="stylesheet">
    <link href="assets/fonts-icons/css/uicons-thin-straight.css" rel="stylesheet">

    <!-- [Page specific CSS] start -->
    <link rel="stylesheet" href="assets/css/plugins/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="assets/css/plugins/flatpickr.min.css">

    <link rel="stylesheet" href="assets/css/plugins/dragula.min.css">
    <link rel="stylesheet" href="assets/css/plugins/quill.core.css">
    <link rel="stylesheet" href="assets/css/plugins/quill.snow.css">
    <link rel="stylesheet" href="assets/css/plugins/quill.bubble.css">

    <!-- data tables css -->
    <link rel="stylesheet" href="assets/css/plugins/buttons.dataTables.min.css"> 
    <link rel="stylesheet" href="assets/css/plugins/dataTables.bootstrap5.min.css"> 
    <link rel="stylesheet" href="assets/css/plugins/buttons.bootstrap5.min.css">

    <!-- Sweet & notifier Alert -->
    <link rel="stylesheet" href="assets/css/plugins/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/plugins/notifier.css">

    <link rel="stylesheet" href="assets/css/style-preset.css">
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">

    <!-- Required jquery Js --> 
    <script src="assets/js/jquery-3.7.1.min.js"></script> 

    <script src="assets/js/plugins/flatpickr.min.js"></script>
    <script src="assets/js/plugins/choices.min.js"></script>

    <!-- Record-webcam styles-->
    <link href="assets/css/record-webcam.css" rel="stylesheet">

</head><!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-theme="dark" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="dark">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="pc-loader">
            <div class="loader-fill">x</div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Main Content ] Start -->
    <div class="auth-main v1">
        <div class="bg-overlay bg-white"></div>  
            <div class="auth-wrapper">
                <div class="auth-form">
                <a href="#" class="d-block mt-5">
                    <img src="assets/images/logo.png" alt="img">
                </a>
                <div class="card mb-5 mt-3">
                    <div class="card-header bg-dark">
                        <h4 class="text-center text-white f-w-500 mb-0" id="loginHeader">Sign in with your username</h4>
                    </div>
                    <div class="card-body">

                        <div id="loginBody">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text">✉️</span>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">🔑</span>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                    <button class="input-group-text" onclick="togglePassword()">
                                        <i class="ti ti-eye-off" id="passwordIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="button" class="btn btn-primary" name="loggedin" id="loggedin" value="loggedin" onclick="postLogin()">Login</button>
                            </div>
                        </div>

                        <div class="saprator my-3"><span>OR</span></div>
                        <div class="row g-2">
                            <div class="col-12">
                                <?PHP
                                    $client = new Google\Client();
                                    $client->setClientId(API_GOOGLE_CLIENT_ID);
                                    $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
                                    $client->setRedirectUri(GOOGLE_CALLBACK_URL);
                                    $client->setScopes(['email', 'profile']);
                                    $client->setAccessType('offline');
                                    $client->setApprovalPrompt('force');
                                    $authGoogleUrl = $client->createAuthUrl();
                                ?>

                                <div class="d-grid">
                                    <button type="button" class="btn mt-2 btn-light text-muted" onclick="googleAuthAPI('<?=$authGoogleUrl?>')">
                                        <img src="assets/images/authentication/google.png" alt="img">
                                        <span class="d-none d-sm-inline-block">Google</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="card-body border-top">

                        <?php
                            $client_key = 'awhbsal49hmgtpwt';
                            $client_secret = 's0nLM0zKBzmPfZqhNmdIpvUNIkVYhh6Y';
                            $redirect_uri = 'https://www.sarasinlab.com/tiktok.php';
                        
                            use gimucco\TikTokLoginKit\Connector;
                        
                            $_TK = new Connector($client_key, $client_secret, $redirect_uri); 
                        
                            if (Connector::receivingResponse()) {
                                try {
                                    $token = $_TK->verifyCode($_GET[Connector::CODE_PARAM]);
                                    $user  = $_TK->getUser();
                                    var_dump($user);
                        
                                } catch (Exception $e) {
                                    echo "Error: ".$e->getMessage();  
                                    echo '<br /><a href="?">Retry</a>';
                                }
                            }
                        ?>
                        <div class="box mt-1">
                            <?php if (Connector::receivingResponse()) : ?> 
                            <div class="data">
                                <pre><?php var_export($user) ?></pre>
                            </div>
                            <?php else : ?>
                            <div class="request">
                                <div class="d-grid"><button type="button" class="btn btn-dark" onclick="googleAuthAPI('<?=$_TK->getRedirect()?>')"><i class="bi bi-tiktok"></i> Connect Tiktok</a></div>
                            </div>
                            <?php endif ?>
                        </div> 
                        
                        <?php
                        use TyperEJ\LineLogin\Login;
                        
                        $login = new Login(LINE_CHANNEL_ID);

                        $options = [
                            'redirect_uri'  => LINE_CALLBACK_URL, 
                            'response_type' => 'code',
                            'state'         => 'line_login',
                            'scope'         => 'openid profile email', 
                            'nonce'         => '09876xyz'
                        ]; 

                        $line_url = $login->generateLoginUrl($options);

                        ?>
                        <div class="box mt-1">
                            <?php if (!isset($_SESSION['Profile_Line'])) : ?> 
                            <div class="request">
                                <div class="d-grid"><button type="button" class="btn btn-success" onclick="googleAuthAPI('<?=$line_url?>')"><i class="bi bi-google"></i> Connect Line</a></div>
                            </div>
                            <?php else : ?>
                            <div class="data">
                                <p>Here is the data from your Id Token:</p>
                                <pre><?php var_export($_SESSION['Profile_Line']) ?></pre> 
                            </div>
                            <?php endif ?>
                        </div>

                    </div> -->
                    <div class="card-footer border-top">
                        <div class="d-flex justify-content-between align-items-end">
                            <div id="loginFooter">
                                <h6 class="f-w-500 mb-0">Don't have an Account?</h6>
                                <a href="#" class="link-primary" onclick="changeCreateAccount()">Create Account</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <script src="pages/login/login-javascript.js"></script>

    <!-- Required Js -->
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/plugins/choices.min.js"></script>
    <script src="assets/js/plugins/simplebar.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/fonts/custom-font.js"></script>
    <script src="assets/js/pcoded.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>

    <!-- Sweet & notifier Alert -->
    <script src="assets/js/plugins/sweetalert2.all.min.js"></script> 
    <script src="assets/js/plugins/notifier.js"></script> 
    <script src="assets/js/plugins/clipboard.min.js"></script>
    <script src="assets/js/component.js"></script>

    <!-- dataTables --> 
    <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/plugins/dataTables/dataTables.buttons.min.js"></script>
    <script src="assets/js/plugins/dataTables/buttons.html5.min.js"></script>
    <script src="assets/js/plugins/dataTables/buttons.print.min.js"></script>
    <script src="assets/js/plugins/dataTables/buttons.colVis.min.js"></script>

     <!-- datepicker full --> 
    <script src="assets/js/plugins/datepicker-full.min.js"></script>

</body>
<!-- [Body] end -->

</html>