<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$wallets = new General('wallets');

$wallet = $user->getWallet();

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
            <h4 class="mb-0 mx-auto pr-40">Wallet Details</h4>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p class="fs-16p mb-5">
                    Fund your wallet to make your meal ordering process even faster.</p>
            </div>

            <div class="col-12 mb-5">
                <div class="card bg-black">
                    <div class="card-body">
                        <p class="text-white fw-bold mb-1">Balance</p>
                        <div class="hstack">
                            <h1 class="text-white balance"><?= $wallet ? Helpers::format_currency($wallet->balance) : null; ?></h1>
                            <i class="fa fa-eye-slash hide-eyedropper ms-auto text-white" data-value="<?= Helpers::format_currency($wallet->balance); ?>" data-elem=".balance"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4">
                <p class="fw-bold mb-3">Fund your wallet with card</p>
                <div class="bg-white shadow rounded mb-5 bg-white p-4">
                    <form action="" method="post" class="form-inline">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary--light small border-end-0 ps-3" id="basic-addon1">₦</span>
                            <input type="text" name="amount" id="fund-amount" class="form-control border-start-0" placeholder="Enter amount to fund">
                        </div>
                        <button type="submit" id='fund-wallet' class="btn w-100">Fund Now</button>
                    </form>
                </div>
            </div>

            <div class="col-12">
                <p class="fw-bold mb-3">Fund your wallet via transfer</p>
                <div class="bg-white shadow rounded mb-5 bg-white p-4">
                    <div class="hstack mb-3 align-items-center">
                        <svg id="Bank" xmlns="http://www.w3.org/2000/svg" width="42" height="20" viewBox="0 0 42 39.53">
                            <path id="Path_1088" data-name="Path 1088" d="M39.529,33.765V31.294H37.059V16.471h2.471V14H32.118v2.471h2.471V31.294H27.176V16.471h2.471V14H22.235v2.471h2.471V31.294H17.294V16.471h2.471V14H12.353v2.471h2.471V31.294H7.412V16.471H9.882V14H2.471v2.471H4.941V31.294H2.471v2.471H0v2.471H42V33.765Z" transform="translate(0 3.294)" />
                            <path id="Path_1089" data-name="Path 1089" d="M19.765,0h2.471L42,12.353v2.471H0V12.353Z" />
                        </svg>
                        <p class="mb-0">
                            First Bank
                        </p>
                    </div>

                    <div class="bg-primary p-3 rounded">
                        <h2 class="mb-0 text-white">3058761193</h2>
                    </div>

                </div>
            </div>

        </div>
    </div>

</section>