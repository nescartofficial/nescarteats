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
            <h4 class="mb-0 mx-auto pr-40">Preferences</h4>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="fs-16p mb-5">
                    Toggle between the options below to customize your experience on Nescart Eats App</p>


                <div class="shadow rounded mb-5 bg-white px-4 py-3 d-flex justify-content-between align-items-center">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 42.471 37.162">
                            <path id="message" d="M45.671,11.709V30.29A5.324,5.324,0,0,1,40.362,35.6H29.744v7.963L19.127,35.6H8.509A5.322,5.322,0,0,1,3.2,30.29V11.709A5.322,5.322,0,0,1,8.509,6.4H40.362a5.324,5.324,0,0,1,5.309,5.309Z" transform="translate(-3.2 -6.4)" />
                        </svg>
                        <label class="fs-18p fw-bold ms-2 mb-0 form-check-label" for="email">Email Alert</label>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="email">
                        </div>
                    </div>
                </div>

                <div class=" shadow rounded mb-5 bg-white px-4 py-3 d-flex justify-content-between align-items-center">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 42.471 37.162">
                            <path id="message" d="M45.671,11.709V30.29A5.324,5.324,0,0,1,40.362,35.6H29.744v7.963L19.127,35.6H8.509A5.322,5.322,0,0,1,3.2,30.29V11.709A5.322,5.322,0,0,1,8.509,6.4H40.362a5.324,5.324,0,0,1,5.309,5.309Z" transform="translate(-3.2 -6.4)" />
                        </svg>
                        <label class="fs-18p fw-bold ms-2 mb-0 form-check-label" for="sms">SMS Notifications</label>
                    </div>
                    <div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="sms">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>