<?php
$user = isset($user) ? $user : new User();
$cart = new Cart();

$profile = $user->getProfile();
$user_id = $user->data()->id;
?>


<nav class="bg-white container-fluid top-nav navbar navbar-expand-lg shadow-sm d-none d-lg-block">
    <div class="container">
        <a class="navbar-brand" href="">
            <img src="assets/logo/Nescart Eats Logo HFC.png" style="height:50px;" alt="" class="img-fluid">
        </a>

        <div class="d-flex align-items-center justify-content-between ms-4 d-none d-lg-block">
            <!-- Delivery -->
            <div class="d-flex align-items-center me-4">
                <div class="icon-60 d-flex align-items-center justify-content-center">
                    <?php if ($profile && $profile->image && $profile->image != 'user-avatar.jpg') { ?>
                        <img class="rounded-circle img-sm border mr-2" src="assets/images/profile/<?= $profile->image ?>" style="height: 50px; width: 50px; object-fit:cover; object-position:center; border-radius: 100%;">
                    <?php } else { ?>
                        <div style="background: red;display: flex; align-items:center; justify-content: center; height: 50px; width: 50px; object-fit:cover; object-position:center; border-radius: 100%;">
                            <span class="fw-bold fs-24p" style="color:white;"><?= $user->data()->first_name[0] ?></span>
                        </div>
                    <?php } ?>
                </div>

                <?php if ($profile) { ?>
                    <div class="ms-3">
                        <p class="mb-0 fs-14p text-muted">Delivery Address</p>
                        <div class="d-flex">
                            <address class="fw-bold fs-16p mb-0 text-truncate me-2">
                                <?= $profile->address ?>
                            </address>
                            <a href="dashboard/profile">
                                <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="53.25" height="53.198" viewBox="0 0 53.25 53.198">
                                    <g id="Edit" transform="translate(-264.043 -93.099)">
                                        <path id="Path_966" data-name="Path 966" d="M46.366,2.744a2.539,2.539,0,0,0-3.591,0L40.56,4.959a7.621,7.621,0,0,0-8.671,1.489L4.955,33.381,19.32,47.745,46.253,20.812a7.621,7.621,0,0,0,1.489-8.671l2.215-2.215a2.539,2.539,0,0,0,0-3.591ZM35.528,24.354,19.32,40.563l-7.182-7.182L28.346,17.172Zm4.617-4.617,2.516-2.516a2.539,2.539,0,0,0,0-3.591l-3.591-3.591a2.539,2.539,0,0,0-3.591,0l-2.516,2.516Z" transform="translate(266.593 91.099)" fill="#8a8a8a" fill-rule="evenodd" />
                                        <path id="Path_967" data-name="Path 967" d="M2,34.923,7.388,15.172,21.751,29.537Z" transform="translate(262.043 111.374)" fill="#8a8a8a" />
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-md-auto">
                <li class="nav-item me-4">
                    <a class="nav-link" href="dashboard">Dashboard</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link" href="dashboard/orders">Orders</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link" href="dashboard/saved-items">Favourite</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link" href="dashboard/settings">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="controllers/logout.php">Sign Out</a>
                </li>
            </ul>
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

<?php if ($user->isLoggedIn() && !$user->data()->vendor) { ?>
    <nav class="fixed-bottom container-fluid mobile bg-white pb-3 pt-3 d-lg-none">
        <div class="container d-flex gap-3 justify-content-between">
            <a class="nav-link <?= !Input::get('action') ? 'active' : null; ?>" href="dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" width="56.263" height="49.854" viewBox="0 0 56.263 49.854">
                    <?php if (!Input::get('action')) { ?>
                        <path id="Home_Menu_Fill" data-name="Home Menu Fill" d="M56.469,31.22h-5.18V49.808a2.821,2.821,0,0,1-3.1,3.1H35.8V34.318H23.407V52.907H11.015a2.821,2.821,0,0,1-3.1-3.1V31.22H2.737c-1.853,0-1.456-1-.186-2.318L27.409,4.019a2.974,2.974,0,0,1,4.388,0L56.653,28.9c1.274,1.317,1.669,2.32-.182,2.32Z" transform="translate(-1.471 -3.053)" fill="#008e8c" />
                    <?php } else { ?>
                        <path id="Home_Menu_Outline" data-name="Home Menu Outline" d="M56.469,31.22h-5.18V49.808a2.821,2.821,0,0,1-3.1,3.1H35.8V34.318H23.407V52.907H11.015a2.821,2.821,0,0,1-3.1-3.1V31.22H2.737c-1.853,0-1.456-1-.186-2.318L27.409,4.019a2.974,2.974,0,0,1,4.388,0L56.653,28.9c1.274,1.317,1.669,2.32-.182,2.32Z" transform="translate(0.53 -1.052)" fill="none" stroke="#000" stroke-width="4" />
                    <?php } ?>
                </svg>

                <span class="d-block">Home</span>
            </a>
            <a class="nav-link <?= Input::get('action') && Input::get('action') == 'saved-items' ? 'active' : null; ?>" href="dashboard/saved-items">
                <svg xmlns="http://www.w3.org/2000/svg" width="64.577" height="54.003" viewBox="0 0 64.577 54.003">
                    <?php if (Input::get('action') && Input::get('action') == 'saved-items') { ?>
                        <path id="Favourite_Menu_Fill" data-name="Favourite Menu Fill" d="M57.85,8.859a16.056,16.056,0,0,0-21.324,0l-4,3.669-4-3.669a16.05,16.05,0,0,0-21.32,0,14.658,14.658,0,0,0,0,22L32.529,54.1,57.85,30.856a14.664,14.664,0,0,0,0-22Z" transform="translate(-2.239 -4.807)" fill="#008e8c" />
                    <?php } else { ?>
                        <path id="Favourite_Menu_Outline" data-name="Favourite Menu Outline" d="M57.85,8.859a16.056,16.056,0,0,0-21.324,0l-4,3.669-4-3.669a16.05,16.05,0,0,0-21.32,0,14.658,14.658,0,0,0,0,22L32.529,54.1,57.85,30.856a14.664,14.664,0,0,0,0-22Z" transform="translate(-0.239 -2.807)" fill="none" stroke="#000" stroke-width="4" />
                    <?php } ?>
                </svg>

                <span class="d-block">Favourite</span>
            </a>

            <a class="nav-link <?= Input::get('action') && Input::get('action') == 'orders' ? 'active' : null; ?>" href="dashboard/orders">
                <svg xmlns="http://www.w3.org/2000/svg" width="61.751" height="58.778" viewBox="0 0 61.751 58.778">
                    <?php if (Input::get('action') && Input::get('action') == 'orders') { ?>
                        <path id="Orders_Menu_Fill" data-name="Orders Menu Fill" d="M57.422,20.851H41.358l-5.813,5.813A7.7,7.7,0,0,1,22.4,21.222c0-.124.032-.247.036-.373H3.525A1.923,1.923,0,0,0,1.6,22.774v7.7H59.352v-7.7a1.928,1.928,0,0,0-1.929-1.925Zm-24.482,3.2L49.159,7.837a1.922,1.922,0,0,0,.006-2.723L46.213,2.162a1.936,1.936,0,0,0-2.727,0L27.267,18.384a4.011,4.011,0,0,0,5.673,5.673ZM10.658,53.805a3.221,3.221,0,0,0,2.974,2.338H47.32a3.224,3.224,0,0,0,2.974-2.338l4.887-20.121H5.771Z" transform="translate(-1.6 -1.6)" fill="#008e8c" />
                    <?php } else { ?>
                        <path id="Orders_Menu_Outline" data-name="Orders Menu Outline" d="M57.422,20.851H41.358l-5.813,5.813A7.7,7.7,0,0,1,22.4,21.222c0-.124.032-.247.036-.373H3.525A1.923,1.923,0,0,0,1.6,22.774v7.7H59.352v-7.7a1.928,1.928,0,0,0-1.929-1.925Zm-24.482,3.2L49.159,7.837a1.922,1.922,0,0,0,.006-2.723L46.213,2.162a1.936,1.936,0,0,0-2.727,0L27.267,18.384a4.011,4.011,0,0,0,5.673,5.673ZM10.658,53.805a3.221,3.221,0,0,0,2.974,2.338H47.32a3.224,3.224,0,0,0,2.974-2.338l4.887-20.121H5.771Z" transform="translate(0.4 0.635)" fill="none" stroke="#000" stroke-width="4" />
                    <?php } ?>
                </svg>

                <span class="d-block">Orders</span>
            </a>

            <a class="nav-link <?= Input::get('action') && Input::get('action') == 'settings' ? 'active' : null; ?>" href="dashboard/settings">
                <svg xmlns="http://www.w3.org/2000/svg" width="59.597" height="65.117" viewBox="0 0 59.597 65.117">
                    <?php if (Input::get('action') && Input::get('action') == 'settings') { ?>
                        <path id="Profile_Menu_Fill" data-name="Profile Menu Fill" d="M22.372,6.315a9.666,9.666,0,0,0-3.826,8c.2,2.405.685,5.538.685,5.538s-.967.525-.967,2.637c.336,5.3,2.11,3.015,2.475,5.34.876,5.6,2.882,4.606,2.882,7.664,0,5.093-2.1,7.475-8.658,10.3C8.384,48.627,1.6,52.194,1.6,58.372v3.089H57.2V58.372c0-6.177-6.786-9.745-13.368-12.577-6.558-2.822-8.652-5.2-8.652-10.3,0-3.058,2-2.06,2.878-7.664.367-2.326,2.137-.037,2.481-5.34,0-2.112-.969-2.637-.969-2.637s.488-3.131.683-5.538a9.545,9.545,0,0,0-7.1-9.563c-1.029-1.05-1.724-2.722,1.44-4.4-6.919-.324-8.529,3.3-12.212,5.957Z" transform="translate(-1.6 -0.337)" fill="#008e8c" />
                    <?php } else { ?>
                        <path id="Profile_Menu_Outline" data-name="Profile Menu Outline" d="M22.372,6.315a9.666,9.666,0,0,0-3.826,8c.2,2.405.685,5.538.685,5.538s-.967.525-.967,2.637c.336,5.3,2.11,3.015,2.475,5.34.876,5.6,2.882,4.606,2.882,7.664,0,5.093-2.1,7.475-8.658,10.3C8.384,48.627,1.6,52.194,1.6,58.372v3.089H57.2V58.372c0-6.177-6.786-9.745-13.368-12.577-6.558-2.822-8.652-5.2-8.652-10.3,0-3.058,2-2.06,2.878-7.664.367-2.326,2.137-.037,2.481-5.34,0-2.112-.969-2.637-.969-2.637s.488-3.131.683-5.538a9.545,9.545,0,0,0-7.1-9.563c-1.029-1.05-1.724-2.722,1.44-4.4-6.919-.324-8.529,3.3-12.212,5.957Z" transform="translate(0.4 1.656)" fill="none" stroke="#000" stroke-width="4" />
                    <?php } ?>
                </svg>

                <span class="d-block">Profile</span>
            </a>
        </div>
    </nav>
<?php } ?>

<?php
$user = isset($user) ? $user : new User();

Alerts::displayError();
Alerts::displaySuccess();
?>