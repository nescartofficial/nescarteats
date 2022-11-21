<?php
require_once('core/init.php');
$user = isset($user) ? $user : new User();
$categories = new General('categories');
$scategories = new General('scategories');
$menus = new General('menus');
$cart = new Cart();
$ccount = $cart->get_count();
$list = $cart->get_cart();

$scat_list = $scategories->getAll(1, 'status', '=');
$hot_item_cat = $categories->get('Hot Items', 'title', '=');
$hot_list = $hot_item_cat ? $scategories->get($hot_item_cat->id . 'category') : null;
$amount = $cart->get_total_amount();

$coupon = Session::exists('coupon') ? Session::get('coupon') : null;
$delivery_fee = Session::exists('delivery_price') ? Session::get('delivery_price') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<!-- Header . . . -->
<section class="container-fluid pt-5">
    <header class="container mb-6">
        <div class="d-flex align-items-center justify-contnt-between">
            <!-- Back -->
            <a href="#" id="back_button" class="d-flex p-2 border-0 rounded shadow bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22.5 38.381">
                    <path id="chevron-left" d="M30.949,7.767a3.526,3.526,0,0,1,0,5.216l-12.4,12.932,12.4,12.932a3.516,3.516,0,0,1,0,5.207,3.93,3.93,0,0,1-5.344,0c-1.384-1.344-14.9-15.535-14.9-15.535a3.619,3.619,0,0,1,0-5.214S24.221,9.119,25.6,7.769a3.928,3.928,0,0,1,5.344,0Z" transform="translate(-9.6 -6.72)" />
                </svg>
            </a>

            <h4 class="mb-0 mx-auto pr-40">Cart</h4>
        </div>
    </header>
</section>

<section class="cart-pg">
    <div class="card bg-primary rounded-bottom">
        <div class="card-body">
            <div class="container-lg">
                <?php if ($list) { ?>

                    <!-- Menu Items -->
                    <?php foreach ($list as $index => $con) {
                        $menu = $menus->get($con['id']); ?>
                        <div class="bg-white rounded p-2 d-flex justify-content-between align-items-center mb-3 menu-item">
                            <img src="assets/images/menus/<?= $menu->cover; ?>" class="img-sm me-3 rounded" style="width: 60px;height: 60px; object-fit: cover;">

                            <div class="flex-grow-1">
                                <a href="menu/<?= $menu->slug ?>" class="d-block fs-14p mb-0 fw-bold">
                                    <?= $con['name']; ?></a>
                                <span class="text-black fs-12p fw-bold"><?= Helpers::format_currency($con['amount']); ?></span>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="product-quantity-controller me-3">
                                    <div class="d-flex gap-2 bg-gray-200 rounded">
                                        <button class="btn bg-transparent py-0 px-2 dec-item" data-pid="<?= $con['id'] ?>" type="button" id="button-minus"> <i class="fa fa-minus text-dark"></i> </button>
                                        <p class="text-black fw-bold mb-0 item-quantity-<?= $con['id'] ?>"><?= $con['quantity']; ?></p>
                                        <button class="btn bg-transparent py-0 px-2 inc-item" data-pid="<?= $con['id'] ?>" type="button" id="button-plus"> <i class="fa fa-plus text-dark"></i> </button>
                                    </div>
                                </div> <!-- col.// -->

                                <div class="col col-lg-2 text-lg-right item-remove  my-3 my-lg-0">
                                    <button class="bg-transparent border-0 cart-remove-item" data-pid="<?= $con['id'] ?>"> <i class="fa fa-trash-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Coupon -->
                    <div class="bg-white rounded p-2 mb-4">
                        <form action="controllers/cart.php" method="post">
                            <div class="form-group">
                                <div class="d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 55 54.35">
                                        <path id="Discount" d="M27.314,54.35a2.385,2.385,0,0,1-1.879-1.132l-3.123-4.356a4.159,4.159,0,0,0-3.352-1.627,4.1,4.1,0,0,0-1.93.471L9.5,51.744a3.035,3.035,0,0,1-1.349.421.843.843,0,0,1-.725-.325,1.881,1.881,0,0,1,.031-1.667l3.281-9.222a2.7,2.7,0,0,0-.211-2.39,3.011,3.011,0,0,0-2.288-1.323l-6.4-.719C.85,36.409.216,36.058.058,35.532c-.153-.509.163-1.12.89-1.719l5.276-4.356a2.863,2.863,0,0,0,0-4.582L.947,20.518A1.96,1.96,0,0,1,.03,18.663c.14-.615.748-1.087,1.711-1.329l4.721-1.171A4.5,4.5,0,0,0,9.834,11.8L9.572,7.228a2.305,2.305,0,0,1,.537-1.772,1.818,1.818,0,0,1,1.359-.541,3.256,3.256,0,0,1,1.272.287l4.89,2.076a4.117,4.117,0,0,0,1.617.321A4.3,4.3,0,0,0,22.862,5.7L25.64,1.231A2.247,2.247,0,0,1,27.482,0a2.232,2.232,0,0,1,1.844,1.253L32.315,6.2A3.63,3.63,0,0,0,35.424,7.9,3.759,3.759,0,0,0,37.3,7.4l8.253-4.727a2.957,2.957,0,0,1,1.364-.466.708.708,0,0,1,.625.284,1.88,1.88,0,0,1-.1,1.634L43.73,13.706a2.555,2.555,0,0,0,.146,2.333,2.906,2.906,0,0,0,2.272,1.252l7,.615c1,.09,1.635.419,1.794.927.153.49-.162,1.089-.888,1.686l-5.276,4.356a2.863,2.863,0,0,0,0,4.582l5.276,4.355a1.954,1.954,0,0,1,.918,1.853c-.141.615-.751,1.087-1.717,1.329l-4.714,1.178a4.509,4.509,0,0,0-3.381,4.356l.266,4.577a2.268,2.268,0,0,1-.522,1.74,1.719,1.719,0,0,1-1.283.512,3.177,3.177,0,0,1-1.316-.32l-5.3-2.455a3.9,3.9,0,0,0-1.653-.361,3.841,3.841,0,0,0-3.382,1.9l-2.746,4.906A2.27,2.27,0,0,1,27.314,54.35Zm9.919-25.9a4.63,4.63,0,0,0-1.779.338,4.154,4.154,0,0,0-1.409.963,4.417,4.417,0,0,0-.93,1.528,5.821,5.821,0,0,0-.333,2.018,5.523,5.523,0,0,0,.333,1.965,4.389,4.389,0,0,0,.93,1.494,4.16,4.16,0,0,0,1.409.956,4.63,4.63,0,0,0,1.779.338,4.422,4.422,0,0,0,3.128-1.294,4.635,4.635,0,0,0,.981-1.494,5.043,5.043,0,0,0,.368-1.965,5.784,5.784,0,0,0-.333-2.018,4.357,4.357,0,0,0-.937-1.528,4.192,4.192,0,0,0-1.423-.963A4.669,4.669,0,0,0,37.232,28.449ZM38.3,18.611a1.209,1.209,0,0,0-.669.159,1.738,1.738,0,0,0-.4.358L22.874,37.809h2.461a1.207,1.207,0,0,0,.595-.138,1.3,1.3,0,0,0,.407-.352L40.693,18.611ZM12.8,28.423v2.735H19.72V28.423ZM26.324,18.411a4.613,4.613,0,0,0-1.777.338,4.147,4.147,0,0,0-1.411.963,4.387,4.387,0,0,0-.928,1.528,5.782,5.782,0,0,0-.335,2.018,5.487,5.487,0,0,0,.335,1.965,4.358,4.358,0,0,0,.928,1.494,4.062,4.062,0,0,0,1.411.949A4.675,4.675,0,0,0,26.324,28a4.467,4.467,0,0,0,1.7-.333,4.264,4.264,0,0,0,1.43-.949,4.646,4.646,0,0,0,.983-1.494,5.072,5.072,0,0,0,.368-1.965,5.782,5.782,0,0,0-.335-2.018,4.348,4.348,0,0,0-.935-1.528,4.217,4.217,0,0,0-1.425-.963A4.656,4.656,0,0,0,26.324,18.411ZM37.232,35.924a2.089,2.089,0,0,1-.749-.133,1.41,1.41,0,0,1-.595-.437,2.325,2.325,0,0,1-.394-.811,4.543,4.543,0,0,1-.148-1.248,4.8,4.8,0,0,1,.148-1.287,2.436,2.436,0,0,1,.394-.836,1.422,1.422,0,0,1,.595-.459,2.02,2.02,0,0,1,.749-.138,2.06,2.06,0,0,1,.755.138,1.5,1.5,0,0,1,.615.459,2.386,2.386,0,0,1,.414.836,4.582,4.582,0,0,1,.155,1.287,4.334,4.334,0,0,1-.155,1.248,2.281,2.281,0,0,1-.414.811,1.492,1.492,0,0,1-.615.437A2.13,2.13,0,0,1,37.232,35.924ZM26.324,25.873a2.071,2.071,0,0,1-.748-.133,1.431,1.431,0,0,1-.6-.437,2.165,2.165,0,0,1-.4-.8,4.69,4.69,0,0,1-.141-1.241,5.051,5.051,0,0,1,.141-1.287,2.261,2.261,0,0,1,.4-.836,1.461,1.461,0,0,1,.6-.459,2,2,0,0,1,.748-.138,2.079,2.079,0,0,1,.756.138,1.508,1.508,0,0,1,.614.459,2.294,2.294,0,0,1,.409.836,4.817,4.817,0,0,1,.146,1.287A4.473,4.473,0,0,1,28.1,24.5a2.17,2.17,0,0,1-.409.8,1.479,1.479,0,0,1-.614.437A2.15,2.15,0,0,1,26.324,25.873Z" transform="translate(0)" />
                                    </svg>
                                    <input type="text" class="form-control form-control-sm" name="code" placeholder="Enter coupon code">
                                    <button class="rounded border-0 px-3 p-2 bg-primary">Apply</button>
                                    <input type="hidden" name="rq" value="coupon">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Details -->
                    <section class="col">
                        <div class="card bg-primary--dark rounded-bottom">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0 fs-16p fw-bold">Subtotal:</p>
                                    <p class="mb-0 fs-16p fw-bold"><?= $amount ? ($amount) : Helpers::format_currency(0); ?></p>
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
                                <hr class="my-2 text-white">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0 fs-18p fw-bold">Total:</p>
                                    <p class="mb-0 fs-18p fw-bold"><?= $amount ? ($amount) : Helpers::format_currency(0); ?></p>
                                </div>



                                <footer class="mt-5">
                                    <a href="dashboard/checkout" class="btn bg-white border-0 text-black fw-bold w-100"> Proceed to Checkout</a>
                                </footer>
                            </div> <!-- card-body.// -->
                        </div> <!-- card .// -->
                    </section> <!-- col.// -->

                <?php } else { ?>
                    <div class="h-100 d-flex align-items-center justify-content-center py-5">
                        <div class="text-center">
                            <i class="fa fa-shopping-cart fa-3x"></i>
                            <h3 class="mt-4 fw-bold">Your cart is empty!</h3>
                            <p class="mb-4">Browse our categories and discover our best deals!</p>

                            <a href="categories" class="btn bg-site-accent">Start Shopping</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>