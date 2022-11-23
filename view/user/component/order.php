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
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <p class="col-12 col-lg fs-14p fw-bold mb-1 mb-lg-0"><i>Date: </i><?= date_format(date_create($order->created_at), 'M d'); ?></p>

                <p class="col-8 col-lg-4 mb-2 mb-lg-0 fs-14p">
                    <i>Order ID: </i> <a href="dashboard/order-details/<?= $order->invoice ?>" class="text-accent">
                        <?= $order->order_id ?>
                    </a>
                </p>
                <p class="col-2 col-lg-2 fs-14p mb-1 mb-lg-0 fw-bold"><?= $count ?> Items</p>

                <p class="col-8 col-lg fs-14p fw-bold mb-1 mb-lg-0"><i>Amount: </i><?= Helpers::format_currency($order->total_amount); ?></p>


                <p class="col-3 col-lg fs-12p mb-0 fw-bold text-end <?= $status['color'] ?>"><?= $status['title'] ?></p>
            </div>
        </div>
        <hr>
    <?php } ?>

    <?php if ($type == 'vendor-list') { ?>
        <div class="shadow border-0 mb-3">
            <a href="dashboard/order-details/view/<?= $order->invoice; ?>" class="text-reset">
                <div class="py-2">
                    <div class="d-flex justify-content-between">
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