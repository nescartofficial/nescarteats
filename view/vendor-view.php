<?php
$user = new User();
$world = new World();
$vendors = new Vendors();
$category_specials = new General('category_specials');
$categories = new General('categories');
$reviews = new General('reviews');
$settings = new General('settings');
$saved_vendors = new General('saved_vendors');
$saved_menus = new General('saved_menus');
$menus = new Menus();
$cart = new Cart();

$vendor = Input::get('action') ? $vendors->get(Input::get('action'), 'slug') : null;

// Menu Category
$selected_category = Input::get('category') ? $categories->get(Input::get('category'), 'slug') : null;
$menu_categories = $menus->getDistinct("category", "WHERE vendor_id = {$vendor->id}");

$special_menus = $menus->getAllSpecials(0, 'special', '<>', $vendor->user_id);
$other_menus = $menus->getAllSpecials(0, 'special', '=', $vendor->user_id);

$is_saved_vendor = $is_vendor_open = null;

if ($user->isLoggedIn()) {
    $is_saved_vendor = $saved_vendors->getByUser($vendor->id, 'menu_id', $user->data()->id);
}

// print_r($cart->add_addon(13, 3, 1000));
// print_r($cart->get_cart());

Alerts::displayError();
Alerts::displaySuccess();
?>

<header class="vendor-introduction py-5 bg-primary--light">
    <div class="container-fluid">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img data-lazy="assets/images/vendor/<?= $vendor->cover ?>" src="assets/images/vendor/<?= $vendor->cover ?>" class="cover" />
                </div>
                <div class="col-lg-6">
                    <!-- Logo & ++ -->
                    <div class="d-lg-flex align-items-center mb-5">
                        <div class="row d-flex align-items-center">
                            <div class="col-3">
                                <img data-lazy="assets/images/vendor/<?= $vendor->logo ?>" src="assets/images/vendor/<?= $vendor->logo ?>" class="logo" />
                            </div>
                            <div class="col-9">
                                <h3 class=""><?= $vendor->name; ?></h3>
                                <p class="text-muted text-truncate mb-0 ">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 39.926 63.842">
                                        <path id="location-pin" d="M27.963,3.214A19.928,19.928,0,0,0,8,23.137C8,42.2,27.963,67.056,27.963,67.056S47.926,42.195,47.926,23.137A19.932,19.932,0,0,0,27.963,3.214Zm0,30.948a10.78,10.78,0,1,1,10.775-10.78,10.778,10.778,0,0,1-10.775,10.78Z" transform="translate(-8 -3.214)" fill="#000000" />
                                    </svg>
                                    <span class="ms-1"><?= $vendor->address; ?>, <?= $world->getStateName($vendor->state); ?></span>
                                </p>
                            </div>
                        </div>
                        <div class="ms-auto justify-content-end d-flex">
                            <!-- Back -->
                            <a href="#" id="back_button" class="d-none p-2 border-0 rounded shadow bg-white me-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22.5 38.381">
                                    <path id="chevron-left" d="M30.949,7.767a3.526,3.526,0,0,1,0,5.216l-12.4,12.932,12.4,12.932a3.516,3.516,0,0,1,0,5.207,3.93,3.93,0,0,1-5.344,0c-1.384-1.344-14.9-15.535-14.9-15.535a3.619,3.619,0,0,1,0-5.214S24.221,9.119,25.6,7.769a3.928,3.928,0,0,1,5.344,0Z" transform="translate(-9.6 -6.72)" />
                                </svg>

                            </a>
                            <!-- Save -->
                            <a href="#" data-type="vendor" data-id="<?= $vendor->id ?>" class="add-favourite d-flex p-2 border-0 rounded shadow bg-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 67.494 57.106">
                                    <path id="Favourite_Outline" data-name="Favourite Outline" d="M58.692,8.92a16.3,16.3,0,0,0-21.647,0l-4.058,3.725L28.925,8.92a16.293,16.293,0,0,0-21.642,0,14.879,14.879,0,0,0,0,22.331l25.7,23.591,25.7-23.591a14.886,14.886,0,0,0,0-22.331Z" transform="translate(0.761 -1.807)" fill="<?= $is_saved_vendor ? '#ef9244' : 'none'; ?>" stroke="#000" stroke-width="6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Metas -->
                    <section class="d-flex align-items-center justify-content-around">
                        <p class="fs-14p mb-0 fw-bold text-center">
                            <i class="fa fa-star"></i> <br />
                            4.5(233)
                        </p>
                        <p class="fs-14p mb-0 fw-bold text-center">
                            <i class="fa fa-map"></i> <br />
                            <?= $world->getStateName($vendor->state) ?>
                        </p>
                        <p class="fs-14p mb-0 fw-bold text-center">
                            <i class="fa fa-clock"></i> <br />
                            <?= $vendor->delivery_time; ?>
                        </p>
                        <p class="fs-14p mb-0 fw-bold text-center">
                            <i class="fa fa-eye"></i> <br />
                            Open
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="vendor content card py-5 position-relative">
    <div class="container position-relative">
        <div class="row">

            <section class="content-menu col-12 d-flex align-items-center gap-5 mb-3">
                <a href="vendor/<?= $vendor->slug ?>?pg=menu" class="active">Menu</a>
                <a href="vendor/<?= $vendor->slug ?>?pg=review" class="">Reviews</a>
                <a href="vendor/<?= $vendor->slug ?>?pg=about" class="">About</a>

                <?php if ($menu_categories) { ?>
                    <form action="" class="ms-auto">
                        <select name="category" id="category" class="form-control form-select">
                            <option value="">Select your category</option>
                            <?php foreach ($menu_categories as $v) {
                                $category = $categories->get($v->category); ?>
                                <option value="<?= $category->slug ?>" <?= $selected_category && $selected_category->id == $category->id ? 'selected'  : null; ?>><?= $category->title ?></option>
                            <?php } ?>
                        </select>
                    </form>
                <?php } ?>
            </section>

            <hr class="col-12 text-primary mb-4">

            <!-- Special Meals -->
            <section class="col-12 mb-4">
                <?php Component::render('menu', array('data' => $special_menus, 'type' => 'list', 'title' => "Special Menus")); ?>
            </section>

            <!-- Other Meals -->
            <section class="col-12 mb-4">
                <?php Component::render('menu', array('data' => $other_menus, 'type' => 'list', 'title' => "Menus")); ?>
            </section>
        </div>

    </div>
</section>