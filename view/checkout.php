<?php
require_once('core/init.php');
$user = isset($user) ? $user : new User();
$world = new World();
$menus = new General('menus');
$profiles = new General('profiles');
$addresses = new General('addresses');
$pickups = new General('pickup_points');
$delivery_prices = new General('delivery_prices');
$cart = new Cart();
$ccount = $cart->get_count();
$list = $cart->get_cart();
!$list ? Redirect::to_js('home') : null;

$checkout = Session::exists('checkout') ? Session::get('checkout') : null;
$profile = $user->isLoggedIn() ? $profiles->get($user->data()->id, 'user_id') : null;
$delivery_price =  $checkout && isset($checkout['delivery_price']) ? json_decode($checkout['delivery_price']) : null;
$address =  $checkout && $checkout['billing'] ? json_decode($checkout['billing']) : null;
$delivery =  $checkout && isset($checkout['delivery']) ? $checkout['delivery'] : null;
$pickup_locate =  $delivery && $checkout['pickup_location'] ? $checkout['pickup_location'] : null;

$pickup_list = $pickups->getAll(1, 'status', '=');

$amount = $cart->get_total_amount();

$countries = $world->getCountries();
!$user->isLoggedIn() ? Session::put('tocheckto', '../checkout') : (Session::exists('tocheckto') ? Session::delete('tocheckto') : null);

$coupon = Session::exists('coupon') ? Session::get('coupon') : null;
$delivery_fee = Session::exists('delivery_price') ? Session::get('delivery_price') : null;

$view = 'view';

Alerts::displayError();
Alerts::displaySuccess();
?>

<?php if ($user->isLoggedIn()) { ?>
    <?php if (!Session::exists('checkout') || empty($address)) {
        Template::render('checkout-delivery', $view);
    } ?>
    
    <?php if (Session::exists('checkout') && !empty($address) && $delivery) {
        Template::render('checkout-payment', $view);
    } ?>
<?php } else {
    Template::render('sign-in', 'view');
} ?>