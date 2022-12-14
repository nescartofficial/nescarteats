<?php
$user = new User();
$world = new World();
$vendors = new Vendors();
$category_specials = new General('category_specials');
$categories = new General('categories');
$reviews = new General('reviews');
$settings = new General('settings');
$menus = new Menus();
$cart = new Cart();

$vendor = Input::get('sub') ? $vendors->get(Input::get('sub'), 'slug') : null;
!$vendor ? Redirect::to_js('dashboard') : null;

$special_menus = $menus->getAllSpecials(0, 'special', '<>', $vendor->user_id);
$other_menus = $menus->getAllSpecials(0, 'special', '=', $vendor->user_id);

$about_page = Input::get('section') && Input::get('section') == 'about' ? true : false;

Alerts::displayError();
Alerts::displaySuccess();
?>

<section class="vendor header position-relative bg-primary py-0" style="background:url('');">
    <div class="vendor-slider--image">
        <img data-lazy="assets/images/vendor/<?= $vendor->cover ?>" src="assets/images/vendor/<?= $vendor->cover ?>" class="item-thumb" style="height: 150px;" />
    </div>

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
                <a href="#" id="back_button" class="d-flex p-2 border-0 rounded shadow bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 67.494 57.106">
                        <path id="Favourite_Outline" data-name="Favourite Outline" d="M58.692,8.92a16.3,16.3,0,0,0-21.647,0l-4.058,3.725L28.925,8.92a16.293,16.293,0,0,0-21.642,0,14.879,14.879,0,0,0,0,22.331l25.7,23.591,25.7-23.591a14.886,14.886,0,0,0,0-22.331Z" transform="translate(0.761 -1.807)" fill="none" stroke="#000" stroke-width="6" />
                    </svg>

                </a>
            </div>
        </header>
    </div>
</section>

<section class="vendor content card bg-primary--light py-5 position-relative">
    <div class="position-absolute top-0 start-50 translate-middle">
        <img data-lazy="assets/images/vendor/<?= $vendor->logo ?>" src="assets/images/vendor/<?= $vendor->logo ?>" class="logo" />
    </div>

    <div class="card-body">
        <div class="container position-relative">
            <div class="row">
                <header class="col-12 mb-3">
                    <div class="text-center">
                        <h3><?= $vendor->name; ?></h3>
                        <p class="fs-12p text-muted text-truncate mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 39.926 63.842">
                                <path id="location-pin" d="M27.963,3.214A19.928,19.928,0,0,0,8,23.137C8,42.2,27.963,67.056,27.963,67.056S47.926,42.195,47.926,23.137A19.932,19.932,0,0,0,27.963,3.214Zm0,30.948a10.78,10.78,0,1,1,10.775-10.78,10.778,10.778,0,0,1-10.775,10.78Z" transform="translate(-8 -3.214)" fill="#8a8a8a" />
                            </svg>
                            <span class="ms-1"><?= $vendor->name; ?>, <?= $world->getStateName($vendor->state); ?></span>
                        </p>
                    </div>
                </header>

                <section class="col-12 w-100 d-flex align-items-center justify-content-between mb-3">
                    <p class="fs-14p mb-0 fw-bold">
                        <i class="fa fa-star"></i> 4.5(233)
                    </p>
                    <p class="fs-14p mb-0 fw-bold">
                        <i class="fa fa-clock"></i> 30-45mins
                    </p>
                    <p class="fs-12p mb-0 fw-bold">
                        <i class="fa fa-eye"></i> Open
                    </p>
                </section>

                <section class="content-menu col-12 d-flex justify-content-between mb-3">
                    <a href="dashboard/vendor/<?= $vendor->slug ?>?section=menu" class="<?= !$about_page ? 'active' : null; ?>">Menu</a>
                    <a href="dashboard/vendor-reviews/<?= $vendor->slug ?>" class="">Reviews</a>
                    <a href="dashboard/vendor/<?= $vendor->slug ?>?section=about" class="<?= $about_page ? 'active' : null; ?>">About</a>
                </section>

                <hr class="col-12 text-primary mb-4">

                <?php if ($review_page) { ?>
                
                    <div class="col-12 mb-4">
                        <h2><?= $vendor->name; ?></h2>
        
                        <div class="d-flex justify-content-between">
                            <p class="fs-14p mb-0 fw-bold">
                                <i class="fa fa-star"></i> 233 Reviews
                            </p>
                            <p class="fs-14p mb-0 fw-bold">
                                <i class="fa fa-badge"></i> 4.5 Rating
                            </p>
                        </div>
                    </div>
        
                    <div class="col-12 mb-5">
                        <h4>Write a review</h4>
                        <p class="fs-16p mb-5">
                            Share your experience to help others.</p>
        
                        <form action="controllers/reviews.php" name="reviews_form" id="reviews_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="rq" value="vendor-review">
                            <input type="hidden" name="vendor_slug" value="<?= $vendor->slug; ?>">
                            <input type="hidden" name="vendor_id" value="<?= $vendor->id; ?>">
                            <input type="hidden" name="token" value="<?php echo Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                            <input type="hidden" name="id" value="<?= $review->id; ?>">
        
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <textarea type="text" name="review" value="" id="review" class="form-control" placeholder="Write a review here" required style="min-height: 120px"><?= $review ? $review->review : ($form_data ? $form_data['review'] : null); ?></textarea>
                                </div>
                            </div>
        
                            <div class="mt-2">
                                <button type="submit" class="btn bg-accent"><?= $review ? 'Update' :  'Submit'; ?> Review</button>
                            </div>
                        </form>
                    </div>
        
                    <div class="col-12">
                        <?php Component::render('review', array('data' => $review_list, 'type' => 'list', 'title' => "Reviews from others.")); ?>
                    </div>
                    
                <?php }else if ($about_page) { ?>
                
                    <h4 class="mb-3">About</h4>
                    <div class="about-content">
                        <?= $vendor->about; ?>
                    </div>
                    
                <?php } else { ?>
                    <!-- Special Meals -->
                    <section class="col-12 mb-4">
                        <?php Component::render('menu', array('data' => $special_menus, 'type' => 'list', 'title' => "Special Menus")); ?>
                    </section>

                    <!-- Other Meals -->
                    <section class="col-12 mb-4">
                        <?php Component::render('menu', array('data' => $other_menus, 'type' => 'list', 'title' => "Menus")); ?>
                    </section>
                <?php } ?>
            </div>

        </div>
    </div>
</section>