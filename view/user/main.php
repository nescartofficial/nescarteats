<?php
$user = new User();
$categories = new General('categories');
$category_specials = new General('category_specials');
$category_special_datas = new General('category_special_datas');
$pagination = new Pagination();
$Slideshows = new General('slideshows');
$orders = new Orders();
$vendors = new Vendors();
$menus = new Menus();
$notifications = new General('notifications');

$profile = $user->getProfile();
$user_id = $user->data()->id;

// Meal
$menu_near_me = $menus->getAllNearMe($profile->state, $profile->city);

// Vendor
$vendor_near_me = $vendors->getAllNearMe($profile->state, $profile->city);

// Nav counters
$unread_notification_count = $pagination->countAll('notifications', "WHERE id > 0 AND user_id = {$user_id} AND status = 0");

$recent_orders = $orders->getPages(4, 0, "WHERE user_id = {$user_id}");

$wish_count = $awaiting_count = $delivered_count = 0;


$searchTerm = "WHERE user_id = {$user->data()->id}";
$order_count = $pagination->countAll('orders', $searchTerm);

$searchTerm = "WHERE user_id = {$user->data()->id} AND status = 2";
$awaiting_count = $pagination->countAll('orders', $searchTerm);

$searchTerm = "WHERE user_id = {$user->data()->id} AND status = 3";
$delivered_count = $pagination->countAll('orders', $searchTerm);


$searchTerm = "WHERE user_id = {$user->data()->id}";
$wish_count = $pagination->countAll('saved_items', $searchTerm);

// Curated - Breakfast, Lunch & Dinner
$breakfast = $category_specials->get('breakfast-bestsellers', 'slug');
$breakfast_data = $breakfast && $breakfast->status ? $category_special_datas->getBy($profile->state, 'state', $breakfast->id, 'category_special_id') : null;
$lunch = $category_specials->get('munchy-lunch', 'slug');
$lunch_data = $lunch && $lunch->status ? $category_special_datas->getBy($profile->state, 'state', $lunch->id, 'category_special_id') : null;
$dinner = $category_specials->get('delicious-dinner', 'slug');
$dinner_data = $dinner && $dinner->status ? $category_special_datas->getBy($profile->state, 'state', $dinner->id, 'category_special_id') : null;

// Slideshow
$slideshow_list = $Slideshows->getAll(1, 'status', '=');

$notification_list = $notifications->getPages(4, 0, "WHERE id > 0 AND user_id = {$user_id}");

$views = 'view/user';

Alerts::displayError();
Alerts::displaySuccess();
?>

<!-- Homepage -->
<section class="py-5">

    <!-- Header -->
    <header class="header container-fluid mb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php if ($user->data()->email_token != 'verified') { ?>
                        <div class="alert alert-danger text-center mb-4">
                            <span>Your email address hasn't been confirmed, click the link to get a confirmation sent to your email. <a href="controllers/profile.php?rq=get-email-verification&email=<?= $user->data()->email; ?>" class="text-accent">Get Confirmation Email</a></span>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row gy-4 align-items-center justify-content-between">

                <div class="col-lg-3 d-lg-none">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Delivery -->
                        <div class="d-flex align-items-center">
                            <div class="icon-60 d-flex align-items-center justify-content-center">
                                <?php if ($profile->image && $profile->image != 'user-avatar.jpg') { ?>
                                    <img class="rounded-circle img-sm border mr-2" src="assets/images/profile/<?= $profile->image ?>" style="height: 50px; width: 50px; object-fit:cover; object-position:center; border-radius: 100%;">
                                <?php } else { ?>
                                    <div style="background: red;display: flex; align-items:center; justify-content: center; height: 50px; width: 50px; object-fit:cover; object-position:center; border-radius: 100%;">
                                        <span class="fw-bold fs-24p" style="color:white;"><?= $user->data()->first_name[0] ?></span>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="ms-3">
                                <p class="mb-0 fs-14p text-muted">Delivery Address</p>
                                <div class="d-flex">
                                    <address class="fw-bold fs-16p mb-0 text-truncate me-2">
                                        <?= $profile->address ?>
                                    </address>
                                    <a href="dashboard/profile">
                                        <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="53.25" height="53.198" viewBox="0 0 53.25 53.198">
                                            <g id="Edit" transform="translate(-264.043 -93.099)">
                                                <path id="Path_966" data-name="Path 966" d="M46.366,2.744a2.539,2.539,0,0,0-3.591,0L40.56,4.959a7.621,7.621,0,0,0-8.671,1.489L4.955,33.381,19.32,47.745,46.253,20.812a7.621,7.621,0,0,0,1.489-8.671l2.215-2.215a2.539,2.539,0,0,0,0-3.591ZM35.528,24.354,19.32,40.563l-7.182-7.182L28.346,17.172Zm4.617-4.617,2.516-2.516a2.539,2.539,0,0,0,0-3.591l-3.591-3.591a2.539,2.539,0,0,0-3.591,0l-2.516,2.516Z" transform="translate(266.593 91.099)" fill="#8a8a8a" fill-rule="evenodd" />
                                                <path id="Path_967" data-name="Path 967" d="M2,34.923,7.388,15.172,21.751,29.537Z" transform="translate(262.043 111.374)" fill="#8a8a8a" />
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Notification -->
                        <a href="dashboard/notifications" class="p-2 border-0 rounded shadow bg-white">
                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 59.5 85.944">
                                <path id="Notification" d="M41.188,8.813V9.946C51.94,13.307,59.781,23.771,59.781,36.159V63.505H63.5v7.813H4V63.505H7.719V36.159c0-12.388,7.841-22.852,18.594-26.214V8.813A7.632,7.632,0,0,1,33.75,1,7.632,7.632,0,0,1,41.188,8.813Zm0,70.318V75.225H26.313v3.907a7.632,7.632,0,0,0,7.438,7.813A7.632,7.632,0,0,0,41.188,79.131Z" transform="translate(-4 -1)" fill-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <figure class="mb-4 text">
                        <p class="mb-1">Hey, <span class="fw-bold"><?= $user->data()->first_name; ?></span></p>
                        <p class="fw-bold fs-16p mb-0">What will you like to eat today?</p>
                    </figure>

                    <section class="search d-flex align-items-stretch justify-content-between">
                        <form action="dashboard/search" method="get" class="search-header  flex-fill me-3">
                            <div class="input-group bg-white w-100 shadow rounded p-1">
                                <span class="input-group-text border-0 bg-transparent">
                                    <svg id="search" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 65.756 65.756">
                                        <path id="search-2" data-name="search" d="M53.82,46.433a27.833,27.833,0,1,0-4.911,4.911q.07.08.147.156L63.817,66.262a3.479,3.479,0,0,0,4.92-4.92L53.976,46.581Q53.9,46.5,53.82,46.433ZM46.6,14.6a20.875,20.875,0,1,1-29.522,0A20.875,20.875,0,0,1,46.6,14.6Z" transform="translate(-4 -1.525)" fill="#8a8a8a" fill-rule="evenodd" />
                                    </svg>
                                </span>
                                <input type="text" name="keyword" class="form-control form-control-lg border-0 bg-transparent " placeholder="Search for a meal or vendor">
                            </div>
                        </form>

                        <button class="shadow p-2 border-0 rounded bg-white" data-bs-toggle="modal" data-bs-target="#searchFilterModal">
                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 65.756 65.754">
                                <path id="Filter" d="M16.212,3.792c0-2.021-1.637-2.192-3.653-2.192s-3.653.171-3.653,2.192V34.478h7.306V3.792ZM8.906,65.164c0,2.014,1.632,2.192,3.653,2.192s3.653-.178,3.653-2.192V52.744H8.906ZM21.322,38.131H3.787c-2.021,0-2.187,1.637-2.187,3.653v3.653c0,2.021.169,3.653,2.187,3.653h17.54c2.009,0,2.192-1.632,2.192-3.653V41.784C23.519,39.768,23.336,38.131,21.322,38.131ZM65.16,41.784H47.62c-2.016,0-2.185,1.637-2.185,3.653v3.653c0,2.021.169,3.653,2.187,3.653h17.54c2.009,0,2.192-1.632,2.192-3.653V45.437c0-2.016-.183-3.653-2.2-3.653ZM45.437,23.519c0-2.016-.183-3.653-2.2-3.653H25.706c-2.021,0-2.187,1.637-2.187,3.653v3.653c0,2.021.169,3.653,2.187,3.653h17.54c2.009,0,2.192-1.632,2.192-3.653ZM38.131,3.792c0-2.021-1.637-2.192-3.653-2.192s-3.653.171-3.653,2.192V16.212h7.306ZM30.825,65.164c0,2.014,1.632,2.192,3.653,2.192s3.653-.178,3.653-2.192V34.478H30.825V65.164ZM60.05,3.792C60.05,1.771,58.413,1.6,56.4,1.6s-3.653.171-3.653,2.192V38.131H60.05V3.792ZM52.744,65.164c0,2.014,1.632,2.192,3.653,2.192s3.653-.178,3.653-2.192V56.4H52.744Z" transform="translate(-1.6 67.354) rotate(-90)" fill="#ef9244" />
                            </svg>
                        </button>
                    </section>

                </div>

                <?php Component::render('slideshow', array('data' => $Slideshows->getAll(1, 'status', '='))); ?>
            </div>
        </div>
    </header>


    <!-- Category List -->
    <section class="container-fluid site-section">
        <div class="container">
            <?php Component::render('category', array('data' => $categories->getAll(1, 'status', '='), 'type' => 'list')); ?>
        </div>
    </section>

    <!-- Popular Meals -->
    <section class="container-fluid site-section-b">
        <div class="container">
            <?php Component::render('menu', array('data' => $menus->getAll(1, 'status', '='), 'type' => 'list', 'title' => "Popular Meals")); ?>
        </div>
    </section>

    <!-- Meals Near You -->
    <?php if ($menu_near_me) { ?>
        <section class="container-fluid site-section-b">
            <div class="container">
                <?php Component::render('menu', array('data' => $menu_near_me, 'type' => 'list-slide', 'title' => "Meals Near You")); ?>
            </div>
        </section>
    <?php } ?>

    <!-- Top Vendors -->
    <section class="container-fluid site-section-b">
        <div class="container">
            <?php Component::render('vendor', array('data' => $vendors->getAll(1, 'status', '='), 'type' => 'list', 'title' => "Top Vendors")); ?>
        </div>
    </section>

    <!-- Curated Breakfast - Launch - Dinner -->
    <?php if (!$breakfast_data || !$lunch_data || !$dinner_data) { ?>
        <section class="container-fluid site-section-b">
            <div class="container">

                <?php $breakfast_data && $breakfast_data->status ? Component::render('menu', array('data' => explode(',', trim($breakfast_data->menus)), 'type' => 'list-slide', 'ids' => true, 'title' => $breakfast->title)) : null; ?>

                <?php $lunch_data && $lunch_data->status ? Component::render('menu', array('data' => explode(',', trim($lunch_data->menus)), 'type' => 'list-slide', 'ids' => true, 'title' => $lunch->title)) : null; ?>

                <?php $dinner_data && $dinner_data->status ? Component::render('menu', array('data' => explode(',', trim($dinner_data->menus)), 'type' => 'list-slide', 'ids' => true, 'title' => $dinner->title)) : null; ?>
            </div>
        </section>
    <?php } ?>

    <?php if ($vendor_near_me) { ?>
        <!-- Vendors Near You -->
        <section class="container-fluid">
            <div class="container">
                <?php Component::render('vendor', array('data' => $vendor_near_me, 'type' => 'single', 'title' => "Vendor Near You")); ?>
            </div>
        </section>
    <?php } ?>

</section>


<!-- Modal -->
<div class="modal modal-lg fade" id="searchFilterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="dashboard/search" method="get">
                    <!-- Sort -->
                    <section class="row mb-4">
                        <div class="col-lg-12">
                            <h4>Sort By</h4>
                        </div>
                        <div class="col">
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <div class="card h-100">
                                    <label class="btn bg-white px-3 py-2 rounded">
                                        <input name="sort" value="popularity" type="radio" class="checkout_delivery">
                                        <div class="">
                                            <i class="fa fa-circle text-dark my-2"></i>
                                            <p class="fs-14p mb-0 text-dark">Popularity</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <div class="card h-100">
                                    <label class="btn bg-white px-3 py-2 rounded">
                                        <input name="sort" value="rating" type="radio" class="checkout_delivery">
                                        <div class="">
                                            <i class="fa fa-circle text-dark my-2"></i>
                                            <p class="fs-14p mb-0 text-dark">rating</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <div class="card h-100">
                                    <label class="btn bg-white px-3 py-2 rounded">
                                        <input name="sort" value="delivery-time" type="radio" class="checkout_delivery">
                                        <div class="">
                                            <i class="fa fa-circle text-dark my-2"></i>
                                            <p class="fs-14p mb-0 text-dark">Delivery Time</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <div class="card h-100">
                                    <label class="btn bg-white px-3 py-2 rounded">
                                        <input name="sort" value="delivery-fee" type="radio" class="checkout_delivery">
                                        <div class="">
                                            <i class="fa fa-circle text-dark my-2"></i>
                                            <p class="fs-14p mb-0 text-dark">Delivery Fee</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <div class="card h-100">
                                    <label class="btn bg-white px-3 py-2 rounded">
                                        <input name="sort" value="minimium-order" type="radio" class="checkout_delivery">
                                        <div class="">
                                            <i class="fa fa-circle text-dark my-2"></i>
                                            <p class="fs-14p mb-0 text-dark">Minimium Order</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Filter -->
                    <section class="row mb-4">
                        <header class="col-lg-12 mb-3">
                            <h5>Filter By</h5>
                        </header>

                        <!-- Budget -->
                        <section class="col-lg-12 mb-3">
                            <h6 class="mb-2">Budget</h6>
                            <div class="row">
                                <div class="col-6">
                                    <label for="min-budget">Min</label>
                                    <div class="input-group">
                                        <span class="bg-primary--light border-end-0 input-group-text">
                                            ₦
                                        </span>
                                        <input type="text" name="min-budget" id="min-budget" class="form-control border-start-0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label for="max-budget">Max</label>
                                    <div class="input-group">
                                        <span class="bg-primary--light border-end-0 input-group-text">
                                            ₦
                                        </span>
                                        <input type="text" name="max-budget" id="max-budget" class="form-control border-start-0"">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <?php
                        $special_categories = new General('category_specials');
                        $category_list = $special_categories->getAll(1, 'status', '=');
                        if ($category_list) { ?>
                            <!-- Quick Filter -->
                            <section class=" col-lg-12 mb-3">
                                        <h6 class="mb-2">Quick Filter</h6>
                                        <div class="d-flex flex-wrap gap-3">
                                            <?php foreach ($category_list as $k => $v) { ?>
                                                <div class="btn-group-toggle" data-toggle="buttons">
                                                    <div class="card shadow-sm">
                                                        <label class="btn bg-white px-3 py-2 rounded">
                                                            <div class="d-flex gap-3 justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="text-start text-black flex-fill">
                                                                        <p class="fs-14p mb-0"><?= $v->title; ?></p>
                                                                    </div>
                                                                </div>
                                                                <input name="quick" value="<?= $v->slug ?>" type="radio" class="checkout_delivery">
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                        </section>
                    <?php } ?>

                    <?php
                    $category_list = $categories->getAll(1, 'status', '=');
                    if ($category_list) { ?>
                        <!-- Category Filter -->
                        <section class="col-lg-12 mb-3">
                            <h6 class="mb-2">Categories</h6>
                            <div class="d-flex flex-wrap gap-3">
                                <?php foreach ($category_list as $k => $v) { ?>
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <div class="card shadow-sm">
                                            <label class="btn bg-white px-3 py-2 rounded">
                                                <div class="d-flex gap-3 justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-start text-black flex-fill">
                                                            <p class="fs-14p mb-0"><?= $v->title ?></p>
                                                        </div>
                                                    </div>
                                                    <input name="category" value="<?= $v->slug ?>" type="radio" class="checkout_delivery">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </section>
                    <?php } ?>

                    </section>

                    <button type="submit" class="btn w-100">Search</button>
                </form>
            </div>
        </div>
    </div>
</div>