<?php
$categories = new General('categories');
$cart = new Cart();

$order = $data ? $data['data'] : null;
$total_amount = $data && isset($data['total_amount']) ? $data['total_amount'] : null;
$cover = $data && isset($data['cover']) ? $data['cover'] : null;
$count = $data && isset($data['count']) ? $data['count'] : null;
$status = $data && isset($data['status']) ? $data['status'] : null;
$type = $data && isset($data['type']) ? $data['type'] : 'list';

?>

<?php if ($order) { ?>
    <?php if ($type == 'list') { ?>
        <div class="list-group-item list-group-item-action mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <p class="col-lg-1 fs-14p fw-bold mb-0"><?= date_format(date_create($order->created_at), 'M d'); ?></p>

                <h4 class="col-lg-5 mb-0">
                    <a href="dashboard/order-details/<?= $order->invoice ?>" class="text-accent">
                        <?= $order->order_id ?></a>
                </h4>

                <p class="col-6 col-lg fs-18p fw-bold mb-0"><?= Helpers::format_currency($order->total_amount); ?></p>

                <p class="col-6 col-lg-2 fs-18p mb-0 fw-bold"><?= $count ?> Items</p>

                <p class="col-lg-2 fs-18p mb-0 fw-bold <?= $status['color'] ?>"><?= $status['title'] ?></p>

            </div>
        </div>
    <?php } ?>

    <?php if ($type == 'vendor-list') { ?>
        <div class="shadow border-0 mb-3">
            <a href="dashboard/order-details/view/<?= $order->invoice; ?>" class="text-reset">
                <div class="p-lg-0">
                    <div class="d-flex justify-content-between">
                        <img src="assets/images/menus/<?= $cover; ?>" style="width: 55px; height: 55px; border-radius: 5px; object-fit: cover;">
                        <div class="mx-3 flex-grow-1 d-flex align-self-center justify-content-between">
                            <div class="">
                                <p class="mb-0 font-weight-bold"><?= $order->order_id ?></p>
                                <p class="mb-0 fs-14p font-weight-bold"><?= Helpers::format_currency($total_amount) ?></p>
                            </div>
                            <div class="text-right">
                                <div class="">
                                    <span class="font-weight-bold"><?= $count ?></span>
                                    <span style="font-size: 12px">items: </span>
                                </div>
                                <p class="mb-0 fs-12p <?= $status['color']; ?>"><?= $status['title'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php } ?>
<?php } ?>