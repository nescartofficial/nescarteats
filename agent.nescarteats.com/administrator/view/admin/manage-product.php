<?php
// include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;

$categories = new General('categories');
$sellers = new General('sellers');
$products = new General('products');
$items = $products->getAll();

$item = Input::get('action') && Input::get('action') == 'edit' && Input::get('sub') && is_numeric(Input::get('sub')) ? $products->get(Input::get('sub')) : null;

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
                                    <li class="breadcrumb-item"><a href="categories">Categories</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Testimonial</li>
                                </ol>
                            </nav>
                            <h1 class="h3 m-0"><?= $item ? 'Edit' : 'Add' ?> Categories</h1>
                        </div>
                        <div class="col-auto d-flex"><a href="#" class="btn btn-secondary me-3">Duplicate</a><button type="submit" form="testimonial" class="btn btn-primary">Save</button></div>
                    </div>
                </div>
                <form action="controllers/categories.php" method="post" enctype="multipart/form-data" name="testimonial" id="testimonial">
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
                                            <label for="title">Category</label>
                                            <select name="category" id="category" class="form-select">
                                                <option value="">- Select Category -</option>
                                                <?php foreach ($items as $k => $v) { ?>
                                                    <option value="<?= $v->id ?>" <?= $item && $item->parent_id == $v->id ? 'selected' : null; ?>><?= $v->title ?></option>
                                                <?php } ?>
                                            </select>
                                            <small id="category" class="form-text text-muted">Parent category selection</small>
                                        </div>
                                        <div class="mb-4">
                                            <label for="form-category/title" class="form-label">Name</label>
                                            <input type="text" name="title" value="<?= $item ? $item->title : null; ?>" id="form-category/title" class="form-control slugit" data-slugit-target="#slug" data-slugit-event="keyup">
                                        </div>
                                        <div class="mb-4">
                                            <label for="form-category/slug" class="form-label">Slug</label>
                                            <div class="input-group input-group--sa-slug">
                                                <span class="input-group-text" id="form-category/slug"><?= SITE_URL  ?>category/</span>
                                                <input type="text" class="form-control" id="slug" aria-describedby="form-category/slug-addon form-category/slug-help" name="slug" placeholder="link" value="<?= $item ? $item->slug : null; ?>" />
                                            </div>
                                            <div id="form-category/slug-help" class="form-text">Unique human-readable category identifier. No longer than 255 characters.</div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="form-category/description" class="form-label">Description</label>
                                            <textarea name="description" id="form-category/description" class="sa-quill-control form-control" rows="8"><?= $item ? $item->description : null; ?></textarea>
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
                                                <input type="radio" class="form-check-input" name="status" value="public" <?= $item && $item->status ? 'checked' : null; ?> />
                                                <span class="form-check-label">Published</span></label>
                                            <label class="form-check mb-0">
                                                <input type="radio" class="form-check-input" name="status" value="hidden" <?= $item && !$item->status ? 'checked' : null; ?> />
                                                <span class="form-check-label">Hidden</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card w-100 mt-5">
                                    <div class="card-body p-5">
                                        <div class="mb-5">
                                            <h2 class="mb-0 fs-exact-18">Featured</h2>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-check">
                                                <input type="radio" class="form-check-input" name="featured" value="public" <?= $item && $item->featured ? 'checked' : null; ?> />
                                                <span class="form-check-label">Published</span></label>
                                            <label class="form-check mb-0">
                                                <input type="radio" class="form-check-input" name="featured" value="hidden" <?= $item && !$item->featured ? 'checked' : null; ?> />
                                                <span class="form-check-label">Hidden</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card w-100 mt-5">
                                    <div class="card-body p-5">
                                        <div class="mb-5">
                                            <h2 class="mb-0 fs-exact-18">Image</h2>
                                        </div>
                                        <?php if ($item) { ?>
                                            <div class="border p-4 d-flex justify-content-center">
                                                <div class="max-w-20x">
                                                    <img src="<?= SITE_URL ?>assets/images/testimonial/<?= $item->image; ?>" class="w-100 h-auto" width="320" height="320" alt="" />
                                                </div>
                                            </div>
                                            <div class="mt-4 mb-n2">
                                                <!-- <a href="#" class="toggler me-3 pe-2">Replace image</a> -->
                                                <!-- <a href="#" class="text-danger me-3 pe-2">Remove image</a> -->
                                            </div>
                                        <?php } ?>
                                        <div class="custom-file">
                                            <label class="custom-file-label" for="file"><?php $item ? 'Replace' : 'Choose' ?> image</label>
                                            <input type="file" name="file" class="form-control" id="file">
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
        <div class="py-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Categories</li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0">Categories</h1>
                </div>
                <div class="col-auto d-flex"><a href="categories/add" class="btn btn-primary">New category</a></div>
            </div>
        </div>
        <div class="card">
            <div class="p-4"><input type="text" placeholder="Start typing to search for categories" class="form-control form-control--search mx-auto" id="table-search" /></div>
            <div class="sa-divider"></div>
            <table class="sa-datatables-init" data-sa-search-input="#table-search">
                <thead>
                    <tr>
                        <th class="w-min" data-orderable="false"><input type="checkbox" class="form-check-input m-0 fs-exact-16 d-block" aria-label="..." /></th>
                        <th class="min-w-15x">Name</th>
                        <th>Parent</th>
                        <th>Featured</th>
                        <th>Visibility</th>
                        <th class="w-min" data-orderable="false"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($items) { ?>
                        <?php foreach ($items as $index => $v) {
                            $ctitle = $v->parent_id ? $categories->get($v->parent_id) : null; ?>
                            <tr>
                                <td><input type="checkbox" class="form-check-input m-0 fs-exact-16 d-block" aria-label="..." /></td>
                                <td><a href="categories/edit/<?= $v->id; ?>" class="text-reset"><?= $v->title; ?></a></td>
                                <td><?= $ctitle ? $ctitle->title : '-'; ?></td>
                                <td>
                                    <a href="controllers/categories.php?rq=featured&id=<?= $v->id; ?>">
                                        <?= $v->featured ? '<div class="badge badge-sa-success">Yes</div>' : '<div class="badge badge-sa-danger">No</div>'; ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="controllers/categories.php?rq=status&id=<?= $v->id; ?>">
                                        <?= $v->status ? '<div class="badge badge-sa-success">Visible</div>' : '<div class="badge badge-sa-danger">hidden</div>'; ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="dropdown"><button class="btn btn-sa-muted btn-sm" type="button" id="category-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                            </svg></button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="category-context-menu-0">
                                            <li><a class="dropdown-item" href="categories/edit/<?= $v->id; ?>">Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/categories.php?rq=delete&id=<?= $v->id; ?>">Delete</a></li>
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

<div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        <!-- List -->
        <div class="col-md-12">
            <div class="card shadow-sm mb-4 border-0">
                <!-- Card Body -->
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="col">
                            <h4 class="card-title">products List</h4>
                            <h6 class="card-subtitle"><?= Input::get('action') ? ucwords(Input::get('action')) . ' category' : 'List of all products' ?></h6>
                        </div>
                        <a href="products<?= !Input::get('action') ? '/add' : null ?>" class="btn btn-sm bg-accent shadow-sm"><i class="fas <?= Input::get('action') ? 'fa-list' : 'fa-plus' ?> fa-sm text-white-50"></i> <?= Input::get('action') ? 'List' : 'Add' ?></a>
                    </div>
                    <hr>
                    <?php if (Input::get('action')) { // managing category
                        if (Input::get('action') == 'add' || Input::get('action') == 'edit') { // add / Edit
                    ?>
                            <div class="col-md-6 mx-auto">
                                <form action="controllers/products.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select type="text" name="category" id="title" class="custom-select">
                                            <?php $cats = $categories->getAll(1, 'status', '=');
                                            if ($cats) {
                                                echo '<option value="default">Select Category</option>';
                                                foreach ($cats as $k => $v) { ?>
                                                    <option value="<?= $v->id ?>" <?= $item && $item->category == $v->id ? 'selected' : null; ?>><?= $v->title ?></option>
                                                <?php
                                                }
                                            } else { ?>
                                                <option value="">No category available</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" name="title" value="<?= $item ? $item->title : null; ?>" id="title" class="form-control slugit" data-slugit-target="#slug" data-slugit-event="keyup">
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label for="price">Price</label>
                                            <input type="text" name="amount" value="<?= $item ? $item->price : null; ?>" id="amount" class="form-control">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="price">Slashed Price?</label>
                                            <input type="text" name="slash" value="<?= $item ? $item->slashed_price : null; ?>" id="slash" class="form-control">
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label for="price">Quantity</label>
                                            <input type="number" min="1" name="quantity" value="<?= $item ? $item->quantity : null; ?>" id="quantity" class="form-control">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="custom-select">
                                                <option value="public">Public</option>
                                                <option value="hidden">Hidden</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product-img">Cover Image</label>
                                        <div class="custom-file">
                                            <input type="file" name="cover[]" class="custom-file-input" id="customFile2">
                                            <label class="custom-file-label" for="customFile">Choose Product Cover</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product-img">Product Images</label>
                                        <div class="custom-file">
                                            <input type="file" name="file[]" class="custom-file-input" id="customFile" multiple>
                                            <label class="custom-file-label" for="customFile">Choose Product Images</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="summernote form-control"><?= $item ? $item->description : null; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="slug" class="">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug" placeholder="link" value="<?= $item ? $item->slug : null; ?>">
                                    </div>
                                    <div class="form-group mt-4">
                                        <input type="hidden" name="rq" value="<?= $item ? 'edit' : 'add'; ?>">
                                        <input type="hidden" name="id" value="<?= $item ? $item->id : null; ?>">
                                        <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                                        <button type="submit" class="btn bg-accent"><?= $item ? 'Edit Product' : 'Add Product'; ?></button>
                                    </div>
                                </form>
                            </div>

                        <?php } else {
                            echo '<p class="font-weight-bold text-info text-center">Something went wrong, Try Again!</p>';
                        }
                    } else { ?>

                        <?php if ($items) { // list of products 
                        ?>
                            <div class="table-responsive">
                                <table id="zero_config" class="table table-striped">
                                    <thead class="bg-gray">
                                        <tr>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $k => $v) {
                                            $category = $categories->get($v->category);
                                            $category = $category ? $category->title : null; ?>
                                            <tr>
                                                <td class="text-accent font-weight-bold"><?= $v->title; ?></td>
                                                <td><?= $category; ?></td>
                                                <td><?= Helpers::format_currency($v->price); ?></td>
                                                <td class="text-accent font-weight-bold text-capitalize"><?= $v->status ? 'public' : 'hidden'; ?></td>
                                                <td class="text-center">
                                                    <div class="btn-group flex-wra">
                                                        <a href="controllers/products.php?rq=status&id=<?= $v->id; ?>" class="btn waves-effect waves-light btn-sm bg-main"><i class="mdi <?= $v->status ? 'mdi-eye' : 'mdi-eye-off'; ?>"> status</i></a>

                                                        <a href="products/edit/<?= $v->id; ?>" class="btn waves-effect waves-light btn-sm bg-accent"><i class="mdi mdi-table-edit"></i> edit</a>

                                                        <a onclick="return confirm('Are you sure you want to delete this product?')" href="controllers/products.php?rq=delete&id=<?= $v->id; ?>" class="btn waves-effect waves-light btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <p class="font-weight-bold text-info text-center">No Item Found!</p>
                        <?php } ?>

                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
</div>