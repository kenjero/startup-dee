<?PHP
  ob_start();
  session_start();

  require_once 'functions/php.config.inc.php';
  require_once 'functions/php.functions.php';
  require_once 'vendor/autoload.php';
  //require_once __DIR__ . '/vendor/autoload.php'; 
  
  echo '<script src="'.HOST_NAME_URL.'/config.js"></script>';
  echo '<script src="'.HOST_NAME_URL.'/ajaxScript.js"></script>';

  date_default_timezone_set('Asia/Bangkok');

  $db = new Database(DB_USER, DB_PASS, DB_NAME, DB_HOST);
?>

<?PHP
    // ตรวจสอบการ Login แบบไม่สำเร็จ
    if (!isset($_SESSION['logged_in'])) {
        header("Location: login.php");
        exit();
    }
?>

<?PHP
  if (isset($_GET['page']))
      $page = $_GET['page'];
  else
      $page = "dashboard";
?>

<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title></title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="Emicon">
    <!-- [Favicon] icon -->
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
    <!-- [Google Font : Public Sans] icon -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet"><!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css"><!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <link rel="stylesheet" href="assets/fonts/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/fonts/uicons-thin-straight.css" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <!-- data tables css -->
    <link rel="stylesheet" href="assets/css/plugins/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/css/plugins/buttons.bootstrap5.min.css"><!-- [Page specific CSS] end -->
    <link rel="stylesheet" href="assets/css/plugins/notifier.css"><!-- [Page specific CSS] end -->
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="assets/fonts/material.css"><!-- [Template CSS Files] -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="assets/css/style-preset.css">
    <link rel="stylesheet" href="assets/css/record-webcam.css">
    <!-- Required jquery Js -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>

    <script src="https://apis.google.com/js/api.js"></script>

    <script>
      $(document).ready(function() {
        // เลือก element ที่มี class เป็น pc-item และมีลิงก์ทั้งหมด
        $('.pc-item a').each(function() {
          // หาก href ของลิงก์เป็น qrcode_dashboard ให้เพิ่ม class ให้กับ element ตามเงื่อนไข
          if ($(this).attr('href') === '<?=$page?>') {
            $(this).parent().addClass('pc-item active');
            $(this).parents('.pc-hasmenu').addClass('pc-trigger active');
            $(this).parents('.pc-hasmenu').find('.pc-submenu').css('display', 'block');
          }
        });
      });
    </script>

</head><!-- [Head] end -->

<!-- [Body] Start -->
<body data-pc-preset="preset-1" data-pc-sidebar-theme="dark" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="dark">

    <!-- [ Pre-loader ] start -->
    <!--<div class="loader-bg">
        <div class="pc-loader">
            <div class="loader-fill"></div>
        </div>
    </div> -->
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    <?PHP include 'pages/menu/sidebar.php'; ?>
    <!-- [ Sidebar Menu ] End -->

    <!-- [ Header Topbar ] start -->
    <?PHP include 'pages/navbar/navbar.php'; ?>
    <!-- [ Header Topbar ] End -->

    <!-- [ Main Content ] start -->
    <?PHP
      if ($page == "dashboard") {
        $title = "Dashboard Page";
        $description = "This is the dashboard page description.";
        include 'pages/dashboard/dashboard.php';
      }

      if ($page == "products") {
        $title = "Products Page";
        $description = "This is the products page description.";
        include 'pages/products/products.php';
      }
      if ($page == "product_add") {
        $title = "Add Product Page";
        $description = "This is the add product page description.";
        include 'pages/products/product_add.php';
      }
      if ($page == "product_edit") {
        $title = "Edit Product Page";
        $description = "This is the edit product page description.";
        include 'pages/products/product_edit.php';
      }

      if ($page == "qrcode_list") {
        $title = "QrCode System";
        $description = "QrCode management";
        include 'pages/qrcode/qrcode_list.php';
      }

      if ($page == "record") {
        $title = "Record";
        $description = "Record management";
        include 'pages/record/page-record.php';
      }

      if ($page == "change_password") {
        $title = "Change Passwordm";
        $description = "Passwordm management";
        include 'pages/system/change_password.php';
      }

    ?>
    <!-- [ Main Content ] End -->

    <!-- [ Title description ] start -->
    <script>
        document.title = "<?=$title?>";
        document.querySelector('meta[name="description"]').setAttribute('content', '<?=$description?>');
    </script>
      <!-- [ Main Content ] End -->

    <!-- Javascript -->
    <script>
      document.querySelectorAll('.formatted-number').forEach(function(element) {
        element.addEventListener('input', function(event) {
          let value = event.target.value;
          let number = parseFloat(value.replace(/,/g, ''));

          if (!isNaN(number)) {
            event.target.value = number.toLocaleString('en-US');
          } else {
            event.target.value = '';
          }
        });
      });

      document.querySelectorAll('.formatted-numberOnly').forEach(function(element) {
        element.addEventListener('input', function(event) {
          let value = event.target.value;

          if (!isNaN(value)) {
            event.target.value = number.toLocaleString('en-US');
          } else {
            event.target.value = '';
          }
        });
      });
    </script>

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
    <script src="assets/js/plugins/notifier.js"></script>

    <script>
        layout_change('dark');
        layout_sidebar_change('dark');
        layout_header_change('dark');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change("preset-1");
    </script>

    <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/plugins/buttons.colVis.min.js"></script>
    <script src="assets/js/plugins/buttons.print.min.js"></script>
    <script src="assets/js/plugins/pdfmake.min.js"></script>
    <script src="assets/js/plugins/jszip.min.js"></script>
    <script src="assets/js/plugins/dataTables.buttons.min.js"></script>
    <script src="assets/js/plugins/vfs_fonts.js"></script>
    <script src="assets/js/plugins/buttons.html5.min.js"></script>
    <script src="assets/js/plugins/buttons.bootstrap5.min.js"></script>
</body>
<!-- [Body] end -->

</html>
