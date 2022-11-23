<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$world = new World();
$banks = new General('banks');

$bank_list = $banks->getAll(1, 'active', '=');
$vendor_bank = $user->getBank();
$countries = $world->getCountries();

$form_data = Session::exists('store_fd') ? Session::get('store_fd') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

    <div class="row g-4 align-items-center mb-4">
        <div class="col-md-12 mx-auto">
            <nav class="mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-sa-simple">
                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Payout Method</li>
                </ol>
            </nav>
            <h1 class="h3 mb-2">Payout Method</h1>
            <p class="mb-1">You are currently providing your Bank Account information to receive payout.</p>
            <p class="mb-0 font-weight-bold">Items marked with * are required fields.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mx-auto">
            <form action="controllers/verification.php" name="store_form" id="store_form" method="post" enctype="multipart/form-data" class="needs-validation" novalidate="">
                <input type="hidden" name="rq" value="bank">
                <input type="hidden" name="token" value="<?php echo Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                <input type="hidden" name="id" value="<?= $user->data()->id; ?>">

                <div class="card h-100 w-100 mb-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3 fs-exact-18">Bank Account</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="bank">Bank</label>
                                <select name="bank" id="bank" data-type="bank" data-placeholder="Select bank" data-world-target="#seller-state" class="world sa-select2 form-control form-control-lg form-select" required>
                                    <option value="">Select bank</option>
                                    <?php if ($bank_list) { ?>
                                        <?php foreach ($bank_list as $k => $v) { ?>
                                            <option value="<?= $v->id; ?>" <?= $vendor_bank && $vendor_bank->bank == $v->id ? 'selected' : null ?>><?= $v->name; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12"></div>
                            <div class="col-md-4 mb-4">
                                <label for="account_number" class="form-label">Account Number</label>
                                <input type="text" name="account_number" value="<?= $vendor_bank ? $vendor_bank->account_number : ($form_data ? $form_data['account_number'] : null); ?>" id="account_number" class="form-control" placeholder="Input account number" required>
                            </div>
                            <div class="col-12"></div>
                            <div class="col-md-4 mb-4">
                                <label for="account_name" class="form-label">Account Name</label>
                                <input type="text" name="account_name" value="<?= $vendor_bank ? $vendor_bank->account_name : ($form_data ? $form_data['account_name'] : null); ?>" id="account_name" class="form-control" placeholder="Input account name" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" form="store_form" class="btn">Save Bank Information</button>
                </div>
            </form>
        </div>
    </div>