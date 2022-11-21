<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$world = new World();
$profiles = new General('profiles');
$sellers = new General('sellers');

$us = $user->data();
$profile = $profiles->get($user->data()->id, 'user_id');
$seller = $user->getVendor();
$countries = $world->getCountries();

$form_data = Session::exists('profile_fd') ? Session::get('profile_fd') : null;

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
            <h1 class="h3 mb-2">Seller’s Profile</h1>
            <p class="mb-1">You are currently providing information about yourself.</p>
            <p class="mb-0 font-weight-bold">Items marked with * are required fields.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mx-auto">
            <form action="controllers/profile.php" name="profile_form" id="profile_form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="rq" value="<?= $profile ? 'edit' : 'setup'; ?>">
                <input type="hidden" name="token" value="<?php echo Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                <input type="hidden" name="id" value="<?= $user->data()->id; ?>">
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3 fs-exact-18">Basic information</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="first_name" class="form-label">First Name <i class="text-danger">*</i></label>
                                <input type="text" name="first_name" value="<?= $us ? $us->first_name : ($form_data ? $form_data['first_name'] : null); ?>" id="first_name" class="form-control form-control-lg" placeholder="Input your first  name" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="last_name" class="form-label">Last Name <i class="text-danger">*</i></label>
                                <input type="text" name="last_name" value="<?= $us ? $us->last_name : ($form_data ? $form_data['last_name'] : null); ?>" id="last_name" class="form-control form-control-lg" placeholder="Input your last  name" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="phone" class="form-label">Phone <i class="text-danger">*</i></label>
                                <input type="text" name="phone" value="<?= $us ? $us->phone : ($form_data ? $form_data['phone'] : null); ?>" id="phone" class="form-control form-control-lg" placeholder="Input phone number (Digit Only)" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-end gap-5">
                                    <div>
                                        <label for="cover" class="d-block"><?= $profile ? 'Change' : null ?> Profile Picture <i class="text-danger">*</i></label>
                                        <input type="file" name="cover" id="cover" class="form-control">
                                        <span class="text-help">Upload a photo of yourself. Picture size is (300x300) pixels. </span>
                                    </div>
                                    <?php if ($profile) { ?>
                                        <img src='media/images/profile/<?= $profile->image ?>' alt='' class='img-fluid' style='height: 62px; width: 62px; object-fit:cover;'>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3 fs-exact-18">Location</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="country">Country <i class="text-danger">*</i></label>
                                <select name="country" id="country" data-type="country" data-world-target="#seller-state" data-placeholder="Select country" class="world sa-select2 form-control form-control-lg form-select">
                                    <?php if ($countries) { ?>
                                        <option value="">Select Country</option>
                                        <?php foreach ($countries as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $profile && $profile->country == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="state">State <i class="text-danger">*</i></label>
                                <select name="state" id="seller-state" data-type="state" data-world-target="#seller-city" data-placeholder="Select State" data-selected="<?= $profile ? $profile->state : null; ?>" class="world sa-select2 form-control form-control-lg form-select">
                                    <option value="">Select a country</option>
                                    <?php if ($profile && $profile->country) {
                                        $states = $world->getStatesByCountryId($profile->country);
                                    ?>
                                        <?php foreach ($states as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $profile && $profile->state == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>


                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="city">City <i class="text-danger">*</i></label>
                                <select name="city" id="seller-city" data-placeholder="Select City" data-selected="<?= $profile ? $profile->city : null; ?>" class="city sa-select2 form-control form-control-lg form-select">
                                    <option value="">Select a state</option>
                                    <?php if ($profile && $profile->state) {
                                        $cities = $world->getCitiesByStateId($profile->state);
                                    ?>
                                        <?php foreach ($cities as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $profile && $profile->city == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label for="address" class="form-label">Address <i class="text-danger">*</i></label>
                                <input type="text" name="address" value="<?= $profile ? $profile->address : ($form_data ? $form_data['address'] : null); ?>" id="address" class="form-control form-control-lg" placeholder="Input address" required>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mt-4">
                    <button type="submit" class="btn btn-primary text-white">Save Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>