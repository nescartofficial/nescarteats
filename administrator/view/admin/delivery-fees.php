<?php
// include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;

$world = new World();
$delivery_fees = new General('delivery_fees');
$categories = new General('categories');

$countries = $world->getCountries();
$items = $delivery_fees->getAll();
$category_list = $categories->getAll();

$item = Input::get('action') && Input::get('sub') && is_numeric(Input::get('sub')) ? $delivery_fees->get(Input::get('sub')) : null;
$item_categories = $item && $item->categories ? explode(',', $item->categories) : null;

$type = Input::get('type') ? Input::get('type') : 'location';

Alerts::displayError();
Alerts::displaySuccess();
?>

<div id="top" class="sa-app__body px-2 px-lg-4">
    <?php if (Input::get('action') && Input::get('action') == 'add' || $item) { ?>
        <?php if (Input::get('type') && Input::get('type') == 'category') { ?>
            <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
                <div class="container container--max--xl">
                    <div class="py-5">
                        <div class="row g-4 align-items-center">
                            <div class="col">
                                <nav class="mb-2" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-sa-simple">
                                        <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="special">Delivery Fee</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Manage Fee</li>
                                    </ol>
                                </nav>
                                <h1 class="h3 m-0"><?= $item ? 'Edit' : 'Add' ?> Fee</h1>
                            </div>
                            <div class="col-auto d-flex">
                                <button type="submit" form="page_form" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    <form action="controllers/delivery-fees.php" method="post" enctype="multipart/form-data" name="page_form" id="page_form">
                        <input type="hidden" name="type" value="<?= $type; ?>">
                        <input type="hidden" name="rq" value="<?= $item ? 'edit' : 'add'; ?>">
                        <input type="hidden" name="id" value="<?= $item ? $item->id : null; ?>">
                        <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
    
                        <div class="sa-entity-layout" data-sa-container-query="{&quot;920&quot;:&quot;sa-entity-layout--size--md&quot;,&quot;1100&quot;:&quot;sa-entity-layout--size--lg&quot;}">
                            <div class="sa-entity-layout__body">
                                <div class="sa-entity-layout__main">
                                    <div class="card">
                                        <div class="card-body p-5">
                                            <div class="mb-5">
                                                <h2 class="mb-0 fs-exact-18">Basic information</h2>
                                            </div>
                                            <div class="mb-4">
                                                <label for="title">Categories</label>
                                                <select name="category" id="category" class="sa-select2 form-select" style="width: 100%;">
                                                    <option value="default">Select Categories</option>
                                                    <?php foreach ($category_list as $k => $v) { 
                                                            if(!isset($item) && $delivery_fees->get($v->id, 'category_id')){
                                                                continue;
                                                            }
                                                    ?>
                                                        <option value="<?= $v->id ?>" <?php if ($item && in_array($v->id, (array) $item_categories)) echo 'selected'; ?>><?= $v->title ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label for="form-category/title" class="form-label">Fee</label>
                                                <input type="text" name="fee" value="<?= $item ? $item->fee : null; ?>" id="form-category/fee" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sa-entity-layout__sidebar">
                                    <div class="card w-100">
                                        <div class="card-body p-5">
                                            <div class="mb-5">
                                                <h2 class="mb-0 fs-exact-18">Visibility</h2>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-check">
                                                    <input type="radio" class="form-check-input" name="status" value="public" <?= $item && $item->status ? 'checked' : 'checked'; ?> />
                                                    <span class="form-check-label">Published</span></label>
                                                <label class="form-check mb-0">
                                                    <input type="radio" class="form-check-input" name="status" value="hidden" <?= $item && !$item->status ? 'checked' : null; ?> />
                                                    <span class="form-check-label">Hidden</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
                <div class="container container--max--xl">
                    <div class="py-5">
                        <div class="row g-4 align-items-center">
                            <div class="col">
                                <nav class="mb-2" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-sa-simple">
                                        <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="special">Delivery Fee</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Manage Fee</li>
                                    </ol>
                                </nav>
                                <h1 class="h3 m-0"><?= $item ? 'Edit' : 'Add' ?> Fee</h1>
                            </div>
                            <div class="col-auto d-flex">
                                <button type="submit" form="page_form" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    <form action="controllers/delivery-fees.php" method="post" enctype="multipart/form-data" name="page_form" id="page_form">
                        <input type="hidden" name="type" value="<?= $type; ?>">
                        <input type="hidden" name="rq" value="<?= $item ? 'edit' : 'add'; ?>">
                        <input type="hidden" name="id" value="<?= $item ? $item->id : null; ?>">
                        <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
    
                        <div class="sa-entity-layout" data-sa-container-query="{&quot;920&quot;:&quot;sa-entity-layout--size--md&quot;,&quot;1100&quot;:&quot;sa-entity-layout--size--lg&quot;}">
                            <div class="sa-entity-layout__body">
                                <div class="sa-entity-layout__main">
                                    <div class="card">
                                        <div class="card-body p-5">
                                            <div class="mb-5">
                                                <h2 class="mb-0 fs-exact-18">Basic information</h2>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-4">
                                                    <label class="form-label" for="country">Country</label>
                                                    <select name="country" id="country" data-type="country" data-world-target="#seller-state" class="world select2 form-select">
                                                        <?php if ($countries) { ?>
                                                            <option value="">Select Country</option>
                                                            <?php foreach ($countries as $k => $v) { ?>
                                                                <option value="<?= $v->id ?>" <?= $profile && $profile->country == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
        
                                                <div class="col-md-4 mb-4">
                                                    <label class="form-label" for="state">State</label>
                                                    <select name="state" id="seller-state" data-type="state" data-world-target="#seller-city" data-selected="<?= $profile ? $profile->state : null; ?>" class="world form-select">
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
                                                    <label class="form-label" for="city">City</label>
                                                    <select name="city" id="seller-city" data-selected="<?= $profile ? $profile->city : null; ?>" class="city form-select">
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
                                            </div>
                                            <div class="mb-4">
                                                <label for="form-category/title" class="form-label">Fee</label>
                                                <input type="text" name="fee" value="<?= $item ? $item->fee : null; ?>" id="form-category/fee" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="sa-entity-layout__sidebar">
                                    <div class="card w-100">
                                        <div class="card-body p-5">
                                            <div class="mb-5">
                                                <h2 class="mb-0 fs-exact-18">Visibility</h2>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-check">
                                                    <input type="radio" class="form-check-input" name="status" value="public" <?= $item && $item->status ? 'checked' : 'checked'; ?> />
                                                    <span class="form-check-label">Published</span></label>
                                                <label class="form-check mb-0">
                                                    <input type="radio" class="form-check-input" name="status" value="hidden" <?= $item && !$item->status ? 'checked' : null; ?> />
                                                    <span class="form-check-label">Hidden</span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="py-5">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-md">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Delivery Fees</li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0">Delivery Fees</h1>
                </div>
                <div class="col-auto d-md-flex">
                    <a href="delivery-fees/add?type=location" class="btn btn-primary me-3">New Location</a>
                    <a href="delivery-fees/add?type=category" class="btn btn-primary">New Category</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="p-4"><input type="text" placeholder="Start typing to search for Delivery Fees" class="form-control form-control--search mx-auto" id="table-search" /></div>
            <div class="sa-divider"></div>
            <table class="sa-datatables-init" data-sa-search-input="#table-search">
                <thead>
                    <tr>
                        <th class="w-min" data-orderable="false"><input type="checkbox" class="form-check-input m-0 fs-exact-16 d-block" aria-label="..." /></th>
                        <th class="min-w-15x">Name</th>
                        <th>Visibility</th>
                        <th class="w-min" data-orderable="false"></th>
                    </tr>
                </thead>
                <tbody>

                    <?php if ($items) { ?>
                        <?php foreach ($items as $index => $v) {
                                $title = $v->type == 'category' ? 'Category: '. $categories->get($v->category_id)->title : $world->getCountryName($v->country).', '.$world->getStateName($v->state).', '.$world->getCityName($v->city);
                        ?>
                            <tr>
                                <td><input type="checkbox" class="form-check-input m-0 fs-exact-16 d-block" aria-label="..." /></td>
                                <td>
                                    <div>
                                        <span><?= $title ?></span>
                                        <div class="sa-meta mt-0">
                                            <ul class="sa-meta__list">
                                                <li class="sa-meta__item">Fee: <span title="Copy fee" class="st-copy"><?= Helpers::format_currency($v->fee); ?></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="controllers/delivery-fees.php?rq=status&id=<?= $v->id; ?>">
                                        <?= $v->status ? '<div class="badge badge-sa-success">Visible</div>' : '<div class="badge badge-sa-danger">hidden</div>'; ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="dropdown"><button class="btn btn-sa-muted btn-sm" type="button" id="category-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                            </svg></button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="category-context-menu-0">
                                            <li><a class="dropdown-item" href="delivery-fees/edit/<?= $v->id.'?type='.$v->type; ?>">Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/delivery-fees.php?rq=delete&id=<?= $v->id; ?>">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>