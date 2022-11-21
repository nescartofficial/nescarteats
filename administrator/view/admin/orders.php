<?php
// include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$pagination = new Pagination();
$world = new World();
$Orders = new Orders();
$Menus = new Menus();
$Vendors = new General('vendors');
$ordering_methods = new General('ordering_methods');

// Getting Orders
$items = $Orders->getAll('pending', 'status', '=');
$title = "Pending Orders";
$items = Input::get('show') && Input::get('show') == 'all' ? $Orders->getAll() : $items;
$title = Input::get('show') && Input::get('show') == 'pending' ? "All Orders" : $title;
$items = Input::get('show') && Input::get('show') == 'pending' ? $Orders->getAll('pending', 'status', '=') : $items;
$title = Input::get('show') && Input::get('show') == 'pending' ? "Pending Orders" : $title;
$items = Input::get('show') && Input::get('show') == 'awaiting' ? $Orders->getAll('picked', 'status', '=') : $items;
$title = Input::get('show') && Input::get('show') == 'awaiting' ? "Awaiting Orders" : $title;
$items = Input::get('show') && Input::get('show') == 'delivered' ? $Orders->getAll('completed', 'status', '=') : $items;
$title = Input::get('show') && Input::get('show') == 'delivered' ? "Delivered Orders" : $title;

// Search Seller Order
$vendor_count = $pagination->countAll('orders', "WHERE id > 0");
$vendor = Input::get('seller') ? $Vendors->get(Input::get('seller')) : null;
$items = $vendor ? $Orders->getPages($vendor_count, 0, "WHERE id > 0 AND details LIKE '%seller_:_{$vendor->id}_%' ", "ORDER BY date_added DESC") : $items;
$title = $vendor ? "Seller Orders: {$vendor->name}" : $title;

// Search Buyer Order
$buyer = Input::get('buyer') ? $user->get(Input::get('buyer')) : null;
$buyer_count = $buyer ? $pagination->countAll('orders', "WHERE id > 0  AND user_id = {$buyer->id} ") : null;
$items = $buyer ? $Orders->getPages($buyer_count, 0, "WHERE id > 0 AND user_id = {$buyer->id} ", "ORDER BY date_added DESC") : $items;
$title = $buyer ? "Buyer Orders: " . $buyer->first_name . ' ' . $buyer->last_name : $title;

// print_r($items);

// Total Earning
$total_earning = $Orders->getAllSumWhere("total_amount", "status = 3 AND cancel = 0");
$total_earning = Helpers::format_currency($total_earning ? $total_earning : 0);


$all_count = $pagination->countAll('orders', "WHERE id > 0");
$pending_count = $pagination->countAll('orders', "WHERE id > 0 AND status = 'pending'");
$awaiting_delivery_count = $pagination->countAll('orders', "WHERE id > 0 AND status = 'picked'");
$delivered_count = $pagination->countAll('orders', "WHERE id > 0 AND status = 'completed'");
$rejected_count = $pagination->countAll('orders', "WHERE id > 0 AND status = 'rejected'");

$order = Input::get('action') && Input::get('action') == 'view' && Input::get('sub') ? $Orders->get(Input::get('sub'), 'invoice') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<?php if ($order && Input::get('action') == 'view') {
    $platform_amount = 0;
    $is_all_picked = true;
    $us = $user->get($order->user_id);
    $profile = $user->getProfile($us->id);

    $details = $Orders->getDetails($order->order_id);

    $status = $order->status == 'pending' ? 'pending' : null;
    $status = $order->status == 'accepted' ? 'accepted' : $status;
    $status = $order->status == 'picked' ? 'picked' : $status;
    $status = $order->status == 'completed' ? 'delivered' : $status;
    $status = $order->status == 'rejected' ? 'rejected' : $status;
?>
    <div id="top" class="sa-app__body">
        <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
            <div class="container container--max--xl">
                <div class="py-5">
                    <div class="row g-4 align-items-center">
                        <div class="col">
                            <nav class="mb-2" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-sa-simple">
                                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="orders">Orders</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Order <?= $order->order_id ?></li>
                                </ol>
                            </nav>
                            <h1 class="h3 m-0">Order <?= $order->order_id ?></h1>
                        </div>
                        <div class="col-auto d-flex">
                            <a onclick="return confirm('Are you sure to delete this item?');" href="controllers/orders.php?rq=delete&id=<?= $order->id ?>" class="btn btn-secondary me-3">Delete</a>
                        </div>
                    </div>
                </div>
                <div class="sa-page-meta mb-5">
                    <div class="sa-page-meta__body">
                        <div class="sa-page-meta__list">
                            <div class="sa-page-meta__item"><?= date_format(date_create($order->created_at), 'M d, Y') ?> at <?= date_format(date_create($order->created_at), 'H:s:a') ?></div>
                            <div class="sa-page-meta__item"><?= count($details) ?> items</div>
                            <div class="sa-page-meta__item">Total <?= Helpers::format_currency($order->total_amount) ?></div>
                            <div class="sa-page-meta__item d-flex align-items-center fs-6"><span class=""><?= $order->payment_method ?></span></div>
                            <div class="sa-page-meta__item d-flex align-items-center fs-6"><span class="badge badge-sa-success me-2"><?= $status ?></span></div>
                        </div>
                    </div>
                </div>
                <div class="sa-entity-layout" data-sa-container-query="{&quot;920&quot;:&quot;sa-entity-layout--size--md&quot;}">
                    <div class="sa-entity-layout__body">
                        <div class="sa-entity-layout__main">
                            <div class="sa-card-area"><textarea class="sa-card-area__area" rows="2" placeholder="Notes about order"></textarea>
                                <div class="sa-card-area__card"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg></div>
                            </div>
                            <div class="card mt-5">
                                <div class="card-body px-5 py-4 d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <h2 class="mb-0 fs-exact-18 me-4">Items</h2>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="sa-table">
                                        <tbody>
                                            <?php
                                            foreach ($details as $k => $detail) {
                                                $platform_amount += $detail->platform_amount;
                                                $menu = $Menus->get($detail->menu);
                                                $vendor = $Vendors->get($menu->vendor_id);
                                                if ($detail->status == 'pending' || $detail->status == 'accepted') {
                                                    $is_all_picked = false;
                                                }
                                            ?>
                                                <tr>
                                                    <td class="min-w-20x">
                                                        <div class="d-flex align-items-center">
                                                            <img src="<?= SITE_URL; ?>assets/images/menus/<?= $menu->cover; ?>" class="me-4" width="40" height="40" alt="" />
                                                            <div class="">
                                                                <p class="mb-1 fw-bold"><?= $menu->title; ?></p>
                                                                <a href="sellers/view/<?= $vendor->id ?>" target="_blank" class="text-reset"><?= $vendor->name; ?></a>
                                                                <span class="mx-3">|</span>
                                                                <span class="">Qty: <?= $detail->quantity; ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end" colSpan="2">
                                                        <div class="sa-price"><span class="sa-price__integer"><?= Helpers::format_currency($detail->amount); ?></span><span class="sa-price__decimal">.00</span></div>
                                                    </td>
                                                    <td class="text-end"><span class="badge bg-success"><?= $detail->status; ?></span></td>
                                                    <td class="text-end">
                                                        <div class="sa-price"><span class="sa-price__integer"><?= Helpers::format_currency($detail->total_amount); ?></span><span class="sa-price__decimal">.00</span></div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <td colSpan="4">Total</td>
                                                <td class="text-end">
                                                    <div class="sa-price"><span class="sa-price__integer"><?= Helpers::format_currency($order->total_amount); ?></span><span class="sa-price__decimal">.00</span></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="sa-entity-layout__sidebar">
                            <div class="card">
                                <div class="card-body d-flex align-items-center justify-content-between pb-0 pt-4">
                                    <h2 class="fs-exact-16 mb-0">State/Action</h2>
                                    <!-- <a href="#" class="fs-exact-14">Edit</a> -->
                                </div>
                                <div class="card-body pt-4">

                                    <?php if ($status == "delivered") { ?>
                                        <p>Order have been fullfilled</p>
                                    <?php } else { ?>
                                        <p class="small mb-1 fw-bold">Now</p>
                                        <?php if (!$is_all_picked) { ?>
                                            <p>No action can be taken as all orders have not been picked up.</p>
                                        <?php } ?>

                                        <?php if ($status == "picked") { ?>
                                            <p>Order have been picked up and ready to be delivered.</p>
                                        <?php } ?>

                                        <?php if ($order->acknowledge_delivery) { ?>
                                            <p>Buyer have acknowledged receiving this order.</p>
                                        <?php } ?>

                                        <p class="small mb-1 fw-bold">Next</p>
                                        <?php if ($is_all_picked && $status == "pending") { ?>
                                            <p>When all orders have been picked up and ready for delivery, change state to awaiting delivery.</p>
                                        <?php } else if ($status == "picked") { ?>
                                            <p>When orders have been fulfilled mark this order as completed.</p>
                                        <?php } ?>
                                    <?php } ?>

                                    <div class="mt-3">
                                        <?php if ($is_all_picked) { ?>
                                            <?php if ($status == "delivered") { ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php } else { ?>
                                                <?php if ($status == "pending" || $status == "accepted") { ?>
                                                    <a onclick="return confirm('Are you sure all items have been picked up and ready for delivery?');" class="btn btn-primary" href="controllers/orders.php?rq=status&id=<?= $order->id ?>&status=picked">Make Awaiting Delivery</a>
                                                <?php } else if ($status == "picked") { ?>
                                                    <a onclick="return confirm('Are you sure all items have been picked up and delivered?');" class="btn btn-primary" href="controllers/orders.php?rq=status&id=<?= $order->id ?>&status=completed">Mark Completed (Delivered)</a>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-5">
                                <div class="card-body d-flex align-items-center justify-content-between pb-0 pt-4">
                                    <h2 class="fs-exact-16 mb-0">Details</h2>
                                    <!-- <a href="#" class="fs-exact-14">Edit</a> -->
                                </div>
                                <div class="card-body  pt-4">
                                    <div class="">Invoice: <b><?= $order->invoice ?></b></div>
                                    <div class="">Date: <b><?= date_format(date_create($order->created_at), 'M d, Y') ?> at <?= date_format(date_create($order->created_at), 'H:s:a') ?></b></div>
                                    <div class="">Items: <b><?= count($details) ?></b></div>
                                    <div class="">Total: <b><?= Helpers::format_currency($order->total_amount) ?></b></div>
                                    <div class="">Commision: <b><?= Helpers::format_currency($platform_amount) ?></b></div>
                                    <div class="mb-3">Payment Method: <b><?= $order->payment_method ?></b></div>
                                    <div class=" d-flex align-items-center fs-6">Status: <span class="badge badge-sa-success ms-2"><?= $status ?></span></div>
                                </div>
                            </div>

                            <div class="card mt-5">
                                <div class="card-body d-flex align-items-center justify-content-between pb-0 pt-4">
                                    <h2 class="fs-exact-16 mb-0">Customer</h2>
                                    <!-- <a href="#" class="fs-exact-14">Edit</a> -->
                                </div>
                                <div class="card-body d-flex align-items-center pt-4">
                                    <div class="sa-symbol sa-symbol--shape--circle sa-symbol--size--lg">
                                        <?php if ($profile->image && $profile->image != 'user-avatar.jpg') { ?>
                                            <img src="<?= SITE_URL ?>assets/images/profile/<?= $profile->image; ?>" width="40" height="40" alt="" style="object-fit: cover;" />
                                        <?php } else { ?>
                                            <div class="sa-symbol__text"><?= $us->first_name[0] . ' ' . $us->last_name[0]; ?></div>
                                        <?php } ?>
                                    </div>
                                    <div class="ms-3 ps-2">
                                        <div class="fs-exact-14 fw-medium">
                                            <a href="buyers/view/<?= $us->id ?>" target="_blank" class="text-reset"><?= $us->first_name . ' ' . $us->last_name; ?></a>
                                        </div>
                                        <div class="mt-1"><a href="#"><?= $us->email; ?></a></div>
                                        <div class="text-muted mt-1"><?= $us->phone; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-5">
                                <div class="card-body d-flex align-items-center justify-content-between pb-0 pt-4">
                                    <h2 class="fs-exact-16 mb-0">Billing/Shipping Address</h2>
                                </div>
                                <div class="card-body pt-4 fs-exact-14">
                                    <?= $profile->address; ?><br />
                                    <?= $world->getCityName($profile->city) . ', ' . $world->getStateName($profile->state) ?><br />
                                    <?= $world->getCountryName($profile->country); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div id="top" class="sa-app__body">
        <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
            <div class="container">
                <div class="py-5">
                    <div class="row g-4 align-items-center">
                        <div class="col">
                            <nav class="mb-2" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-sa-simple">
                                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                                </ol>
                            </nav>
                            <h1 class="h3 m-0">Orders</h1>
                        </div>
                        <!-- <div class="col-auto d-flex"><a href="app-order.html" class="btn btn-primary">New order</a></div> -->
                    </div>
                </div>

                <div class="row gy-5 mb-6">
                    <div class="col-12 col-md-4 d-flex mb-3">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Total Earning</h2>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $total_earning ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Total Orders</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="orders?show=all" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $all_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Pending Orders</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="orders?show=pending" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $pending_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Awaiting Delivery</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="orders?show=awaiting" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $awaiting_delivery_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Order Delivered</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="orders?show=delivered" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $delivered_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Order Rejected</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="orders?show=rejected" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $rejected_count ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="p-4">
                        <h4 class=""><?= $title ?></h4>
                    </div>

                    <div class="p-4"><input type="text" placeholder="Start typing to search for orders" class="form-control form-control--search mx-auto" id="table-search" /></div>
                    <div class="sa-divider"></div>
                    <table class="sa-datatables-init text-nowrap" data-order="[[ 1, &quot;desc&quot; ]]" data-sa-search-input="#table-search">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Payment Method</th>
                                <?php if (Input::get('show') && Input::get('show') == 'all') { ?>
                                    <th>Status</th>
                                <?php } ?>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Commission</th>
                                <th class="w-min" data-orderable="false"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_commission = 0;
                            foreach ($items as $k => $v) {
                                $us = $user->get($v->user_id);
                                $details = $Orders->getDetails($v->order_id);

                                foreach ($details as $kk => $detail) {
                                    $total_commission += $detail->platform_amount;
                                }

                                $status = $v->status == 1 ? 'Pending' : null;
                                $status_color = $v->status == 1 ? 'danger' : null;
                                $status = $v->status == 2 ? 'Accepted' : $status;
                                $status_color = $v->status == 2 ? 'warning' : $status_color;
                                $status = $v->status == 3 ? 'Delivered' : $status;
                                $status_color = $v->status == 3 ? 'success' : $status_color;
                                $status = $v->status == 0 ? 'Rejected' : $status;
                            ?>
                                <tr>
                                    <td><b><a href="orders/view/<?= $v->order_id; ?>" class="text-reset"><?= $v->order_id; ?></a></b></td>
                                    <td><?= date_format(date_create($v->created_at), 'Y-m-d') ?></td>
                                    <td><a href="buyers/view/<?= $us->id ?>" class="text-reset"><?= $us->first_name . ' ' . $us->last_name; ?></a></td>
                                    <td><?= $v->payment_method; ?></td>
                                    <?php if (Input::get('show') && Input::get('show') == 'all') { ?>
                                        <td>
                                            <div class="d-flex fs-6">
                                                <div class="badge badge-sa-<?= $status_color ?>"><?= $status ?></div>
                                            </div>
                                        </td>
                                    <?php } ?>
                                    <td><?= count($details) ?> items</td>
                                    <td>
                                        <div class="sa-price"><span class="sa-price__integer"><?= Helpers::format_currency($v->total_amount) ?></span><span class="sa-price__decimal">.00</span></div>
                                    </td>
                                    <td>
                                        <div class="sa-price"><span class="sa-price__integer"><?= Helpers::format_currency($total_commission) ?></span><span class="sa-price__decimal">.00</span></div>
                                    </td>
                                    <td>
                                        <div class="dropdown"><button class="btn btn-sa-muted btn-sm" type="button" id="order-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                    <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                                </svg></button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="order-context-menu-0">
                                                <li><a class="dropdown-item" href="orders/view/<?= $v->invoice; ?>">View</a></li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/orders.php?rq=delete&id=<?= $v->id ?>">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>