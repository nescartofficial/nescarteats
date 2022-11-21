<?php
$user = new User();
$pagination = new Pagination();
$constants = new Constants();
$notifications = new General('notifications');
$notification_snippets  = new General('notification_snippets ');
$profiles = new General('profiles');
$orders = new General('orders');
$menus = new General('menus');
$wallets = new General('wallets');
$categories = new General('categories');

$vendor = $user->getVendor();
$verification = $user->getVerification();
$recent_orders = null;

if ($vendor) {
    // Nav counters
    $unread_notification_count = $pagination->countAll('notifications', "WHERE id > 0 AND user_id = {$vendor->user_id} AND status = 0");

    // Others
    $order_count = $pagination->countAll('orders', "WHERE id > 0 AND details LIKE '%seller_:_{$vendor->id}_%' ");
    $product_count = count($menus->getAll($user->data()->id, 'user_id', '='));
    $awaiting_count = $delivered_count = 0;

    $searchTerm = "WHERE id > 0 AND user_id = {$vendor->user_id} ";
    $recent_products = $menus->getPages(4, 0, $searchTerm);

    $searchTerm = "WHERE id > 0 AND details LIKE '%seller_:_{$vendor->id}_%' ";
    $order_count = $pagination->countAll('orders', $searchTerm);
    $paginate = new Pagination(1, $order_count, $order_count);
    $all_orders = $orders->getPages($order_count, $paginate->offset(), $searchTerm);

    $searchTerm = "WHERE id > 0 AND details LIKE '%seller_:_{$vendor->id}_%' ";
    $order_count = $pagination->countAll('orders', $searchTerm);
    $paginate = new Pagination(1, $order_count, $order_count);
    $recent_orders = $orders->getPages(2, $paginate->offset(), $searchTerm);

    $searchTerm = "WHERE id > 0 AND status = 1 AND details LIKE '%seller_:_{$vendor->id}_%' ";
    $pending_count = $pagination->countAll('orders', $searchTerm);
    $pending_orders = $orders->getPages(4, 0, $searchTerm);

    $searchTerm = "WHERE id > 0 AND status = 2 AND details LIKE '%seller_:_{$vendor->id}_%' ";
    $awaiting_count = $pagination->countAll('orders', $searchTerm);
    $awaiting_orders = $orders->getPages(4, 0, $searchTerm);

    $searchTerm = "WHERE id > 0 AND status = 3 AND details LIKE '%seller_:_{$vendor->id}_%' ";
    $delivered_count = $pagination->countAll('orders', $searchTerm);

    // Earnings....
    $uwallet = $user->getWallet();
    $total_earning = $uwallet->total_earning;
    $total_payout =  $uwallet->total_payout;
    $current_earning =  $uwallet->current_earning;
    $payout_balance =  $uwallet->payout_balance;
    $last_earning_date = $uwallet->last_earning_date;
    $today = date('Y-m-d H:i:s', time());

    $hour_countdown = Helpers::date_difference($today, $last_earning_date, 'hours');
    $min_countdown = Helpers::date_difference($today, $last_earning_date, 'minutes');

    // Update payout Balance
    if (($hour_countdown >= 0 && $min_countdown >= $constants->getText('BALANCE_UPDATE_DURATION')) && $current_earning > $payout_balance) { // implement by minute
        // Send NOTIFICATION/SMS to seller
        $notifications->create(array(
            'user_id' => $user->data()->id,
            'snippet_id' => $notification_snippets->get('S_BALANCE_UPDATED', 'title')->id,
        ));

        $wallets->update(array(
            'payout_balance' => (($current_earning - $payout_balance) + $payout_balance)
        ), $uwallet->id);
    }

    $current_date = date('Y-m');
    $searchTerm = "WHERE id > 0 AND delivery_date LIKE '%{$current_date}%' AND details LIKE '%seller_:_{$vendor->id}_%' AND details LIKE '%status_:_delivered_%' ";
    $order_count = $pagination->countAll('orders', $searchTerm);
    $paginate = new Pagination(1, $order_count, $order_count);
    $current_month_orders = $orders->getPages(5, $paginate->offset(), $searchTerm);

    // Monthly Earned - Delivered Delivery
    $monthly_earned = 0;
    if ($current_month_orders) {
        foreach ($current_month_orders as $k => $v) {
            $details = json_decode($v->details);
            foreach ($details as $kk => $kv) {
                if ($vendor->id == $kv->seller) {
                    $monthly_earned += $kv->commision_amount;
                }
            }
        }
    }
}

$views = 'view/vendor';

Alerts::displayError();
Alerts::displaySuccess();
?>





<div class="site-section dashboard container">
    <div class="row">
        <div class="col-lg-3 flex-lg-grow-0 mb-5 mb-lg-0">
            <div class="card border-0 card-category h-100">
                <div class="card-body nav-home-aside w-100 mb-0">
                    <?php if ($vendor) { ?>
                        <div class="mb-4">
                            <div class="d-flex mb-3">
                                <img class="rounded-circle img-sm me-3" src="assets/images/vendor/<?= $vendor->logo ?>" style="height: 50px; width: 50px; border-radius: 100%; object-fit: cover;">
                                <div class="text w-100">
                                    <h5 class="mb-0 fw-bold"><?= $vendor->name; ?></h5>
                                    <p class="mb-0" style="font-size: .9rem"><?= $user->data()->first_name . ' ' . $user->data()->last_name; ?> </p>
                                </div>
                            </div>
                        </div>
                        <?php if ($vendor->is_verified) { ?>
                            <button class="btn btn-lg bg-accent text-white  w-100 mb-5 shadow" data-bs-toggle="modal" data-bs-target="#newProductModal"><i class="fa fa-plus me-2"></i>Add Menu</button>
                        <?php } ?>
                    <?php } ?>

                    <h6 class="title-category mb-lg-4 d-lg-none">MENU <i class="d-md-none icon fa fa-chevron-down"></i></h6>
                    <ul class="menu-category sidebar list-group list-group-flush">
                        <a class="list-group-item list-group-item-action  <?= !Input::get('action') ? 'active' : null; ?>" href="dashboard">
                            <img class="icon-menu me-2" src="assets/icons/Dashboard.svg" alt="Icon" /> Dashboard </a>
                        <?php if (Input::get('action') && Input::get('action') == 'settings' || Input::get('action') == 'profile' || Input::get('action') == 'store' || Input::get('action') == 'verification' || Input::get('action') == 'bank' || Input::get('action') == 'change-password') { ?>

                            <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'profile' || Input::get('action') == 'settings' ? 'active' : null; ?>" href="dashboard/profile">
                                <img class="icon-menu me-2" src="assets/icons/Sellers Profile.svg" alt="Icon" /> Profile </a>
                            <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'store' ? 'active' : null; ?>" href="dashboard/store">
                                <img class="icon-menu me-2" src="assets/icons/Store.svg" alt="Icon" /> Restaurant</a>
                            <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'bank' ? 'active' : null; ?>" href="dashboard/bank">
                                <img class="icon-menu me-2" src="assets/icons/Seller Payment Method.svg" alt="Icon" /> Payout Method</a>
                            <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'change-password' ? 'active' : null; ?>" href="dashboard/change-password">
                                <img class="icon-menu me-2" src="assets/icons/Change Password.svg" alt="Icon" /> Change Password </a>
                        <?php } else { ?>
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= Input::get('action') && Input::get('action') == 'orders' || Input::get('action') == 'order-details' ? 'active' : null; ?>" href="dashboard/orders">
                                <div><img class="icon-menu me-2" src="assets/icons/My Orders.svg" alt="Icon" /> My Orders </div>

                                <?php if ($pending_count) { ?>
                                    <span class="badge badge-primary badge-pill"><?= $pending_count ?></span>
                                <?php } ?>
                            </a>
                            <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'menus' ? 'active' : null; ?>" href="dashboard/menus">
                                <img class="icon-menu me-2" src="assets/icons/Products.svg" alt="Icon" /> Menus</a>
                            <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'earnings' ? 'active' : null; ?>" href="dashboard/earnings">
                                <img class="icon-menu me-2" src="assets/icons/wallet.svg" alt="Icon" /> Earnings</a>
                            <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'messages' ? 'active' : null; ?>" href="dashboard/messages">
                                <img class="icon-menu me-2" src="assets/icons/Messages.svg" alt="Icon" /> Messages</a>
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= Input::get('action') && Input::get('action') == 'notifications' ? 'active' : null; ?>" href="dashboard/notifications">
                                <div>
                                    <img class="icon-menu me-2" src="assets/icons/Notifications.svg" alt="Icon" />
                                    Notifications
                                </div>
                                <?php if ($unread_notification_count) { ?>
                                    <span class="badge badge-primary badge-pill"><?= $unread_notification_count ?></span>
                                <?php } ?>
                            </a>
                            <a class="list-group-item list-group-item-action  <?= Input::get('action') && Input::get('action') == 'settings' ? 'active' : null; ?>" href="dashboard/settings">
                                <img class="icon-menu me-2" src="assets/icons/Settings.svg" alt="Icon" /> Settings</a>
                        <?php } ?>
                        <a class="list-group-item list-group-item-action" href="controllers/logout.php">
                            <img class="icon-menu me-2" src="assets/icons/Log-out.svg" alt="Icon" /> Log out</a>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <?php
            if (Input::get('action')) {
                if ($vendor && $vendor->is_verified) {
                    Template::render(Input::get('action'), $views);
                } else {
                    if (Input::get('action') == 'profile' || Input::get('action') == 'store' || Input::get('action') == 'verification') {
                        Template::render(Input::get('action'), $views);
                    } else {
                        Template::render('awaiting-verification', $views);
                    }
                }
            } else { ?>

                <?php if (!$vendor->is_verified) { ?>
                    <div class="alert alert-danger text-center">
                        <span>We're reviewing your restaurant activation request! Please expect feedback within 24 hours via your email address provided during registration. Thank You.</span>
                    </div>
                <?php } ?>

                <section class="row">
                    <div class="col-md-8 mb-4">
                        <!-- Orders -->
                        <div class="row px-2 px-md-0">
                            <div class="col-md-4 mb-4">
                                <div class="card shadow border-0 h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-3">
                                            <img src="assets/icons/Orders.svg" alt="Orders" style="width: 40px;">
                                            <a href="dashboard/orders"><i class="fa fa-external-link-alt"></i></a>
                                        </div>
                                        <span class="font-weight-bold text-black" style="font-size: 12px;">Total Orders</span>
                                        <h3 class="title mb-0"><?= $order_count ? $order_count : 0; ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow border-0 h-100 bg-yellow-shade">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-3">
                                            <img src="assets/icons/Awaiting Delivery.svg" alt="Awaiting Order" style="width: 40px;">
                                            <a href="dashboard/orders"><i class="fa fa-external-link-alt"></i></a>
                                        </div>
                                        <span class="font-weight-bold text-black" style="font-size: 12px;">Orders Awaiting Delivery</span>
                                        <h3 class="title mb-0"><?= $awaiting_count ? $awaiting_count : 0; ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card shadow border-0 h-100 bg-green-shade">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-3">
                                            <img src="assets/icons/Items%20Delivered.svg" style="width: 40px;">
                                            <a href="dashboard/orders"><i class="fa fa-external-link-alt"></i></a>
                                        </div>
                                        <span class="font-weight-bold text-black" style="font-size: 12px;">Completed Orders</span>
                                        <h3 class="title mb-0"><?= $delivered_count ? $delivered_count : 0; ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Order: Mobile -->
                        <div class="row px-2 px-md-0 d-lg-none">
                            <div class="col-lg-12 mb-4 <?= $recent_orders && count($recent_orders) < 4 ? null : 'overflow-auto'; ?>">
                                <div class="card shadow mobile-card-section border-0  <?= $recent_orders && count($recent_orders) < 4 ? 'h-100' : null; ?>">
                                    <div class="card-body">
                                        <div class="mb-4 d-flex justify-content-between">
                                            <h5 class="">Recent Orders</h5>
                                            <a href="dashboard/orders" class="text-site-accent">View all</a>
                                        </div>
                                        <?php if ($recent_orders) { ?>
                                            <?php foreach ($recent_orders as $k => $v) {
                                                $us = $user->get($v->user_id);
                                                $profile = $profiles->get($v->user_id, 'user_id');
                                                $details = json_decode($v->details);
                                                $detail_list = array_filter($details, function ($order) use ($vendor) {
                                                    return $order->seller == $vendor->id;
                                                });
                                                $first_product = $menus->get($details[0]->id);

                                                $total_amount = 0;
                                                foreach ($detail_list as $dk => $dv) {
                                                    $total_amount += $dv->commision_amount;
                                                }
                                                $status = $v->status == 1 ? 'Pending' : null;
                                                $status = $v->status == 2 ? 'Accepted' : $status;
                                                $status = $v->status == 3 ? 'Delivered' : $status;
                                                $status = $v->status == 0 ? 'Rejected' : $status; ?>
                                                <div class="card  shadow border-0 mb-3">
                                                    <a href="dashboard/order-details/view/<?= $v->invoice; ?>" class="text-reset">
                                                        <div class="card-body p-lg-0">
                                                            <div class="d-flex justify-content-between">
                                                                <img src="assets/images/menus/<?= $first_product->cover; ?>" style="width: 40px; object-fit: cover;">
                                                                <div class="ms-3 flex-grow-1 d-flex align-self-center justify-content-between">
                                                                    <div class="">
                                                                        <p class="mb-0 font-weight-bold"><?= $v->order_id ?></p>
                                                                        <span style="font-size: 12px">items: </span>
                                                                        <span class="font-weight-bold"><?= count($detail_list) ?></span>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <p class="mb-0 font-weight-bold"><?= Helpers::format_currency($total_amount) ?></p>
                                                                        <span class="<?= $status == 'Delivered' ? 'text-success' : ($status == 'Rejected' ? 'text-danger' : 'text-warning'); ?>"><?= $status ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="h-75 d-flex align-items-center justify-content-center py-5">
                                                <div class="text-center">
                                                    <i class="fa fa-shopping-cart fa-2x"></i>
                                                    <h4 class="mt-4 font-weight-bold">You have no order in your store.</h4>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings -->
                        <div class="row px-2 px-md-0 mb-4 mb-lg-0">
                            <div class="col-md-12">
                                <div class="card shadow border-0 py-4 bg-blue-shade">
                                    <div class="card-body">
                                        <div class="row px-2 px-lg-0">
                                            <div class="col-md-4 mb-4 mb-md-0">
                                                <p class="font-weight-bold mb-1" style="font-size: 12px">Total Earnings</p>
                                                <h3 class="title mb-0"><?= Helpers::format_currency($total_earning); ?></h3>
                                            </div>
                                            <div class="col-md-4 mb-4 mb-md-0">
                                                <p class="font-weight-bold mb-1" style="font-size: 12px">This Month Earnings (<?= date('M') ?>)</p>
                                                <h3 class="title mb-0"><?= Helpers::format_currency($monthly_earned); ?></h3>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="font-weight-bold mb-0" style="font-size: 12px">Earnings Awaiting Payout</p>
                                                <h3 class="title mb-0"><?= Helpers::format_currency($payout_balance); ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Orders awaiting delivery - Mobile -->
                        <div class="row px-2 px-md-0 d-md-none">
                            <div class="col-md-12">
                                <div class="card shadow mobile-card-section border-0">
                                    <div class="card-body">
                                        <div class="mb-4 align-items-center justify-content-between d-flex">
                                            <div class="d-flex align-items-center">
                                                <img class="icon-30 me-3" src="assets/icons/Orders.svg" alt="mp icon" />
                                                <p class="font-weight-bold mb-0">Orders Awaiting Delivery</p>
                                            </div>
                                            <a href="dashboard/orders" class="text-site-accent">View all</a>
                                        </div>
                                        <?php if ($awaiting_orders) { ?>
                                            <?php foreach ($awaiting_orders as $k => $v) {
                                                $us = $user->get($v->user_id);
                                                $details = json_decode($v->details);
                                                $detail_list = array_filter($details, function ($order) use ($vendor) {
                                                    return $order->seller == $vendor->id;
                                                });
                                                $first_product = $menus->get($details[0]->id);

                                                $total_amount = 0;
                                                foreach ($detail_list as $dk => $dv) {
                                                    $total_amount += $dv->commision_amount;
                                                }
                                                $status = $v->status == 1 ? 'Pending' : null;
                                                $status = $v->status == 2 ? 'Accepted' : $status;
                                                $status = $v->status == 3 ? 'Delivered' : $status;
                                                $status = $v->status == 0 ? 'Rejected' : $status; ?>
                                                <div class="card shadow border-0 mb-3">
                                                    <a href="dashboard/order-details/view/<?= $v->invoice; ?>" class="text-reset">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <img src="assets/images/menus/<?= $first_product->cover; ?>" style="width: 40px; object-fit: cover;">

                                                                <div class="ms-3 flex-grow-1 d-flex align-self-center justify-content-between">
                                                                    <div class="">
                                                                        <p class="mb-0 font-weight-bold"><?= $v->order_id ?></p>
                                                                        <span style="font-size: 12px">items: </span>
                                                                        <span class="font-weight-bold"><?= count($detail_list) ?></span>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <p class="mb-0 font-weight-bold"><?= Helpers::format_currency($total_amount) ?></p>
                                                                        <span class="<?= $status == 'Delivered' ? 'text-success' : ($status == 'Rejected' ? 'text-danger' : 'text-warning'); ?>"><?= $status ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="h-75 d-flex align-items-center justify-content-center py-5">
                                                <div class="text-center">
                                                    <i class="fa fa-shopping-cart fa-2x"></i>
                                                    <h4 class="mt-4 font-weight-bold">You have no order in your store.</h4>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Order -->
                    <div class="col-md-4 d-none d-lg-block mb-4 <?= $recent_orders && count($recent_orders) < 4 ? null : 'overflow-auto'; ?>">
                        <div class="card shadow mobile-card-section border-0  <?= $recent_orders && count($recent_orders) < 4 ? 'h-100' : null; ?>">
                            <div class="card-body">
                                <div class="mb-4 d-flex justify-content-between">
                                    <h5 class="">Recent Orders</h5>
                                    <a href="dashboard/orders" class="text-site-accent">View all</a>
                                </div>
                                <?php if ($recent_orders) { ?>
                                    <?php foreach ($recent_orders as $k => $v) {
                                        $us = $user->get($v->user_id);
                                        $profile = $profiles->get($v->user_id, 'user_id');
                                        $details = json_decode($v->details);
                                        $detail_list = array_filter($details, function ($order) use ($vendor) {
                                            return $order->seller == $vendor->id;
                                        });
                                        $first_product = $menus->get($details[0]->id);

                                        $total_amount = 0;
                                        foreach ($detail_list as $dk => $dv) {
                                            $total_amount += $dv->commision_amount;
                                        }
                                        $status = $v->status == 1 ? 'Pending' : null;
                                        $status = $v->status == 2 ? 'Accepted' : $status;
                                        $status = $v->status == 3 ? 'Delivered' : $status;
                                        $status = $v->status == 0 ? 'Rejected' : $status; ?>
                                        <div class="card shadow border-0 mb-3">
                                            <a href="dashboard/order-details/view/<?= $v->invoice; ?>" class="text-reset">
                                                <div class="card-body p-lg-0">
                                                    <div class="d-flex justify-content-between">
                                                        <img src="assets/images/menus/<?= $first_product->cover; ?>" style="width: 40px; object-fit: cover;">
                                                        <div class="ms-3 flex-grow-1 d-flex align-self-center justify-content-between">
                                                            <div class="">
                                                                <p class="mb-0 font-weight-bold"><?= $v->order_id ?></p>
                                                                <span style="font-size: 12px">items: </span>
                                                                <span class="font-weight-bold"><?= count($detail_list) ?></span>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="mb-0 font-weight-bold"><?= Helpers::format_currency($total_amount) ?></p>
                                                                <span class="<?= $status == 'Delivered' ? 'text-success' : ($status == 'Rejected' ? 'text-danger' : 'text-warning'); ?>"><?= $status ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="h-75 d-flex align-items-center justify-content-center py-5">
                                        <div class="text-center">
                                            <i class="fa fa-shopping-cart fa-2x"></i>
                                            <h4 class="mt-4 font-weight-bold">You have no order in your store.</h4>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="row">
                    <!-- Recent Products -->
                    <div class="col-md-7 mb-4 mb-lg-0 order-2 <?= $recent_products && count($recent_products) < 4 ? null : 'overflow-auto'; ?>" style="min-height: 530px">
                        <div class="card shadow mobile-card-section border-0 <?= !$recent_products || !count($recent_products) < 4 ? 'h-100' : null; ?>">
                            <div class="card-body">
                                <div class="mb-4 d-flex justify-content-between">
                                    <div class="d-flex">
                                        <img class="me-3" src="assets/icons/All Products.svg" alt="mp icon" />
                                        <div>
                                            <h5 class="">Recent Menus</h5>
                                            <span>Total Menus: </span>
                                            <span><?= $product_count ?></span>
                                        </div>
                                    </div>
                                    <a href="dashboard/menus" class="text-site-accent">View all</a>
                                </div>

                                <?php if ($recent_products) { ?>
                                    <?php foreach ($recent_products as $k => $v) {
                                        $category = $categories->get($v->category);
                                    ?>
                                        <div class="card shadow border-0 mb-3">
                                            <a href="dashboard/manage-product/edit/<?= $v->id; ?>" class="text-reset">
                                                <div class="card-body p-lg-0">
                                                    <div class="d-flex justify-content-between">
                                                        <img src="assets/images/menus/<?= $v->cover ?>" style="width: 40px; object-fit: cover;">
                                                        <div class="ms-3 flex-grow-1 d-flex align-self-center justify-content-between">
                                                            <div class="">
                                                                <p class="mb-0 font-weight-bold"><?= $v->title; ?></p>
                                                                <span style="font-size: 12px"><?= $category->title; ?></span>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="mb-0 font-weight-bold"><?= Helpers::format_currency($v->price); ?></p>
                                                                <span class="<?= $v->status ? 'text-success' : 'text-danger'; ?>"><?= $v->status ? 'Public' : 'Hidden'; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="h-75 d-flex align-items-center justify-content-center py-5">
                                        <div class="text-center">
                                            <i class="fa fa-shopping-cart fa-2x"></i>
                                            <h4 class="my-4 font-weight-bold">You have no products.</h4>
                                            <!--<p class="mb-4">Browse our categories and discover our best deals!</p>-->

                                            <a href="javascript:;" class="btn bg-site-accent" data-bs-toggle="modal" data-bs-target="#newProductModal">Add products</a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Order Awaiting Delivery -->
                    <div class="col-md-5 mb-4 mb-lg-0  mb-lg-0  d-none d-md-block <?= !isset($awaiting_orders) && count($awaiting_orders) < 4 ? null : 'overflow-auto'; ?>" style="min-height: 530px">
                        <div class="card shadow border-0 <?= !$awaiting_orders || count($awaiting_orders) < 4 ? 'h-100' : null; ?>">
                            <div class="card-body">
                                <div class="mb-3 d-flex justify-content-between">
                                    <h5 class="">Orders Awaiting Delivery</h5>
                                    <a href="dashboard/orders" class="text-site-accent">View all</a>
                                </div>
                                <?php if ($awaiting_orders) { ?>
                                    <?php foreach ($awaiting_orders as $k => $v) {
                                        $us = $user->get($v->user_id);
                                        $details = json_decode($v->details);
                                        $detail_list = array_filter($details, function ($order) use ($vendor) {
                                            return $order->seller == $vendor->id;
                                        });
                                        $first_product = $menus->get($details[0]->id);

                                        $total_amount = 0;
                                        foreach ($detail_list as $dk => $dv) {
                                            $total_amount += $dv->commision_amount;
                                        }
                                        $status = $v->status == 1 ? 'Pending' : null;
                                        $status = $v->status == 2 ? 'Accepted' : $status;
                                        $status = $v->status == 3 ? 'Delivered' : $status;
                                        $status = $v->status == 0 ? 'Rejected' : $status; ?>
                                        <div class="card shadow border-0 mb-3">
                                            <a href="dashboard/order-details/view/<?= $v->invoice; ?>" class="text-reset">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <img src="assets/images/menus/<?= $first_product->cover ?>" style="width: 40px; object-fit: cover;">
                                                        <div class="ms-3 flex-grow-1 d-flex align-self-center justify-content-between">
                                                            <div class="">
                                                                <p class="mb-0 font-weight-bold"><?= $v->order_id ?></p>
                                                                <span style="font-size: 12px">items: </span>
                                                                <span class="font-weight-bold"><?= count($detail_list) ?></span>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="mb-0 font-weight-bold"><?= Helpers::format_currency($total_amount) ?></p>
                                                                <span class="<?= $status == 'Delivered' ? 'text-success' : ($status == 'Rejected' ? 'text-danger' : 'text-warning'); ?>"><?= $status ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="h-75 d-flex align-items-center justify-content-center py-5">
                                        <div class="text-center">
                                            <i class="fa fa-shopping-cart fa-2x"></i>
                                            <h4 class="mt-4 font-weight-bold">You have no orders awaiting delivery.</h4>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </section>

            <?php } ?>
        </div>
    </div>



    <?php
    $category_list =  $categories->getAll(0, 'parent_id', '=');
    ?>
    <div class="modal fade" id="newProductModal" tabindex="-1" aria-labelledby="newProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="sa-close sa-close--modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="card-body">
                        <p class="text-center mb-5">What category of meal do you want to add?</p>
                        <form action="dashboard/manage-menus" method="get" class="needs-validation" novalidate="">
                            <div class="mb-4">
                                <label for="category" class="form-label sr-only">Select a Category</label>
                                <select name="category" id="category" class="form-control form-select-lg sa-select2 form-select" required placeholder="">
                                    <option value="">Select Select a Category</option>
                                    <?php if ($category_list) { ?>
                                        <?php foreach ($category_list as $k => $v) { ?>
                                            <option value="<?= $v->id ?>"><?= $v->title ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <button class="btn bg-accent text-white btn-lg w-100">Continue</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newSpecialMenuModal" tabindex="-1" aria-labelledby="newSpecialMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="sa-close sa-close--modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="card-body">
                        <p class="text-center mb-5">What category of meal do you want to add?</p>
                        <form action="dashboard/manage-menus" method="get" class="needs-validation" novalidate="">
                            <div class="mb-4">
                                <label for="category" class="form-label sr-only">Select a Category</label>
                                <select name="category" id="category" class="form-control form-select-lg sa-select2 form-select" required placeholder="">
                                    <option value="">Select Select a Category</option>
                                    <?php if ($category_list) { ?>
                                        <?php foreach ($category_list as $k => $v) { ?>
                                            <option value="<?= $v->id ?>"><?= $v->title ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="special" value="1">
                            <button class="btn bg-accent text-white btn-lg w-100">Continue</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>