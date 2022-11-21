<?php
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;

$categories = new General('categories');
$menus = new General('menus');
$Variations = new General('menu_variations');
$Addons = new General('menu_addons');
$world = new World();

$items = $menus->getAll($user->data()->id, 'user_id', '=');
$item = Input::get('sub') && Input::get('sub') == 'edit' && Input::get('sub1') && is_numeric(Input::get('sub1')) ? $menus->get(Input::get('sub1')) : null;
// print_r($item); die;
$selected_category =  $item ? $categories->get($item->category, 'id', '=') : (Input::get('category') ? $categories->get(Input::get('category'), 'id', '=') : null);
if (!$selected_category) {
    Session::flash('error', 'Category to add product is required');
    Redirect::to_js('dashboard/menus');
}

$category_list =  $categories->getAll($selected_category->id, 'parent_id', '=');
$menu_list = $menus->getAll($user->data()->id, 'user_id', '=');

$form_data = Session::exists('form-data') ? Session::get('form-data') : null;

// Menu Variations
$variations = $Variations->getAll($item->id, 'menu_id', '=');
// Menu Addons
$addons = $Addons->getAll($item->id, 'menu_id', '=');
// Ingredients
$item_ingredients = $item && $item->ingredients ? json_decode($item->ingredients) : null;

Alerts::displayError();
Alerts::displaySuccess();
?>
<?php
if ($selected_category) {  ?>
    <div class="container-fluid container-md">
        <div class="mb-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="dashboard/menus">Menus</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= $item ? 'Edit Menus' : 'Add Menus'; ?></li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-3"><?= $item ? 'Edit' : 'Add' ?> Menus</h1>
                    <p class="small fw-bold">Items marked with <span class="text-danger">*</span> are required fields.</p>
                </div>
            </div>
        </div>
        <form action="controllers/menus.php" name="form_product" id="form_product" method="post" enctype="multipart/form-data" class="needs-validation " novalidate="">
            <!--Basic Info-->
            <div class="card mb-5">
                <div class="card-body">
                    <h4>Basic Info</h4>
                    <div class="row g-4 mt-4">
                        <div class="col-md-12 mb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="form-product/name" class="form-label">Name <i class="text-danger">*</i></label>
                                </div>
                                <div class="col-lg">
                                    <input type="text" name="title" value="<?= $item ? $item->title : ($form_data ? $form_data['title'] : null); ?>" id="title" class="form-control form-control-lg" placeholder="Input name of your menu" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="form-product/description" class="form-label">Price <i class="text-danger">*</i></label>
                                </div>
                                <div class="col-md mb-4">
                                    <label for="price" class="form-label sr-only">Price <i class="text-danger">*</i></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-primary--light border-end-0 fs-12p" id="inputGroup-sizing-sm">₦</span>
                                        <input type="text" name="price" pattern="[0-9]*" value="<?= $item ? $item->price : ($form_data ? $form_data['price'] : null); ?>" id="price" class="form-control border-start-0" placeholder="Price" required>
                                    </div>
                                </div>

                                <div class="col-md mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-primary--light border-end-0 fs-12p" id="inputGroup-sizing-sm">₦</span>
                                        <input type="text" name="discount_price" pattern="[0-9]*" value="<?= $item ? $item->discount_price : ($form_data ? $form_data['discount_price'] : 0); ?>" id="discount_price" class="form-control border-start-0" placeholder="Discount Price" />
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check">
                                            <input name="apply_discount" value="1" <?= $item && $item->apply_discount ? 'checked' : null; ?> class="form-check-input" type="checkbox" value="" id="apply_discount">
                                            <label class="form-check-label" for="apply_discount" class="fs-12p"> Apply discount price</label>
                                        </div>
                                        <p for="discount_price" class="fw-bold fs-12p mb-0 text-end">Discount price</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="d-md-flex">
                                <div class="col-lg-3">
                                    <label for="form-product/description" class="form-label">Description <i class="text-danger">*</i></label>
                                </div>
                                <textarea name="description" id="form-product/description" class="form-control form-control-lg" rows="4" required><?= $item ? Helpers::stripnl2br($item->description) : ($form_data ? Helpers::stripnl2br($form_data['description']) : null); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Product Info.-->
            <div class="card mb-5">
                <div class="card-body">
                    <h4>Product Info</h4>
                    <div class="row g-4 mt-4">
                        <div class="col-md-12 mb-4">
                            <div class="d-md-flex">
                                <div class="col-lg-3">
                                    <label for="ingredients" class="form-label">Ingredients</label>
                                </div>
                                <input type="text" name="ingredients" value='<?= $item ? $item_ingredients : ($form_data ? $form_data['ingredients'] : null); ?>' id="ingredients" class="form-control form-control-lg ot-tagify" placeholder="" required>
                            </div>
                        </div>

                        <?php if (Input::get('special')) { ?>
                            <div class="col-md-12 mb-4">
                                <div class="d-md-flex">
                                    <div class="col-lg-3">
                                        <label for="special" class="form-label">Special</label>
                                    </div>
                                    <select name="special[]" id="special" class="form-select form-select-lg select2" required multiple>
                                        <?php if ($menu_list) { ?>
                                            <?php foreach ($menu_list as $k => $v) { ?>
                                                <option value="" <?= $item ? $item->special : ($form_data ? $form_data['special'] : null); ?>><?= $v->title; ?></option>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <option value="">No menus available</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-12 mb-4">
                                <?php Component::render('variations', array('data' => $variations), 'view/vendor/component'); ?>
                            </div>
                        <?php } ?>

                        <div class="col-md-12 mb-4">
                            <?php Component::render('addons', array('data' => $addons), 'view/vendor/component'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!--Photos-->
            <div class="card mb-5">
                <div class="card-body">
                    <h4>Photos</h4>
                    <div class="row g-4 mt-4">
                        <?php
                        Component::render(
                            'filepond',
                            array(
                                'item' => $item ? $item : null,
                                'classified' => 'yes',
                            ),
                            'view/vendor/component'
                        );
                        ?>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-8 mx-auto text-center">
                    <input type="hidden" id="slug" name="slug" value="<?= $item ? $item->slug : ($form_data ? $form_data['slug'] : null); ?>">
                    <input type="hidden" name="category" value="<?= $selected_category ? $selected_category->id : null; ?>">
                    <input type="hidden" name="status" value="public">
                    <input type="hidden" name="rq" value="<?= $item ? 'edit' : 'add'; ?>">
                    <input type="hidden" name="id" value="<?= $item ? $item->id : null; ?>">
                    <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                    <button class="btn w-50 mb-5" type="submit">Submit</button>
                    <a href="dashboard/menus" class="d-block text-reset"><i class="fa fa-arrow-left me-2"></i> Back</a>
                </div>
            </div>

        </form>

    </div>
<?php } ?>