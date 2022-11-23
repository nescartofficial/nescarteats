<?php
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$constants = new Constants();

Alerts::displayError();
Alerts::displaySuccess();
?>

<div id="top" class="sa-app__body px-2 px-lg-4">
    <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
        <div class="container container--max--xl">
            <div class="py-5">
                <div class="row g-4 align-items-center">
                    <div class="col">
                        <nav class="mb-2" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-sa-simple">
                                <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="general">General</a></li>
                            </ol>
                        </nav>
                        <h1 class="h3 m-0">General Settings</h1>
                    </div>
                    <div class="col-auto d-flex">
                        <button type="submit" form="page_form" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
            <form action="controllers/general.php" method="post" enctype="multipart/form-data" name="page_form" id="page_form">
                <input type="hidden" name="rq" value="update">
                <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">

                <div class="sa-entity-layout" data-sa-container-query="{&quot;920&quot;:&quot;sa-entity-layout--size--md&quot;,&quot;1100&quot;:&quot;sa-entity-layout--size--lg&quot;}">
                    <div class="sa-entity-layout__body">
                        <div class="sa-entity-layout__main">
                            <div class="card">
                                <div class="card-body p-5">
                                    <div class="mb-5">
                                        <h2 class="mb-0 fs-exact-18">Manage site settings</h2>
                                    </div>
                                    <div class="mb-4">
                                        <label for="form-category/balance_update" class="form-label">Balance Update Duration (In Minutes)</label>
                                        <input type="text" name="balance_update" value="<?= $constants->getText('BALANCE_UPDATE_DURATION') ?>" id="form-category/balance_update" class="form-control" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="form-category/payout_date" class="form-label">Upcoming Payout Date</label>
                                        <input type="date" name="payout_date" value="<?= $constants->getText('PAYOUT_DATE') ?>" id="form-category/payout_date" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sa-entity-layout__sidebar">
                            <div class="card w-100">
                                <div class="card-body p-5">
                                    <div class="mb-5">
                                        <h2 class="mb-0 fs-exact-18">Socials</h2>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="form-category/facebook" class="form-label">Facebook</label>
                                        <input type="url" name="facebook" value="<?= $constants->getText('FACEBOOK') ?>" id="form-category/facebook" class="form-control" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="form-category/twitter" class="form-label">Twitter</label>
                                        <input type="url" name="twitter" value="<?= $constants->getText('TWITTER') ?>" id="form-category/twitter" class="form-control" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="form-category/instagram" class="form-label">Instagram</label>
                                        <input type="url" name="instagram" value="<?= $constants->getText('INSTAGRAM') ?>" id="form-category/instagram" class="form-control" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="form-category/whatsapp" class="form-label">WhatsApp</label>
                                        <input type="url" name="whatsapp" value="<?= $constants->getText('WHATSAPP') ?>" id="form-category/whatsapp" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>