<?php
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$profile = $user->getProfile();

Alerts::displayError();
Alerts::displaySuccess();
?>

<section class="container-fluid bg-primary py-5">
    <header class="container">
        <div class="d-flex align-items-center justify-contnt-between">
            <!-- Back -->
            <a href="#" id="back_button" class="d-flex p-2 border-0 rounded shadow bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 22.5 38.381">
                    <path id="chevron-left" d="M30.949,7.767a3.526,3.526,0,0,1,0,5.216l-12.4,12.932,12.4,12.932a3.516,3.516,0,0,1,0,5.207,3.93,3.93,0,0,1-5.344,0c-1.384-1.344-14.9-15.535-14.9-15.535a3.619,3.619,0,0,1,0-5.214S24.221,9.119,25.6,7.769a3.928,3.928,0,0,1,5.344,0Z" transform="translate(-9.6 -6.72)" />
                </svg>

            </a>
            <h4 class="mb-0 mx-auto pr-40 text-white">My Profile</h4>
        </div>

        <div class="text-center mt-4">

            <div class="mb-3 mx-auto icon-60 d-flex align-items-center justify-content-center">
                <?php if ($profile->image && $profile->image != 'user-avatar.jpg') { ?>
                    <img class="bg-white rounded-circle shadow img-sm mr-2" src="assets/images/profile/<?= $profile->image ?>" style="height: 60px; width: 60px; object-fit:cover; object-position:center; border-radius: 100%; padding: 2px;">
                <?php } else { ?>
                    <div style="background: white;display: flex; align-items:center; justify-content: center; height: 50px; width: 50px; object-fit:cover; object-position:center; border-radius: 100%;">
                        <span class="fw-bold fs-24p" style="color:white;"><?= $user->data()->first_name[0] ?></span>
                    </div>
                <?php } ?>
            </div>
            <h5 class="text-white mb-0"><?= $user->data()->first_name . ' ' . $user->data()->last_name; ?></h5>
            <p class="fs-12p mb-0"><?= $user->data()->email ?></p>
        </div>
    </header>
</section>

<section class="container-fluid br-top py-5">
    <div class="container">
        <div class="row gy-3">

            <!-- Profile -->
            <div class="col-12 col-lg-6">
                <a href="dashboard/profile">
                    <div class="rounded p-3 bg-white d-flex align-items-center">
                        <svg id="Profile" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 52.69 52.69">
                            <path id="Path_1041" data-name="Path 1041" d="M25.246,13.623A8.623,8.623,0,1,1,16.623,5,8.623,8.623,0,0,1,25.246,13.623Zm-4.312,0a4.312,4.312,0,1,1-4.312-4.312A4.312,4.312,0,0,1,20.935,13.623Z" transform="translate(9.722 4.098)" fill-rule="evenodd" />
                            <path id="Path_1042" data-name="Path 1042" d="M27.345,1A26.345,26.345,0,1,0,53.69,27.345,26.345,26.345,0,0,0,27.345,1ZM5.79,27.345a21.462,21.462,0,0,0,4.57,13.272,21.558,21.558,0,0,1,34.125-.2A21.555,21.555,0,1,0,5.79,27.345ZM27.345,48.9A21.465,21.465,0,0,1,13.76,44.08a16.768,16.768,0,0,1,27.365-.16A21.469,21.469,0,0,1,27.345,48.9Z" transform="translate(-1 -1)" fill-rule="evenodd" />
                        </svg>

                        <div class="flex-fill ms-3">
                            <h5 class="mb-0 fs-16p" style="line-height:0.8;">Profile</h5>
                            <span class="fs-12p text-muted">Manage your profile</span>
                        </div>

                        <svg id="chevron-right" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 31.393 52.867">
                            <path id="chevron-right-2" data-name="chevron-right" d="M10.586,10.216l5.232-5.287L41.979,31.363,15.818,57.8,10.586,52.51,31.514,31.363Z" transform="translate(-10.586 -4.929)" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Address -->
            <div class="col-12 col-lg-6">
                <a href="dashboard/address">
                    <div class="rounded p-3 bg-white d-flex align-items-center">
                        <svg id="Address" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 45.503 54.917">
                            <path id="Path_982" data-name="Path 982" d="M28.5,16.384A10.112,10.112,0,1,1,18.384,6.272,10.112,10.112,0,0,1,28.5,16.384Zm-5.056,0a5.056,5.056,0,1,1-5.056-5.056A5.056,5.056,0,0,1,23.44,16.384Z" transform="translate(5.055 7.055)" fill-rule="evenodd" />
                            <path id="Path_983" data-name="Path 983" d="M10.063,40.229a22.751,22.751,0,1,1,32.166-.79L26.541,55.917Zm28.5-4.276-12.2,12.816L13.55,36.568a17.7,17.7,0,1,1,25.018-.614Z" transform="translate(-3 -1)" fill-rule="evenodd" />
                        </svg>

                        <div class="flex-fill ms-3">
                            <h5 class="mb-0 fs-16p" style="line-height: 0.8;">Address</h5>
                            <span class="fs-12p text-muted">Manage your addresses</span>
                        </div>

                        <svg id="chevron-right" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 31.393 52.867">
                            <path id="chevron-right-2" data-name="chevron-right" d="M10.586,10.216l5.232-5.287L41.979,31.363,15.818,57.8,10.586,52.51,31.514,31.363Z" transform="translate(-10.586 -4.929)" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Wallet -->
            <div class="col-12 col-lg-6">
                <a href="dashboard/wallet">
                    <div class="rounded p-3 bg-white d-flex align-items-center">
                        <svg id="Wallet" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 52.116 39.087">
                            <path id="Path_346" data-name="Path 346" d="M4,10.172A2.172,2.172,0,0,1,6.172,8h8.686a2.172,2.172,0,1,1,0,4.343H6.172A2.172,2.172,0,0,1,4,10.172Z" transform="translate(4.686 2.858)" />
                            <path id="Path_347" data-name="Path 347" d="M8.686,3A8.686,8.686,0,0,0,0,11.686V33.4a8.686,8.686,0,0,0,8.686,8.686H43.43A8.686,8.686,0,0,0,52.116,33.4V11.686A8.686,8.686,0,0,0,43.43,3ZM43.43,7.343H8.686a4.343,4.343,0,0,0-4.343,4.343v15.2h43.43v-15.2A4.343,4.343,0,0,0,43.43,7.343ZM47.773,31.23H4.343V33.4a4.343,4.343,0,0,0,4.343,4.343H43.43A4.343,4.343,0,0,0,47.773,33.4Z" transform="translate(0 -3)" fill-rule="evenodd" />
                        </svg>


                        <div class="flex-fill ms-3">
                            <h5 class="mb-0 fs-16p" style="line-height: 0.8;">Wallet</h5>
                            <span class="fs-12p text-muted">Manage your payment methods</span>
                        </div>

                        <svg id="chevron-right" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 31.393 52.867">
                            <path id="chevron-right-2" data-name="chevron-right" d="M10.586,10.216l5.232-5.287L41.979,31.363,15.818,57.8,10.586,52.51,31.514,31.363Z" transform="translate(-10.586 -4.929)" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Subscription -->
            <div class="col-12 col-lg-6">
                <a href="dashboard/subscriptions">
                    <div class="rounded p-3 bg-white d-flex align-items-center">
                        <svg id="Supscription" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 40.844 44.199">
                            <path id="Path_1281" data-name="Path 1281" d="M4.561,22.321,9.483,3.95,14.7,9.166a24.716,24.716,0,0,1,30.625,3.449L41.443,16.5a19.225,19.225,0,0,0-22.731-3.32l4.22,4.221Z" transform="translate(-4.561 -3.95)" />
                            <path id="Path_1282" data-name="Path 1282" d="M45.354,13.358,40.432,31.729l-5.216-5.216A24.716,24.716,0,0,1,4.59,23.064l3.882-3.882A19.225,19.225,0,0,0,31.2,22.5l-4.22-4.221Z" transform="translate(-4.51 12.47)" />
                        </svg>


                        <div class="flex-fill ms-3">
                            <h5 class="mb-0 fs-16p" style="line-height: 0.8;">Subscriptions</h5>
                            <span class="fs-12p text-muted">Manage your meal subscription</span>
                        </div>

                        <svg id="chevron-right" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 31.393 52.867">
                            <path id="chevron-right-2" data-name="chevron-right" d="M10.586,10.216l5.232-5.287L41.979,31.363,15.818,57.8,10.586,52.51,31.514,31.363Z" transform="translate(-10.586 -4.929)" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Referral -->
            <div class="col-12 col-lg-6">
                <a href="dashboard/referral">
                    <div class="rounded p-3 bg-white d-flex align-items-center">
                        <svg id="Refferals" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 45.459 41.326">
                            <path id="gift" d="M35.167,3.816a6.2,6.2,0,0,0-8.767,0L23.479,6.738q-.187.187-.355.385-.167-.2-.354-.385L19.847,3.816a6.2,6.2,0,0,0-8.767,8.767L12.9,14.4H1V26.8H5.133v16.53H42.326V26.8h4.133V14.4H33.352l1.816-1.816A6.2,6.2,0,0,0,35.167,3.816Zm-5.844,8.767L32.245,9.66a2.066,2.066,0,0,0-2.922-2.922L26.4,9.66a2.066,2.066,0,1,0,2.922,2.922ZM19.847,9.66,16.925,6.738A2.066,2.066,0,0,0,14,9.66l2.922,2.922A2.066,2.066,0,0,0,19.847,9.66Zm22.479,8.87v4.133H5.133V18.53ZM25.624,26.8h12.57v12.4H25.624Zm-3.788,0v12.4H9.265V26.8Z" transform="translate(-1 -2)" fill-rule="evenodd" />
                        </svg>

                        <div class="flex-fill ms-3">
                            <h5 class="mb-0 fs-16p" style="line-height: 0.8;">Referral</h5>
                            <span class="fs-12p text-muted">Refer your friends and earn</span>
                        </div>

                        <svg id="chevron-right" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 31.393 52.867">
                            <path id="chevron-right-2" data-name="chevron-right" d="M10.586,10.216l5.232-5.287L41.979,31.363,15.818,57.8,10.586,52.51,31.514,31.363Z" transform="translate(-10.586 -4.929)" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Preferences -->
            <div class="col-12 col-lg-6">
                <a href="dashboard/preferences">
                    <div class="rounded p-3 bg-white d-flex align-items-center">
                        <svg id="Preferences" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 42.667 40.297">
                            <path id="Path_943" data-name="Path 943" d="M12.482,3a9.486,9.486,0,0,1,9.183,7.111H40.926v4.741H21.664A9.483,9.483,0,1,1,12.482,3Zm0,14.222a4.741,4.741,0,1,0-4.741-4.741A4.741,4.741,0,0,0,12.482,17.222Z" transform="translate(-3 -3)" fill-rule="evenodd" />
                            <path id="Path_944" data-name="Path 944" d="M33.445,30.963a9.486,9.486,0,0,1-9.183-7.111H5V19.111H24.262a9.483,9.483,0,1,1,9.183,11.852Zm0-4.741A4.741,4.741,0,1,0,28.7,21.482,4.741,4.741,0,0,0,33.445,26.222Z" transform="translate(-0.259 9.333)" fill-rule="evenodd" />
                        </svg>

                        <div class="flex-fill ms-3">
                            <h5 class="mb-0 fs-16p" style="line-height: 0.8;">Preferences</h5>
                            <span class="fs-12p text-muted">Customise Nescart to suit you.</span>
                        </div>

                        <svg id="chevron-right" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 31.393 52.867">
                            <path id="chevron-right-2" data-name="chevron-right" d="M10.586,10.216l5.232-5.287L41.979,31.363,15.818,57.8,10.586,52.51,31.514,31.363Z" transform="translate(-10.586 -4.929)" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Change Password -->
            <div class="col-12 col-lg-6">
                <a href="dashboard/change-password">
                    <div class="rounded p-3 bg-white d-flex align-items-center">
                        <svg id="Profile" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 52.69 52.69">
                            <path id="Path_1041" data-name="Path 1041" d="M25.246,13.623A8.623,8.623,0,1,1,16.623,5,8.623,8.623,0,0,1,25.246,13.623Zm-4.312,0a4.312,4.312,0,1,1-4.312-4.312A4.312,4.312,0,0,1,20.935,13.623Z" transform="translate(9.722 4.098)" fill-rule="evenodd" />
                            <path id="Path_1042" data-name="Path 1042" d="M27.345,1A26.345,26.345,0,1,0,53.69,27.345,26.345,26.345,0,0,0,27.345,1ZM5.79,27.345a21.462,21.462,0,0,0,4.57,13.272,21.558,21.558,0,0,1,34.125-.2A21.555,21.555,0,1,0,5.79,27.345ZM27.345,48.9A21.465,21.465,0,0,1,13.76,44.08a16.768,16.768,0,0,1,27.365-.16A21.469,21.469,0,0,1,27.345,48.9Z" transform="translate(-1 -1)" fill-rule="evenodd" />
                        </svg>

                        <div class="flex-fill ms-3">
                            <h5 class="mb-0 fs-16p" style="line-height: 0.8;">Change Password</h5>
                            <span class="fs-12p text-muted">Manage your security.</span>
                        </div>

                        <svg id="chevron-right" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 31.393 52.867">
                            <path id="chevron-right-2" data-name="chevron-right" d="M10.586,10.216l5.232-5.287L41.979,31.363,15.818,57.8,10.586,52.51,31.514,31.363Z" transform="translate(-10.586 -4.929)" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Sign Out -->
            <div class="col-12 col-lg-6">
                <a href="controllers/logout.php">
                    <div class="rounded p-3 bg-white d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 51.37 51.37">
                            <g id="Sign_Out" data-name="Sign Out" transform="translate(0 51.37) rotate(-90)">
                                <path id="Path_1233" data-name="Path 1233" d="M14.951,35.272a3.211,3.211,0,0,0,6.421,0V12.291L31.784,22.7l4.541-4.541L18.162,0,0,18.162,4.541,22.7,14.951,12.292Z" transform="translate(7.523)" />
                                <path id="Path_1234" data-name="Path 1234" d="M0,0H6.421V12.843H44.949V0H51.37V12.843a6.421,6.421,0,0,1-6.421,6.421H6.421A6.421,6.421,0,0,1,0,12.843Z" transform="translate(0 32.106)" />
                            </g>
                        </svg>


                        <div class="flex-fill ms-3">
                            <h5 class="mb-0 fs-16p" style="line-height: 0.8;">Sign Out</h5>
                            <span class="fs-12p text-muted">Ooops! you are leaving.</span>
                        </div>

                        <svg id="chevron-right" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 31.393 52.867">
                            <path id="chevron-right-2" data-name="chevron-right" d="M10.586,10.216l5.232-5.287L41.979,31.363,15.818,57.8,10.586,52.51,31.514,31.363Z" transform="translate(-10.586 -4.929)" />
                        </svg>
                    </div>
                </a>
            </div>


        </div>
    </div>
</section>