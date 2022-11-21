<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$world = new World();


$bank = $user->getBank();
$vendor = $user->getVendor();
$countries = $world->getCountries();

$form_data = Session::exists('store_fd') ? Session::get('store_fd') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<div class="container-fluid container-md">
    <div class="row g-4 align-items-center mb-4">
        <div class="col-md-12 mx-auto">
            <nav class="mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-sa-simple">
                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </nav>

            <h1 class="h3 mb-2">Your Restaurant</h1>
            <p class="mb-1">You are currently providing information about your restaurant.</p>
            <p class="mb-0 font-weight-bold">Items marked with * are required fields.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="controllers/store.php" name="store_form" id="store_form" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="">
                <input type="hidden" name="rq" value="<?= $vendor ? 'edit' : 'setup'; ?>">
                <input type="hidden" name="token" value="<?php echo Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                <input type="hidden" name="id" value="<?= $user->data()->id; ?>">

                <div class="card mb-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3 fs-exact-18">Basic information</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="name" class="form-label">Restaurant Name <i class="text-danger">*</i></label>
                                <input type="text" name="name" value="<?= $vendor ? $vendor->name : ($form_data ? $form_data['name'] : null); ?>" id="name" aria-describedby="seller name" class="form-control slugit" placeholder="Input  your store name" data-slugit-target="#slug" data-slugit-event="keyup" required>
                                <span class="text-help">Your restaurant name is public and needs to be unique.</span>
                            </div>
                            <div class="col-md-12 mb-4">
                                <label class="form-label" for="about">Description <i class="text-danger">*</i></label>
                                <textarea name="about" class="form-control " id="about" aria-describedby="seller about" placeholder="Input your restaurant description" style="height: 120px;" required><?= $vendor ? $vendor->about : ($form_data ? $form_data['about'] : null); ?></textarea>
                                <span class="text-help">This is your chance to tell shoppers about your restaurant and business in your own words. This is displayed on your restaturant page.</span>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="opening_time" class="form-label">Open Time <i class="text-danger">*</i></label>
                                <input type="time" name="opening_time" value="<?= $vendor ? $vendor->opening_time : ($form_data ? $form_data['opening_time'] : null); ?>" id="opening_time" class="form-control" placeholder="Input your opening time" required>
                                <span class="text-help">By providing us with your opening time, orders taking on your store will begin at this time.</span>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="closing_time" class="form-label">Closing Time <i class="text-danger">*</i></label>
                                <input type="time" name="closing_time" value="<?= $vendor ? $vendor->closing_time : ($form_data ? $form_data['closing_time'] : $user->data()->closing_time); ?>" id="closing_time" class="form-control" placeholder="Input your closing time" required>
                                <span class="text-help">By providing us with your opening time, orders taking on your store will end at this time.</span>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="delivery_time" class="form-label">Delivery Time <i class="text-danger">*</i></label>
                                <input type="text" name="delivery_time" value="<?= $vendor ? $vendor->delivery_time : ($form_data ? $form_data['delivery_time'] : $user->data()->delivery_time); ?>" id="delivery_time" class="form-control" placeholder="Input your delivery time" required>
                                <span class="text-help">This is the minimium time required for you to prepare an order for delivery.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="mb-3 fs-exact-18">Location</h5>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="country">Country <i class="text-danger">*</i></label>
                                <select name="country" id="country" data-type="country" data-placeholder="Select Country" data-world-target="#seller-state" class="world  sa-select2 form-control form-control-lg form-select">
                                    <?php if ($countries) { ?>
                                        <option value="">Select Country</option>
                                        <?php foreach ($countries as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $vendor && $vendor->country == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="state">State <i class="text-danger">*</i></label>
                                <select name="state" id="seller-state" data-type="state" data-placeholder="Select State" data-world-target="#seller-city" data-selected="<?= $vendor ? $vendor->state : null; ?>" class="world  sa-select2 form-control form-control-lg form-select">
                                    <option value="">Select a country</option>
                                    <?php if ($vendor && $vendor->country) {
                                        $states = $world->getStatesByCountryId($vendor->country);
                                    ?>
                                        <?php foreach ($states as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $vendor && $vendor->state == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="city">City <i class="text-danger">*</i></label>
                                <select name="city" id="seller-city" data-placeholder="Select City" data-selected="<?= $vendor ? $vendor->city : null; ?>" class="city form-control form-control-lg sa-select2 form-select">
                                    <option value="">Select a state</option>
                                    <?php if ($vendor && $vendor->state) {
                                        $cities = $world->getCitiesByStateId($vendor->state);
                                    ?>
                                        <?php foreach ($cities as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $vendor && $vendor->city == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label for="address" class="form-label">Address <i class="text-danger">*</i></label>
                                <input type="text" name="address" value="<?= $vendor ? $vendor->address : ($form_data ? $form_data['address'] : null); ?>" id="address" class="form-control" placeholder="Input your address" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card w-100 mb-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3 fs-exact-18">Contact</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="phone" class="form-label">Phone <i class="text-danger">*</i></label>
                                <input form="store_form" type="text" name="phone" value="<?= $vendor ? $vendor->phone : ($form_data ? $form_data['phone'] : $user->data()->phone); ?>" id="phone" class="form-control" placeholder="Input your phone number (Digit only)" required>
                                <span class="text-help">By providing us with your phone number, you acknowledge and agree that we (and/or our service providers) may contact you in order to provide you with additional information or offers regarding our services</span>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label">Email <i class="text-danger">*</i></label>
                                <input form="store_form" type="text" name="email" value="<?= $vendor ? $vendor->email : ($form_data ? $form_data['email'] : $user->data()->email); ?>" id="email" class="form-control" placeholder="Input your email address" required>
                                <span class="text-help">We'll email you notifications and information about your restaurant, but we'll never display your email address. Please read our <a href="privacy-policy" class="text-site-accent">Privacy Statement.</a></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card w-100 mb-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3 fs-exact-18">Media</h5>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-8 mb-4">
                                <label for="cover" class="form-label"><?= $vendor && $vendor->cover ? 'Change' : null; ?> Cover <i class="text-danger">*</i></label>
                                <input form="store_form" type="file" name="cover" id="cover" class="form-control">
                                <span class="text-help">Upload a banner for your restaurant. Banner size is (625x300) pixels.</span>
                            </div>

                            <?php if ($vendor && $vendor->cover) { ?>
                                <div class="col-md-4 mb-4">
                                    <img src='assets/images/vendor/<?= $vendor->cover ?>' alt='' class='img-fluid' style="height: 105px; object-fit: cover;">
                                </div>
                            <?php } ?>

                            <div class="col-md-8 mb-4">
                                <label for="logo" class="form-label"><?= $vendor && $vendor->logo ? 'Change' : null; ?> Logo <i class="text-danger">*</i></label>
                                <input form="store_form" type="file" name="logo" id="logo" class="form-control">
                                <span class="text-help">Upload a logo for your restaurant. Logo size is (300x300) pixels.</span>
                            </div>

                            <?php if ($vendor && $vendor->logo) { ?>
                                <div class="col-md-4 my-4">
                                    <img src='assets/images/vendor/<?= $vendor->logo ?>' alt='' class='img-fluid' style="height: 105px; object-fit: cover;">
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" form="store_form" class="btn">Submit Store</button>
                </div>
            </form>
        </div>
    </div>
</div>