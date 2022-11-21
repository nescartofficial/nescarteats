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
            <h4 class="mb-0 mx-auto pr-40">Subscription</h4>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="fs-16p mb-5">
                    Select a monthly meal package and begin to enjoy daily delicious meals delivered to you.</p>


                <section class="subscription-slider mb-6">
                    <div class="card shadow mb-3">
                        <div class="card-body text-center py-5">
                            <h2 class="mb-4">Premium</h2>

                            <p class="mb-4">Get two meals per day delivered to you.</p>

                            <h1 class="text-accent mb-4">N19,500.00</h1>

                            <button class="btn bg-accent mt-2"> Select Package</button>
                        </div>
                    </div>
                    <div class="card bg-primary shadow mb-3">
                        <div class="card-body text-center py-5">
                            <h2 class="text-white mb-4">Premium</h2>

                            <p class="text-white mb-4">Get two meals per day delivered to you.</p>

                            <h1 class="text-white mb-4">N19,500.00</h1>

                            <button class="btn bg-accent mt-2"> Select Package</button>
                        </div>
                    </div>
                    <div class="card bg-black shadow mb-3">
                        <div class="card-body text-center py-5">
                            <h2 class="text-white mb-4">Premium</h2>

                            <p class="text-white mb-4">Get two meals per day delivered to you.</p>

                            <h1 class="text-white mb-4">N19,500.00</h1>

                            <button class="btn bg-accent mt-2"> Select Package</button>
                        </div>
                    </div>
                </section>

                <div class="card-body bg-white shadow rounded py-3 mb-5">
                    <p class="fw-bold mb-0">
                        Current Plan</p>
                    <p class="mb-0 fs-16p">
                        You are not on any subscription plan</p>
                </div>

                <div class="card-body bg-white shadow rounded py-3 mb-5">
                    <p class="fw-bold mb-0">
                        Meals Plan History</p>
                    <p class="mb-0 fs-16p">
                        You are not on any subscription Plan</p>
                </div>

            </div>
        </div>
    </div>
</section>