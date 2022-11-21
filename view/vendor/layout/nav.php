<?php
$user = new User();
$pagination = new Pagination();

$vendor = $user->getVendor();
$searchTerm = $vendor ? "WHERE id > 0 AND status = 1 AND details LIKE '%seller_:_{$vendor->id}_%' " : null;
$pending_count = $searchTerm ? $pagination->countAll('orders', $searchTerm) : 0;
// Nav counters
$unread_notification_count = $vendor ? $pagination->countAll('notifications', "WHERE id > 0 AND user_id = {$vendor->user_id} AND status = 0") : 0;
?>

<header class="navbar navbar-light sticky-top bg-white flex-md-nowrap py-3">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">
        <img src="assets/logo/Nescart Eats Logo HFC.png" style="height:28px;" alt="" class="img-fluid">
    </a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="col-md-3 col-lg-2 me-auto d-none d-md-block">
        <input class="form-control" type="text" placeholder="Search" aria-label="Search">
    </div>

    <div class="navbar-nav d-none">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="#">Sign out</a>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse">
            <div class="position-sticky pt-3">

                <div class="logo d-none mb-4 p-3">
                    <img src="assets/logo/Nescart Eats Logo HFC.png" style="height:50px;" alt="" class="img-fluid">
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link  <?= !Input::get('action') ? 'active' : null; ?>" href="dashboard">
                            <img class="icon-menu me-2" src="assets/icons/Dashboard.svg" alt="Icon" /> Dashboard </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center <?= Input::get('action') && Input::get('action') == 'orders' || Input::get('action') == 'order-details' ? 'active' : null; ?>" href="dashboard/orders">
                            <div><img class="icon-menu me-2" src="assets/icons/My Orders.svg" alt="Icon" /> My Orders </div>


                            <?php if ($pending_count) { ?>
                                <span class="badge badge-primary badge-pill"><?= $pending_count ?></span>
                            <?php } ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  <?= Input::get('action') && Input::get('action') == 'menus' ? 'active' : null; ?>" href="dashboard/menus">
                            <img class="icon-menu me-2" src="assets/icons/Products.svg" alt="Icon" /> Menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  <?= Input::get('action') && Input::get('action') == 'earnings' ? 'active' : null; ?>" href="dashboard/earnings">
                            <img class="icon-menu me-2" src="assets/icons/wallet.svg" alt="Icon" /> Earnings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  <?= Input::get('action') && Input::get('action') == 'messages' ? 'active' : null; ?>" href="dashboard/messages">
                            <img class="icon-menu me-2" src="assets/icons/Messages.svg" alt="Icon" /> Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center <?= Input::get('action') && Input::get('action') == 'notifications' ? 'active' : null; ?>" href="dashboard/notifications">
                            <div>

                                <img class="icon-menu me-2" src="assets/icons/Notifications.svg" alt="Icon" />
                                Notifications
                            </div>
                            <?php if ($unread_notification_count) { ?>
                                <span class="badge badge-primary badge-pill"><?= $unread_notification_count ?></span>
                            <?php } ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="controllers/logout.php">
                            <img class="icon-menu me-2" src="assets/icons/Log-out.svg" alt="Icon" /> Log out</a>
                    </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                    <span>Settings</span>
                    <a class="link-secondary" href="dashboard/profile" aria-label="Add a new report">
                        <img class="icon-menu me-2" src="assets/icons/Settings.svg" alt="Icon" />
                    </a>
                </h6>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link  <?= Input::get('action') && Input::get('action') == 'profile' || Input::get('action') == 'settings' ? 'active' : null; ?>" href="dashboard/profile">
                            <img class="icon-menu me-2" src="assets/icons/Sellers Profile.svg" alt="Icon" /> Profile </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  <?= Input::get('action') && Input::get('action') == 'store' ? 'active' : null; ?>" href="dashboard/store">
                            <img class="icon-menu me-2" src="assets/icons/Store.svg" alt="Icon" /> Restaurant</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  <?= Input::get('action') && Input::get('action') == 'bank' ? 'active' : null; ?>" href="dashboard/bank">
                            <img class="icon-menu me-2" src="assets/icons/Seller Payment Method.svg" alt="Icon" /> Payout Method</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  <?= Input::get('action') && Input::get('action') == 'change-password' ? 'active' : null; ?>" href="dashboard/change-password">
                            <img class="icon-menu me-2" src="assets/icons/Change Password.svg" alt="Icon" /> Change Password </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="content-container col-md-9 ms-sm-auto col-lg-10 px-md-4">