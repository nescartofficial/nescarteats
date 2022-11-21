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
            <h4 class="mb-0 mx-auto pr-40">Referrals</h4>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="fs-16p mb-5">
                    Share your referral link to your friends and family to earn when they sign up and order meals</p>

                <div class="card bg-black shadow mb-5">
                    <div class="card-body">
                        <p class="text-white mb-0">Referral Earnings</p>
                        <h2 class="text-white mb-0"><?= Helpers::format_currency(0) ?></h2>
                    </div>
                </div>

                <div class="card shadow mb-5">
                    <div class="card-body">
                        <p class="mb-0">Referred Downlines</p>
                        <h2 class="mb-0"><?= 0 ?></h2>
                    </div>
                </div>

                <div class="card bg-primary--light shadow mb-5">
                    <div class="card-body">
                        <p class="mb-2">Referral Links</p>
                        <input type="text" value="<?= $user->data()->uid; ?>" class="form-control text-white bg-primary" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>