<?php
  ob_start();
  session_start();

  require_once '../functions_emicon/config.inc.php';
  require_once '../functions_emicon/functions.php';
  
  echo '<script src="'.HOST_NAME_URL.'/config.js"></script>';
  echo '<script src="'.HOST_NAME_URL.'/ajaxScript.js"></script>';

  date_default_timezone_set('Asia/Bangkok');

  $db = new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST);
?>
<?php

    if (isset($_SESSION['logged_in'])) {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Login | Emicon System</title><!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Emicon System.">
    <meta name="author" content="Emicon">
    <!-- [Favicon] icon -->
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
    <!-- [Page specific CSS] start -->
    <link rel="stylesheet" href="assets/css/plugins/animate.min.css" type="text/css"><!-- [Page specific CSS] end -->
    <!-- [Google Font : Public Sans] icon -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&amp;display=swap"><!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css"><!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="assets/fonts/material.css"><!-- [Template CSS Files] -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="assets/css/style-preset.css">
    <link rel="stylesheet" href="assets/css/uikit.css">

</head><!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-theme="dark" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="dark">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="pc-loader">
            <div class="loader-fill"></div>
        </div>
    </div><!-- [ Pre-loader ] End -->
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
                        <div class="form-group mb-3">
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
                    </div>
                    <div class="card-footer border-top">
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <a href="#" class="link-primary">Create an account.</a>
                                <h6 class="f-w-500 mb-0">Please contact the system administrator.</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- [ Main Content ] end -->

    <!-- Required jquery Js -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <!-- Required Js -->
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/plugins/simplebar.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/fonts/custom-font.js"></script>
    <script src="assets/js/pcoded.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>

    <script src="assets/js/highlight.min.js"></script>
    <script src="assets/js/plugins/clipboard.min.js"></script>
    <script src="assets/js/component.js"></script>
    <!-- Sweet Alert -->
    <script src="assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="assets/js/pages/ac-alert.js"></script><!-- [Page Specific JS] end -->

    <script>
        layout_change('dark');
        layout_sidebar_change('dark');
        layout_header_change('dark');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change("preset-1");
    </script>

    <script>
    function togglePassword() {
        var passwordField = document.getElementById('password');
        var passwordIcon = document.querySelector('.input-group-text i');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.classList.remove('ti-eye-off');
            passwordIcon.classList.add('ti-eye');
        } else {
            passwordField.type = 'password';
            passwordIcon.classList.remove('ti-eye');
            passwordIcon.classList.add('ti-eye-off');
        }
    }
    </script>


</body><!-- [Body] end -->

</html>
