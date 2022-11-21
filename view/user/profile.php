<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$world = new World();

$us = $user->data();
$profile = $user->getProfile();
$countries = $world->getCountries();

$form_data = Session::exists('profile_fd') ? Session::get('profile_fd') : null;

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
            <h4 class="mb-0 mx-auto pr-40">Profile Details</h4>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="fs-16p mb-5">
                    Below are your personal details shared on Nescart Eats, you can edit and make changes if needed.</p>

                <form action="controllers/profile.php" name="profile_form" id="profile_form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="rq" value="<?= $profile ? 'edit' : 'setup'; ?>">
                    <input type="hidden" name="token" value="<?php echo Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                    <input type="hidden" name="id" value="<?= $user->data()->id; ?>">

                    <div class="row">
                        <div class="col-6 mb-4">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" value="<?= $us ? $us->first_name : ($form_data ? $form_data['first_name'] : null); ?>" id="first_name" class="form-control" placeholder="" required>
                        </div>
                        <div class="col-6 mb-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" value="<?= $us ? $us->last_name : ($form_data ? $form_data['last_name'] : null); ?>" id="last_name" class="form-control" placeholder="" required>
                        </div>
                    </div>
                    <div class="row align-items-center ">
                        <div class="col-md-6 mb-4">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="<?= $us ? $us->phone : ($form_data ? $form_data['phone'] : null); ?>" id="phone" class="form-control" placeholder="" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" value="<?= $profile ? $profile->address : ($form_data ? $form_data['address'] : null); ?>" id="address" class="form-control" placeholder="" required>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label" for="country">Country</label>
                            <select name="country" id="country" data-type="country" data-world-target="#seller-state" class="world select2 form-control">
                                <?php if ($countries) { ?>
                                    <option value="">Select Country</option>
                                    <?php foreach ($countries as $k => $v) { ?>
                                        <option value="<?= $v->id ?>" <?= $profile && $profile->country == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label" for="state">State</label>
                            <select name="state" id="seller-state" data-type="state" data-world-target="#seller-city" data-selected="<?= $profile ? $profile->state : null; ?>" class="world form-control">
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
                            <label class="form-label" for="city">City</label>
                            <select name="city" id="seller-city" data-selected="<?= $profile ? $profile->city : null; ?>" class="city form-control">
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

                        <?php if ($profile) { ?>
                            <div class="col col-md-2 mb-0">
                                <img src='assets/images/profile/<?= $profile->image ?>' alt='' class='img-fluid' style='height: 80px; width: 80px;'>
                            </div>
                        <?php } ?>

                        <div class="col-md-10 my-4">
                            <label for="cover" class="d-block">Profile Picture image</label>
                            <input type="file" name="cover" id="cover" class="form-control">
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn bg-primary w-100">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>