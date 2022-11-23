<?php
$user = isset($user) ? $user : new User();
$cart = new Cart();

Alerts::displayError();
Alerts::displaySuccess();
?>

<nav class="navbar navbar-expand-lg container-fluid top-nav shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="">
            <img src="assets/logo/Nescart Eats Logo HFC.png" style="height:50px;" alt="" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                    <img src="assets/logo/Nescart Eats Logo HFC.png" style="height:50px;" alt="" class="img-fluid">
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1">
                    <li class="nav-item me-4">
                        <a class="nav-link <?= Input::is('vendor') && !Input::get('action') ? 'active' : null; ?>" href="<?= VENDOR_URL ?>">Become a Vendor</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link <?= Input::is('contact') ? 'active' : null; ?>" href="contact">Contact Us</a>
                    </li>
                    <?php if ($user->isLoggedIn()) { ?>
                        <li class="nav-item me-4">
                            <a class="nav-link <?= Input::is('dashboard') ? 'active' : null; ?>" href="dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="controllers/logout.php">Sign Out</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item me-4">
                            <a class="nav-link" href="sign-in">Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="sign-up">Sign Up</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</nav>


<?php if (Input::get('page') && Input::get('page') == 'cart' || Input::get('page') == 'checkout') { ?>
<?php } else { ?>
    <a href="cart" class="<?= $cart->isEmpty() ? 'd-none' : null; ?> nav-cart rounded px-2 d-flex fixed-bottom bg-accent align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 61.751 58.778">
            <path id="Orders_Menu_Outline" data-name="Orders Menu Outline" d="M57.422,20.851H41.358l-5.813,5.813A7.7,7.7,0,0,1,22.4,21.222c0-.124.032-.247.036-.373H3.525A1.923,1.923,0,0,0,1.6,22.774v7.7H59.352v-7.7a1.928,1.928,0,0,0-1.929-1.925Zm-24.482,3.2L49.159,7.837a1.922,1.922,0,0,0,.006-2.723L46.213,2.162a1.936,1.936,0,0,0-2.727,0L27.267,18.384a4.011,4.011,0,0,0,5.673,5.673ZM10.658,53.805a3.221,3.221,0,0,0,2.974,2.338H47.32a3.224,3.224,0,0,0,2.974-2.338l4.887-20.121H5.771Z" transform="translate(0.4 0.635)" fill="none" stroke="#fff" stroke-width="4" />
        </svg>

        <div class="ms-2">
            <small class="fs-12p mb-0 notify nav-cart-count">
                <span class="cart-count"><?= $cart->get_count(); ?></span> Items</small>
            <p class="fs-12p fw-bold mb-1"> Goto Cart </p>
        </div>
    </a>
<?php } ?>