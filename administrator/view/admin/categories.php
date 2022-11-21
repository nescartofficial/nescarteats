<?php
// include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$pagination = new Pagination();
$categories = new General('categories');

$items = $categories->getAll(0, 'parent_id', '=');
$title = "Parent Categories";
$items = Input::get('show') && Input::get('show') == 'all' ? $categories->getAll() : $items;
$title = Input::get('show') && Input::get('show') == 'all' ? "All Categories" : $title;
$items = Input::get('show') && Input::get('show') == 'parent' ? $categories->getAll(0, 'parent_id', '=') : $items;
$title = Input::get('show') && Input::get('show') == 'parent' ? "All Parent Categories" : $title;
$items = Input::get('show') && Input::get('show') == 'sub' ? $categories->getAll(0, 'parent_id', '>') : $items;
$title = Input::get('show') && Input::get('show') == 'sub' ? "All Sub Categories" : $title;
$items = Input::get('show') && Input::get('show') == 'published' ? $categories->getAll(1, 'status', '=') : $items;
$title = Input::get('show') && Input::get('show') == 'published' ? "Published Products" : $title;
$items = Input::get('show') && Input::get('show') == 'hidden' ? $categories->getAll(0, 'status', '=') : $items;
$title = Input::get('show') && Input::get('show') == 'hidden' ? "Hidden Products" : $title;

// View Category Subs
$category = Input::get('parent') && is_numeric(Input::get('parent')) ? $categories->get(Input::get('parent')) : null;
$items = $category ? $categories->getAll(Input::get('parent'), 'parent_id', '=') : $items;
$title = $category ? $category->title . ": Sub Categories" : $title;

// Header Counter
$total_count = $pagination->countAll('categories', "WHERE id > 0");
$parent_count = $pagination->countAll('categories', "WHERE id > 0 AND parent_id = 0");
$sub_count = $pagination->countAll('categories', "WHERE id > 0 AND parent_id <> 0");
$published_count = $pagination->countAll('categories', "WHERE id > 0 AND status = 1");
$hidden_count = $pagination->countAll('categories', "WHERE id > 0 AND status = 0");

$item = Input::get('action') && Input::get('action') == 'edit' && Input::get('sub') && is_numeric(Input::get('sub')) ? $categories->get(Input::get('sub')) : null;

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
                                        <div class="row">
                                            <div class="col-lg-8 mb-4">
                                                <label for="form-category/title" class="form-label">Name</label>
                                                <input type="text" name="title" value="<?= $item ? $item->title : null; ?>" id="form-category/title" class="form-control slugit" data-slugit-target="#slug" data-slugit-event="keyup">
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="form-category/percentage" class="form-label">Commision to Charge</label>
                                                <div class="input-group">
                                                    <input type="text" name="percentage" value="<?= $item ? $item->percentage : null; ?>" id="form-category/percentage" class="form-control">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon1">%</span>
                                                    </div>
                                                </div>
                                            </div>
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
                                                <input type="radio" class="form-check-input" name="status" value="public" <?= $item && $item->status ? 'checked' : 'checked'; ?> />
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
                                                    <img src="<?= SITE_URL ?>assets/images/category/<?= $item->image; ?>" class="w-100 h-auto" width="320" height="320" alt="" />
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
        <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
            <div class="container">
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

                <!-- Header -->
                <div class="row gy-5 mb-6">
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Total Categories</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="categories?show=all" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $total_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Parent Categories</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="categories?show=parent" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $parent_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Sub Categories</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="categories?show=sub" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $sub_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Published Categories</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="categories?show=published" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $published_count ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 d-flex">
                        <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                            <div class="sa-widget-header saw-indicator__header">
                                <h2 class="sa-widget-header__title">Hidden Categories</h2>
                                <div class="sa-widget-header__actions">
                                    <a href="categories?show=hidden" class="active">view</a>
                                </div>
                            </div>
                            <div class="saw-indicator__body">
                                <div class="saw-indicator__value"><?= $hidden_count ?></div>
                            </div>
                        </div>
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
                                <th class="min-w-15x">Name</th>
                                <th>Parent</th>
                                <th>Featured</th>
                                <th>Popular</th>
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
                                                <?= $v->popular ? '<div class="badge badge-sa-success">Yes</div>' : '<div class="badge badge-sa-danger">No</div>'; ?>
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
                                                    <?php if (!$v->parent_id) { ?>
                                                        <li><a class="dropdown-item" href="categories?parent=<?= $v->id; ?>">View Sub Categories</a></li>
                                                    <?php } ?>
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
            </div>
        </div>
    <?php } ?>
</div>