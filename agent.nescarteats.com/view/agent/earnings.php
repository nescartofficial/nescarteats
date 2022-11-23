<?php
$user = new User();
$constants = new Constants();
$pagination = new Pagination();
$vendors = new General('vendors');
$orders = new Orders();
$wallets = new General('wallets');
$products = new General('products');
$payouts = new General('payouts');
$notifications = new General('notifications');
$notification_snippets = new General('notification_snippets');

$vendor = $user->getVendor();
$pending_earning = $earned = null;

$searchTerm = "WHERE id > 0 ";
$order_count = $pagination->countAll('orders', $searchTerm);
$paginate = new Pagination(1, $order_count, $order_count);
$all_orders = $orders->getPages(4, $paginate->offset(), $searchTerm);

$current_date = date('Y-m');
$searchTerm = "WHERE id > 0 AND delivery_date LIKE '%{$current_date}%' AND details LIKE '%status_:_delivered_%' ";
$order_count = $pagination->countAll('orders', $searchTerm);
$paginate = new Pagination(1, $order_count, $order_count);
$current_month_orders = $orders->getPages(5, $paginate->offset(), $searchTerm);

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

// Monthly Earned - Delivered Delivery
$monthly_earned = 0;
if ($current_month_orders) {
    foreach ($current_month_orders as $k => $v) {
        $details = $orders->getDetails($v->order_id);
        foreach ($details as $kk => $kv) {
            // print_r($kv);
            if ($vendor->id == $kv->vendor_id) {
                $monthly_earned += $kv->vendor_amount;
            }
        }
    }
}

// Payout List
$payout_list = $payouts->getAll($vendor->id, 'seller_id', '=');
// Product List
$top_product_list = null;


Alerts::displayError();
Alerts::displaySuccess();
?>

    <div class="row">
        <div class="col-md-12 mb-5">
            <div class="row gy-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Earnings</li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0">My Earnings</h1>
                </div>
                <!-- <div class="col-auto d-flex"><a href="app-order.html" class="btn btn-primary">New order</a></div> -->
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Stats -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <img src="assets/icons/Total Earnings.svg" style="width: 40px;">
                            </div>
                            <span class="text-black" style="font-size: 16px;">Total Earnings</span>
                            <h4 class="title mb-0"><?= Helpers::format_currency($total_earning); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 h-100 bg-blue-shade">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <img src="assets/icons/This Month Earnings.svg" style="width: 40px;">
                            </div>
                            <span class="text-black" style="font-size: 16px;">This Month (<?= date('M') ?>)</span>
                            <h4 class="title mb-0"><?= Helpers::format_currency($monthly_earned); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 h-100 bg-yellow-shade">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <img src="assets/icons/Available Balance.svg" style="width: 40px;">
                            </div>
                            <span class="text-black" style="font-size: 16px;">Available Balance</span>
                            <h4 class="title mb-0"><?= Helpers::format_currency($current_earning); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-lg-none">
                    <!-- Available Payout: mobile -->
                    <div class="card  border-0 bg-green-shade mb-4">
                        <div class="card-body">
                            <img src="assets/icons/Available for Next Payout.svg" style="width: 40px;">

                            <div class="my-3">
                                <span style="font-size: 16px;">Available For Next Payout</span>
                                <h4 class="title mb-0"><?= Helpers::format_currency($payout_balance); ?></h4>
                            </div>

                            <div class="text-center">
                                <a href="dashboard/bank" style="font-size: 13px;" class="col-md-8 d-block mx-auto">Update your payout method in settings</a>
                                <div class="col-md-10 bg-green-shade-100 mt-3 py-2 mx-auto rounded">
                                    <span>Next Payout Date</span>
                                    <h5 class="mb-0"><?= date_format(date_create($constants->getText("PAYOUT_DATE")), "d M, Y"); ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card border-0" style="min-height: 580px;">
                        <div class="card-body">
                            <header class="d-flex mb-4">
                                <img class="me-3" src="assets/icons/Available for Next Payout.svg" alt="mp icon" />
                                <div class="">
                                    <h5 class="">Last Payouts</h5>
                                    <p class="mb-0">See recent payouts to you.</p>
                                </div>
                            </header>

                            <?php if ($payout_list) { ?>
                                <?php
                                $count = 0;
                                foreach ($payout_list as $k => $v) {
                                    $count++;
                                    if ($count > 3) {
                                        break;
                                    }
                                ?>
                                    <div class="card border-0 mb-3">
                                        <div class="card-body p-0">
                                            <p class="mb-0">Amount Paid</p>
                                            <h5><?= Helpers::format_currency($v->amount); ?></h5>
                                            <p class="mb-0">Paid: <b><?= date_format(date_create($v->date_added), 'd M, Y'); ?></b></p>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="h-100 d-flex align-items-center justify-content-center py-5">
                                    <div class="text-center">
                                        <i class="fa fa-shopping-cart fa-2x"></i>
                                        <h4 class="mb-0 font-weight-bold">You have not been paid out.</h4>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card border-0" style="min-height: 580px;">
                        <div class="card-body">
                            <header class="d-flex mb-4">
                                <img class="me-3" src="assets/icons/All Products.svg" alt="mp icon" />
                                <div class="">
                                    <h5 class="">Top Products</h5>
                                    <p class="mb-0">Best selling products.</p>
                                </div>
                            </header>

                            <?php if ($top_product_list) { ?>
                                <?php
                                $count = 0;
                                foreach ($top_product_list as $k => $v) {
                                    $count++;
                                    if ($count > 3) {
                                        break;
                                    }
                                ?>
                                    <div class="card border-0 mb-3">
                                        <div class="card-body p-0">
                                            <p class="mb-0">Amount Paid</p>
                                            <h5><?= Helpers::format_currency($v->amount); ?></h5>
                                            <p class="mb-0">Paid: <b><?= date_format(date_create($v->date_added), 'd M, Y'); ?></b></p>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="h-75 d-flex align-items-center justify-content-center py-5">
                                    <div class="text-center">
                                        <img src="assets/icons/Products.svg" class="img-fluid mb-3">
                                        <h5 class="mb-0 font-weight-bold">Processing . . .</h5>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Available Payout -->
            <div class="card  border-0 bg-green-shade mb-4 d-none d-lg-block">
                <div class="card-body">
                    <img src="assets/icons/Available for Next Payout.svg" style="width: 40px;">

                    <div class="my-3">
                        <span style="font-size: 16px;">Available For Next Payout</span>
                        <h4 class="title mb-0"><?= Helpers::format_currency($payout_balance); ?></h4>
                    </div>

                    <div class="text-center">
                        <a href="dashboard/bank" style="font-size: 13px;" class="col-md-8 d-block mx-auto">Update your payout method in settings</a>
                        <div class="col-md-10 bg-green-shade-100 mt-3 py-2 mx-auto rounded">
                            <span>Next Payout Date</span>
                            <h5 class="mb-0"><?= date_format(date_create($constants->getText("PAYOUT_DATE")), "d M, Y"); ?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Orders -->
            <div class="card border-0 bg-black mb-4 mb-lg-0" style="min-height: 450px;">
                <div class="card-body">
                    <header class="d-flex justify-content-between mb-4">
                        <h5 class="text-white"><img class="icon-30 me-2" src="assets/icons/Orders.svg" alt="mp icon" /> Last Orders </h5>
                    </header>

                    <?php if ($current_month_orders) {
                        $count = 0; ?>
                        <?php foreach ($current_month_orders as $k => $v) {
                            $count++;
                            if ($count > 3) {
                                break;
                            }
                            $us = $user->get($v->user_id);
                            $profile = $user->getProfile($v->user_id);
                            $details = $orders->getDetails($v->order_id);
                            $detail_list = array_filter($details, function ($order) use ($vendor) {
                                return $order->vendor_id == $vendor->id;
                            });
                            // $first_product = $products->get($details[0]->id);

                            $total_amount = 0;
                            foreach ($detail_list as $dv) {
                                $total_amount += $dv->vendor_amount;
                            }
                            $status = $v->status == 1 ? 'Pending' : null;
                            $status = $v->status == 2 ? 'Accepted' : $status;
                            $status = $v->status == 3 ? 'Delivered' : $status;
                            $status = $v->status == 0 ? 'Rejected' : $status; ?>
                            <div class="mb-3">
                                <a href="dashboard/order-details/view/<?= $v->invoice; ?>" class="text-reset">
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1 d-flex align-self-center justify-content-between">
                                            <div class="">
                                                <p class="text-white mb-0 font-weight-bold"><?= $us->first_name . ' ' . $us->last_name; ?></p>
                                                <span class="text-white" style="font-size: 12px">items: </span>
                                                <span class="text-white font-weight-bold"><?= count($detail_list) ?></span>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-white mb-0 font-weight-bold"><?= Helpers::format_currency($total_amount) ?></p>
                                                <span class="font-weight-bold <?= $status == 'Delivered' ? 'text-success' : ($status == 'Rejected' ? 'text-danger' : 'text-warning'); ?>"><?= $status ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="h-75 d-flex align-items-center justify-content-center py-5">
                            <div class="text-center">
                                <i class="fa fa-shopping-cart fa-2x text-white"></i>
                                <h4 class="mt-4 text-white font-weight-bold">You have no order.</h4>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>