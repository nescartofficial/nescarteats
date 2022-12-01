<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <base href="<?= SITE_URL ?>">
  <title>Nescart</title>

  <link rel="shortcut icon" href="assets/logo/Nescart Eats Icon.png" type="image/x-icon">

  <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&display=swap" rel="stylesheet"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">


  <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
  <!--<link rel="stylesheet" href="https://unpkg.com/flickity-fullscreen@2/fullscreen.css">-->

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
  <link rel="stylesheet" type="text/css" href="assets/vendors/slick/slick.css" />
  <link rel="stylesheet" type="text/css" href="assets/vendors/slick/slick-theme.css" />

  <?php if ($user->isLoggedIn()) { ?>
    <?php if ($user->data()->vendor) { ?>

      <?php if (Input::get('action') && Input::get('action') == 'manage-menus') { ?>
        <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/base.min.css" />-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

        <link rel="stylesheet" type="text/css" href="assets/vendors/select2/select2.min.css" />
        <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
        <link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
      <?php } ?>
    <?php } else { ?>
    <?php } ?>
  <?php } ?>

  <link rel="stylesheet" href="assets/css/style.min.css">


  <link rel="stylesheet" type="" href="assets/vendors/sweetalert2.min.css">
  <script src="assets/vendors/sweetalert2.all.min.js"></script>
  <script src="assets/js/myalerts.js"></script>
</head>

<body class="<?= $user->isLoggedIn() ? (Input::get('page') && Input::get('page') == 'dashboard' ? 'dashboard  bg-primary--light' : 'dashboard') : null; ?> <?= $user->isLoggedIn() && $user->data()->vendor ? 'vendor' : 'user' ?>">