<?php
include_once('core/init.php');
$User = isset($User) ? $User : new User();
!$User->isLoggedIn() ? Redirect::to('login') : null;
$world = new World();
$Menus = new Menus();
$pagination = new Pagination();
$sellers = new General('sellers');
$Orders = new Orders();
$products = new General('products');

$countries = $world->getCountries();
$agent = $User->getAgent();

// $Orders = $Orders->getAll($User->data()->id, 'supplier_user_id', '=');
$title = "All Agents";
$status_icon = "Orders.svg";
$title = Input::get('status') && Input::get('status') == 2 ? "Orders Awaiting Delivery" : $title;
$status_icon = Input::get('status') && Input::get('status') == 2 ? "Awaiting Delivery.svg" : $status_icon;
$title = Input::get('status') && Input::get('status') == 3 ? "Completed Orders" : $title;
$status_icon = Input::get('status') && Input::get('status') == 3 ? "Items Delivered.svg" : $status_icon;


$search = Input::get('order') ? Input::get('order') : null;
$searchTerm = $search ? "WHERE id > 0 " : "WHERE id > 0 AND account_type = 'agent' AND managed_by = '{$User->data()->uid}' ";


$next = Input::get('p') ? Input::get('p') : 1;
$per_page = 4;
$total_record = $pagination->countAll('users', $searchTerm);
$paginate = new Pagination($next, $per_page, $total_record);
$items = $total_record ? $User->getPages($per_page, $paginate->offset(), $searchTerm) : null;

// print_r($searchTerm); 

$enquiry = Input::get('sub') && Input::get('sub') == 'view' && Input::get('sub1') && is_numeric(Input::get('sub1')) ? $Orders->get(Input::get('sub1')) : null;
$enquiry_details = $enquiry ? json_decode($enquiry->details, true) : null;

// Counters
$awaiting_count = $delivered_count = 0;
$searchTerm = "WHERE id > 0 {$agent_like} AND status = 'picked'";
$awaiting_count = $pagination->countAll('orders', $searchTerm);
$searchTerm = "WHERE id > 0 {$agent_like} AND status = 'completed'";
$delivered_count = $pagination->countAll('orders', $searchTerm);

Alerts::displayError();
Alerts::displaySuccess();
?>

<div class="row">
    <?php if(Input::get('sub') || $item){ ?>
        <div class="col-md-12 mb-5">
            <div class="row g-4 align-items-center">
                <div class="col-12">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Agents</li>
                        </ol>
                    </nav>
                    <div class="d-flex justify-content-between">
                        <h1 class="h3 m-0">Add Agent</h1>
                        <a href="dashboard/agents" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-12 mx-auto">
            <form action="controllers/agents.php" name="profile_form" id="profile_form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="rq" value="<?= $item ? 'edit' : 'add'; ?>">
                <input type="hidden" name="token" value="<?php echo Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                <input type="hidden" name="id" value="<?= $User->data()->id; ?>">
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3 fs-exact-18">Basic information</h5>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" value="<?= $item ? $item->first_name : null; ?>" id="first_name" class="form-control" required>
                            </div>
                            
                            <div class="col-lg-6 mb-4">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" value="<?= $item ? $item->last_name : null; ?>" id="last_name" class="form-control" required>
                            </div>
                            
                            <div class="col-lg-6 mb-4">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone" value="<?= $item ? $item->phone : null; ?>" id="phone" class="form-control" required>
                            </div>
                            
                            <div class="col-lg-6 mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" value="<?= $item ? $item->email : null; ?>" id="email" class="form-control" required>
                            </div>
                            
                            <div class="col-lg-6 mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" name="password" value="<?= $item ? $item->password : null; ?>" id="password" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="mb-3 fs-exact-18">Location</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="country">Country <i class="text-danger">*</i></label>
                                <select name="country" id="country" data-type="country" data-world-target="#seller-state" data-placeholder="Select country" class="world sa-select2 form-control form-control-lg form-select">
                                    <?php if ($countries) { ?>
                                        <option value="">Select Country</option>
                                        <?php foreach ($countries as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $profile && $profile->country == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
    
                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="state">State <i class="text-danger">*</i></label>
                                <select name="state" id="seller-state" data-type="state" data-world-target="#seller-city" data-placeholder="Select State" data-selected="<?= $profile ? $profile->state : null; ?>" class="world sa-select2 form-control form-control-lg form-select">
                                    <option value="">Select a country</option>
                                    <?php if ($profile && $profile->country) {
                                        $states = $world->getStatesByCountryId($profile->country);
                                    ?>
                                        <?php foreach ($states as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $profile && $profile->state == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
    
    
                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="city">City <i class="text-danger">*</i></label>
                                <select name="city" id="seller-city" data-placeholder="Select City" data-selected="<?= $profile ? $profile->city : null; ?>" class="city sa-select2 form-control form-control-lg form-select">
                                    <option value="">Select a state</option>
                                    <?php if ($profile && $profile->state) {
                                        $cities = $world->getCitiesByStateId($profile->state);
                                    ?>
                                        <?php foreach ($cities as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $profile && $profile->city == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
    
                            <div class="col-md-12 mb-4">
                                <label for="address" class="form-label">Address <i class="text-danger">*</i></label>
                                <input type="text" name="address" value="<?= $profile ? $profile->address : ($form_data ? $form_data['address'] : null); ?>" id="address" class="form-control form-control-lg" placeholder="Input address" required>
                            </div>
                        </div>
                    </div>
                </div>
    
    
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary text-white">Save Profile</button>
                </div>
            </form>
        </div>
        
    <?php }else{ ?>
        
        <div class="col-md-12 mb-5">
            <div class="row g-4 align-items-center">
                <div class="col-12">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Agents</li>
                        </ol>
                    </nav>
                    <div class="d-flex justify-content-between">
                        <h1 class="h3 m-0">Agent</h1>
                        <a href="dashboard/agents/add" class="btn btn-primary">New Agent</a>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Header -->
        <div class="col-md-12 mb-4 d-none">
            <!-- Orders -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <a href="dashboard/orders">
                        <div class="card shadow stats border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <img src="assets/icons/Orders.svg" style="width: 40px;">
                                    <i class="fa fa-external-link-alt"></i>
                                </div>
                                <span class="font-weight-bold" style="font-size: 12px;">Total Orders</span>
                                <h4 class="title mb-0"><?= $total_record ? $total_record : 0; ?></h4>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-4">
                    <a href="dashboard/orders?status=2">
                        <div class="card shadow stats border-0 h-100 bg-yellow-shade">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <img src="assets/icons/Awaiting Delivery.svg" style="width: 40px;">
                                    <i class="fa fa-external-link-alt"></i>
                                </div>
                                <span class="font-weight-bold" style="font-size: 12px;">Orders Awaiting Delivery</span>
                                <h4 class="title mb-0"><?= $awaiting_count ? $awaiting_count : 0; ?></h4>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-4">
                    <a href="dashboard/orders?status=3">
                        <div class="card shadow stats border-0 h-100  bg-green-shade">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <img src="assets/icons/Items Delivered.svg" style="width: 40px;">
                                    <i class="fa fa-external-link-alt"></i>
                                </div>
                                <span class="font-weight-bold" style="font-size: 12px;">Completed Orders</span>
                                <h4 class="title mb-0"><?= $delivered_count ? $delivered_count : 0; ?></h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    
        <div class="col-md-12">
            <div class="card shadow border-0 mobile-card-section" style="min-height: 595px;">
                <?php if ($items) { ?>
                    <div class="card-body">
                        <div class="mb-4 d-flex justify-content-between">
                            <div class="d-flex">
                                <img class="me-3" src="assets/icons/<?= $status_icon ?>" alt="mp icon" />
                                <div>
                                    <h5 class=""><?= $title ?></h5>
    
                                    <span>Total: </span>
                                    <span><?= $total_record ?></span>
                                </div>
                            </div>
                        </div>
    
                        <form class="mb-4">
                            <input type="text" name="order" placeholder="Search for your orders using Order ID or Invoice ID" class="form-control form-control--search mx-auto" id="table-search" />
                        </form>
    
                        <div class="table-responsive">
                            <table class="table">
                              <thead>
                                <tr>
                                  <th scope="col">Name</th>
                                  <th scope="col">Vendors</th>
                                  <th scope="col">Verified</th>
                                  <th scope="col">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($items as $us) { 
                                        $agent = $User->getAgent($us->id);
                                        $agent_vendors = $User->getWhere("account_type = 'vendor' AND managed_by = '{$us->uid}'");
                                ?>
                                    <tr>
                                      <td><?= $us->first_name. ' ' .$us->last_name ?></td>
                                      <td><?= $agent_vendors ? count($agent_vendors) : 0; ?></td>
                                      <td><?= $agent->is_verified ? 'Yes' : 'No'; ?></td>
                                      <td>
                                          <div class="dropdown">
                                              <a href="#" class="" id="order-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                    <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                                </svg></a>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="order-context-menu-0">
                                                <li><a class="dropdown-item" href="dashboard/vendors?agent=<?= $agent->id; ?>">Vendors</a></li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/agents.php?rq=delete&id=<?= $us->id ?>">Delete</a></li>
                                            </ul>
                                        </div>
                                      </td>
                                    </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                        </div>
                        
    
                        <?php if ($paginate && $paginate->total_pages() > 1) { // Pagination 
                        ?>
                            <nav class="mt-5 mb-4" aria-label="Page navigation sample">
                                <ul class="pagination">
                                    <li class="page-item <?= $paginate->has_previous_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=<?= $paginate->previous_page() ?>">Previous</a></li>
                                    <?php if ($paginate->total_pages() > 2) { ?>
                                        <li class="page-item <?= Input::get('p') && Input::get('p') == 1 ? 'active' : null; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=1">1</a></li>
                                        <li class="page-item <?= Input::get('p') && Input::get('p') == 2 ? 'active' : null; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=2">2</a></li>
                                        <li class="page-item <?= Input::get('p') && Input::get('p') == 3 ? 'active' : null; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=3">3</a></li>
                                    <?php } ?>
                                    <li class="page-item disabled">
                                        <a class="page-link"><?= $next . ' of ' . $paginate->total_pages() ?></a>
                                    </li>
                                    <?php if ($paginate->total_pages() > 4) { ?>
                                        <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 4 ? 'active' : null; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=4">4</a></li>
                                        <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 5 ? 'active' : null; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=5">5</a></li>
                                    <?php } ?>
                                    <li class="page-item <?= $paginate->has_next_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/orders?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=<?= $paginate->next_page() ?>">Next</a></li>
                                </ul>
                            </nav>
                        <?php } ?>
                    </div>
    
                <?php } else { ?>
                    <div class="d-flex align-items-center justify-content-center py-5" style="min-height: 595px;">
                        <div class="col-lg-6 text-center">
                            <i class="fa fa-shopping-cart fa-3x"></i>
                            <?php if ($status || $search) { ?>
                                <h3 class="mb-3 mt-4 font-weight-bold">Oops! We couldn't find what you are looking for.</h3>
                                <a href="dashboard/orders">See all Orders</a>
                            <?php } else { ?>
                                <h3 class="mt-4 font-weight-bold">No items found.</h3>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>