<?php
// include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;

$world = new World();
$category_specials = new General('category_specials');
$category_special_datas = new General('category_special_datas');
$categories = new General('categories');
$menus = new General('menus');
$vendors = new General('vendors');

$special_category = Input::get('action') && Input::get('sub') && is_numeric(Input::get('sub')) ? $category_specials->get(Input::get('sub')) : null;
!$special_category ? Redirect::to('special-category') : null;
$type = $special_category->type;

$data_list = $category_special_datas->getAll($special_category->id, 'category_special_id', '=');

// Edit data
$item_data = Input::get('edit') ? $category_special_datas->get(Input::get('edit')) : null;
$meal_list = $menus->getAll(1, 'status', '=');
$category_list = $categories->getAll();
$vendor_list = $vendors->getAll(1, 'status', '=');

$item_categories = $item_data && $item_data->categories ? explode(',', $item_data->categories) : null;
$item_menus = $item_data && $item_data->menus ? explode(',', $item_data->menus) : null;
$item_vendors = $item_data && $item_data->vendors ? explode(',', $item_data->vendors) : null;

$countries = $world->getCountries();
 
 
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
                                <li class="breadcrumb-item"><a href="special-category">Special Menu</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                        <h1 class="h3 m-0">Edit <?= $special_category->title ?></h1>
                    </div>
                
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="p-4"><input type="text" placeholder="Start typing to search for Special Categories" class="form-control form-control--search mx-auto" id="table-search" /></div>
                        <div class="sa-divider"></div>
                        <table class="sa-datatables-init" data-sa-search-input="#table-search">
                            <thead>
                                <tr>
                                    <th class="w-min">Country</th>
                                    <th>State</th>
                                    <th>Items</th>
                                    <th>Visibility</th>
                                    <th class="w-min" data-orderable="false"></th>
                                </tr>
                            </thead>
                            <tbody>
            
                                <?php if ($data_list) { ?>
                                    <?php foreach ($data_list as $index => $v) { 
                                            $menu_count = count(explode(',', $v->menus));
                                    ?>
                                        <tr>
                                            <td><?= $world->getCountryName($v->country) ?></td>
                                            <td><?= $world->getStateName($v->state) ?></td>
                                            <td><?= $menu_count; ?></a></td>
                                            <td>
                                                <a href="controllers/specialcat.php?rq=data-status&id=<?= $v->id; ?>">
                                                    <?= $v->status ? '<div class="badge badge-sa-success">Visible</div>' : '<div class="badge badge-sa-danger">hidden</div>'; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="dropdown"><button class="btn btn-sa-muted btn-sm" type="button" id="category-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                            <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                                        </svg></button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="category-context-menu-0">
                                                        <li><a class="dropdown-item" href="special-category-<?= $special_category->type ?>/edit/<?= $special_category->id; ?>?edit=<?= $v->id ?>">Edit</a></li>
                                                        <li>
                                                            <hr class="dropdown-divider" />
                                                        </li>
                                                        <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/specialcat.php?rq=data-delete&id=<?= $v->id; ?>">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form action="controllers/specialcat.php" method="post" enctype="multipart/form-data" name="page_form" id="page_form">
                        <input type="hidden" name="category_special_id" value="<?= $special_category->id; ?>">
                        <input type="hidden" name="rq" value="<?= $item_data ? 'edit-data' : 'add-data'; ?>">
                        <input type="hidden" name="type" value="<?= $type ?>">
                        <input type="hidden" name="id" value="<?= $item_data ? $item_data->id : null; ?>">
                        <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
    
                        <div class="sa-entity-layout" data-sa-container-query="{&quot;920&quot;:&quot;sa-entity-layout--size--md&quot;,&quot;1100&quot;:&quot;sa-entity-layout--size--lg&quot;}">
                            <div class="sa-entity-layout__body">
                                <div class="sa-entity-layout__main">
                                    <div class="card">
                                        <div class="card-body p-5">
                                            <div class="mb-5">
                                                <h2 class="mb-0 fs-exact-18">Manage Menu</h2>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <label class="form-label" for="country">Country</label>
                                                    <select name="country" id="country" data-type="country" data-world-target="#seller-state" class="world select2 form-select">
                                                        <?php if ($countries) { ?>
                                                            <option value="">Select Country</option>
                                                            <?php foreach ($countries as $k => $v) { ?>
                                                                <option value="<?= $v->id ?>" <?= $item_data && $item_data->country == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
        
                                                <div class="col-md-6 mb-4">
                                                    <label class="form-label" for="state">State</label>
                                                    <select name="state" id="seller-state" data-type="state" data-menu-type="state" data-world-target="#seller-city" data-menu-target="#menus" data-selected="<?= $profile ? $profile->state : null; ?>" class="worl menus form-select">
                                                        <option value="">Select a country</option>
                                                        <?php if ($item_data && $item_data->country) {
                                                            $states = $world->getStatesByCountryId($item_data->country);
                                                        ?>
                                                            <?php foreach ($states as $k => $v) { ?>
                                                                <option value="<?= $v->id ?>" <?= $item_data && $item_data->state == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                
                                            </div>
                                            
                                            <?php if($type == 'category'){ ?>
                                                <div class="mb-4">
                                                    <label for="title">Categories</label>
                                                    <select name="categories[]" id="categories" class="sa-select2 form-select" multiple="multiple" style="width: 100%;">
                                                        <option value="default">Select Categories</option>
                                                        <?php foreach ($category_list as $k => $v) { ?>
                                                            <option value="<?= $v->id ?>" <?php if ($item_data && in_array($v->id, (array) $item_categories)) echo 'selected'; ?>><?= $v->title ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <small id="category" class="form-text text-muted">Special Category Selection </small>
                                                </div>
                                            <?php } ?>
                                            
                                            <?php if($type == 'menu'){ ?>
                                                <div class="mb-4">
                                                    <label for="title">Menus</label>
                                                    <select name="menus[]" id="menus" class="sa-select2 form-select" multiple="multiple" style="width: 100%;">
                                                        <option value="default">Select menus</option>
                                                        <?php foreach ($meal_list as $k => $v) { if(!$v->status){ continue; } ?>
                                                            <option value="<?= $v->id ?>" <?php if ($item_data && in_array($v->id, (array) $item_menus)) echo 'selected'; ?>><?= $v->title ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <small id="category" class="form-text text-muted">Special menus Selection</small>
                                                </div>
                                            <?php } ?>
                                            
                                            <?php if($type == 'vendor'){ ?>
                                                <div class="mb-4">
                                                    <label for="title">Vendors</label>
                                                    <select name="vendors[]" id="vendors" class="sa-select2 form-select" multiple="multiple" style="width: 100%;">
                                                        <option value="default">Select Vendor</option>
                                                        <?php foreach ($vendor_list as $k => $v) { if(!$v->status){ continue; } ?>
                                                            <option value="<?= $v->id ?>" <?php if ($item_data && in_array($v->id, (array) $item_vendors)) echo 'selected'; ?>><?= $v->title ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <small id="category" class="form-text text-muted">Special Vendor Selection</small>
                                                </div>
                                            <?php } ?>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="sa-entity-layout__sidebar">
                                    <div class="card w-100 mb-5">
                                        <div class="card-body p-5">
                                            <div class="mb-5">
                                                <h2 class="mb-0 fs-exact-18">Visibility</h2>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-check">
                                                    <input type="radio" class="form-check-input" name="status" value="public" <?= $item_data && $item_data->status ? 'checked' : 'checked'; ?> />
                                                    <span class="form-check-label">Published</span></label>
                                                <label class="form-check mb-0">
                                                    <input type="radio" class="form-check-input" name="status" value="hidden" <?= $item_data && !$item_data->status ? 'checked' : null; ?> />
                                                    <span class="form-check-label">Hidden</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" form="page_form" class="btn btn-primary w-100">Save</button>
                                </div>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>