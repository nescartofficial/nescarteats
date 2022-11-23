<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$Menus = new Menus();
$pagination = new Pagination();
$sellers = new General('sellers');
$Orders = new Orders();
$products = new General('products');

$vendor = $user->getVendor();

// $Orders = $Orders->getAll($user->data()->id, 'supplier_user_id', '=');
$title = "All Orders";
$status_icon = "Orders.svg";
$title = Input::get('status') && Input::get('status') == 2 ? "Orders Awaiting Delivery" : $title;
$status_icon = Input::get('status') && Input::get('status') == 2 ? "Awaiting Delivery.svg" : $status_icon;
$title = Input::get('status') && Input::get('status') == 3 ? "Completed Orders" : $title;
$status_icon = Input::get('status') && Input::get('status') == 3 ? "Items Delivered.svg" : $status_icon;


$status = Input::get('status') && Input::get('status') != 'all' ? Input::get('status') : null;
$search = Input::get('order') ? Input::get('order') : null;

$formated_vendor_id = '%"' . $vendor->id . '"%';
$vendor_like = " AND vendors LIKE '{$formated_vendor_id}' ";
$searchTerm = $status ? "WHERE id > 0 {$vendor_like} AND status = {$status}" : "WHERE id > 0 {$vendor_like}";
$searchTerm = $search ? "WHERE id > 0 {$vendor_like} AND order_id LIKE '%{$search}%' OR invoice LIKE '{$search}'" : $searchTerm;


$next = Input::get('p') ? Input::get('p') : 1;
$per_page = 4;
$total_record = $pagination->countAll('orders', $searchTerm);
$paginate = new Pagination($next, $per_page, $total_record);
$items = $total_record ? $Orders->getPages($per_page, $paginate->offset(), $searchTerm) : null;

$enquiry = Input::get('sub') && Input::get('sub') == 'view' && Input::get('sub1') && is_numeric(Input::get('sub1')) ? $Orders->get(Input::get('sub1')) : null;
$enquiry_details = $enquiry ? json_decode($enquiry->details, true) : null;

// Counters
$awaiting_count = $delivered_count = 0;
$searchTerm = "WHERE id > 0 {$vendor_like} AND status = 'picked'";
$awaiting_count = $pagination->countAll('orders', $searchTerm);
$searchTerm = "WHERE id > 0 {$vendor_like} AND status = 'completed'";
$delivered_count = $pagination->countAll('orders', $searchTerm);

Alerts::displayError();
Alerts::displaySuccess();
?>

<div class="row">
    <div class="col-md-12 mb-5">
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

    <!-- Header -->
    <div class="col-md-12 mb-4">
        <!-- Orders -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <a href="dashboard/orders">
                    <div class="card shadow stats border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <img src="assets/icons/Orders.svg" style="width: 40px;">
                                <i class="fa fa-external-link-alt"></i>
                            </div>
                            <span class="font-weight-bold" style="font-size: 12px;">Total Orders</span>
                            <h4 class="title mb-0"><?= $total_record ? $total_record : 0; ?></h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="dashboard/orders?status=2">
                    <div class="card shadow stats border-0 h-100 bg-yellow-shade">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <img src="assets/icons/Awaiting Delivery.svg" style="width: 40px;">
                                <i class="fa fa-external-link-alt"></i>
                            </div>
                            <span class="font-weight-bold" style="font-size: 12px;">Orders Awaiting Delivery</span>
                            <h4 class="title mb-0"><?= $awaiting_count ? $awaiting_count : 0; ?></h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="dashboard/orders?status=3">
                    <div class="card shadow stats border-0 h-100  bg-green-shade">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <img src="assets/icons/Items Delivered.svg" style="width: 40px;">
                                <i class="fa fa-external-link-alt"></i>
                            </div>
                            <span class="font-weight-bold" style="font-size: 12px;">Completed Orders</span>
                            <h4 class="title mb-0"><?= $delivered_count ? $delivered_count : 0; ?></h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow border-0 mobile-card-section" style="min-height: 595px;">
            <?php if ($items) { ?>
                <div class="card-body">
                    <div class="mb-4 d-flex justify-content-between">
                        <div class="d-flex">
                            <img class="me-3" src="assets/icons/<?= $status_icon ?>" alt="mp icon" />
                            <div>
                                <h5 class=""><?= $title ?></h5>

                                <span>Total Orders: </span>
                                <span><?= $total_record ?></span>
                            </div>
                        </div>
                    </div>

                    <form class="mb-4">
                        <input type="text" name="order" placeholder="Search for your orders using Order ID or Invoice ID" class="form-control form-control--search mx-auto" id="table-search" />
                    </form>


                    <?php foreach ($items as $k => $order) { // Order items
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
                            'type' => 'list',
                            'total_amount' => $total_amount,
                            'count' => count($details),
                            'cover' => $first_product->cover,
                            'status' => $status
                        ), 'view/user/component');
                    } ?>

                    <?php if ($paginate && $paginate->total_pages() > 1) { // Pagination 
                    ?>
                        <nav class="mt-5 mb-4" aria-label="Page navigation sample">
                            <ul class="pagination">
                                <li class="page-item <?= $paginate->has_previous_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=<?= $paginate->previous_page() ?>">Previous</a></li>
                                <?php if ($paginate->total_pages() > 2) { ?>
                                    <li class="page-item <?= Input::get('p') && Input::get('p') == 1 ? 'active' : null; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=1">1</a></li>
                                    <li class="page-item <?= Input::get('p') && Input::get('p') == 2 ? 'active' : null; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=2">2</a></li>
                                    <li class="page-item <?= Input::get('p') && Input::get('p') == 3 ? 'active' : null; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=3">3</a></li>
                                <?php } ?>
                                <li class="page-item disabled">
                                    <a class="page-link"><?= $next . ' of ' . $paginate->total_pages() ?></a>
                                </li>
                                <?php if ($paginate->total_pages() > 4) { ?>
                                    <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 4 ? 'active' : null; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=4">4</a></li>
                                    <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 5 ? 'active' : null; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=5">5</a></li>
                                <?php } ?>
                                <li class="page-item <?= $paginate->has_next_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=<?= $paginate->next_page() ?>">Next</a></li>
                            </ul>
                        </nav>
                    <?php } ?>
                </div>

            <?php } else { ?>
                <div class="d-flex align-items-center justify-content-center py-5" style="min-height: 595px;">
                    <div class="col-lg-6 text-center">
                        <i class="fa fa-shopping-cart fa-3x"></i>
                        <?php if ($status || $search) { ?>
                            <h3 class="mb-3 mt-4 font-weight-bold">Oops! We couldn't find what you are looking for.</h3>
                            <a href="dashboard/orders">See all Orders</a>
                        <?php } else { ?>
                            <h3 class="mt-4 font-weight-bold">You have no order to your store.</h3>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>