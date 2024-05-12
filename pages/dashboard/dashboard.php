<div class="pc-container">
    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center justify-content-between">
                    <div class="col-sm-auto">
                        <div class="page-header-title">
                            <h5 class="mb-0">Affiliate Dashboard</h5>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="ph-duotone ph-house"></i></a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Affiliate Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div><!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- [ Row 2 ] start -->
            <div class="col-md-12 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Add Fund</h5>
                        </div>

                        <div class="border rounded p-3 my-3">
                         
                            <?php
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
                                }
                            ?>
                            <div class="box mb-3">
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
                                $client->setRedirectUri(REDIRECT_URL);
                                $client->setScopes('email');
                                $client->setState('google_login');
                                $client->addScope("https://www.googleapis.com/auth/drive");
                                $service = new Google\Service\Drive($client);

                                if (isset($_GET['code'])) {
                                    $token = $client->fetchAccessTokenWithAuthCode($_GET['code'], $_SESSION['google_code_verifier']);
                                    $client->setAccessToken($token);
                            
                                    $_SESSION['id_google_token'] = $token;
                                    header('Location: ' . filter_var(REDIRECT_URL, FILTER_SANITIZE_URL));
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
                            <div class="box mb-3">
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
                                'redirect_uri'  => REDIRECT_URL, 
                                'response_type' => 'code',
                                'state'         => 'line_login',
                                'scope'         => 'openid profile email', 
                                'nonce'         => '09876xyz'
                            ]; 

                            $line_url = $login->generateLoginUrl($options);

                            ?>
                            <div class="box">
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

                    </div>
                </div>
            </div>

        </div><!-- [ Main Content ] end -->
    </div>
</div><!-- [ Main Content ] end -->
