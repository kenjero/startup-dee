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
                    <h4 class="text-center text-white f-w-500 mb-0">Login with your username</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text">🔒</span>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <span class="input-group-text">🔑</span>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                            <button class="input-group-text" onclick="togglePassword()">
                                <i class="ti ti-eye-off"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="button" class="btn btn-primary" name="loggedin" id="loggedin" value="loggedin" onclick="postLogin()">Login</button>
                    </div>
                    <div class="saprator my-3"><span>OR</span></div>
                    <div class="row g-2">
                        <div class="col-4">
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
                        <div class="col-4">
                            <div class="d-grid"><button type="button" class="btn mt-2 btn-light text-muted"><img src="assets/images/authentication/tiktok.png" alt="img"> <span class="d-none d-sm-inline-block">TikTok</span></button>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-grid"><button type="button" class="btn mt-2 btn-light text-muted"><img src="assets/images/authentication/line.png" alt="img"> <span class="d-none d-sm-inline-block">Line</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">

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
                        $client = new Google\Client();
                        $client->setClientId(API_GOOGLE_CLIENT_ID);
                        $client->setClientSecret(API_GOOGLE_CLIENT_SECRET);
                        $client->setRedirectUri(GOOGLE_CALLBACK_URL);
                        $client->setScopes(['email', 'profile']);
                        $client->setAccessType('offline');
                        $client->setApprovalPrompt('force');

                        if (isset($_GET['code'])) {
                            $token = $client->fetchAccessTokenWithAuthCode($_GET['code'], $_SESSION['google_code_verifier']);
                            $client->setAccessToken($token);
                    
                            $_SESSION['id_google_token'] = $token;
                            header('Location: ' . filter_var(GOOGLE_CALLBACK_URL, FILTER_SANITIZE_URL));
                        }
                        // set the access token as part of the clien
                    
                        if (!empty($_SESSION['id_google_token'])) {
                            $client->setAccessToken($_SESSION['id_token_token']);
                            if ($client->isAccessTokenExpired()) {
                                unset($_SESSION['id_google_token']);
                            }
                        } else {
                            $_SESSION['google_code_verifier'] = $client->getOAuth2Service()->generateCodeVerifier();
                            $authUrl = $client->createAuthUrl();
                            
                        } 
                    
                        if ($client->getAccessToken()) {
                            $_SESSION['Profile_Google'] = $client->verifyIdToken();
                        } 

                    ?> 
                    <div class="box mt-1">
                        <?php if (empty($_SESSION['id_google_token'])) : ?>
                        <div class="request">                               
                            <div class="d-grid"><button type="button" class="btn btn-danger" onclick="googleAuthAPI('<?=$authUrl?>')"><i class="bi bi-google"></i> Connect Google</a></div>
                        </div>
                        <?php else : ?>
                        <div class="data">
                            <p>Here is the data from your Id Token:</p>
                            <pre><?php var_export($_SESSION['Profile_Google']) ?></pre>
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

                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <!-- <a href="#" class="link-primary">Create an account.</a> -->
                            <h6 class="f-w-500 mb-0">Please contact the system administrator.</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

<script src="pages/login/login-javascript.js"></script>