<?php
$User = new User();
$pagination = new Pagination();
$constants = new Constants();
$notifications = new General('notifications');
$notification_snippets  = new General('notification_snippets ');
$profiles = new General('profiles');
$Orders = new Orders();
$OrderDetails = new General('order_details');
$Menus = new Menus();
$Wallets = new General('wallets');
$categories = new General('categories');

//Delete failed uploads
if (Session::exists('menu_images')) {
    $images = Session::get('menu_images');
    foreach ($images as $v) {
        $path = ASSET_PATH . "/images/menus/" . $v;
        Helpers::deleteFile($path);
    }
}

$vendor = $User->getVendor();
$verification = $User->getVerification();
$recent_orders = null;



if ($vendor) {

    $order_details_list = $Orders->getVendorOrders($vendor->id);
    // print_r($Orders->getVendorOrdersDistinct($vendor->id, null, 'order_id', " AND status = 'pending' "));
    // Others
    $order_count = $order_details_list ? count($order_details_list) : 0;
    $product_count = count($Menus->getAll($User->data()->id, 'user_id', '='));
    $awaiting_count = $delivered_count = 0;

    $searchTerm = "WHERE id > 0 AND user_id = {$vendor->user_id} ";
    $recent_products = $Menus->getPages(4, 0, $searchTerm);

    // Recent Orders
    $recent_orders = $Orders->getVendorOrders($vendor->id, null, null, "ORDER BY created_at DESC", 4, 0);
    // Pending Orders
    $pending_orders = $Orders->getVendorOrders($vendor->id, null, " AND status = 'pending' ", "ORDER BY created_at DESC", 4, 0);
    // Awaiting Delivery
    $awaiting_orders = $Orders->getVendorOrders($vendor->id, null, " AND status = 'pending' ", "ORDER BY created_at DESC", 4, 0);

    // Earnings....
    $uwallet = $User->getWallet();
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
            'user_id' => $User->data()->id,
            'snippet_id' => $notification_snippets->get('S_BALANCE_UPDATED', 'title')->id,
        ));

        $Wallets->update(array(
            'payout_balance' => (($current_earning - $payout_balance) + $payout_balance)
        ), $uwallet->id);
    }

    $current_date = date('Y-m');
    $searchTerm = "WHERE id > 0 AND created_at LIKE '%{$current_date}%' AND details LIKE '%status_:_delivered_%' ";
    $pcount = $pagination->countAll('orders', $searchTerm);
    $paginate = new Pagination(1, $pcount, $pcount);
    $current_month_orders = $Orders->getPages(5, $paginate->offset(), $searchTerm);

    // Monthly Earned - Delivered Delivery
    $monthly_earned = 0;
    if ($current_month_orders) {
        foreach ($current_month_orders as $k => $v) {
            $details = isset($v->details) ? json_decode($v->details) : null;
            if ($details) {
                foreach ($details as $kk => $kv) {
                    if ($vendor->id == $kv->seller) {
                        $monthly_earned += $kv->commision_amount;
                    }
                }
            }
        }
    }
}

$views = 'view/vendor';


Alerts::displayError();
Alerts::displaySuccess();
?>

<div class="card bg-primary--light no-shadow" style="background: #fffaf5 !important;">
    <div class="p-3 p-lg-4 pt-5">
        <?php if (Input::get('action')) {
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
                <div class="alert alert-danger text-center mb-4">
                    <span>We're reviewing your restaurant activation request! Please expect feedback within 24 hours via your email address provided during registration. Thank You.</span>
                </div>
            <?php } ?>

            <?php if ($User->data()->email_token != 'verified') { ?>
                <div class="alert alert-danger text-center mb-4">
                    <span>Your email address hasn't been confirmed, click the link to get a confirmation sent to your email. <a href="controllers/profile.php?rq=get-email-verification&email=<?= $User->data()->email; ?>" class="text-accent">Get Confirmation Email</a></span>
                </div>
            <?php } ?>

            <section class="row">
                <div class="col-md-8 mb-4">
                    <!-- Orders -->
                    <div class="row">
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
                    <div class="row d-lg-none">
                        <div class="col-lg-12 mb-4 <?= $recent_orders && count($recent_orders) < 4 ? null : 'overflow-auto'; ?>">
                            <div class="card shadow mobile-card-section border-0  <?= $recent_orders && count($recent_orders) < 4 ? 'h-100' : null; ?>">
                                <div class="card-body">
                                    <div class="mb-4 d-flex justify-content-between">
                                        <h5 class="">Recent Orders</h5>
                                        <a href="dashboard/orders" class="text-accent">View all</a>
                                    </div>
                                    <?php if ($recent_orders) { ?>
                                        <?php foreach ($recent_orders as $order) {
                                            $details = $Orders->getDetails($order->order_id, " AND vendor_id = {$vendor->id} ");
                                            $first_product = $Menus->get($details[0]->id);

                                            // Calculate total
                                            $total_amount = 0;
                                            foreach ($details as $detail) {
                                                $total_amount += $detail->vendor_amount;
                                            }

                                            // Set Status
                                            $status = $order->status == 'pending' ? array('title' => 'Pending', 'color' => 'text-accent') : 'Rejected';
                                            $status = $order->status == 'awaiting' ? array('title' => 'Awaiting Delivery', 'color' => 'text-accent') : $status;
                                            $status = $order->status == 'completed' ? array('title' => 'Awaiting Delivery', 'color' => 'text-green') : $status;

                                            // Render
                                            Component::render('order', array(
                                                'data' => $order,
                                                'type' => 'vendor-list',
                                                'total_amount' => $total_amount,
                                                'count' => count($details),
                                                'cover' => $first_product->cover,
                                                'status' => $status
                                            ), 'view/user/component');
                                        }
                                    } else { ?>
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
                    <div class="row mb-4 mb-lg-0">
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
                    <div class="row d-md-none">
                        <div class="col-md-12">
                            <div class="card shadow mobile-card-section border-0">
                                <div class="card-body">
                                    <div class="mb-4 align-items-center justify-content-between d-flex flex-wrap">
                                        <h5 class="font-weight-bold mb-0">Orders Awaiting Delivery</h5>
                                        <a href="dashboard/orders" class="text-accent">View all</a>
                                    </div>
                                    <?php if ($awaiting_orders) { ?>
                                        <?php foreach ($awaiting_orders as $k => $order) {
                                            $details = $Orders->getDetails($order->order_id, " AND vendor_id = {$vendor->id} ");
                                            $first_product = $Menus->get($details[0]->id);

                                            // Calculate total
                                            $total_amount = 0;
                                            foreach ($details as $detail) {
                                                $total_amount += $detail->vendor_amount;
                                            }

                                            // Set Status
                                            $status = $order->status == 'pending' ? array('title' => 'Pending', 'color' => 'text-accent') : 'Rejected';
                                            $status = $order->status == 'awaiting' ? array('title' => 'Awaiting Delivery', 'color' => 'text-accent') : $status;
                                            $status = $order->status == 'completed' ? array('title' => 'Awaiting Delivery', 'color' => 'text-green') : $status;

                                            // Render
                                            Component::render('order', array(
                                                'data' => $order,
                                                'type' => 'vendor-list',
                                                'total_amount' => $total_amount,
                                                'count' => count($details),
                                                'cover' => $first_product->cover,
                                                'status' => $status
                                            ), 'view/user/component');
                                        }
                                    } else { ?>
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
                                <a href="dashboard/orders" class="text-accent">View all</a>
                            </div>
                            <?php if ($recent_orders) { ?>
                                <?php foreach ($recent_orders as $k => $order) {
                                    $details = $Orders->getDetails($order->order_id, " AND vendor_id = {$vendor->id} ");
                                    $first_product = $Menus->get($details[0]->id);

                                    // Calculate total
                                    $total_amount = 0;
                                    foreach ($details as $detail) {
                                        $total_amount += $detail->vendor_amount;
                                    }

                                    // Set Status
                                    $status = $order->status == 'pending' ? array('title' => 'Pending', 'color' => 'text-accent') : 'Rejected';
                                    $status = $order->status == 'awaiting' || $order->status == 'picked' ? array('title' => 'Awaiting Delivery', 'color' => 'text-accent') : $status;
                                    $status = $order->status == 'completed' ? array('title' => 'Awaiting Delivery', 'color' => 'text-success') : $status;

                                    // Render
                                    Component::render('order', array(
                                        'data' => $order,
                                        'type' => 'vendor-list',
                                        'total_amount' => $total_amount,
                                        'count' => count($details),
                                        'cover' => $first_product->cover,
                                        'status' => $status
                                    ), 'view/user/component');
                                }
                            } else { ?>
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
                                <div class="mb-0">
                                    <h5 class="mb-0">Recent Menus</h5>
                                    <span class='fs-14p'>Total Menus: <b><?= $product_count ?></b></span>
                                </div>
                                <a href="dashboard/menus" class="text-accent">View all</a>
                            </div>

                            <?php if ($recent_products) { ?>
                                <?php foreach ($recent_products as $k => $v) {
                                    $category = $categories->get($v->category);
                                ?>
                                    <div class="card shadow border-0 mb-3">
                                        <a href="dashboard/manage-menus/edit/<?= $v->id; ?>" class="text-reset">
                                            <div class="card-body p-2">
                                                <div class="d-flex justify-content-between">
                                                    <img src="assets/images/menus/<?= $v->cover ?>" style="width: 40px; height:40px; object-fit: cover;">
                                                    <div class="ms-3 flex-fill">
                                                        <div class="d-flex  justify-content-between mb-1">
                                                            <p class="mb-0 font-weight-bold thellipsis" data-thellipsis-line="2" style="line-height: 1.1;"><?= $v->title; ?></p>
                                                            <span class="fs-12p <?= $v->status ? 'text-success' : 'text-danger'; ?>"><?= $v->status ? 'Public' : 'Hidden'; ?></span>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <p class="mb-0 fs-14p" style="line-height: 1.1;"><?= Helpers::format_currency($v->price); ?></p>
                                                            <span class="fs-12p"><?= $category->title; ?></span>
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
                                <a href="dashboard/orders" class="text-accent">View all</a>
                            </div>
                            <?php if ($awaiting_orders) { ?>
                                <?php foreach ($awaiting_orders as $order) {
                                    $details = $Orders->getDetails($order->order_id, " AND vendor_id = {$vendor->id} ");
                                    $first_product = $Menus->get($details[0]->id);

                                    // Calculate total
                                    $total_amount = 0;
                                    foreach ($details as $detail) {
                                        $total_amount += $detail->vendor_amount;
                                    }

                                    // Set Status
                                    $status = $order->status == 'pending' ? array('title' => 'Pending', 'color' => 'text-accent') : 'Rejected';
                                    $status = $order->status == 'awaiting' ? array('title' => 'Awaiting Delivery', 'color' => 'text-accent') : $status;
                                    $status = $order->status == 'completed' ? array('title' => 'Awaiting Delivery', 'color' => 'text-green') : $status;

                                    // Render
                                    Component::render('order', array(
                                        'data' => $order,
                                        'type' => 'vendor-list',
                                        'total_amount' => $total_amount,
                                        'count' => count($details),
                                        'cover' => $first_product->cover,
                                        'status' => $status
                                    ), 'view/user/component');
                                }
                            } else { ?>
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
</div>