<?php
$User = new User();
$Vendors = new Vendors();
$World = new World();

$profile = $User->isLoggedIn() ? $User->getProfile() : null;
$states = $World->getStatesByCountryId(160);

// Search
$name = Input::get('keyword') ? Input::get('keyword') : null;
$state = Input::get('state') ? Input::get('state') : null;
$popular_vendors = $profile ? $Vendors->getPopulars($name, $state ? $state : $profile->state, $profile->city) : $Vendors->getPopulars($name, $state);

$form_data = Session::exists('form_data') ? Session::get('form_data') : null;
?>

<section class="site-section pg-find-vendors">
    <header class="container spacer-lg search-header">
        <div class="row justify-content-between mb-4">
            <div class="col-lg-12">
                <div class="card bg-accent">
                    <div class="col-lg-8 mx-auto py-5 text-center">
                        <h2 class="fs-title mb-4" data-aos="fade-left" data-aos-duration="1800">Discover the best food & drinks from vendors near you.</h2>

                        <form action="" method="get">
                            <div class="row px-3 gy-3 g-0 mb-3">
                                <div class="col-lg-4">
                                    <select name="state" id="state" class="form-control border-end-0 form-select">
                                        <option value="">Select your State</option>
                                        <?php if ($states) { ?>
                                            <?php foreach ($states as $k => $v) { ?>
                                                <option value="<?= $v->id ?>" <?= $profile && $profile->state == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg">
                                    <div class="d-flex">
                                        <span class="input-group-text bg-primary--light border-0 rounded-0 d-none d-lg-block">|</span>
                                        <span class="input-group-text bg-primary--light border-0 rounded-0"><i class="fa fa-search"></i></span>
                                        <input type="text" name="keyword" class="form-control border-start-0 rounded-0 rounded-end" placeholder="Search for restaurant, cuisine or a dish" aria-label="Search for restaurant, cuisine or a dish" required>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <!-- Top Vendors -->
    <section class="container-fluid mb-5">
        <div class="container">
            <?php if ($state || $name) { ?>
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <p class="mb-0 text-center text-accent fw-bold">
                            <?= $name ? 'Search keyword: ' . $name : null; ?>
                            <?= $state ? ' Search state: ' . $World->getStateName($state) : null; ?>
                        </p>
                    </div>
                </div>
            <?php } ?>
            <?php Component::render('vendor', array('data' => $popular_vendors, 'type' => 'single', 'title' => "Popular Vendor Near You.")); ?>
        </div>
    </section>
</section>