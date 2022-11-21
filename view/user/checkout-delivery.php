<?php
require_once('core/init.php');
$user = isset($user) ? $user : new User();
$world = new World();
$profiles = new General('profiles');
$addresses = new General('addresses');
$pickups = new General('pickup_points');
$delivery_prices = new General('delivery_prices');
$cart = new Cart();
$ccount = $cart->get_count();
$list = $cart->get_cart();
!$list ? Redirect::to_js('home') : null;

$address_list = $addresses->getAll($user->data()->id, 'user_id', '=');

$checkout = Session::exists('checkout') ? Session::get('checkout') : null;
$profile = $user->isLoggedIn() ? $profiles->get($user->data()->id, 'user_id') : null;
$delivery_price =  $checkout && isset($checkout['delivery_price']) ? json_decode($checkout['delivery_price']) : null;
$address =  $checkout && $checkout['billing'] ? json_decode($checkout['billing']) : null;
$delivery =  $checkout && isset($checkout['delivery']) ? $checkout['delivery'] : null;
$pickup_locate =  $delivery && $checkout['pickup_location'] ? $checkout['pickup_location'] : null;

$pickup_list = $pickups->getAll(1, 'status', '=');

$countries = $world->getCountries();
!$user->isLoggedIn() ? Session::put('tocheckto', '../checkout') : (Session::exists('tocheckto') ? Session::delete('tocheckto') : null);

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

            <h4 class="mb-0 mx-auto pr-40">Checkout Delivery</h4>
        </div>
    </header>

    <section class="container">
        <div class="row">
            <div class="col-lg-12">
                <p>Choose your delivery address</p>
            </div>
        </div>

        <div class="mt-4">
            <form id="billing_form" action="controllers/checkout.php" method="post">
                <div class="" id="delivery_addr">
                    <div id="delivery_option" class="mb-5">
                        <div class="" id="delivery_addr">
                            <?php if ($address_list) { ?>
                                <?php foreach ($address_list as $k => $v) { ?>
                                    <div class="mb-3">
                                        <div class="btn-group-toggle" data-toggle="buttons">
                                            <div class="card">
                                                <label class="btn bg-white px-3 py-2 rounded">
                                                    <div class="d-flex justify-content-between">
                                                        <address class="d-flex">
                                                            <div class="col-12 text-start flex-fill">
                                                                <h6><?= $v->title; ?></h6>
                                                                <p class="text-black text-truncate mb-0">
                                                                    <?= $v->address ?></p>
                                                            </div>
                                                        </address>
                                                        <input name="address_id" value="<?= $v->id; ?>" type="radio" class="checkout_delivery" checked>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="rq" value="address">
                        <input type="hidden" name="delivery_method" value="delivery">
                        <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                        <button type="submit" class="btn bg-primary w-100">Choose Delivery</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</section>