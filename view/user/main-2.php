<?php
$user = new User();
$categories = new General('categories');
$pagination = new Pagination();
$orders = new Orders();
$vendors = new Vendors();
$menus = new Menus();
$notifications = new General('notifications');

$profile = $user->getProfile();
$user_id = $user->data()->id;

// Nav counters
$unread_notification_count = $pagination->countAll('notifications', "WHERE id > 0 AND user_id = {$user_id} AND status = 0");

$recent_orders = $orders->getPages(4, 0, "WHERE user_id = {$user_id}");

$wish_count = $awaiting_count = $delivered_count = 0;


$searchTerm = "WHERE user_id = {$user->data()->id}";
$order_count = $pagination->countAll('orders', $searchTerm);

$searchTerm = "WHERE user_id = {$user->data()->id} AND status = 2";
$awaiting_count = $pagination->countAll('orders', $searchTerm);

$searchTerm = "WHERE user_id = {$user->data()->id} AND status = 3";
$delivered_count = $pagination->countAll('orders', $searchTerm);


$searchTerm = "WHERE user_id = {$user->data()->id}";
$wish_count = $pagination->countAll('saved_items', $searchTerm);

$notification_list = $notifications->getPages(4, 0, "WHERE id > 0 AND user_id = {$user_id}");

$views = 'view/user';

Alerts::displayError();
Alerts::displaySuccess();
?>

<!-- Homepage -->
<section class="dashboard bg-primary--light py-5">
    <!-- Header -->
    <header class="header container-fluid mb-5">
        <div class="container">
            <div class="row gy-4 justify-content-between">

                <div class="col-lg-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Delivery -->
                        <div class="d-flex align-items-center">
                            <div class="icon-60 d-flex align-items-center justify-content-center">
                                <?php if ($profile->image && $profile->image != 'user-avatar.jpg') { ?>
                                    <img class="rounded-circle img-sm border mr-2" src="assets/images/profile/<?= $profile->image ?>" style="height: 50px; width: 50px; object-fit:cover; object-position:center; border-radius: 100%;">
                                <?php } else { ?>
                                    <div style="background: red;display: flex; align-items:center; justify-content: center; height: 50px; width: 50px; object-fit:cover; object-position:center; border-radius: 100%;">
                                        <span class="fw-bold fs-24p" style="color:white;"><?= $user->data()->first_name[0] ?></span>
                                    </div>
                                <?php } ?>
                            </div>

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
                        </div>

                        <!-- Notification -->
                        <a href="dashboard/notifications" class="p-2 border-0 rounded bg-white">
                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 59.5 85.944">
                                <path id="Notification" d="M41.188,8.813V9.946C51.94,13.307,59.781,23.771,59.781,36.159V63.505H63.5v7.813H4V63.505H7.719V36.159c0-12.388,7.841-22.852,18.594-26.214V8.813A7.632,7.632,0,0,1,33.75,1,7.632,7.632,0,0,1,41.188,8.813Zm0,70.318V75.225H26.313v3.907a7.632,7.632,0,0,0,7.438,7.813A7.632,7.632,0,0,0,41.188,79.131Z" transform="translate(-4 -1)" fill-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <figure class="d-flex icontext mb-4">
                        <div class="text">
                            <p class="mb-1">Hey, <span class="fw-bold"><?= $user->data()->first_name; ?></span></p>
                            <p class="fw-bold fs-16p mb-0">What will you like to eat today?</p>
                        </div>
                    </figure>

                    <section class="search d-flex align-items-stretch justify-content-between">
                        <form action="search" method="get" class="search-header flex-fill me-3">
                            <div class="input-group w-100">
                                <span class="input-group-text">
                                    <svg id="search" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 65.756 65.756">
                                        <path id="search-2" data-name="search" d="M53.82,46.433a27.833,27.833,0,1,0-4.911,4.911q.07.08.147.156L63.817,66.262a3.479,3.479,0,0,0,4.92-4.92L53.976,46.581Q53.9,46.5,53.82,46.433ZM46.6,14.6a20.875,20.875,0,1,1-29.522,0A20.875,20.875,0,0,1,46.6,14.6Z" transform="translate(-4 -1.525)" fill="#8a8a8a" fill-rule="evenodd" />
                                    </svg>
                                </span>
                                <input type="text" name="keyword" class="form-control" placeholder="Search for a meal or vendor">
                            </div>
                        </form>

                        <button class="p-2 border-0 rounded bg-white">
                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 65.756 65.754">
                                <path id="Filter" d="M16.212,3.792c0-2.021-1.637-2.192-3.653-2.192s-3.653.171-3.653,2.192V34.478h7.306V3.792ZM8.906,65.164c0,2.014,1.632,2.192,3.653,2.192s3.653-.178,3.653-2.192V52.744H8.906ZM21.322,38.131H3.787c-2.021,0-2.187,1.637-2.187,3.653v3.653c0,2.021.169,3.653,2.187,3.653h17.54c2.009,0,2.192-1.632,2.192-3.653V41.784C23.519,39.768,23.336,38.131,21.322,38.131ZM65.16,41.784H47.62c-2.016,0-2.185,1.637-2.185,3.653v3.653c0,2.021.169,3.653,2.187,3.653h17.54c2.009,0,2.192-1.632,2.192-3.653V45.437c0-2.016-.183-3.653-2.2-3.653ZM45.437,23.519c0-2.016-.183-3.653-2.2-3.653H25.706c-2.021,0-2.187,1.637-2.187,3.653v3.653c0,2.021.169,3.653,2.187,3.653h17.54c2.009,0,2.192-1.632,2.192-3.653ZM38.131,3.792c0-2.021-1.637-2.192-3.653-2.192s-3.653.171-3.653,2.192V16.212h7.306ZM30.825,65.164c0,2.014,1.632,2.192,3.653,2.192s3.653-.178,3.653-2.192V34.478H30.825V65.164ZM60.05,3.792C60.05,1.771,58.413,1.6,56.4,1.6s-3.653.171-3.653,2.192V38.131H60.05V3.792ZM52.744,65.164c0,2.014,1.632,2.192,3.653,2.192s3.653-.178,3.653-2.192V56.4H52.744Z" transform="translate(-1.6 67.354) rotate(-90)" fill="#ef9244" />
                            </svg>
                        </button>
                    </section>

                </div>
            </div>
        </div>
    </header>


    <!-- Category List -->
    <section class="container-fluid mb-5">
        <div class="container">
            <?php Component::render('category', array('data' => $categories->getAll(1, 'status', '='), 'type' => 'list')); ?>
        </div>
    </section>

    <!-- Popular Meals -->
    <section class="container-fluid mb-5">
        <div class="container">
            <?php Component::render('menu', array('data' => $menus->getAll(1, 'status', '='), 'type' => 'list', 'title' => "Popular Meals")); ?>
        </div>
    </section>

    <!-- Meals Near You -->
    <section class="container-fluid mb-5">
        <div class="container">
            <?php Component::render('menu', array('data' => $menus->getAll(1, 'status', '='), 'type' => 'list-slide', 'title' => "Meals Near You")); ?>
        </div>
    </section>

    <!-- Top Vendors -->
    <section class="container-fluid mb-5">
        <div class="container">
            <?php Component::render('vendor', array('data' => $vendors->getAll(1, 'status', '='), 'type' => 'list', 'title' => "Top Vendors")); ?>
        </div>
    </section>

    <!-- Top Vendors -->
    <section class="container-fluid">
        <div class="container">
            <?php Component::render('vendor', array('data' => $vendors->getAll(1, 'status', '='), 'type' => 'single', 'title' => "Vendor Near You")); ?>
        </div>
    </section>

</section>

<div class="dashboard container py-5">
    <div class="row">
        <div class="col-lg-3 mb-5 mb-lg-0 flex-lg-grow-0">
            <div class="card border-0 card-category h-100">
                <div class="card-body w-100 nav-home-aside">
                    <figure class="d-flex py-3 icontext">
                        <div class="icon me-4">
                            <?php if ($user->data()->type && $profile->image && $profile->image != 'user-avatar.jpg') { ?>
                                <img class="rounded-circle img-sm border mr-2" src="media/images/profile/<?= $profile->image ?>" style="height: 50px; width: 50px; object-fit:cover; object-position:center; border-radius: 100%;">
                            <?php } else { ?>
                                <div style="background: red;display: flex; align-items:center; justify-content: center; height: 50px; width: 50px; object-fit:cover; object-position:center; border-radius: 100%;">
                                    <span style="color:white;"><?= $user->data()->first_name[0] . $user->data()->last_name[0] ?></span>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="text">
                            <p class="mb-0 font-weight-bold"> <?= $user->data()->first_name . ' ' . $user->data()->last_name; ?> </p>
                            <p class="fs-14p mb-0"><?= $user->data()->email; ?> </p>
                        </div>
                    </figure>
                    <hr class="d-none d-lg-block my-3">
                    <h6 class="title-category d-lg-none">MENU <i class="d-md-none icon fa fa-chevron-down"></i></h6>
                    <ul class="menu-category sidebar  list-group list-group-flush">
                        <a class="list-group-item list-group-item-action  <?= !Input::get('action') ? 'active' : null; ?>" href="dashboard">
                            <img class="icon-menu mr-2" src="assets/icons/Dashboard.svg" alt="Icon" /> Dashboard </a>
                        <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'orders' || Input::get('action') == 'order-details' ? 'active' : null; ?>" href="dashboard/orders">
                            <img class="icon-menu mr-2" src="assets/icons/My Orders.svg" alt="Icon" /> My Orders </a>
                        <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'saved-items' ? 'active' : null; ?>" href="dashboard/saved-items">
                            <img class="icon-menu mr-2" src="assets/icons/Saved Items.svg" alt="Icon" /> Saved Item</a>
                        <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'reviews' ? 'active' : null; ?>" href="dashboard/reviews">
                            <img class="icon-menu mr-2" src="assets/icons/Review.svg" alt="Icon" /> Reviews </a>
                        <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'wallet' ? 'active' : null; ?>" href="dashboard/wallet">
                            <img class="icon-menu mr-2" src="assets/icons/wallet.svg" alt="Icon" /> Wallet</a>
                        <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'messages' ? 'active' : null; ?>" href="dashboard/messages">
                            <img class="icon-menu mr-2" src="assets/icons/Messages.svg" alt="Icon" /> Messages</a>

                        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center  <?= Input::get('action') && Input::get('action') == 'notifications' ? 'active' : null; ?>" href="dashboard/notifications">
                            <div>
                                <img class="icon-menu mr-2" src="assets/icons/Notifications.svg" alt="Icon" />
                                Notifications
                            </div>
                            <?php if ($unread_notification_count) { ?>
                                <span class="badge badge-primary badge-pill"><?= $unread_notification_count ?></span>
                            <?php } ?>
                        </a>

                        <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'address' ? 'active' : null; ?>" href="dashboard/address">
                            <img class="icon-menu mr-2" src="assets/icons/Addressbook.svg" alt="Icon" /> Address Book</a>
                        <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'profile' ? 'active' : null; ?>" href="dashboard/profile">
                            <img class="icon-menu mr-2" src="assets/icons/Profile.svg" alt="Icon" /> Profile </a>
                        <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'change-password' ? 'active' : null; ?>" href="dashboard/change-password">
                            <img class="icon-menu mr-2" src="assets/icons/Change Password.svg" alt="Icon" /> Change Password </a>
                        <a class="list-group-item list-group-item-action" href="controllers/logout.php">
                            <img class="icon-menu mr-2" src="assets/icons/Log-out.svg" alt="Icon" /> Log out</a>
                    </ul>
                </div>
            </div>
        </div>


        <div class="col-lg-9 d-flex flex-column">
            <?php if (Input::get('page') && Input::get('page') == 'dashboard' && Input::get('action')) {
                Template::render(Input::get('action'), $views);
            } else { ?>
                <div class="row h-100">
                    <!-- Counter -->
                    <div class="col-6 col-lg-3 mb-4">
                        <div class="card border-0 shadow-sm" style="min-height: 149px;">
                            <div class="card-body">
                                <img class="icon-40 mb-3" src="assets/icons/Orders.svg" alt="icon" />
                                <h3><?= $order_count ? $order_count : 0; ?> <span class="mb-0 fs-16p"> Orders</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 mb-4">
                        <div class="card border-0 shadow-sm bg-blue-shade" style="min-height: 149px;">
                            <div class="card-body">
                                <img class="icon-40 mb-3" src="assets/icons/Wishlist Items.svg" alt="icon" />
                                <h3><?= $wish_count ? $wish_count : 0; ?> <span class="mb-0 fs-16p">Wishlist Items</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 mb-4">
                        <div class="card border-0 shadow-sm bg-yellow-shade" style="min-height: 149px;">
                            <div class="card-body">
                                <img class="icon-40 mb-3" src="assets/icons/Awaiting Delivery.svg" alt="icon" />
                                <h3><?= $awaiting_count ? $awaiting_count : 0; ?> <span class="mb-0 fs-16p">Awaiting Delivery</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 mb-5 mb-lg-4">
                        <div class="card border-0 shadow-sm bg-green-shade" style="min-height: 149px;">
                            <div class="card-body">
                                <img class="icon-40 mb-3" src="assets/icons/Items Delivered.svg" alt="icon" />
                                <h3><?= $delivered_count ? $delivered_count : 0; ?><span class="mb-0 fs-16p"> Items Delivered</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="col-lg-6 mb-5 mb-lg-0" style="min-height: 510px;">
                        <div class="card mobile-card-section border-0 h-100">
                            <div class="card-body">
                                <header class="d-flex justify-content-between mb-4">
                                    <h5 class=""><img class="icon-30 mr-2" src="assets/icons/Orders.svg" alt="mp icon" /> Recent orders </h5>
                                    <a href="dashboard/orders" class="text-accent">view</a>
                                </header>

                                <section class="row">
                                    <div class="col-md-12">
                                        <?php if ($recent_orders) { ?>
                                            <?php foreach ($recent_orders as $index => $con) {
                                                $product_list = json_decode($con->details);
                                                $prod = $menus->get($product_list[0]->id);

                                                $status = $con->status == 1 ? 'Pending' : 'Rejected';
                                                $status_color = $con->status == 1 ? 'text-yellow' : 'text-primary';
                                                $status = $con->status == 2 ? 'Awaiting Delivery' : $status;
                                                $status_color = $con->status == 2 ? 'text-yelow' : $status_color;
                                                $status = $con->status == 3 ? 'Completed' : $status;
                                                $status_color = $con->status == 3 ? 'text-green' : $status_color;
                                            ?>
                                                <div class="list-group list-group-flush">
                                                    <a href="dashboard/order-details/<?= $con->invoice ?>" class="d-block list-group-item list-group-item-action">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center w-100">
                                                                <img src="media/images/product/<?= $prod->cover; ?>" alt="" class="img-fluid mr-3" style="height: 65px; width: 65px; object-fit: cover;">
                                                                <div class="w-100 d-flex justify-content-between">
                                                                    <div class="mt-2">
                                                                        <div class="mb-1">
                                                                            <span class="small mr-3"><?= date_format(date_create($con->created), 'M d, Y'); ?></span>
                                                                            <span class="small font-weight-bold <?= $status_color ?>"><?= $status ?></span>
                                                                        </div>
                                                                        <h6 class="mb-1"><?= $con->order_id ?></h6>
                                                                        <span class="fs-12p">Quantity: <?= count($product_list); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h6 class="mb-0"><?= Helpers::format_currency($con->total_amount); ?></h6>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="h-100 d-flex align-items-center justify-content-center py-5">
                                                <div class="text-center">
                                                    <i class="fa fa-shopping-cart fa-2x"></i>
                                                    <h4 class="mt-3 mb-4 font-weight-bold">You have made no order.</h4>
                                                    <a href="category" class="btn">Start Shopping</a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Notification -->
                    <div class="col-lg-6 mb-5 mb-lg-0" style="min-height: 510px;">
                        <div class="card mobile-card-section border-0 h-100">
                            <div class="card-body">
                                <header class="d-flex justify-content-between mb-4">
                                    <h5 class=""><img class="icon-30 mr-2" src="assets/icons/Notifications-1.svg" alt="mp icon" /> Notifications </h5>
                                    <a href="dashboard/notifications" class="text-accent">view</a>
                                </header>

                                <section class="row">
                                    <div class="col-md-12">
                                        <?php if ($notification_list) { ?>
                                            <ul class="list-group list-group-flush">
                                                <?php foreach ($notification_list as $index => $con) {
                                                    $id = $con->id;
                                                    $date_added = $con->date_added;
                                                    $status = $con->status;
                                                    if ($con->snippet_id) {
                                                        $con = $notification_snippets->get($con->snippet_id);
                                                    } ?>
                                                    <div class="list-group-item d-flex justify-content-between">
                                                        <div class="col-12">
                                                            <span class="small"><?= date_format($date_added, 'M d, Y'); ?></span>
                                                            <h5><?= $con->message ?></h5>
                                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                                <div>
                                                                    <span class="badge <?= $status ? 'bg-green' : 'bg-yellow' ?> px-2 br-2p"><?= $status ? 'Read' : 'Unread' ?></span>
                                                                    <a href="controllers/notifications.php?rq=status&id=<?= $id ?>" class="font-weight-bold text-site-accent fs-12p ml-3"><?= $status ? 'Mark as Unread' : 'Mark as Read' ?></a>
                                                                </div>
                                                                <a href="controllers/notifications.php?rq=delete&id=<?= $id ?>" class="font-weight-bold text-site-accent fs-12p"><i class="fa fa-trash-alt"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </ul>
                                        <?php } else { ?>
                                            <div class="h-100 d-flex align-items-center justify-content-center py-5">
                                                <div class="text-center">
                                                    <i class="fa fa-bell fa-2x"></i>
                                                    <h4 class="mt-3 mb-4 font-weight-bold">You have no new notification.</h4>
                                                    <!--<a href="category" class="btn">Start Shopping</a>-->
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>

                </div>

            <?php } ?>
        </div>
    </div>
</div>