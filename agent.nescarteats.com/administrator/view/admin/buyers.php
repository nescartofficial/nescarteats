<?php
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$pagination = new Pagination();
$world = new World();
$users = new General('users');
$banks = new General('banks');
$orders = new General('orders');
$addresses = new General('addresses');



// Header Counter
$total_count = $pagination->countAll('users', "WHERE type = 'buyer'");
$spent_count = $pagination->countAll('orders', "WHERE user_id > 0 ");
$not_spent_count = $total_count - $spent_count;

$items = $user->getAll(0, 'vendor', "=");
$title = "All Buyers";

// Header Sorter
if (Input::get('spent')) {
    $query_string = '';
    $order_list = $orders->getAll(0, 'user_id', '>');
    foreach ($order_list as $k => $v) {
        $query_string .= Input::get('spent') == "yes" ? ' OR id=' . $v->user_id : ' AND id <> ' . $v->user_id;
    }
    $query_string = "WHERE type = 'buyer' " . $query_string;
    print_r($query_string);

    $items = $users->getPages(Input::get('spent') == "yes" ? $spent_count : $not_spent_count, 0, $query_string, "ORDER BY created DESC");
    $title = Input::get('spent') == "yes" ? "Buyers With Orders" : "Buyers without Orders";
}



$notif = Input::get('action') && Input::get('action') == 'notification' && Input::get('sub') && is_numeric(Input::get('sub')) ? $user->get(Input::get('sub')) : null;
$vuser = Input::get('action') && Input::get('action') == 'view' && Input::get('sub') && is_numeric(Input::get('sub')) ? $user->get(Input::get('sub')) : null;
$vorders = $vuser ? $orders->getPages(6, 0, "WHERE user_id = {$vuser->id}") : null;
$vaddresses = $vuser ? $addresses->getAll($vuser->id, 'user_id', '=') : null;
$profile = $vuser ? $user->getProfile($vuser->id) : null;


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
                                    <li class="breadcrumb-item"><a href="buyers">Buyers</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $vuser->first_name . ' ' . $vuser->last_name; ?></li>
                                </ol>
                            </nav>
                            <h1 class="h3 m-0"><?= $vuser->first_name . ' ' . $vuser->last_name; ?></h1>
                        </div>
                        <div class="col-auto d-flex">
                            <a onclick="return confirm('Are you sure to delete this item?');" href="controllers/buyers.php?rq=delete&id=<?= $vuser->id; ?>" class="btn btn-secondary me-3">Delete</a>
                        </div>
                    </div>
                </div>
                <div class="sa-entity-layout" data-sa-container-query="{&quot;920&quot;:&quot;sa-entity-layout--size--md&quot;}">
                    <div class="sa-entity-layout__body">
                        <div class="sa-entity-layout__sidebar">
                            <div class="card">
                                <div class="card-body d-flex flex-column align-items-center">
                                    <div class="pt-3">
                                        <div class="sa-symbol sa-symbol--shape--circle" style="--sa-symbol--size:6rem">
                                            <?php if ($profile->image && $profile->image != 'user-avatar.jpg') { ?>
                                                <div class="sa-symbol sa-symbol--shape--rounded sa-symbol--size--lg">
                                                    <img src="<?= SITE_URL ?>media/images/profile/<?= $seller->image; ?>" width="96" height="96" alt="" style="object-fit: cover;" />
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
                                            <div class="mt-1"><?= $profile ? $profile->phone : '-'; ?></div>
                                        </div>
                                    </div>
                                    <div class="sa-divider my-5"></div>
                                    <div class="w-100">
                                        <dl class="list-unstyled m-0">
                                            <dt class="fs-exact-14 fw-medium">Last Order</dt>
                                            <dd class="fs-exact-13 text-muted mb-0 mt-1">
                                                <?php if ($vorders) { ?>
                                                    <?= $vorders ? date_diff(date_create($vorders[0]->created), date_create())->d : '-'; ?> days ago – <a href="orders<?= $vorders ? '/view/' . $vorders[0]->order_id : null; ?>">#<?= $vorders ? $vorders[0]->invoice : '-' ?></a>
                                                <?php } else { ?>
                                                    Nil
                                                <?php } ?>
                                            </dd>
                                        </dl>
                                        <dl class="list-unstyled m-0 mt-4">
                                            <dt class="fs-exact-14 fw-medium">Average Order Value</dt>
                                            <dd class="fs-exact-13 text-muted mb-0 mt-1">
                                                <?php if ($vorders) { ?>
                                                    <?= $vorders ? date_diff(date_create($vorders[0]->created), date_create())->d : '-'; ?> days ago – <a href="orders<?= $vorders ? '/view/' . $vorders[0]->order_id : null; ?>">#<?= $vorders ? $vorders[0]->invoice : '-' ?></a>
                                                <?php } else { ?>
                                                    Nil
                                                <?php } ?>
                                            </dd>
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
                                    <?php if ($vorders) { ?>
                                        <!--<div class="text-muted fs-exact-14 text-end">Total spent $34,980.34 on 7 orders</div>-->
                                    <?php } ?>
                                </div>
                                <div class="table-responsive">
                                    <table class="sa-table text-nowrap">
                                        <tbody>
                                            <?php if ($vorders) { ?>
                                                <?php foreach ($vorders as $k => $v) {
                                                    $details = json_decode($v->details);
                                                    $status = $v->status == 1 ? 'Pending' : null;
                                                    $status = $v->status == 2 ? 'Accepted' : $status;
                                                    $status = $v->status == 3 ? 'Delivered' : $status;
                                                    $status = $v->status == 0 ? 'Rejected' : $status;
                                                ?>
                                                    <tr>
                                                        <td><a href="orders/view/<?= $v->order_id; ?>">#<?= $v->invoice; ?></a></td>
                                                        <td><?= date_format(date_create($v->created), 'M d, Y'); ?></td>
                                                        <td><?= $status; ?></td>
                                                        <td><? count($details); ?> items</td>
                                                        <td><?= Helpers::format_currency($v->total_amount); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if ($vorders) { ?>
                                    <div class="sa-divider"></div>
                                    <div class="px-5 py-4 text-center"><a href="orders?buyers=<?= $vuser->id; ?>">View all orders</a></div>
                                <?php } ?>
                            </div>

                            <!-- Address -->
                            <div class="card mt-5">
                                <div class="card-body px-5 py-4 d-flex align-items-center justify-content-between">
                                    <h2 class="mb-0 fs-exact-18 me-4">Addresses</h2>
                                    <!-- <div class="text-muted fs-exact-14"><a href="#">New address</a></div> -->
                                </div>

                                <?php if ($profile) { ?>
                                    <div class="sa-divider"></div>
                                    <div class="px-5 py-3 my-2 d-flex align-items-center justify-content-between">
                                        <div>
                                            <div>Main Address</div>
                                            <div class="text-muted fs-exact-14 mt-1"><?= $profile->address; ?>, <?= $world->getCityName($profile->city) . ', ' . $world->getStateName($profile->state) . '<br/>' . $world->getCountryName($profile->country); ?></div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($vaddresses) { ?>
                                    <?php foreach ($vaddresses as $k => $v) { ?>
                                        <div class="sa-divider"></div>
                                        <div class="px-5 py-3 my-2 d-flex align-items-center justify-content-between">
                                            <div>
                                                <div>Address <?= $k + 1 ?></div>
                                                <div class="text-muted fs-exact-14 mt-1"><?= $v->address; ?>, <?= $world->getCityName($v->city) . ', ' . $world->getStateName($v->state) . '<br/>' . $world->getCountryName($v->country); ?></div>
                                            </div>
                                        </div>
                                    <?php } ?>
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
                                    <li class="breadcrumb-item"><a href="index-2.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Buyers</li>
                                </ol>
                            </nav>
                            <h1 class="h3 m-0">Buyers</h1>
                        </div>
                        <div class="col-auto d-flex"><a href="app-customer.html" class="btn btn-primary">New customer</a></div>
                    </div>
                </div>

                <!-- Header -->
                <div class="row gy-5 mb-6">
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Total</h2>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $total_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Spent</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="buyers?spent=yes" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $spent_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Not Spent</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="buyers?spent=no" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $not_spent_count ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">

                    <div class="p-4">
                        <h4 class=""><?= $title ?></h4>
                    </div>

                    <div class="p-4"><input type="text" placeholder="Start typing to search for customers" class="form-control form-control--search mx-auto" id="table-search" /></div>
                    <div class="sa-divider"></div>
                    <table class="sa-datatables-init" data-order="[[ 1, &quot;desc&quot; ]]" data-sa-search-input="#table-search">
                        <thead>
                            <tr>
                                <th class="min-w-20x">Name</th>
                                <th>Registered</th>
                                <th>OTP</th>
                                <th>Spent</th>
                                <th class="w-min" data-orderable="false"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $k => $v) {
                                if ($v->group > 1) {
                                    continue;
                                }
                                $profile = $user->getProfile($v->id);
                                $total_spent = $profile ? $orders->getAllSumWhere("total_amount", "id > 0 AND user_id = {$profile->user_id}") : 0;
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="buyers/view/<?= $v->id ?>" class="me-4">
                                                <?php if ($profile && $profile->image && $profile->image != 'user-avatar.jpg') { ?>
                                                    <div class="sa-symbol sa-symbol--shape--rounded sa-symbol--size--lg">
                                                        <img src="<?= SITE_URL ?>media/images/profile/<?= $profile->image; ?>" width="40" height="40" alt="" style="object-fit: cover;" />
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="d-flex align-items-center justify-content-center bg-secondary sa-symbol--shape--rounded" style="width: 40px; height: 40px;">
                                                        <span class="text-muted fs-exact-13"><?= $v->first_name[0] . $v->last_name[0]; ?></span>
                                                    </div>
                                                <?php } ?>
                                            </a>
                                            <div><a href="buyers/view/<?= $v->id ?>" class="text-reset"><?= $v->first_name . ' ' . $v->last_name; ?></a>
                                                <div class="text-muted mt-n1"><?= $v->email; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-nowrap"><?= date_format(date_create($v->joined), 'Y-m-d'); ?></td>
                                    <td class="text-nowrap"><?= $v->phone_otp; ?></td>
                                    <td>
                                        <div class="sa-price"><span class="sa-price__symbol"><?= Helpers::format_currency($total_spent); ?></div>
                                    </td>
                                    <td>
                                        <div class="dropdown"><button class="btn btn-sa-muted btn-sm" type="button" id="customer-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                    <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                                </svg></button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="customer-context-menu-0">
                                                <li><a class="dropdown-item" href="buyers/view/<?= $v->id ?>">View</a></li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/buyers.php?rq=delete&id=<?= $v->id; ?>">Delete</a></li>
                                            </ul>
                                        </div>
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