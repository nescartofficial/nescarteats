<?php
$user = new User();
$world = new World();
$vendors = new Vendors();
$category_specials = new General('category_specials');
$categories = new General('categories');
$reviews = new General('reviews');
$settings = new General('settings');
$saved_menus = new General('saved_menus');
$menus = new Menus();
$cart = new Cart();

$menu = Input::get('sub') ? $menus->get(Input::get('sub'), 'slug') : null;
!$menu ? Redirect::to_js('dashboard') : null;
$options = isset($menu->options) ? json_decode($menu->options) : null;
$vendor = $menu ? $vendors->get($menu->vendor_id) : null;
$images = $menu ? explode(',', $menu->image) : null;
$product_category = $menu ? $categories->get($menu->category) : null;
$product_parent_category = $product_category && $product_category->parent_id ? $categories->get($product_category->parent_id) : null;
$is_saved_menu = $saved_menus->getByUser($menu->id, 'menu_id', $user->data()->id);

$other_products = $menus->getPages(12, 0, "WHERE status = 1 AND seller_id = {$vendor->id}");

$related = $menu->id ? $menus->getPages(12, 0, "WHERE id <> {$menu->id} AND category = {$menu->category} AND status = 1") : null;
$review_items = $reviews->getAll($menu->id, 'product_id', '=');

$featured_categories = $category_specials->get('featured-categories', 'slug');
$featured_categories = $featured_categories && $featured_categories->categories ? explode(',', $featured_categories->categories) : null;

// Menu Options
$serving_with = $menu->serving_with && json_decode($menu->serving_with)[0]->title  ? json_decode($menu->serving_with) : null;
$serving_option = $menu->serving_option && json_decode($menu->serving_option)[0]->title  ? json_decode($menu->serving_option) : null;
$ingredients = $menu->ingredients && json_decode($menu->ingredients)[0]->value != "[{" ? json_decode($menu->ingredients) : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<section class="menu header position-relative bg-primar" style="background:url('');">
    <?php if ($images) { ?>
        <div class="container-fluid images d-none d-lg-block">
            <div class="container">
                <div class="row g-3">
                    <div class="col-lg-7">
                        <a data-fancybox="gallery" data-src="assets/images/menus/<?= $images[0] ?>" data-caption="<?= $menu->title; ?>">
                            <img src="assets/images/menus/<?= $images[0] ?>" class="cover">
                        </a>
                    </div>
                    <div class="col-lg-5">
                        <div class="row g-3">
                            <?php if (isset($images[1])) { ?>
                                <div class="col-lg-6">
                                    <a data-fancybox="gallery" data-src="assets/images/menus/<?= $images[1] ?>" data-caption="<?= $menu->title; ?>">
                                        <img src="assets/images/menus/<?= $images[1] ?>" class="thumb">
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if (isset($images[2])) { ?>
                                <div class="col-lg-6">
                                    <a data-fancybox="gallery" data-src="assets/images/menus/<?= $images[2] ?>" data-caption="<?= $menu->title; ?>">
                                        <img src="assets/images/menus/<?= $images[2] ?>" class="thumb">
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if (isset($images[3])) { ?>
                                <div class="col-lg-6">
                                    <a data-fancybox="gallery" data-src="assets/images/menus/<?= $images[3] ?>" data-caption="<?= $menu->title; ?>">
                                        <img data-lazy="assets/images/menus/<?= $images[0] ?>" src="assets/images/menus/<?= $images[3] ?>" class="thumb">
                                    </a>
                                </div>
                            <?php } ?>

                            <?php
                            if (count($images) > 4) {
                                foreach ($images as $k => $v) {
                                    if ($k < 4) continue; ?>
                                    <a data-fancybox="gallery" data-src="assets/images/menus/<?= $v ?>" data-caption="<?= $menu->title; ?>">
                                        <img src="assets/images/menus/<?= $v ?>" class="thumb">
                                    </a>
                            <?php
                                }
                            } ?>

                            <div class="col-lg-6">
                                <a data-fancybox="gallery" data-src="assets/images/menus/<?= $images[count($images) - 1] ?>" data-thumb="assets/images/menus/<?= $images[count($images) - 1] ?>" data-caption="<?= $menu->title; ?>">
                                    <div class="show-all-img d-flex align-items-center justify-content-center">
                                        <h3>Show<br /> All</h3>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="menu-slider--image d-lg-none">
            <?php foreach ($images as $k => $v) { ?>
                <img data-lazy="assets/images/menus/<?= $v ?>" src="assets/images/menus/<?= $v ?>" class="item-thumb" style="height: 150px;" <?= $menu->cover == $v ? null : 'data-fancybox="gallery"'; ?>>
            <?php } ?>
        </div> <!-- slider-nav.// -->
    <?php } ?>
    <div class="container-fluid position-absolute top-0 start-0 pt-5">
        <header class="container">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Back -->
                <a href="#" id="back_button" class="d-flex p-2 border-0 rounded shadow bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22.5 38.381">
                        <path id="chevron-left" d="M30.949,7.767a3.526,3.526,0,0,1,0,5.216l-12.4,12.932,12.4,12.932a3.516,3.516,0,0,1,0,5.207,3.93,3.93,0,0,1-5.344,0c-1.384-1.344-14.9-15.535-14.9-15.535a3.619,3.619,0,0,1,0-5.214S24.221,9.119,25.6,7.769a3.928,3.928,0,0,1,5.344,0Z" transform="translate(-9.6 -6.72)" />
                    </svg>

                </a>
                <!-- Save -->
                <a href="#" class="d-flex p-2 border-0 rounded shadow bg-white add-favourite" data-type="menu" data-id="<?= $menu->id ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 67.494 57.106">
                        <path id="Favourite_Outline" data-name="Favourite Outline" d="M58.692,8.92a16.3,16.3,0,0,0-21.647,0l-4.058,3.725L28.925,8.92a16.293,16.293,0,0,0-21.642,0,14.879,14.879,0,0,0,0,22.331l25.7,23.591,25.7-23.591a14.886,14.886,0,0,0,0-22.331Z" transform="translate(0.761 -1.807)" fill="<?= $is_saved_menu ? '#ef9244' : 'none'; ?>" stroke="#ef9244" stroke-width="6" />
                    </svg>
                </a>
            </div>
        </header>
    </div>
</section>

<section class="menu content container-fluid py-5">
    <div class="container position-relative">
        <div class="row">
            <header class="col-12 d-flex gap-2 justify-content-between">
                <div class="col-8">
                    <h3><?= $menu->title; ?></h3>
                    <p class="fs-12p text-muted text-truncate mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 39.926 63.842">
                            <path id="location-pin" d="M27.963,3.214A19.928,19.928,0,0,0,8,23.137C8,42.2,27.963,67.056,27.963,67.056S47.926,42.195,47.926,23.137A19.932,19.932,0,0,0,27.963,3.214Zm0,30.948a10.78,10.78,0,1,1,10.775-10.78,10.778,10.778,0,0,1-10.775,10.78Z" transform="translate(-8 -3.214)" fill="#8a8a8a" />
                        </svg>
                        <span class="ms-1"><?= $vendor->name; ?>, <?= $world->getStateName($vendor->state); ?></span>
                    </p>
                </div>

                <div class="text-end">
                    <h3><?= Helpers::format_currency($menu->price) ?></h3>

                    <div class="product-quantity-controller <?= !$cart->exists($menu->id) ? "d-none" : null ?> form-group col-md flex-grow-0">
                        <div class="input-group mb-3 input-spinner">
                            <button class="input-group-text dec-item" data-pid="<?= $menu->id ?>" type="button" id="button-minus"> &minus; </button>
                            <input type="text" class="form-control item-quantity-<?= $menu->id ?>" id="item-quantity-<?= $menu->id ?>" value="<?= $cart->get_cart($menu->id) ? $cart->get_cart($menu->id)['quantity'] : 1; ?>">
                            <button class="input-group-text inc-item" data-pid="<?= $menu->id ?>" type="button" id="button-plus"> &plus; </button>
                        </div>
                    </div>
                </div>
            </header>

            <section class="col-12 w-100 d-flex align-items-center justify-content-between mb-3">
                <p class="fs-14p mb-0 fw-bold">
                    <i class="fa fa-star"></i> 4.5(233)
                </p>
                <p class="fs-14p mb-0 fw-bold">
                    <i class="fa fa-bus"></i> <?= Helpers::format_currency(500) ?>
                </p>
                <p class="fs-12p mb-0 fw-bold">
                    <i class="fa fa-clock"></i> 5-10mins
                </p>
            </section>

            <hr class="col-12 text-primary mb-4">

            <?php if ($ingredients) { ?>
                <!-- Ingredients -->
                <section class="col-12 mb-5">
                    <h4>Ingredients</h4>
                    <?php foreach ($ingredients as $k => $v) { ?>
                        <span class="badge text-bg-secondary me-3"><?= $v->value; ?></span>
                    <?php } ?>
                </section>
            <?php } ?>

            <!-- Description -->
            <section class="col-12 mb-5">
                <h4>Description</h4>

                <div>
                    <?= $menu->description; ?>
                </div>
            </section>

            <?php if ($serving_with) { ?>
                <!-- Serve With -->
                <section class="col-12 mb-5">
                    <h4 class="mb-3 d-none">Serve With Options</h4>
                    <?php foreach ($serving_with as $k => $v) {
                        $list = $v->list; ?>
                        <div class="row align-items-center mb-4">
                            <div class="col-lg-4">
                                <h5><?= $v->title ?></h5>
                            </div>

                            <?php foreach ($list as $lk => $lv) { ?>
                                <div class="col-lg-2 mb-2">
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <div class="card h-100">
                                            <label class="btn bg-white px-3 py-2 rounded">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-start text-black flex-fill">
                                                            <p class="fs-18p fw-bold mb-0"><?= $lv->option ?></p>
                                                        </div>
                                                    </div>
                                                    <input class="form-check-input" name="serving_with" type="radio" id="serving_with_<?= $k . '-' . $lk ?>" value="<?= $lv->option ?>">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </section>
            <?php } ?>

            <?php if ($serving_option) { ?>
                <!-- Serve Options -->
                <section class="col-12 mb-5">
                    <h4 class="mb-3 d-none">Serve Options</h4>
                    <?php foreach ($serving_option as $k => $v) {
                        $list = $v->list; ?>
                        <div class="row align-items-center mb-4">
                            <div class="col-lg-4">
                                <h5><?= $v->title ?></h5>
                            </div>
                            <?php foreach ($list as $lk => $lv) { ?>
                                <?php foreach ($lv->option as $ok => $ov) {
                                    $menu = $menus->get($ov); ?>
                                    <div class="col-lg-2 mb-2">
                                        <div class="btn-group-toggle" data-toggle="buttons">
                                            <div class="card h-100">
                                                <label class="btn bg-white px-3 py-2 rounded">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="text-start text-black flex-fill">
                                                                <p class="fs-18p fw-bold mb-0"><?= $menu->title ?></p>
                                                                <p class="fs-14p fw-bold mb-0"><?= Helpers::format_currency($menu->price) ?></p>
                                                            </div>
                                                        </div>
                                                        <input class="form-check-input" name="serving_option" type="checkbox" id="serving_option_<?= $menu->id . '-' . $ok ?>" value="<?= $menu->id ?>">
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </section>
            <?php } ?>

        </div>

        <!-- Add to cart -->
        <div class="menu-cart fixed-bottom text-center">
            <?php if (!$cart->exists($menu->id)) { ?>
                <button class="btn fw-bold add-cart" data-pid="<?= $menu->id ?>">
                    <i class="fa fa-plus me-2"></i> Add to Cart
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 55 55">
                        <g id="Add_to_Cart" data-name="Add to Cart" transform="translate(-183.827 -1193)">
                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="27.5" cy="27.5" r="27.5" transform="translate(183.827 1193)" fill="#ef9244" />
                            <path id="Path_3" data-name="Path 3" d="M14.831,6A1.472,1.472,0,0,1,16.3,7.472v5.888h5.888a1.472,1.472,0,1,1,0,2.944H16.3v5.888a1.472,1.472,0,1,1-2.944,0V16.3H7.472a1.472,1.472,0,0,1,0-2.944h5.888V7.472A1.472,1.472,0,0,1,14.831,6Z" transform="translate(196.889 1205.889)" fill="#fff" />
                        </g>
                    </svg> -->
                </button>
            <?php } else { ?>
                <span class="text-site-accent small">In Cart</span>
            <?php } ?>
        </div>
    </div>
</section>