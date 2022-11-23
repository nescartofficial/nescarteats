<?php
// include_once('core/init.php');
$User = isset($User) ? $User : new User();
!$User->isLoggedIn() ? Redirect::to('login') : null;
$world = new World();
$pagination = new Pagination();
$categories = new General('categories');
$Agents = new General('agents');

$countries = $world->getCountries();

$title = "Head Agents";
$items = $Agents->getAll('head', 'type', '=');

// View Category Fields
if(Input::get('show') && Input::get('show') == 'agents'){
    $title = "Sub Agents";
    $items = Input::get('agent_id') && is_numeric(Input::get('agent_id')) ? $Agents->getAll(Input::get('agent_id'), 'sub', '=') : null;
}

$items = Input::get('show') && Input::get('show') == 'field' && $category ? $Agents->getAll($category->id, 'category_id', '=') : $items;
$title = Input::get('show') && Input::get('show') == 'field' && $category ? $category->title.": Fields" : $title;

$item = Input::get('action') && Input::get('action') == 'edit' && Input::get('sub') && is_numeric(Input::get('sub')) ? $Agents->get(Input::get('sub')) : null;
$item_fields = $item ? $Agents->getAll($item->category_id, 'category_id', '=') : null;
$depend_fields = $item ? json_decode($item->depend_field) : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<div id="top" class="sa-app__body px-2 px-lg-4">
    <?php if (Input::get('action') && Input::get('action') == 'add' || $item) { ?>
        <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
            <div class="container container--max--xl">
                <div class="py-5">
                    <div class="row g-4 align-items-center">
                        <div class="col">
                            <nav class="mb-2" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-sa-simple">
                                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="categories">Agents</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Manage</li>
                                </ol>
                            </nav>
                            <h1 class="h3 m-0"><?= $item ? 'Edit' : 'Add' ?> Agent</h1>
                        </div>
                        <div class="col-auto d-flex"><a href="#" class="btn btn-secondary me-3">Duplicate</a><button type="submit" form="testimonial" class="btn btn-primary">Save</button></div>
                    </div>
                </div>
                <form action="controllers/agents.php" method="post" enctype="multipart/form-data" name="testimonial" id="testimonial">
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
                                            <div class="col-lg-4 mb-4">
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
    
                                            <div class="col-lg-4 mb-4">
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
    
                                            <div class="col-lg-4 mb-4">
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
                                        <div class="mb-4">
                                            <label for="form-category/note" class="form-label">Note</label>
                                            <textarea name="note" id="form-category/note" class="sa-quill-control form-control" rows="5"><?= $item ? $item->note : null; ?></textarea>
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
        <div class="container">
            <div class="py-5">
                <div class="row g-4 align-items-center">
                    <div class="col">
                        <nav class="mb-2" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-sa-simple">
                                <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Agents</li>
                            </ol>
                        </nav>
                        <h1 class="h3 m-0">Agents</h1>
                    </div>
                    <div class="col-auto d-flex"><a href="agents/add<?= $agent ? "?agent=".$agent->id : null; ?>" class="btn btn-primary">New Agent</a></div>
                </div>
            </div>
            
            <!-- Table -->
            <div class="card">
                <div class="p-4">
                    <h4 class=""><?= $title ?></h4>
                </div>
                
                <div class="p-4"><input type="text" placeholder="Start typing to search for categories" class="form-control form-control--search mx-auto" id="table-search" /></div>
                <div class="sa-divider"></div>
                <table class="sa-datatables-init" data-sa-search-input="#table-search">
                    <thead>
                        <tr>
                            <th class="w-min" data-orderable="false"><input type="checkbox" class="form-check-input m-0 fs-exact-16 d-block" aria-label="..." /></th>
                            <th>Name</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Agents</th>
                            <th>Verified</th>
                            <th>Visibility</th>
                            <th class="w-min" data-orderable="false"></th>
                        </tr>
                    </thead>
    
                    <tbody>
                        <?php if ($items) { ?>
                            <?php foreach ($items as $agent) {
                                $us = $User->get($agent->user_id);
                                $sub_agent = $Agents->getAll($agent->id, 'sub', '=');
                                $state = $world->getStateName($agent->state);
                                $city = $world->getCityName($agent->city);
                            ?>
                                <tr>
                                    <td><input type="checkbox" class="form-check-input m-0 fs-exact-16 d-block" aria-label="..." /></td>
                                    <td><?= $us->first_name. ' '. $us->last_name; ?></td>
                                    <td><?= $state; ?></td>
                                    <td><?= $city; ?></td>
                                    <td><?= $sub_agent ? count($agents) : 0; ?></td>
                                    <td>
                                        <a onclick="return confirm('Are you sure to activate agent?');" href="controllers/agents.php?rq=verify&id=<?= $agent->id; ?>" class="px-3"><span class="badge bg-<?= $agent->is_verified ? 'success' : 'danger'; ?>"><?= $agent->is_verified ? 'yes' : 'no'; ?></a></span>
                                    </td>
                                    <td>
                                        <a href="controllers/agents.php?rq=status&id=<?= $agent->id; ?>">
                                            <?= $agent->status ? '<div class="badge badge-sa-success">Visible</div>' : '<div class="badge badge-sa-danger">hidden</div>'; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sa-muted btn-sm" type="button" id="category-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                    <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                            </svg></button>
                                            
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="category-context-menu-0">
                                                <li><a class="dropdown-item" href="agents/edit/<?= $agent->id; ?>">Edit</a></li>
                                                <?php if($agent->type == 'head'){ ?>
                                                    <li><a class="dropdown-item" href="agents?show=agents&agent_id=<?= $agent->id; ?>">Manage Sub Agents</a></li>
                                                <?php }else{ ?>
                                                    <li><a class="dropdown-item" href="agents?show=stores&agent=<?= $agent->id; ?>">See Vendors</a></li>
                                                <?php } ?>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/agents.php?rq=delete&id=<?= $agent->id; ?>">Delete</a></li>
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
    </div>
    <?php } ?>
</div>