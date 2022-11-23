<?php
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$pagination = new Pagination();
$world = new World();
$banks = new General('banks');
$addresses = new General('addresses');
$orders = new General('orders');
$products = new General('products');
$vendors = new General('vendors');
$user_banks = new General('user_banks');

$items = $user->getAll(1, 'vendor', "=");
$title = "All vendors";
if (Input::get('show') && Input::get('show') == 'verified') {
    $items = array_filter($items, function ($us) use ($vendors) {
        return $vendors->get($us->id, 'user_id')->is_verified;
    });

    $title = "Verified vendors";
}
if (Input::get('show') && Input::get('show') == 'unverified') {
    $items = array_filter($items, function ($us) use ($vendors) {
        return !($vendors->get($us->id, 'user_id')->is_verified);
    });

    $title = "Unverified vendors";
}

// Header Counter
$total_count = $pagination->countAll('vendors', "WHERE id > 0");
$verified_count = $pagination->countAll('vendors', "WHERE id > 0 AND is_verified = 1");
$unverified_count = $pagination->countAll('vendors', "WHERE id > 0 AND is_verified = 0 OR is_verified IS NULL");


$profile = Input::get('action') && Input::get('action') == 'view' && Input::get('sub') && is_numeric(Input::get('sub')) ? $vendors->get(Input::get('sub')) : null;
$vuser = $profile ? $user->get($profile->user_id) : null;
$vendor = $profile ? $user->getVendor($profile->user_id) : null;

$verification = $profile ? $user->getVerification($profile->user_id, 'user_id') : null;
$vproducts = $vuser ? $products->getPages(6, 0, "WHERE seller_id = {$vendor->id}", "ORDER BY date_added DESC") : null;
$vorders = $vendor ? $orders->getPages(6, 0, "WHERE id > 0 AND details LIKE '%seller_:_{$vendor->id}_%' ", "ORDER BY date_added DESC") : null;
$vaddresses = $vuser ? $addresses->getAll($vuser->id, 'user_id', '=') : null;

$vendor_bank = $vendor ? $user_banks->get($vendor->user_id, 'user_id') : null;

$vendor_wallet = $vendor ? $user->getWallet($vendor->user_id) : null;
if ($vendor_wallet) {
    $payout_balance = $vendor_wallet->payout_balance;
    $current_earning = $vendor_wallet->current_earning;
    $total_payout = $vendor_wallet->total_payout;
}

Alerts::displayError();
Alerts::displaySuccess();
?>

<?php if ($vuser) { ?>
    <div id="top" class="sa-app__body">
        <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
            <div class="container container--max--xl">
                <div class="py-5">
                    <div class="row g-4 align-items-center">
                        <div class="col">
                            <nav class="mb-2" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-sa-simple">
                                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="vendors">vendors</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $vuser->first_name . ' ' . $vuser->last_name; ?></li>
                                </ol>
                            </nav>
                            <h1 class="h3 m-0"><?= $vuser->first_name . ' ' . $vuser->last_name; ?></h1>
                        </div>
                        <?php if (!$vendor->is_verified) { ?>
                            <div class="col-auto d-flex">
                                <a onclick="return confirm('Are you sure to activate seller?');" href="controllers/vendors.php?rq=verify&id=<?= $vendor->id; ?>" class="btn btn-secondary me-3">Activate Seller</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="sa-entity-layout" data-sa-container-query="{&quot;920&quot;:&quot;sa-entity-layout--size--md&quot;}">
                    <div class="sa-entity-layout__body">
                        <div class="sa-entity-layout__sidebar">
                            <div class="card">
                                <div class="card-body d-flex flex-column align-items-center">
                                    <div class="pt-3">
                                        <div class="sa-symbol sa-symbol--shape--circle" style="--sa-symbol--size:6rem">
                                            <?php if ($vendor && isset($vendor->image) && $vendor->image != 'user-avatar.jpg') { ?>
                                                <div class="sa-symbol sa-symbol--shape--rounded sa-symbol--size--lg">
                                                    <img src="<?= SITE_URL ?>assets/images/profile/<?= $vendor->image; ?>" width="96" height="96" alt="" style="object-fit: cover;" />
                                                </div>
                                            <?php } else { ?>
                                                <div class="d-flex align-items-center justify-content-center bg-secondary sa-symbol--shape--rounded" style="width: 96px; height: 96px;">
                                                    <span class="text-muted fs-exact-13"><?= $vuser->first_name[0] . $vuser->last_name[0]; ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <div class="fs-exact-16 fw-medium"><?= $vuser->first_name . ' ' . $vuser->last_name; ?></div>
                                        <div class="fs-exact-13 text-muted">
                                            <div class="mt-1"><a href="#"><?= $vuser->email; ?></a></div>
                                            <div class="mt-1"><?= $profile->phone; ?></div>
                                        </div>
                                    </div>
                                    <div class="sa-divider my-5"></div>
                                    <div class="w-100">
                                        <dl class="list-unstyled m-0">
                                            <dt class="fs-exact-14 fw-medium">Shop</dt>
                                            <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $profile->name; ?></dd>
                                            <dd class="fs-exact-13 text-muted mb-0 mt-1"><a href="<?= SITE_URL ?>store/<?= $profile->slug; ?>" target="_blank">visit store</a></dd>
                                        </dl>
                                        <dl class="list-unstyled m-0 mt-4">
                                            <dt class="fs-exact-14 fw-medium">Last Product</dt>
                                            <dd class="fs-exact-13 text-muted mb-0 mt-1">
                                                <?php if ($vproducts) { ?>
                                                    <?= $vproducts ? date_diff(date_create($vproducts[0]->date_added), date_create())->d : '-'; ?> days ago – <a href="<?= SITE_URL ?>product/<?= $vproducts[0]->slug; ?>" target="_blank"><?= $vproducts ? $vproducts[0]->title : '-' ?></a>
                                                <?php } else { ?>
                                                    Nil
                                                <?php } ?>
                                            </dd>
                                        </dl>
                                        <dl class="list-unstyled m-0 mt-4">
                                            <dt class="fs-exact-14 fw-medium">Total Order Value</dt>
                                            <dd class="fs-exact-13 text-muted mb-0 mt-1">0</dd>
                                        </dl>
                                        <dl class="list-unstyled m-0 mt-4">
                                            <dt class="fs-exact-14 fw-medium">Registered</dt>
                                            <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= date_format(date_create($vuser->joined), 'M d, Y'); ?></dd>
                                        </dl>
                                        <dl class="list-unstyled m-0 mt-4">
                                            <dt class="fs-exact-14 fw-medium">Phone OTP</dt>
                                            <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $vuser->phone_otp ? $vuser->phone_otp : 'verified'; ?></dd>
                                        </dl>
                                        <dl class="list-unstyled m-0 mt-4">
                                            <dt class="fs-exact-14 fw-medium">Email Verification</dt>
                                            <dd class="fs-exact-13 text-muted mb-0 mt-1"><?= $vuser->email_token && $vuser->email_token != 'verified' ? "<a href=''>Verify</a>" : 'verified'; ?></dd>
                                        </dl>
                                        <dl class="list-unstyled m-0 mt-4">
                                            <dt class="fs-exact-14 fw-medium">Email Marketing</dt>
                                            <dd class="fs-exact-13 text-muted mb-0 mt-1">Subscribed</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sa-entity-layout__main">
                            <div class="card">
                                <div class="card-body px-5 py-4 d-flex align-items-center justify-content-between">
                                    <h2 class="mb-0 fs-exact-18 me-4">Latest Orders</h2>
                                    <!-- <div class="text-muted fs-exact-14 text-end">Total spent $34,980.34 on 7 orders</div> -->
                                </div>
                                <div class="table-responsive">
                                    <table class="sa-table text-nowrap">
                                        <tbody>
                                            <?php if ($vorders) { ?>
                                                <?php foreach ($vorders as $k => $v) {

                                                    $total_commission = $total_amount = 0;
                                                    $details = json_decode($v->details);
                                                    foreach ($details as $kk => $vv) {
                                                        if ($vv->seller == $vendor->id) {
                                                            $total_amount += $vv->commision_amount;
                                                            $total_commission += $vv->mp_amount;
                                                        }
                                                    }

                                                    $status = $v->status == 1 ? 'Pending' : null;
                                                    $status_color = $v->status == 1 ? 'danger' : null;
                                                    $status = $v->status == 2 ? 'Accepted' : $status;
                                                    $status_color = $v->status == 2 ? 'warning' : $status_color;
                                                    $status = $v->status == 3 ? 'Delivered' : $status;
                                                    $status_color = $v->status == 3 ? 'success' : $status_color;
                                                    $status = $v->status == 0 ? 'Rejected' : $status;
                                                ?>
                                                    <tr>
                                                        <td><b><a href="orders/view/<?= $v->order_id; ?>" class="text-reset"><?= $v->order_id; ?></a></b></td>
                                                        <td><?= date_format(date_create($v->created), 'Y-m-d') ?></td>
                                                        <td>
                                                            <div class="d-flex fs-6">
                                                                <div class="badge badge-sa-<?= $status_color ?>"><?= $status ?></div>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                        <td>
                                                            <div class="sa-price"><span class="sa-price__integer"><?= Helpers::format_currency($total_amount) ?></span><span class="sa-price__decimal">.00</span></div>
                                                        </td>
                                                        <td>
                                                            <div class="sa-price"><span class="sa-price__integer"><?= Helpers::format_currency($total_commission) ?></span><span class="sa-price__decimal">.00</span></div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sa-divider"></div>
                                <div class="px-5 py-4 text-center">
                                    <a href="orders?seller=<?= $vendor->id; ?>">View all Orders</a>
                                </div>
                            </div>

                            <div class="card mt-5">
                                <div class="card-body px-5 py-4 d-flex align-items-center justify-content-between">
                                    <h2 class="mb-0 fs-exact-18 me-4">Latest Product</h2>
                                    <!-- <div class="text-muted fs-exact-14 text-end">Total spent $34,980.34 on 7 orders</div> -->
                                </div>
                                <div class="table-responsive">
                                    <table class="sa-table text-nowrap">
                                        <tbody>
                                            <?php if ($vproducts) { ?>
                                                <?php foreach ($vproducts as $k => $v) {
                                                    $status = $v->status == 1 ? 'Pending' : null;
                                                    $status = $v->status == 2 ? 'Accepted' : $status;
                                                    $status = $v->status == 3 ? 'Delivered' : $status;
                                                    $status = $v->status == 0 ? 'Rejected' : $status;
                                                ?>
                                                    <tr>
                                                        <td><a href="<?= SITE_URL ?>product/<?= $v->slug; ?>" target="_blank"><?= $v->title; ?></a></td>
                                                        <td><?= date_format(date_create($v->date_added), 'M d, Y'); ?></td>
                                                        <td><?= $status; ?></td>
                                                        <td></td>
                                                        <td><?= Helpers::format_currency($v->price); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sa-divider"></div>
                                <div class="px-5 py-4 text-center">
                                    <a href="products?seller=<?= $vendor->id; ?>">View all products</a>
                                </div>
                            </div>

                            <!-- Addresses -->
                            <div class="card mt-5">
                                <div class="card-body px-5 py-4 d-flex align-items-center justify-content-between">
                                    <h2 class="mb-0 fs-exact-18 me-4">Addresses</h2>
                                    <!-- <div class="text-muted fs-exact-14"><a href="#">New address</a></div> -->
                                </div>

                                <?php if ($profile) { ?>
                                    <div class="sa-divider"></div>
                                    <div class="px-5 py-3 my-2 d-flex align-items-center justify-content-between">
                                        <div>
                                            <div><?= $vuser->first_name . ' ' . $vuser->last_name; ?></div>
                                            <div class="text-muted fs-exact-14 mt-1"><?= $profile->address; ?>, <?= $world->getCityName($profile->city) . ', ' . $world->getStateName($profile->state) . '<br/>' . $world->getCountryName($profile->country); ?></div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($vaddresses) { ?>
                                    <?php foreach ($vaddresses as $k => $v) { ?>
                                        <div class="sa-divider"></div>
                                        <div class="px-5 py-3 my-2 d-flex align-items-center justify-content-between">
                                            <div>
                                                <div><?= $vuser->first_name . ' ' . $vuser->last_name; ?></div>
                                                <div class="text-muted fs-exact-14 mt-1">Random Federation 115302, Moscow ul. Varshavskaya, 15-2-178</div>
                                            </div>
                                            <div>
                                                <div class="dropdown"><button class="btn btn-sa-muted btn-sm" type="button" id="address-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                            <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                                        </svg></button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="address-context-menu-0">
                                                        <li><a class="dropdown-item" href="#">Edit</a></li>
                                                        <li><a class="dropdown-item" href="#">Duplicate</a></li>
                                                        <li><a class="dropdown-item" href="#">Add tag</a></li>
                                                        <li><a class="dropdown-item" href="#">Remove tag</a></li>
                                                        <li>
                                                            <hr class="dropdown-divider" />
                                                        </li>
                                                        <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>

                            <!-- Payout Account -->
                            <div class="card mt-5">
                                <div class="card-body px-5 py-4 d-flex align-items-center justify-content-between">
                                    <h2 class="mb-0 fs-exact-18 me-4">Payout Account</h2>
                                </div>

                                <?php if ($vendor_bank) { ?>
                                    <div class="sa-divider"></div>
                                    <div class="px-5 py-3 my-2 d-flex align-items-center justify-content-between">
                                        <div>
                                            <div><?= $banks->get($vendor_bank->bank)->name; ?></div>
                                            <div class="text-muted fs-exact-14 mt-1">
                                                <?= $vendor_bank->account_name; ?><br />
                                                <?= $vendor_bank->account_number ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="sa-divider"></div>
                                    <div class="px-5 py-3 my-2 d-flex align-items-center justify-content-between">
                                        <div class="text-muted fs-exact-14 mt-1">
                                            No Payout Provided.
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- Payout Details -->
                            <div class="card mt-5" id="PayoutAmount">
                                <div class="card-body px-5 py-4 d-flex align-items-center justify-content-between">
                                    <h2 class="mb-0 fs-exact-18 me-4">Payout Amount</h2>
                                </div>

                                <div class="sa-divider"></div>
                                <div class="px-5 py-3 my-2 d-md-flex align-items-center justify-content-between">
                                    <div>Total Payout Paid: <b><?= Helpers::format_currency($total_payout); ?></b></div>
                                    <div>Current Earning: <b><?= Helpers::format_currency($current_earning); ?></b></div>
                                    <div class="mb-3 mb-md-0">Payout Balance: <b><?= Helpers::format_currency($payout_balance); ?></b></div>

                                    <?php if ($payout_balance) { ?>
                                        <a onclick="return confirm('Are you sure you to pay?')" class="btn btn-primary" href="controllers/payouts.php?rq=single-payout&id=<?= $vendor_wallet->id ?>">Pay Balance</a>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Verification -->
                            <div class="card mt-5">
                                <div class="card-body px-5 py-4 d-flex align-items-center justify-content-between">
                                    <h2 class="mb-0 fs-exact-18 me-4">Verification</h2>
                                </div>
                                <?php if ($verification) { ?>
                                    <div class="px-5 py-3 my-2 d-flex align-items-center justify-content-between">
                                        <span class="">CAC: <?= $verification->cac; ?></span>
                                        <a href="<?= SITE_URL ?>assets/images/cac/<?= $verification->cac_file; ?>" target="_blank" rel="noopener noreferrer"><img src="<?= SITE_URL; ?>assets/images/cac/<?= $verification->cac_file; ?>" alt="" class="img-fluid" style="height:50px"></a>
                                    </div>
                                    <div class="px-5 py-3 my-2 d-flex align-items-center justify-content-between">
                                        <span class="">TIN: <?= $verification->tin; ?></span>
                                        <a href="<?= SITE_URL ?>assets/images/tin/<?= $verification->tin_file; ?>" target="_blank" rel="noopener noreferrer"><img src="<?= SITE_URL; ?>assets/images/tin/<?= $verification->tin_file; ?>" alt="" class="img-fluid" style="height:50px"></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div id="top" class="sa-app__body">
        <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
            <div class="container">
                <div class="py-5">
                    <div class="row g-4 align-items-center">
                        <div class="col">
                            <nav class="mb-2" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-sa-simple">
                                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">vendors</li>
                                </ol>
                            </nav>
                            <h1 class="h3 m-0">vendors</h1>
                        </div>
                        <!-- <div class="col-auto d-flex"><a href="app-customer.html" class="btn btn-primary">New customer</a></div> -->
                    </div>
                </div>

                <!-- Header -->
                <div class="row gy-5 mb-6">
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Total vendors</h2>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $total_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Verified vendors</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="vendors?show=verified" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $verified_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Unverified vendors</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="vendors?show=unverified" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $unverified_count ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card">
                    <div class="p-4">
                        <h5><?= $title; ?></h5>
                    </div>

                    <div class="p-4"><input type="text" placeholder="Start typing to search for vendors" class="form-control form-control--search mx-auto" id="table-search" /></div>
                    <div class="sa-divider"></div>
                    <table class="sa-datatables-init" data-order="[[ 1, &quot;desc&quot; ]]" data-sa-search-input="#table-search">
                        <thead>
                            <tr>
                                <th class="min-w-20x">Name</th>
                                <th>Registered</th>
                                <th>OTP</th>
                                <th>Menus</th>
                                <th>Verified</th>
                                <th>Status</th>
                                <th class="w-min" data-orderable="false"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $k => $v) {
                                if ($v->group > 1) {
                                    continue;
                                }

                                $vendor = $vendors->get($v->id, 'user_id');
                                $profile = $user->getProfile($v->id);
                                $product_count = $vendor ?  $pagination->countAll('menus', "WHERE id > 0 AND user_id = {$vendor->user_id}") : 0;
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="vendors/view/<?= $vendor->id ?>" class="me-4">
                                                <?php if ($profile && $profile->image && $profile->image != 'user-avatar.jpg') { ?>
                                                    <div class="sa-symbol sa-symbol--shape--rounded sa-symbol--size--lg">
                                                        <img src="<?= SITE_URL ?>assets/images/profile/<?= $profile->image; ?>" width="40" height="40" alt="" style="object-fit: cover;" />
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="d-flex align-items-center justify-content-center bg-secondary sa-symbol--shape--rounded" style="width: 40px; height: 40px;">
                                                        <span class="text-muted fs-exact-13"><?= $v->first_name[0] . $v->last_name[0]; ?></span>
                                                    </div>
                                                <?php } ?>
                                            </a>
                                            <div>
                                                <a href="vendors/view/<?= $vendor->id ?>" class="text-reset"><?= $vendor ? $vendor->name : 'nil'; ?></a>
                                                <div class="text-muted mt-n1"><?= $v->email; ?></div>
                                                <div class="text-muted mt-n1"><?= $v->first_name . ' ' . $v->last_name; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-nowrap"><?= date_format(date_create($v->joined), 'Y-m-d'); ?></td>
                                    <td class="text-nowrap"><?= $v->phone_otp; ?></td>
                                    <td><?= $vendor ? $product_count : '-'; ?></td>
                                    <td>
                                        <?php if ($vendor) { ?>
                                            <a onclick="return confirm('Are you sure to activate seller?');" href="controllers/vendors.php?rq=verify&id=<?= $vendor->id; ?>" class="px-3"><span class="badge bg-<?= $vendor->is_verified ? 'success' : 'danger'; ?>"><?= $vendor->is_verified ? 'yes' : 'no'; ?></a></span>
                                        <?php } else { ?>
                                            <span>Nil</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($vendor) { ?>
                                            <a href="controllers/vendors.php?rq=status&id=<?= $vendor->id; ?>" class=""><span class="badge bg-<?= $vendor->status ? 'success' : 'danger'; ?>"><?= $vendor->status ? 'public' : 'hidden'; ?></a></span>
                                        <?php } else { ?>
                                            <span>Nil</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($vendor) { ?>
                                            <div class="dropdown">
                                                <button class="btn btn-sa-muted btn-sm" type="button" id="customer-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                        <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                                    </svg></button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="customer-context-menu-0">
                                                    <li><a class="dropdown-item" href="vendors/view/<?= $vendor->id ?>">View</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider" />
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/vendors.php?rq=delete&id=<?= $vendor->id; ?>">Delete</a></li>
                                                </ul>
                                            </div>
                                        <?php } else { ?>
                                            <div class="dropdown">
                                                <button class="btn btn-sa-muted btn-sm" type="button" id="customer-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                        <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                                    </svg></button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="customer-context-menu-0">
                                                    <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/vendors.php?rq=delete-user&id=<?= $v->id; ?>">Delete</a></li>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>