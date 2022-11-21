<?php
require_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to_js('home') : null;
$world = new World();
$orders = new General('orders');
$cart = new Cart();
$ccount = $cart->get_count();
$list = $cart->get_cart();

// print_r(Session::exists('checkout') ? Session::get('checkout') : null);

Session::exists('checkout') ? Session::put('thanks', Session::get('checkout')) : null;
Session::exists('checkout') ? Session::delete('checkout') : null;

$checkout = Session::exists('thanks') ? Session::get('thanks') : null;
$billing = json_decode($checkout['billing']);
$coupon = $checkout['coupon'] ? json_decode($checkout['coupon']) : null;
$delivery = $checkout['delivery_price'];
$payment = json_decode($checkout['payment']);
$invoice = ($checkout['invoice']);

$order = $orders->get($invoice, 'invoice');
// $list = json_decode($order->details);

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
            <h4 class="mb-0 mx-auto pr-40">Success</h4>
        </div>
    </header>


    <section class="container">
        <div class="row">

            <div class="col-md-12 text-center">
                <h2 class="mb-5">Wahoo! Your order was<br /> placed successfully</h2>

                <svg xmlns="http://www.w3.org/2000/svg" width="188" height="188" viewBox="0 0 42 42">
                    <g id="Success" transform="translate(364.341 -1257)">
                        <circle id="Ellipse_58" data-name="Ellipse 58" cx="21" cy="21" r="21" transform="translate(-364.341 1257)" fill="#008e8c" opacity="0.3" />
                        <circle id="Ellipse_59" data-name="Ellipse 59" cx="16.5" cy="16.5" r="16.5" transform="translate(-359.5 1262)" fill="#008e8c" />
                        <g id="Layer_2" data-name="Layer 2" transform="translate(-351.941 1269.059)">
                            <g id="invisible_box" data-name="invisible box" transform="translate(0 0)">
                                <rect id="Rectangle_35" data-name="Rectangle 35" width="18" height="18" transform="translate(-0.4 -0.059)" fill="none" />
                            </g>
                            <g id="icons_Q2" data-name="icons Q2" transform="translate(0.498 4.498)">
                                <g id="Group_15" data-name="Group 15">
                                    <path id="Path_10" data-name="Path 10" d="M15.154,22.44l-2.933-2.9a.77.77,0,0,1-.073-.99.7.7,0,0,1,1.1-.073l2.42,2.42,7.553-7.553a.733.733,0,0,1,1.027,1.027L16.18,22.44a.7.7,0,0,1-1.027,0Z" transform="translate(-8.328 -13.2)" fill="#fff" />
                                    <path id="Path_11" data-name="Path 11" d="M5.657,31.39a.733.733,0,0,1-.513-.22L2.21,28.237a.7.7,0,0,1,0-1.027.7.7,0,0,1,1.027,0L6.17,30.143a.7.7,0,0,1,0,1.027A.733.733,0,0,1,5.657,31.39Z" transform="translate(-1.985 -21.93)" fill="#fff" />
                                    <path id="Path_12" data-name="Path 12" d="M20.723,18.993a.733.733,0,0,1-.513-.22.7.7,0,0,1,0-1.027l4.4-4.4a.733.733,0,0,1,1.027,1.027l-4.4,4.4A.733.733,0,0,1,20.723,18.993Z" transform="translate(-13.385 -13.2)" fill="#fff" />
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>


                <p class="my-4">Your order is being picked<br /> up by the courier</p>

                <button class="btn bg-accent w-100 mb-4"> Track Order</button>

                <a href="dashboard/order-details/<?= $invoice ?>" class="text-accent">See Order Details</a>
            </div>

        </div>
    </section>
</section>