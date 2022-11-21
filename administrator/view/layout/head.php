<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <base href="<?= SITE_ADMIN_URL ?>" />
  <!-- Favicon icon -->
  <link rel="shortcut icon" href="media/icons/favicon.ico" type="image/x-icon">
  <link rel="icon" href="media/icons/favicon.ico" type="image/x-icon">
  <title>Administrator</title>
  <?php if ($user->isLoggedIn()) { ?>
    <!-- This page plugin CSS -->
    <link href="includes/template/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="includes/vendors/summernote2/dist/summernote-bs4.css">
    <link rel="stylesheet" type="text/css" href="includes/template/assets/libs/select2/dist/css/select2.min.css">
  <?php } ?>
  <!-- Custom CSS -->
  <link href="includes/template/assets/extra-libs/c3/c3.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="includes/template/dist/css/style.min.css" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

  <link rel="stylesheet" href="includes/css/site.css">
  <link rel="stylesheet" href="includes/css/devices.css">

  <link rel="stylesheet" href="includes/css/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  <script src="includes/js/sweetalert2.all.min.js"></script>
  <script src="includes/js/myalerts.js"></script>
  <!--respons-->
  <script src="includes/js/respond.min.js"></script>
</head>

<body>
  <?php if ($user->isLoggedIn()) { ?>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>
  <?php } ?>
  <!-- ============================================================== -->
  <!-- Main wrapper - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <div id="main-wrapper">