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

    <div class="mb-2">
        <div class="row g-4 align-items-center">
            <div class="col">
                <nav class="mb-2" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-sa-simple">
                        <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="sa-entity-layout" data-sa-container-query="{&quot;920&quot;:&quot;sa-entity-layout--size--md&quot;,&quot;1100&quot;:&quot;sa-entity-layout--size--lg&quot;}">
        <div class="sa-entity-layout__body">
            <div class="sa-entity-layout__main">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-0 fs-exact-18">Change Password</h5>
                        </div>
                        <form action="controllers/profile.php" method="post">
                            <input type="hidden" name="token" value="<?php echo Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                            <input type="hidden" name="rq" value="change-password">
                            <div class="mb-4">
                                <label for="password" class="form-label">Current Password</label>
                                <input type="text" name="password" id="password" class="form-control form-control-lg" placeholder="Input your current password" required>
                            </div>
                            <div class="mb-4">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="text" name="new_password" id="new_password" class="form-control form-control-lg" placeholder="Input new password" required>
                            </div>
                            <div class="">
                                <button type="submit" class="btn">Submit Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="sa-entity-layout__sidebar">
            </div>
        </div>
    </div>
