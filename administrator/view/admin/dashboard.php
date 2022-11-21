<?php
// require_once("core/init.php");
$User = new User();
$Orders = new Orders();

$tickets = new General('tickets');
$Updates = new General('updates');
$Notifications = new General('notifications');
$NotificationSnippets = new General('notification_snippets');
$Wallets = new General('wallets');
$pagination = new Pagination();
!$User->isLoggedIn() ? Redirect::to('../') : null;

// Total Earning
$total_earning = $Orders->getAllSumWhere("total_amount", "status = 'completed' AND is_cancel = 0");
$total_earning = Helpers::format_currency($total_earning ? $total_earning : 0);

// Total Commission
$completed_orders = $Orders->getAll('completed', 'status', '=');
$total_commission = 0;
foreach ($completed_orders as $order) {
    $details = $Orders->getDetails($order->order_id);
    foreach ($details as $detail) {
        $total_commission += $detail->platform_amount;
    }
}
$total_commission = Helpers::format_currency($total_commission ? $total_commission : 0);



// Total Payout
$total_payout = $Wallets->getAllSum('total_payout', 0, 'total_payout', '>');
$total_payout = Helpers::format_currency($total_payout ? $total_payout : 0);

// Pending Payout
$pending_payout = $Wallets->getAllSum('payout_balance', 0, 'payout_balance', '>');
$pending_payout = Helpers::format_currency($pending_payout ? $pending_payout : 0);

// Recent Notification
$recent_notifications = $Notifications->getPages(5, 0, "WHERE id > 0 and user_id = 0");

// Recent buyers/Vendors
$recent_buyers = $User->getPages(5, 0, "WHERE id > 0 and vendor = 0 ");
$recent_vendors = $User->getPages(5, 0, "WHERE id > 0 and vendor = 1 ");

$order_count = count($Orders->getWhere("id > 0"));

$date = date('Y-m-d');
$earning_month = $Orders->getAllSumWhere("total_amount", "status = 2 AND cancel = 0 AND created LIKE '%{$date}%'");
$earning_month = Helpers::format_currency($earning_month ? $earning_month : 0);

$ticket_list = $tickets->getAll($User->data()->id, 'user_id', '=');
$update_list = $Updates->getAll($User->data()->id, 'user_id', '=');

// Order Counts
$pending_count = $pagination->countAll('orders', "WHERE id > 0 AND status = 'pending'");
$awaiting_delivery_count = $pagination->countAll('orders', "WHERE id > 0 AND status = 'picked'");
$delivered_count = $pagination->countAll('orders', "WHERE id > 0 AND status = 'completed'");
$recent_orders = $Orders->getPages(6, 0, "WHERE id > 0", "ORDER BY created_at DESC");

Alerts::displayError();
Alerts::displaySuccess();
?>
<div id="top" class="sa-app__body px-2 px-lg-4">
    <div class="container pb-6">
        <div class="py-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <h1 class="h3 m-0">Dashboard</h1>
                </div>
            </div>
        </div>

        <div class="row g-4 g-xl-5">
            <div class="col-12 col-md-4 d-flex">
                <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                    <div class="sa-widget-header saw-indicator__header">
                        <h2 class="sa-widget-header__title">Total Earning</h2>
                        <div class="sa-widget-header__actions">
                            <div class="dropdown"><button type="button" class="btn btn-sm btn-sa-muted d-block" id="widget-context-menu-1" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                        <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z">
                                        </path>
                                    </svg></button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="widget-context-menu-1">
                                    <li><a class="dropdown-item" href="orders">Orders</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="saw-indicator__body">
                        <div class="saw-indicator__value"><?= $total_earning ?></div>
                    </div>
                    <hr class="my-2" />
                    <div class="">
                        <span class="sa-widget-header__title">Total Commission: <span class="fw-bold"><?= $total_commission ?></span></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex">
                <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                    <div class="sa-widget-header saw-indicator__header">
                        <h2 class="sa-widget-header__title">Total Payout</h2>
                        <div class="sa-widget-header__actions">
                            <div class="dropdown"><button type="button" class="btn btn-sm btn-sa-muted d-block" id="widget-context-menu-1" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                        <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z">
                                        </path>
                                    </svg></button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="widget-context-menu-1">
                                    <li><a class="dropdown-item" href="payouts">Go to payout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="saw-indicator__body">
                        <div class="saw-indicator__value"><?= $total_payout ?></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex">
                <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                    <div class="sa-widget-header saw-indicator__header">
                        <h2 class="sa-widget-header__title">Pending Payout</h2>
                        <div class="sa-widget-header__actions">
                            <div class="dropdown"><button type="button" class="btn btn-sm btn-sa-muted d-block" id="widget-context-menu-1" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                        <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z">
                                        </path>
                                    </svg></button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="widget-context-menu-1">
                                    <li><a class="dropdown-item" href="payouts">Go to Payout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="saw-indicator__body">
                        <div class="saw-indicator__value"><?= $pending_payout ?></div>
                    </div>
                </div>
            </div>

            <?php if (false) { ?>
                <div class="col-12 col-lg-4 col-xxl-3 d-flex">
                    <div class="card flex-grow-1 saw-pulse" data-sa-container-query="{&quot;560&quot;:&quot;saw-pulse--size--lg&quot;}">
                        <div class="sa-widget-header saw-pulse__header">
                            <h2 class="sa-widget-header__title">Active users</h2>
                            <div class="sa-widget-header__actions">
                                <div class="dropdown"><button type="button" class="btn btn-sm btn-sa-muted d-block" id="widget-context-menu-4" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                            <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z">
                                            </path>
                                        </svg></button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="widget-context-menu-4">
                                        <li><a class="dropdown-item" href="#">Settings</a></li>
                                        <li><a class="dropdown-item" href="#">Move</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="#">Remove</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="saw-pulse__counter">148</div>
                        <div class="sa-widget-table saw-pulse__table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Active pages</th>
                                        <th class="text-end">Users</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="#" class="text-reset">/products/brandix-z4</a></td>
                                        <td class="text-end">15</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="text-reset">/categories/drivetrain</a></td>
                                        <td class="text-end">11</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="text-reset">/categories/monitors</a></td>
                                        <td class="text-end">7</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="text-reset">/account/orders</a></td>
                                        <td class="text-end">4</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="text-reset">/cart</a></td>
                                        <td class="text-end">3</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="text-reset">/checkout</a></td>
                                        <td class="text-end">3</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#" class="text-reset">/pages/about-us</a></td>
                                        <td class="text-end">1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8 col-xxl-9 d-flex">
                    <div class="card flex-grow-1 saw-chart" data-sa-data="[{&quot;label&quot;:&quot;Jan&quot;,&quot;value&quot;:50},{&quot;label&quot;:&quot;Feb&quot;,&quot;value&quot;:130},{&quot;label&quot;:&quot;Mar&quot;,&quot;value&quot;:525},{&quot;label&quot;:&quot;Apr&quot;,&quot;value&quot;:285},{&quot;label&quot;:&quot;May&quot;,&quot;value&quot;:470},{&quot;label&quot;:&quot;Jun&quot;,&quot;value&quot;:130},{&quot;label&quot;:&quot;Jul&quot;,&quot;value&quot;:285},{&quot;label&quot;:&quot;Aug&quot;,&quot;value&quot;:240},{&quot;label&quot;:&quot;Sep&quot;,&quot;value&quot;:710},{&quot;label&quot;:&quot;Oct&quot;,&quot;value&quot;:470},{&quot;label&quot;:&quot;Nov&quot;,&quot;value&quot;:640},{&quot;label&quot;:&quot;Dec&quot;,&quot;value&quot;:1110}]">
                        <div class="sa-widget-header saw-chart__header">
                            <h2 class="sa-widget-header__title">Income statistics</h2>
                            <div class="sa-widget-header__actions">
                                <div class="dropdown"><button type="button" class="btn btn-sm btn-sa-muted d-block" id="widget-context-menu-5" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                            <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z">
                                            </path>
                                        </svg></button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="widget-context-menu-5">
                                        <li><a class="dropdown-item" href="#">Settings</a></li>
                                        <li><a class="dropdown-item" href="#">Move</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="#">Remove</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="saw-chart__body">
                            <div class="saw-chart__container"><canvas></canvas></div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="col-12 col-xxl-9 d-flex">
                <div class="card flex-grow-1 saw-table">
                    <div class="sa-widget-header saw-table__header">
                        <h2 class="sa-widget-header__title">Recent orders</h2>
                    </div>
                    <div class="saw-table__body sa-widget-table text-nowrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Item</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recent_orders) { ?>
                                    <?php foreach ($recent_orders as $k => $order) {
                                        $us = $User->get($order->user_id);
                                        $profile = $User->getProfile($order->user_id);

                                        $details = $Orders->getDetails($order->order_id);
                                        // Calculate total
                                        $total_amount = 0;
                                        foreach ($details as $detail) {
                                            $total_amount += $detail->vendor_amount;
                                        }

                                        // Set Status
                                        $status = $order->status == 'pending' ? array('title' => 'Pending', 'color' => 'warning') : 'Rejected';
                                        $status = $order->status == 'accepted' ? array('title' => 'Accepted', 'color' => 'warning') : $status;
                                        $status = $order->status == 'picked' ? array('title' => 'Awaiting Delivery', 'color' => 'warning') : $status;
                                        $status = $order->status == 'completed' ? array('title' => 'Delivered', 'color' => 'success') : $status;
                                    ?>
                                        <tr>
                                            <td><a href="orders/view/<?= $order->invoice; ?>" class="text-reset fw-bold">#<?= $order->invoice; ?></a></td>
                                            <td><?= $order->payment_method; ?></td>
                                            <td>
                                                <div class="d-flex fs-6">
                                                    <div class="badge badge-sa-<?= $status['color'] ?>"><?= $status['title'] ?></div>
                                                </div>
                                            </td>
                                            <td><?= count($details) ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="buyer/view/<?= $us->id ?>" class="sa-symbol sa-symbol--shape--circle sa-symbol--size--md me-3">
                                                        <?php if ($profile->image && $profile->image != 'user-avatar.jpg') { ?>
                                                            <img src="<?= SITE_URL ?>assets/images/profile/<?= $profile->image; ?>" width="40" height="40" alt="" style="object-fit: cover;" />
                                                        <?php } else { ?>
                                                            <div class="sa-symbol__text"><?= $us->first_name[0] . ' ' . $us->last_name[0]; ?></div>
                                                        <?php } ?>
                                                    </a>
                                                    <div><a href="buyer/view/<?= $us->id ?>" class="text-reset"><?= $us->first_name . ' ' . $us->last_name; ?></a></div>
                                                </div>
                                            </td>
                                            <td><?= date_format(date_create($order->created_at), 'M d, Y') ?></td>
                                            <td><?= Helpers::format_currency($order->total_amount) ?></span><span class="sa-price__decimal">.00</span></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xxl-3">
                <div class="card saw-indicator flex-grow-1 mb-4">
                    <div class="sa-widget-header saw-indicator__header">
                        <h2 class="sa-widget-header__title">Pending Orders</h2>
                    </div>
                    <div class="saw-indicator__body">
                        <div class="saw-indicator__value text-start"><?= $pending_count ?></div>
                    </div>
                </div>
                <div class="card saw-indicator flex-grow-1 mb-4">
                    <div class="sa-widget-header saw-indicator__header">
                        <h2 class="sa-widget-header__title">Orders Awaiting Delivery</h2>
                    </div>
                    <div class="saw-indicator__body">
                        <div class="saw-indicator__value text-start"><?= $awaiting_delivery_count ?></div>
                    </div>
                </div>
                <div class="card saw-indicator flex-grow-1">
                    <div class="sa-widget-header saw-indicator__header">
                        <h2 class="sa-widget-header__title">Orders Completed</h2>
                    </div>
                    <div class="saw-indicator__body">
                        <div class="saw-indicator__value text-start"><?= $delivered_count ?></div>
                    </div>
                </div>
            </div>


            <div class="col-12 col-lg-6 d-flex">
                <div class="card flex-grow-1">
                    <div class="card-body">
                        <div class="sa-widget-header">
                            <h2 class="sa-widget-header__title">Recent notification</h2>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush  <?= !$recent_notifications ? 'h-100 align-items-center justify-content-center' : null; ?>">
                        <?php if ($recent_notifications) { ?>
                            <?php foreach ($recent_notifications as $k => $v) {
                                $id = $v->id;
                                $date_added = $v->date_added;
                                $status = $v->status;
                                if ($v->snippet_id) {
                                    $v = $NotificationSnippets->get($v->snippet_id);
                                }
                            ?>
                                <li class="list-group-item py-2">
                                    <div class="d-flex align-items-center py-3">
                                        <div class="d-flex align-items-center flex-grow-1 flex-wrap">
                                            <div class="col">
                                                <div class="text-muted fs-exact-13">
                                                    <h6><?= $v->subject; ?></h6>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-auto">
                                                <?= date_format(date_create($date_added), 'Y-m-d, H:ia'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } else { ?>
                            <li class="list-group-item py-2">
                                <p>No new Notifiction</p>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>


            <div class="col-12 col-lg-3 d-flex">
                <div class="card flex-grow-1">
                    <div class="card-body">
                        <div class="sa-widget-header">
                            <h2 class="sa-widget-header__title">Recent Vendors</h2>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush <?= !$recent_vendors ? 'h-100 align-items-center justify-content-center' : 'h-100 align-items-start'; ?>">
                        <?php if ($recent_vendors) { ?>
                            <?php foreach ($recent_vendors as $v) {
                                $vendor = $User->getVendor($v->id);
                                $profile = $User->getProfile($v->id);
                                $total_spent = 0;
                            ?>
                                <li class="list-group-item py-2">
                                    <div class="d-flex align-items-center py-3">
                                        <a href="vendors/view/<?= $vendor->id ?>" class="me-4">
                                            <?php if ($profile->image && $profile->image != 'user-avatar.jpg') { ?>
                                                <div class="sa-symbol sa-symbol--shape--rounded sa-symbol--size--lg">
                                                    <img src="<?= SITE_URL ?>assets/images/profile/<?= $profile->image; ?>" width="40" height="40" alt="" style="object-fit: cover;" />
                                                </div>
                                            <?php } else { ?>
                                                <div class="d-flex align-items-center justify-content-center bg-secondary sa-symbol--shape--rounded" style="width: 40px; height: 40px;">
                                                    <span class="text-muted fs-exact-13"><?= $v->first_name[0] . $v->last_name[0]; ?></span>
                                                </div>
                                            <?php } ?>
                                        </a>
                                        <div class="col">
                                            <a href="vendors/view/<?= $vendor->id ?>" class="text-reset fw-bold"><?= $v->first_name . ' ' . $v->last_name; ?></a>
                                            <span class="text-muted fs-exact-13 text-truncate"><?= $v->email; ?> </span>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } else { ?>
                            <li class="list-group-item py-2">
                                <p>No new Buyer</p>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-lg-3 d-flex">
                <div class="card flex-grow-1">
                    <div class="card-body">
                        <div class="sa-widget-header">
                            <h2 class="sa-widget-header__title">Recent Buyer</h2>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush <?= !$recent_buyers ? 'h-100 align-items-center justify-content-center' :  'h-100 align-items-start'; ?>">
                        <?php if ($recent_buyers) { ?>
                            <?php foreach ($recent_buyers as $k => $v) {
                                $profile = $User->getProfile($v->id);
                                $total_spent = 0;
                            ?>
                                <li class="list-group-item py-2">
                                    <div class="d-flex align-items-center py-3">
                                        <a href="buyers/view/<?= $v->id ?>" class="me-4">
                                            <?php if ($profile->image && $profile->image != 'user-avatar.jpg') { ?>
                                                <div class="sa-symbol sa-symbol--shape--rounded sa-symbol--size--lg">
                                                    <img src="<?= SITE_URL ?>assets/images/profile/<?= $profile->image; ?>" width="40" height="40" alt="" style="object-fit: cover;" />
                                                </div>
                                            <?php } else { ?>
                                                <div class="d-flex align-items-center justify-content-center bg-secondary sa-symbol--shape--rounded" style="width: 40px; height: 40px;">
                                                    <span class="text-muted fs-exact-13"><?= $v->first_name[0] . $v->last_name[0]; ?></span>
                                                </div>
                                            <?php } ?>
                                        </a>
                                        <div class="d-flex align-items-center flex-grow-1 flex-wrap">
                                            <div class="col">
                                                <a href="buyers/view/<?= $v->id ?>" class="text-reset fw-bold"><?= $v->first_name . ' ' . $v->last_name; ?></a>
                                                <div class="text-muted fs-exact-13">
                                                    <?= $v->email; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } else { ?>
                            <li class="list-group-item py-2">
                                <p>No new Buyer</p>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <?php if (false) { ?>
                <div class="col-12 col-xxl-3 d-flex">
                    <div class="card flex-grow-1 saw-chart-circle" data-sa-data="[{&quot;label&quot;:&quot;Yandex&quot;,&quot;value&quot;:2742,&quot;color&quot;:&quot;#ffd333&quot;,&quot;orders&quot;:12},{&quot;label&quot;:&quot;YouTube&quot;,&quot;value&quot;:3272,&quot;color&quot;:&quot;#e62e2e&quot;,&quot;orders&quot;:51},{&quot;label&quot;:&quot;Google&quot;,&quot;value&quot;:2303,&quot;color&quot;:&quot;#3377ff&quot;,&quot;orders&quot;:4},{&quot;label&quot;:&quot;Facebook&quot;,&quot;value&quot;:1434,&quot;color&quot;:&quot;#29cccc&quot;,&quot;orders&quot;:10},{&quot;label&quot;:&quot;Instagram&quot;,&quot;value&quot;:799,&quot;color&quot;:&quot;#5dc728&quot;,&quot;orders&quot;:1}]" data-sa-container-query="{&quot;600&quot;:&quot;saw-chart-circle--size--lg&quot;}">
                        <div class="sa-widget-header saw-chart-circle__header">
                            <h2 class="sa-widget-header__title">Sales by traffic source</h2>
                            <div class="sa-widget-header__actions">
                                <div class="dropdown"><button type="button" class="btn btn-sm btn-sa-muted d-block" id="widget-context-menu-7" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                            <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z">
                                            </path>
                                        </svg></button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="widget-context-menu-7">
                                        <li><a class="dropdown-item" href="#">Settings</a></li>
                                        <li><a class="dropdown-item" href="#">Move</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="#">Remove</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="saw-chart-circle__body">
                            <div class="saw-chart-circle__container"><canvas></canvas></div>
                        </div>
                        <div class="sa-widget-table saw-chart-circle__table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th class="text-center">Orders</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="saw-chart-circle__symbol" style="--saw-chart-circle__symbol--color:#ffd333"></div>
                                                <div class="ps-2">Yandex</div>
                                            </div>
                                        </td>
                                        <td class="text-center">12</td>
                                        <td class="text-end">
                                            <div class="sa-price"><span class="sa-price__symbol">$</span><span class="sa-price__integer">2,742</span><span class="sa-price__decimal">.00</span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="saw-chart-circle__symbol" style="--saw-chart-circle__symbol--color:#e62e2e"></div>
                                                <div class="ps-2">YouTube</div>
                                            </div>
                                        </td>
                                        <td class="text-center">51</td>
                                        <td class="text-end">
                                            <div class="sa-price"><span class="sa-price__symbol">$</span><span class="sa-price__integer">3,272</span><span class="sa-price__decimal">.00</span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="saw-chart-circle__symbol" style="--saw-chart-circle__symbol--color:#3377ff"></div>
                                                <div class="ps-2">Google</div>
                                            </div>
                                        </td>
                                        <td class="text-center">4</td>
                                        <td class="text-end">
                                            <div class="sa-price"><span class="sa-price__symbol">$</span><span class="sa-price__integer">2,303</span><span class="sa-price__decimal">.00</span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="saw-chart-circle__symbol" style="--saw-chart-circle__symbol--color:#29cccc"></div>
                                                <div class="ps-2">Facebook</div>
                                            </div>
                                        </td>
                                        <td class="text-center">10</td>
                                        <td class="text-end">
                                            <div class="sa-price"><span class="sa-price__symbol">$</span><span class="sa-price__integer">1,434</span><span class="sa-price__decimal">.00</span></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="saw-chart-circle__symbol" style="--saw-chart-circle__symbol--color:#5dc728"></div>
                                                <div class="ps-2">Instagram</div>
                                            </div>
                                        </td>
                                        <td class="text-center">1</td>
                                        <td class="text-end">
                                            <div class="sa-price"><span class="sa-price__symbol">$</span><span class="sa-price__integer">799</span><span class="sa-price__decimal">.00</span></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 d-flex">
                    <div class="card flex-grow-1">
                        <div class="card-body">
                            <div class="sa-widget-header mb-4">
                                <h2 class="sa-widget-header__title">Recent activity</h2>
                                <div class="sa-widget-header__actions">
                                    <div class="dropdown"><button type="button" class="btn btn-sm btn-sa-muted d-block" id="widget-context-menu-8" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z">
                                                </path>
                                            </svg></button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="widget-context-menu-8">
                                            <li><a class="dropdown-item" href="#">Settings</a></li>
                                            <li><a class="dropdown-item" href="#">Move</a></li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li><a class="dropdown-item text-danger" href="#">Remove</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="sa-timeline mb-n2 pt-2">
                                <ul class="sa-timeline__list">
                                    <li class="sa-timeline__item">
                                        <div class="sa-timeline__item-title">Yesterday</div>
                                        <div class="sa-timeline__item-content">Phasellus id mattis nulla. Mauris
                                            velit nisi, imperdiet vitae sodales in, maximus ut lectus. Vivamus
                                            commodo scelerisque lacus, at porttitor dui iaculis id. <a href="#">Curabitur imperdiet ultrices fermentum.</a></div>
                                    </li>
                                    <li class="sa-timeline__item">
                                        <div class="sa-timeline__item-title">5 days ago</div>
                                        <div class="sa-timeline__item-content">Nulla ut ex mollis, volutpat
                                            tellus vitae, accumsan ligula. <a href="#">Curabitur imperdiet
                                                ultrices fermentum.</a></div>
                                    </li>
                                    <li class="sa-timeline__item">
                                        <div class="sa-timeline__item-title">March 27</div>
                                        <div class="sa-timeline__item-content">Donec tempor sapien et fringilla
                                            facilisis. Nam maximus consectetur diam.</div>
                                    </li>
                                    <li class="sa-timeline__item">
                                        <div class="sa-timeline__item-title">November 30</div>
                                        <div class="sa-timeline__item-content">Many philosophical debates that
                                            began in ancient times are still debated today. In one general
                                            sense, philosophy is associated with wisdom, intellectual culture
                                            and a search for knowledge.</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 d-flex">
                    <div class="card flex-grow-1">
                        <div class="card-body">
                            <div class="sa-widget-header">
                                <h2 class="sa-widget-header__title">Recent reviews</h2>
                                <div class="sa-widget-header__actions">
                                    <div class="dropdown"><button type="button" class="btn btn-sm btn-sa-muted d-block" id="widget-context-menu-9" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z">
                                                </path>
                                            </svg></button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="widget-context-menu-9">
                                            <li><a class="dropdown-item" href="#">Settings</a></li>
                                            <li><a class="dropdown-item" href="#">Move</a></li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li><a class="dropdown-item text-danger" href="#">Remove</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item py-2">
                                <div class="d-flex align-items-center py-3"><a href="app-product.html" class="me-4">
                                        <div class="sa-symbol sa-symbol--shape--rounded sa-symbol--size--lg">
                                            <img src="images/products/product-1-40x40.jpg" width="40" height="40" alt="" />
                                        </div>
                                    </a>
                                    <div class="d-flex align-items-center flex-grow-1 flex-wrap">
                                        <div class="col"><a href="app-product.html" class="text-reset fs-exact-14">Wiper Blades Brandix WL2</a>
                                            <div class="text-muted fs-exact-13">Reviewed by <a href="app-customer.html" class="text-reset">Ryan Ford</a>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-auto">
                                            <div class="sa-rating ms-sm-3 my-2 my-sm-0" style="--sa-rating--value:0.6">
                                                <div class="sa-rating__body"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>