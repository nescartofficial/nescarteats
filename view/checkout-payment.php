<?php
require_once('core/init.php');
$user = isset($user) ? $user : new User();
$world = new World();
$profiles = new General('profiles');
$addresses = new General('addresses');
$pickups = new General('pickup_points');
$delivery_prices = new General('delivery_prices');
$Cart = new Cart();
$ccount = $Cart->get_count();
$list = $Cart->get_cart();
!$list ? Redirect::to_js('home') : null;

$wallet = $user->getWallet();

$checkout = Session::exists('checkout') ? Session::get('checkout') : null;
$profile = $user->isLoggedIn() ? $profiles->get($wallet->user_id, 'user_id') : null;
$delivery_price =  $checkout && isset($checkout['delivery_price']) ? json_decode($checkout['delivery_price']) : null;
$address =  $checkout && $checkout['billing'] ? json_decode($checkout['billing']) : null;
$delivery =  $checkout && isset($checkout['delivery']) ? $checkout['delivery'] : null;
$pickup_locate =  $delivery && $checkout['pickup_location'] ? $checkout['pickup_location'] : null;

$pickup_list = $pickups->getAll(1, 'status', '=');

$countries = $world->getCountries();
!$user->isLoggedIn() ? Session::put('tocheckto', '../checkout') : (Session::exists('tocheckto') ? Session::delete('tocheckto') : null);


// Prices
$cart_amount = $Cart->get_cart_amount();
$menu_amount = $cart_amount['price'];
$addons_price = $cart_amount['addon'];
$variation_amount = $cart_amount['variation'];
$total_amount = $cart_amount['total'];

$amount = $Cart->get_total_amount();
$coupon = Session::exists('coupon') ? Session::get('coupon') : null;
$delivery_fee = Session::exists('delivery_price') ? Session::get('delivery_price') : null;

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

            <h4 class="mb-0 mx-auto pr-40">Payment</h4>
        </div>
    </header>

    <!-- Address -->
    <section class="container mb-5">
        <div class="d-flex justify-content-between mb-3">
            <p class="fw-bold mb-1">Delivery Address</p>
            <a href="controllers/checkout.php?rq=change&action=address" class="text-accent">Change</a>
        </div>

        <?php if ($address) { ?>
            <div class="col-12">
                <address class="d-flex bg-white rounded shadow p-3">
                    <img src="assets/icons/Delivery Address.svg" alt="Delivery Address Icon" class="h-30p img-fluid">

                    <div class="ms-3 col-12 flex-fill">
                        <h6 class="mb-1"><?= $address->title; ?></h6>
                        <p class="text-truncate mb-0">
                            <?= $address->address ?>, <?= $world->getCityName($address->city) ?>, <?= $world->getStateName($address->state) ?></p>
                    </div>
                </address>
            </div>
        <?php } ?>
    </section>

    <!-- Payment Methods -->
    <section class="container">
        <p class="fw-bold mb-3">Payment Methods</p>

        <form id="payment_form" class="" action="controllers/checkout.php" method="post">
            <div class="" id="delivery_addr">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="btn-group-toggle" data-toggle="buttons">
                            <div class="card">
                                <label class="btn bg-white px-3 py-2 rounded">
                                    <div class="d-flex align-items-baseline justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img src="assets/icons/wallet.svg" class="h-30p me-3">
                                            <div class="text-start text-black flex-fill">
                                                <h5 class="mb-0">Pay with Wallet</h5>
                                                <p class="fs-10p mb-0 d-block">Pay with funds in your Nescart Wallet</p>
                                                <p class="fs-10p mb-0">Your wallet balance is <?= $wallet ? Helpers::format_currency($wallet->balance) : null; ?></p>
                                            </div>
                                        </div>
                                        <input name="payment_method" value="wallet" type="radio" class="checkout_delivery">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="btn-group-toggle" data-toggle="buttons">
                            <div class="card">
                                <label class="btn bg-white px-3 py-2 rounded">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img src="assets/icons/Flutterwave.svg" class="h-30p me-3">
                                            <div class="text-start text-black flex-fill">
                                                <h5 class="mb-0">Pay with Flutterwave</h5>
                                                <p class="fs-10p mb-0">Payment is supported by all banks</p>
                                            </div>
                                        </div>
                                        <input name="payment_method" value="card-flutterwave" type="radio" class="checkout_delivery" checked>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="btn-group-toggle" data-toggle="buttons">
                            <div class="card">
                                <label class="btn bg-white px-3 py-2 rounded">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img src="assets/icons/Paystack.svg" class="h-30p me-3">
                                            <div class="text-start text-black flex-fill">
                                                <h5 class="mb-0">Pay with Paystack</h5>
                                                <p class="fs-10p mb-0">Payment is supported by all banks</p>
                                            </div>
                                        </div>
                                        <input name="payment_method" value="card" type="radio" class="checkout_delivery">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6 mb-4">
                        <div class="btn-group-toggle" data-toggle="buttons">
                            <div class="card h-100">
                                <label class="btn bg-white px-3 py-2 rounded">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img src="assets/icons/stack.svg" class="h-30p me-3">
                                            <div class="text-start text-black flex-fill">
                                                <h5 class="mb-0">Pay on delivery</h5>
                                                <p class="fs-10p mb-0">Pay when your order is delivered</p>
                                            </div>
                                        </div>
                                        <input name="payment_method" value="ondelivery" type="radio" class="checkout_delivery">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="mb-3">
                <div class="form-group mt-3">
                    <input type="hidden" name="rq" value="payment">
                    <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                </div>
            </div>
        </form>
    </section>
</section>

<section class="cart-pg">
    <div class="card bg-primary rounded-bottom">
        <div class="card-body">
            <div class="container-lg">
                <div class="d-flex justify-content-between">
                    <p class="mb-0 fs-16p fw-bold">Items:</p>
                    <p class="mb-0 fs-16p fw-bold"><?= Helpers::format_currency($menu_amount); ?></p>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 fs-16p fw-bold">Variation:</p>
                    <p class="mb-0 fs-16p fw-bold">(+)<?= Helpers::format_currency($variation_amount); ?></p>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="mb-0 fs-16p fw-bold">Addons:</p>
                    <p class="mb-0 fs-16p fw-bold">(+)<?= Helpers::format_currency($addons_price); ?></p>
                </div>
                <?php if ($coupon) { ?>
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fs-16p fw-bold">Discount:</p>
                        <p class="fw-bold text-right mb-0"><?= $coupon ? $coupon->percentage . '%' : '0%'; ?></p>
                    </div>
                <?php } ?>
                <?php if ($delivery_fee) { ?>
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fs-16p fw-bold">Delivery Fee:</p>
                        <p class="mb-0 fs-16p fw-bold"><?= Helpers::format_currency($delivery_fee) ?></p>
                    </div>
                <?php } ?>
                <hr class="mt-3 mb-2 text-white">
                <div class="d-flex justify-content-between">
                    <p class="mb-0 fs-18p fw-bold">Total:</p>
                    <p class="mb-0 fs-18p fw-bold"><?= Helpers::format_currency($total_amount); ?></p>
                </div>

                <footer class="mt-5">
                    <button type="submit" class="btn bg-white text-black fw-bold w-100" id="checkout_order" data-position="payment">Proceed to payment</button>
                </footer>
            </div>
        </div>
    </div>
</section>