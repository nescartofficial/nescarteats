<?php
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$saved_menus = new Saves('saved_menus');
$saved_vendors = new Saves('saved_vendors');
$vendors = new Vendors();
$menus = new Menus();

$favorite_meals = $saved_menus->getSaves();
$favorite_vendors = $saved_vendors->getSaves('vendor_id', 'vendors');

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
            <h4 class="mb-0 mx-auto pr-40">Favourites</h4>
        </div>
    </header>

    <?php if ($favorite_meals) { ?>
        <!-- Favourite Meals -->
        <div class="container mb-5">
            <?php Component::render('menu', array('data' => $favorite_meals, 'type' => 'list', 'title' => "Favourite Meals")); ?>
        </div>
    <?php } ?>

    <?php if ($favorite_vendors) { ?>
        <!-- Favourite Meals -->
        <div class="container mb-5">
            <?php Component::render('vendor', array('data' => $favorite_vendors, 'type' => 'single', 'title' => "Favourite Vendors")); ?>
        </div>
    <?php } ?>

    <?php if (!$favorite_meals && !$favorite_vendors) { ?>
        <div class="card border-0">
            <div class="card-body d-flex align-items-center justify-content-center ">
                <div class="col-md-6 text-center mx-auto py-5">
                    <i class="fa fa-heart fa-3x"></i>
                    <h4 class="mt-4">You haven’t saved an item yet!</h4>
                    <p class="mb-4">Found something you like? Tap on the heart shaped icon next to the item to add it to your wishlist! All your saved items will appear here.</p>
                    <a href="categories" class="btn bg-site-accent border-0">Start Shopping</a>
                </div>
            </div>
        </div>
    <?php } ?>
</section>