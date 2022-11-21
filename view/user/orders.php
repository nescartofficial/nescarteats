<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$Orders = new General('orders');
$OrderDetails = new General('order_details');
$Vendors = new General('vendors');
$Menus = new General('menus');

$uid = $user->data()->id;

$title = "All Orders";
$status_icon = "Orders.svg";
$title = Input::get('status') && Input::get('status') == 2 ? "Orders Awaiting Delivery" : $title;
$status_icon = Input::get('status') && Input::get('status') == 2 ? "Awaiting Delivery.svg" : $status_icon;
$title = Input::get('status') && Input::get('status') == 3 ? "Completed Orders" : $title;
$status_icon = Input::get('status') && Input::get('status') == 3 ? "Items Delivered.svg" : $status_icon;


$status = Input::get('status') && Input::get('status') != 'all' ? Input::get('status') : null;
$search = Input::get('order') ? Input::get('order') : null;

$searchTerm = $status ? "WHERE id > 0 AND user_id = {$uid} AND status = {$status}" : "WHERE id > 0 AND  user_id = {$uid}";
$searchTerm = $search ? "WHERE id > 0 AND order_id LIKE '%{$search}%' OR invoice LIKE '%{$search}%' AND user_id = {$uid}" : $searchTerm;

$next = Input::get('p') ? Input::get('p') : 1;
$per_page = 4;
$pagination = new Pagination();
$total_record = $pagination->countAll('orders', $searchTerm);
$paginate = new Pagination($next, $per_page, $total_record);
$items = $Orders->getPages($per_page, $paginate->offset(), $searchTerm);

$week_items = $Orders->getPages(7, 0, "WHERE user_id = {$uid}");
$month_items = $Orders->getPages(30, 0, "WHERE user_id = {$uid}");

$order = Input::get('sub') && Input::get('sub') == 'view' && Input::get('sub1') && is_numeric(Input::get('sub1')) ? $Orders->get(Input::get('sub1')) : null;
$order_details = $order ? json_decode($order->details, true) : null;

$awaiting_count = $delivered_count = 0;
$order_count = $pagination->countAll('orders', "WHERE id > 0 AND user_id = {$uid} ");
$searchTerm = "WHERE id > 0 AND status = 2 AND user_id = {$uid}  ";
$awaiting_count = $pagination->countAll('orders', $searchTerm);
$searchTerm = "WHERE id > 0 AND status = 3 AND user_id = {$uid}  ";
$delivered_count = $pagination->countAll('orders', $searchTerm);

Alerts::displayError();
Alerts::displaySuccess();
?>
<section class="container-fluid py-5">
    <header class="container mb-6">
        <div class="d-flex align-items-center justify-contnt-between">
            <!-- Back -->
            <a href="#" id="back_button" class="d-flex p-2 border-0 rounded shadow bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22.5 38.381">
                    <path id="chevron-left" d="M30.949,7.767a3.526,3.526,0,0,1,0,5.216l-12.4,12.932,12.4,12.932a3.516,3.516,0,0,1,0,5.207,3.93,3.93,0,0,1-5.344,0c-1.384-1.344-14.9-15.535-14.9-15.535a3.619,3.619,0,0,1,0-5.214S24.221,9.119,25.6,7.769a3.928,3.928,0,0,1,5.344,0Z" transform="translate(-9.6 -6.72)" />
                </svg>

            </a>
            <h4 class="mb-0 mx-auto pr-40">My Orders</h4>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if ($items) { ?>
                    <form class="mb-4">
                        <input type="text" name="order" placeholder="Search for your orders using Order ID or Invoice ID" class="form-control form-control--search mx-auto" id="table-search" />
                    </form>

                    <?php if ($week_items) { ?>
                        <!-- This week -->
                        <h3 class="mb-4">This Week</h3>
                        <div class="list-group list-group-flush mb-4">
                            <?php foreach ($week_items as $index => $con) {
                                $item_list = $OrderDetails->getAll($con->order_id, 'order_id', '=');

                                $status = $con->status == 'pending' ? array('title' => 'Pending', 'color' => 'text-accent') : 'Rejected';
                                $status = $con->status == 'awaiting' ? array('title' => 'Awaiting Delivery', 'color' => 'text-accent') : $status;
                                $status = $con->status == 'completed' ? array('title' => 'Awaiting Delivery', 'color' => 'text-green') : $status;

                                Component::render('order', array('data' => $con, 'count' => count($item_list), 'type' => 'list', 'status' => $status), 'view/user/component');
                            } ?>
                        </div>
                    <?php } ?>

                    <?php if ($month_items) { ?>
                        <!-- This Month -->
                        <h3 class="mb-4">This Month</h3>
                        <div class="list-group list-group-flush mb-4">
                            <?php foreach ($month_items as $index => $con) {
                                $item_list = $OrderDetails->getAll($con->order_id, 'order_id', '=');

                                $status = $con->status == 'pending' ? array('title' => 'Pending', 'color' => 'text-accent') : 'Rejected';
                                $status = $con->status == 'awaiting' ? array('title' => 'Awaiting Delivery', 'color' => 'text-accent') : $status;
                                $status = $con->status == 'completed' ? array('title' => 'Awaiting Delivery', 'color' => 'text-green') : $status;

                                Component::render('order', array('data' => $con, 'count' => count($item_list), 'type' => 'list', 'status' => $status), 'view/user/component');
                            } ?>
                        </div>
                    <?php } ?>

                    <!-- Others -->
                    <h3 class="mb-4">Others</h3>
                    <div class="list-group list-group-flush">
                        <?php foreach ($items as $index => $con) {
                            $item_list = $OrderDetails->getAll($con->order_id, 'order_id', '=');

                            $status = $con->status == 'pending' ? array('title' => 'Pending', 'color' => 'text-accent') : 'Rejected';
                            $status = $con->status == 'awaiting' ? array('title' => 'Awaiting Delivery', 'color' => 'text-accent') : $status;
                            $status = $con->status == 'completed' ? array('title' => 'Awaiting Delivery', 'color' => 'text-green') : $status;

                            Component::render('order', array('data' => $con, 'count' => count($item_list), 'type' => 'list', 'status' => $status), 'view/user/component');
                        } ?>
                    </div>

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
                <?php } else { ?>
                    <div class="d-flex align-items-center justify-content-center py-5">
                        <div class="text-center">
                            <i class="fa fa-shopping-cart fa-3x"></i>
                            <?php if ($status || $search) { ?>
                                <h3 class="mb-3 mt-4 font-weight-bold">Oops! We couldn't find what you are looking for.</h3>
                                <a href="dashboard/orders">See all Orders</a>
                            <?php } else { ?>
                                <h3 class="mt-4 font-weight-bold">You have placed no orders yet!</h3>
                                <p class="mb-4">All your orders will be saved here for you to access their state anytime.</p>
                                <a href="dashboard" class="btn bg-site-primary border-0">Get Started</a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>