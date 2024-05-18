<?PHP
    require_once 'functions/php.config.inc.php';
    require_once 'functions/php.functions.php';
    require_once 'vendor/autoload.php';
    
    $auth = new Authentication();
    $result = $auth->check_indexLogin();
?>

<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Dashboard Template</title>
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
    <?PHP
    include_once "component/sidebar/sidebar.php";
    include_once "component/header/header.php";
    include_once "pages/main.php";
    include_once "component/footer/footer.php";

    ?>
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