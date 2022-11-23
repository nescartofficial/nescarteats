<?php
$user = new User();
$pagination = new Pagination();
$sellers = new General('sellers');
$orders = new General('orders');
$payouts = new General('payouts');
$wallets = new General('wallets');

$pending_payouts = $wallets->getAll(0, 'payout_balance', '>');
$payouts_amount = $wallets->getAllSum('payout_balance', 0, 'payout_balance', '>');
$total_payout = $wallets->getAllSum('total_payout', 0, 'total_payout', '>');

Alerts::displayError();
Alerts::displaySuccess();
?>

<div id="top" class="sa-app__body">
    <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
        <div class="container">
            <div class="py-5">
                
                <article class="card border-0 shadow-sm bg-site-primary--light mt-5">
                    <div class="card-body">
                        <div class="d-flex mb-2">
                            <p class="fw-bold">Payouts</p>
                        </div>
                        <hr>
                        
                        <article class="card-group card-stat">
                            <figure class="card  bg-light">
                                <div class="card-body">
                                    <span>Total Payout</span>
                                    <h4 class="title"><?=  Helpers::format_currency($total_payout); ?></h4>
                                </div>
                            </figure>
                            <figure class="card bg-light">
                                <div class="card-body">
                                    <span>Pending Payouts</span>
                                    <h4 class="title"><?= $pending_payouts ? count($pending_payouts) : 0; ?></h4>
                                </div>
                            </figure>
                            <figure class="card  bg-light">
                                <div class="card-body">
                                    <span>Total Pending</span>
                                    <h4 class="title"><?=  Helpers::format_currency($payouts_amount); ?></h4>
                                    
                                    <a onclick="return confirm('Are you sure you want to make all payout?')" class="" href="controllers/payouts.php?rq=bulk-payout">Pay All</a>
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
                                    <th>Name</th>
                                    <th>Seller</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($pending_payouts) { ?>
                                    <?php foreach ($pending_payouts as $k => $v) { 
                                        $us = $user->get($v->user_id); 
                                        $seller = $sellers->get($v->user_id, 'user_id'); 
                                        ?>
                                        <tr>
                                            <td><?= $us->first_name. ' '.$us->last_name; ?></td>
                                            <td><?= $seller->name; ?></td>
                                            <td><?= Helpers::format_currency($v->payout_balance) ?></td>
                                            <td>
                                                <a class="btn btn-primary" href="sellers/view/<?= $seller->id ?>#PayoutAmount">View</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>