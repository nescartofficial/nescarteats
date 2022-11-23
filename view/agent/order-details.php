<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$Menus = new Menus();
$world = new World();
$Orders = new Orders();
$OrderDetails = new General('order_details');

$vendor = $user->getVendor();

// $Orders = $Orders->getAll($user->data()->id, 'supplier_user_id', '=');

$sbid = Input::exists() && Input::get('content') ? Input::get('content') : null;

$formated_vendor_id = '%"' . $vendor->id . '"%';
$vendor_like = " AND vendors LIKE '{$formated_vendor_id}' ";
$searchTerm = "WHERE id > 0 {$vendor_like} ";

$next = isset($_GET['sub1']) ? trim($_GET['sub1']) : 1;
$per_page = 5;
$pagination = new Pagination();
$total_record = $pagination->countAll('orders', $searchTerm);
$paginate = new Pagination($next, $per_page, $total_record);
$items = $Orders->getPages($per_page, $paginate->offset(), $searchTerm);

$order = Input::get('sub') && is_numeric(Input::get('sub')) ? $Orders->get(Input::get('sub'), 'invoice') : null;
$order_details = $order ? $Orders->getDetails($order->order_id, " AND vendor_id = {$vendor->id} ") : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<?php if ($order) {
    $us = $user->get($order->user_id);
    $profile = $user->getProfile($us->id);

    // Calculate total
    $total_amount = 0;
    foreach ($order_details as $detail) {
        $total_amount += $detail->total_amount;
    }

    // Set Status
    $order_status = $order->status == 'pending' ? array('title' => 'Pending', 'color' => 'text-accent') : 'Rejected';
    $order_status = $order->status == 'picked' || $order->status == 'awaiting' ? array('title' => 'Awaiting Delivery', 'color' => 'text-accent') : $order_status;
    $order_status = $order->status == 'completed' ? array('title' => 'Delivered', 'color' => 'text-success') : $order_status;

    $status = $order_details ? current($order_details)->status : null;
?>
        <div class="pb-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="dashboard/orders">Orders</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= $order->order_id ?></li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0">Order <?= $order->order_id ?></h1>
                </div>
            </div>
        </div>
        <div class="d-none sa-page-meta mb-5">
            <div class="sa-page-meta__body">
                <div class="sa-page-meta__list">
                    <div class="sa-page-meta__item"><?= date_format(date_create($order->created), 'M d, Y') ?> at <?= date_format(date_create($order->created), 'H:s:a') ?></div>
                    <div class="sa-page-meta__item"><?= count($order_details) ?> items</div>
                    <div class="sa-page-meta__item">Total <?= Helpers::format_currency($total_amount) ?></div>
                    <div class="sa-page-meta__item d-flex align-items-center fs-6"><span class="badge badge-sa-success me-2"><?= ucwords($order_status) ?></span></div>
                </div>
            </div>
        </div>
        
    
        <div class="row">
            <div class="col-lg-8">
                <!-- <div class="sa-card-area"><textarea class="sa-card-area__area" rows="2" placeholder="Notes about order"></textarea>
                    <div class="sa-card-area__card"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg></div>
                </div> -->
                <div class="card border-0">
                    <div class="card-header py-3">
                        <div class="mb-3 d-flex alight-items-center justify-content-between">
                            <h4 class="mb-0 me-4">Items</h4>
                            <?php if ($status == "picked") { ?>
                                <?php if ($order_status['title'] == "Awaiting Delivery") { ?>
                                    <span class="align-self-center badge bg-warning">Awaiting Delivery</span>
                                <?php } else if ($order_status['title'] == "Delivered") {  ?>
                                    <span class="align-self-center badge bg-warning">Delivered</span>
                                <?php } else if ($order_status['title'] == "Rejected") {  ?>
                                    <span class="align-self-center badge bg-warning">Rejected</span>
                                <?php } else { ?>
                                    <span class="align-self-center badge bg-warning">Pending Delivery</span>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if ($status == "pending") { ?>
                                    <a onclick="return confirm('Are you sure you want to accept this order?')" class="btn bg-primary border-0" href="controllers/orders.php?rq=order-allstatus&id=<?= $order->invoice ?>&status=accepted">Accept Order</a>
                                <?php } else if ($status == "accepted") { ?>
                                    <a onclick="return confirm('Are you sure you want to confirm this order have been picked up?')" class="btn bg-primary border-0" href="controllers/orders.php?rq=order-allstatus&id=<?= $order->invoice ?>&status=picked">Order Picked</a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <p class="mb-1">
                            <?php if ($status == "pending") { ?>
                                This order request is currently awaiting your action, accept the order when order is ready to be picked up.
                            <?php } else if ($status == "accepted") { ?>
                                You have accepted this order request and is ready to be picked up for delivery, if order have been picked up click picked to change the state of this order.
                            <?php } else if ($order->status == 2) { ?>
                                Order is awaiting delivery.
                            <?php } ?>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <?php
                            $total_amount = $total_earned_amount = 0;
                            foreach ($order_details as $k => $detail) {
                                $menu = $Menus->get($detail->menu);

                                $total_amount = $detail->total_amount;
                                $total_earned_amount = $detail->vendor_amount;

                                $variations = $detail->variations ? json_decode($detail->variations) : null;
                                $variation_titles = $variations ? $variations->title : null;

                                $addon_titles = '';
                                $addon_list = $detail->addons ? json_decode($detail->addons) : null;

                                if ($addon_list) {
                                    foreach ($addon_list as $addon) {
                                        $addon_titles .= $addon->quantity . ' ' . $addon->title . ',';
                                    }
                                }
                            ?>
                                <div class="row align-items-cente justify-content-between mb-">
                                    <div class="col-lg-6">
                                        <div class="d-flex align-items-center flex-wrap gap-3">
                                            <img src="<?= SITE_URL; ?>assets/images/menus/<?= $menu->cover; ?>" alt="" class="img-fluid align-self-start" style="height: 65px; width: 65px; border-radius: 5px; object-fit: cover;">

                                            <div class="col-8">
                                                <p class="fs-18p fw-bold mb-1 text-truncate"><?= $menu->title; ?></p>

                                                <?php if ($variation_titles) { ?>
                                                    <p class="fs-14p mb-0">Variation: <b><?= $variation_titles ?></b></p>
                                                <?php } ?>

                                                <?php if ($addon_titles) { ?>
                                                    <p class="fs-14p mb-0">Addons: <b><?= $addon_titles ?></b></p>
                                                <?php } ?>

                                                <div class="mt-3 mt-lg-1 d-flex justify-content-between">
                                                    <span class="fs-12p">Qty: <?= $detail->quantity; ?></span>
                                                    <span class="d-lg-none fs-12p font-weight-bold"><?= Helpers::format_currency($total_amount); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-none d-lg-block col-4 col-lg">
                                        <span class="">Price</span>
                                        <h6 class="mt-1 mb-0 fs-12p"><?= Helpers::format_currency($menu->price); ?></h6>
                                    </div>

                                    <div class="d-none d-lg-block col-4 col-lg">
                                        <span class="">Total</span>
                                        <h6 class="mt-1 mb-0 fs-12p"><?= Helpers::format_currency(($menu->price * $detail->quantity)); ?></h6>
                                    </div>
                                    <div class="d-none d-lg-block col-4 col-lg">
                                        <span class="">Earned</span>
                                        <h6 class="mt-1 mb-0 fs-12p"><?= Helpers::format_currency(($detail->vendor_amount)); ?></h6>
                                    </div>
                                </div>
                                <hr class="mt-3">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 py-3">
                        <h5>Total: <?= Helpers::format_currency($total_amount); ?></h5>
                        <h5>Earned: <?= Helpers::format_currency($total_earned_amount); ?></h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <h5 class="fs-exact-16 mb-3">Order Details</h5>
                        <p class="mb-1 ">Order ID: <b><?= $order->order_id ?></b></p>
                        <p class="mb-2 ">Invoice ID: <b>#<?= $order->invoice ?></b></p>
                        <p class="mb-2 "><?= count($order_details) ?> items</p>
                        <p class="mb-1 text-right">
                            Status: <span class="badge me-2 <?= $order_status['color'] ?>"><?= ucwords($order_status['title']) ?></span></p>
                        <p class="mb-1 "><?= date_format(date_create($order->created_at), 'M d, Y') ?></p>
                    </div>
                </div>
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <h5 class="fs-exact-16 mb-3">Customer Details</h5>
                        <p><?= $us ? $us->first_name . ' ' . $us->last_name : '-'; ?><br /></p>
                    </div>
                </div>
                <div class="card border-0">
                    <div class="card-body">
                        <h5 class="fs-exact-16 mb-3">Delivery Details</h5>
                        <?= $profile && $profile->city ? $world->getCityName($profile->city) . ', ' . $world->getStateName($profile->state) : 'No City'; ?><br />
                        <?= $profile && $profile->country ? $world->getCountryName($profile->country) : 'Country'; ?>
                    </div>
                </div>
            </div>
        </div>
<?php } ?>