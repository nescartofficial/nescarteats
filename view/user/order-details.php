<?php
include_once('core/init.php');
$User = isset($User) ? $User : new User();
!$User->isLoggedIn() ? Redirect::to('login') : null;
$Menus = new Menus();
$World = new World();
$Orders = new Orders();
$OrderDetails = new General('order_details');

$profile = $User->getProfile();

$order = Input::get('sub') && is_numeric(Input::get('sub')) ? $Orders->get(Input::get('sub'), 'invoice') : null;
$order_details = $order ? $OrderDetails->getAll($order->order_id, 'order_id', '=') : null;

$status = $order->status == 1 ? 'Pending' : null;
$status = $order->status == 2 ? 'Awaiting Delivery' : $status;
$status = $order->status == 3 || $order->acknowledge_delivery ? 'Completed' : $status;
$status = $order->status == 0 ? 'Rejected' : $status;

$us = $User->get($order->user_id);
// $order_details = json_decode($order->details);

Alerts::displayError();
Alerts::displaySuccess();
?>
<section class="container-fluid pt-5">
    <header class="container mb-6">
        <div class="d-flex align-items-center justify-contnt-between">
            <!-- Back -->
            <a href="#" id="back_button" class="d-flex p-2 border-0 rounded shadow bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22.5 38.381">
                    <path id="chevron-left" d="M30.949,7.767a3.526,3.526,0,0,1,0,5.216l-12.4,12.932,12.4,12.932a3.516,3.516,0,0,1,0,5.207,3.93,3.93,0,0,1-5.344,0c-1.384-1.344-14.9-15.535-14.9-15.535a3.619,3.619,0,0,1,0-5.214S24.221,9.119,25.6,7.769a3.928,3.928,0,0,1,5.344,0Z" transform="translate(-9.6 -6.72)" />
                </svg>

            </a>
            <h4 class="mb-0 mx-auto pr-40">Order Details</h4>
        </div>
    </header>
</section>

<section class="card bg-primary rounded-bottom py-5">
    <div class="container-fluid">
        <div class="container">
            <header class="d-flex justify-content-between mb-5">
                <div class="">
                    <h4 class="mb-0 text-white">Your Order Details</h4>
                    <p class="mb-0">[Order ID: <b><?= $order->invoice ?></b>]</p>
                </div>

                <div class="">
                    <?php if ($order->acknowledge_delivery) { ?>
                        <a href="dashboard/review-details?order=<?= $order->invoice ?>" class="btn border-0 bg-accent w-100 mb-4">See Order Rating</a>
                    <?php }  ?>

                    <?php if ($order->status == 2) { ?>
                        <?php if (!$order->acknowledge_delivery) { ?>
                            <a href="controllers/orders.php?rq=complete-order&id=<?= $order->id ?>" class="btn border-0 bg-accent w-100 mb-4">Order received</a>
                        <?php }  ?>
                    <?php } ?>

                </div>
            </header>

            <!-- order Items -->
            <section class="mb-5">
                <h5 class="text-white mb-3">Items</h5>

                <div class="list-group list-group-flush ">
                    <div class="row gy-3">
                        <?php
                        $total_amount = 0;
                        foreach ($order_details as $k => $v) {
                            $menu = $Menus->get($v->menu);
                            $total_amount += ($v->quantity * $menu->price);
                            $status = $v->status;

                            $variation_titles = $v->variations && json_decode($v->variations) ? json_decode($v->variations)->title : null;

                            $addon_titles = '';
                            $addon_list = $v->addons ? json_decode($v->addons) : null;

                            if (count($addon_list) > 0) {
                                foreach ($addon_list as $addon) {
                                    $addon_titles .= $addon->title . ',';
                                }
                            }
                        ?>
                            <div class="col-lg-6">
                                <div class="list-group-item list-group-item-action rounded">
                                    <div class="d-flex align-items-center">
                                        <img src="assets/images/menus/<?= $menu->cover; ?>" class="rounded h-60p w-60p me-3 align-self-start" style="object-fit:cover;" alt="" class="" />
                                        <div class="flex-fill">
                                            <div>
                                                <h4 class="mb-1"><?= $menu->title; ?></h4>

                                                <?= $variation_titles ? "<p class='text-black mb-0 fs-14p'> Variations: {$variation_titles}</p>" : null; ?>
                                                <?php if ($addon_titles) { ?>
                                                    <p class='text-black mb-1 fs-14p'> Addons: <?= trim($addon_titles, ',') ?></p>
                                                <?php } ?>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <p class="fs-14p fw-bold mb-0"><small>Amount</small><br /><?= Helpers::format_currency($v->amount); ?>.00</p>
                                                <p class="fs-14p fw-bold mb-0"><small>Total Amount</small><br /><?= Helpers::format_currency($v->total_amount); ?>.00</p>
                                                <p class="fs-14p fw-bold mb-0"><small>Qty</small><br /><?= $v->quantity; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>

            <div class="row">
                <!-- Cost Summary -->
                <section class="col-lg-6 mb-5">
                    <h5 class="text-white mb-3">Cost Summary</h5>

                    <div class="list-group">
                        <div class="list-group-item list-group-action rounded">
                            <div class="d-flex justify-content-between">
                                <p class="mb-0 fs-14p fw-bold">Variation Amount</p>
                                <p class="mb-0 fs-14p fw-bold"><?= Helpers::format_currency($order->variations_amount); ?>.00</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-0 fs-14p fw-bold">Addons Amount</p>
                                <p class="mb-0 fs-14p fw-bold"><?= Helpers::format_currency($order->addons_amount); ?>.00</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-0 fs-14p fw-bold">Menu Amount</p>
                                <p class="mb-0 fs-14p fw-bold"><?= Helpers::format_currency($order->menu_amount); ?>.00</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-0 fs-14p fw-bold">Delivery</p>
                                <p class="mb-0 fs-14p fw-bold"><?= Helpers::format_currency($order->delivery_price); ?>.00</p>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <p class="mb-0 fs-18p fw-bold">Total</p>
                                <p class="mb-0 fs-18p fw-bold"><?= Helpers::format_currency($order->total_amount); ?>.00</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Delivery Details -->
                <section class="col-lg-6 mb-5">
                    <h5 class="text-white mb-3">Delivery Address</h5>

                    <div class="list-group h-100">
                        <div class="list-group-item list-group-action rounded">
                            <?= $us->first_name . ' ' . $us->last_name; ?><br />
                            <?= $profile ? $profile->address : 'No Address'; ?><br />
                            <?= $profile ? $World->getCityName($profile->city) : 'No City'; ?><br />
                            <?= $profile ? $World->getStateName($profile->state) . ', ' . $World->getCountryName($profile->country) : 'No State and Country'; ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Status -->
            <section class="mb-5">
                <h5 class="text-white mb-3">Order Status</h5>

                <div class="list-group">
                    <div class="list-group-item list-group-action rounded">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="">
                                <p class="fw-bold mb-0"><?= ucwords($status) ?></p>
                                <?php if ($order->delivery_date) { ?>
                                    <?= date_format(date_create($order->delivery_date), 'M d, Y'); ?>
                                <?php } ?>
                            </div>

                            <?php if (ucwords($status) == 'Delivered') { ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 65 65">
                                    <path id="checkbox-checked" d="M56.875,0H8.125A8.149,8.149,0,0,0,0,8.125v48.75A8.149,8.149,0,0,0,8.125,65h48.75A8.149,8.149,0,0,0,65,56.875V8.125A8.149,8.149,0,0,0,56.875,0ZM28.438,50.432l-15.06-15.06,5.744-5.744,9.315,9.315L47.909,19.472l5.744,5.744L28.438,50.432Z" fill="#008e8c" />
                                </svg>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Action -->
            <section class="mb-5">
                <?php if (ucwords($status) == 'Pending') { ?>
                    <a href="javascript:;" class="btn d-block">Cancel Order</a>
                <?php } else { ?>
                    <a href="javascript:;" class="btn d-block">Reorder</a>
                <?php } ?>
            </section>
        </div>
    </div>
</section>