<?php
$user = new User();
$pagination = new Pagination();
$sellers = new General('sellers');
$orders = new General('orders');
$payout_requests = new General('payout_requests');
$products = new General('products');

$seller = $user->getVendor();
$payout_list = $payout_requests->getAll($seller->id, 'seller_id', '=');
$current_earning = 0;
if ($payout_list) {
    foreach ($payout_list as $k => $v) {
        if ($v->status) {
            $current_earning += $v->amount;
        }
    }
}


$searchTerm = "WHERE id > 0 AND status = 3 AND details LIKE '%seller_:_{$seller->id}_%' ";
$order_count = $pagination->countAll('orders', $searchTerm);
$paginate = new Pagination(1, $order_count, $order_count);
$delivered_order = $orders->getPages($order_count, $paginate->offset(), $searchTerm);

// Payout Order
$payout_orders = array_filter($delivered_order, function ($order) {
    $countdown = strtotime("+5 days", strtotime($order->delivery_date));
    $today = strtotime("today");
    return $today >= $countdown;
});

$payment_amount = 0;
if ($payout_orders) {
    foreach ($payout_orders as $k => $v) {
        $details = json_decode($v->details);
        $detail_list = array_filter($details, function ($order) use ($seller) {
            return $order->seller == $seller->id;
        });

        foreach ($detail_list as $dk => $dv) {
            $payment_amount += $dv->commision_amount;
        }
    }
}

$views = 'view/user/seller';

Alerts::displayError();
Alerts::displaySuccess();
?>

        <article class="card border-0 shadow-sm bg-site-primary--light mt-5">
            <div class="card-body">
                <div class="d-flex mb-2">
                    <p class="fw-bold">Payouts</p>
                </div>
                <hr>

                <article class="card-group card-stat">
                    <figure class="card bg-light">
                        <div class="card-body">
                            <span>All Payouts</span>
                            <h4 class="title"><?= $payout_list ? count($payout_list) : 0; ?></h4>
                        </div>
                    </figure>
                    <figure class="card  bg-light">
                        <div class="card-body">
                            <span>Total Paid</span>
                            <h4 class="title"><?= $current_earning ? $current_earning : Helpers::format_currency(0); ?></h4>
                        </div>
                    </figure>
                    <figure class="card bg-warning">
                        <div class="card-body d-md-flex justify-content-between align-items-start">
                            <div class="mb-4">
                                <p class="mb-0">Available Payout</p>
                                <h4 class="title"><?= Helpers::format_currency($payment_amount); ?></h4>
                            </div>
                            <?php if ($payment_amount) { ?>
                                <a href="controllers/orders.php?rq=request-payout" class="btn btn-sm bg-site-accent">Request Payout</a>
                            <?php } ?>
                        </div>
                    </figure>
                </article>

            </div> <!-- card-body .// -->
        </article> <!-- card.// -->


        <div class="card mt-5">
            <div class="card-body">
                <div class="d-flex mb-2">
                    <p class="fw-bold">Payout List</p>
                </div>
                <div class="">
                    <input type="text" placeholder="Start typing to search for orders" class="form-control form-control--search mx-auto" id="table-search" />
                </div>

                <div class="sa-divider"></div>
                <table class="sa-datatables-init text-nowrap" data-order="[[ 1, &quot;desc&quot; ]]" data-sa-search-input="#table-search">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($payout_list) { ?>
                            <?php foreach ($payout_list as $k => $v) { ?>
                                <tr>
                                    <td><?= Helpers::format_currency($v->amount) ?></td>
                                    <td><?= date_format(date_create($v->created), 'M d, Y') ?></td>
                                    <td><span class="badge bg-<?= $v->status == 1 ? 'success' : 'warning' ?>"><?= $v->status == 1 ? 'Paid Out' : 'Pending' ?></span></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
