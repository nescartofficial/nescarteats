<?php
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$pagination = new Pagination();
$categories = new General('categories');
$menus = new General('menus');

$searchTerm = null;
$search = Input::get('search') ? Input::get('search') : null;
$status = Input::get('status') ? Input::get('status') : null;
if ($status) {
    if ($status == 'public') {
        $searchTerm = 'WHERE user_id = ' . $user->data()->id . ' AND status = 1';
    }
    if ($status == 'unpublished') {
        $searchTerm = 'WHERE user_id = ' . $user->data()->id . ' AND status = 0';
    }
    if ($status == 'soldout') {
        $searchTerm = 'WHERE user_id = ' . $user->data()->id . ' AND quantity < 1';
    }
}

$searchTerm = $search ? ($status ? $searchTerm . " AND title LIKE '%{$search}%' " : "WHERE user_id = " . $user->data()->id . " AND title LIKE '%{$search}%' ") : $searchTerm;
$searchTerm = $searchTerm ? $searchTerm : "WHERE user_id = " . $user->data()->id . " AND id > 0 ";

$next = Input::get('p') ? Input::get('p') : 1;
$per_page = 4;
$total_record = $pagination->countAll('menus', $searchTerm);
$all_total_record = $pagination->countAll('menus', 'WHERE user_id = ' . $user->data()->id . ' AND id > 0');
$pub_total_record = $pagination->countAll('menus', 'WHERE user_id = ' . $user->data()->id . ' AND status = 1');
$clo_total_record = $pagination->countAll('menus', 'WHERE user_id = ' . $user->data()->id . ' AND status = 0');
$sol_total_record = $pagination->countAll('menus', 'WHERE user_id = ' . $user->data()->id . ' AND quantity < 1');

$paginate = new Pagination($next, $per_page, $total_record);
$items = $menus->getPages($per_page, $paginate->offset(), $searchTerm, 'ORDER BY date_added DESC');

// $items = $menus->getAll($user->data()->id, 'user_id', '=');

$item = Input::get('sub') && Input::get('sub') == 'edit' && Input::get('sub1') && is_numeric(Input::get('sub1')) ? $menus->get(Input::get('sub1')) : null;

$category_list =  $categories->getAll(0, 'parent_id', '=');
$category_sub_list = $item ? $categories->getAll($item->parent_category, 'parent_id', '=') : null;

$form_data = Session::exists('form-data') ? Session::get('form-data') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<div class="container-fluid container-md">
    <div class="row gy-4 align-items-center mb-4">
        <div class="col-md-12 mx-auto">
            <nav class="mb-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-sa-simple">
                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Menus</li>
                </ol>
            </nav>
            <div class="d-flex align-items-end justify-content-between">
                <div>
                    <h1 class="h3 mb-2">Menus</h1>
                    <p class="mb-1">Manage your Menus</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Nav tabs-->
            <ul class="nav nav-pill nav-fill border-bottom mb-4" role="tablist">
                <li class="col-6 col-lg-3 nav-item mb-2 mb-lg-3 px-0">
                    <a class="ps-0 pe-2 nav-link <?= !$status || $status == 'all' ? 'active' : null; ?>" href="dashboard/menus?status=all" role="tab" aria-selected="true">
                        <div class="card shadow stats border-0">
                            <div class="card-body text-start">
                                <div class="d-flex justify-content-between mb-3">
                                    <img src="assets/icons/All Products.svg" style="width: 40px;">
                                    <i class="fa fa-external-link-alt"></i>
                                </div>
                                <p class="fs-14p fw-bold mb-0">All Menus</p>
                                <h3 class="title mb-0"><?= $all_total_record ?></h3>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="col-6 col-lg-3 nav-item mb-2 mb-lg-3 px-0">
                    <a class="px-2 nav-link <?= $status && $status == 'public' ? 'active' : null; ?>" href="dashboard/menus?status=public" role="tab" aria-selected="true">
                        <div class="card shadow stats border-0  bg-green-shade">
                            <div class="card-body text-start">
                                <div class="d-flex justify-content-between mb-3">
                                    <img src="assets/icons/Published Products.svg" style="width: 40px;">
                                    <i class="fa fa-external-link-alt"></i>
                                </div>
                                <p class="fs-14p fw-bold mb-0">Special Menus</p>
                                <h3 class="title mb-0"><?= $pub_total_record ?></h3>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="col-6 col-lg-3 nav-item mb-2 mb-lg-3 px-0">
                    <a class="px-2 nav-link <?= $status && $status == 'unpublished' ? 'active' : null; ?>" href="dashboard/menus?status=unpublished" role="tab" aria-selected="false">
                        <div class="card shadow stats border-0 bg-yellow-shade">
                            <div class="card-body text-start">
                                <div class="d-flex justify-content-between mb-3">
                                    <img src="assets/icons/Closed Products.svg" style="width: 40px;">
                                    <i class="fa fa-external-link-alt"></i>
                                </div>
                                <p class="fs-14p fw-bold mb-0">Closed Products</p>
                                <h3 class="title mb-0"><?= $clo_total_record ?></h3>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="col-6 col-lg-3 nav-item mb-2 mb-lg-3 px-0">
                    <a class="ps-2 pe-0 nav-link <?= $status && $status == 'soldout' ? 'active' : null; ?>" href="dashboard/menus?status=soldout" role="tab" aria-selected="false">
                        <div class="card shadow stats border-0 bg-primary-shade">
                            <div class="card-body text-start">
                                <div class="d-flex justify-content-between mb-3">
                                    <img src="assets/icons/Sold Out Products.svg" style="width: 40px;">
                                    <i class="fa fa-external-link-alt"></i>
                                </div>
                                <p class="fs-14p fw-bold mb-0">Sold Out Products</p>
                                <h3 class="title mb-0"><?= $sol_total_record ?></h3>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php if ($searchTerm || $items) { ?>
        <div class="row gy-4 mb-4">
            <div class="col-lg-6">
                <div class="d-flex">
                    <button class="btn bg-accent me-3 text-white" data-bs-toggle="modal" data-bs-target="#newProductModal">Add New Menu</button>
                    <button class="btn bg-primary text-white" data-bs-toggle="modal" data-bs-target="#newSpecialMenuModal">Add New Special</button>
                </div>
            </div>

            <div class="col-lg-6">
                <form action="">
                    <div class="input-group input-group-lg">
                        <input type="text" name="search" class="form-control" placeholder="Search product. . ." aria-label="Search Product" aria-describedby="search">
                        <div class="input-group-append">
                            <input type="hidden" name="status" value="<?= $status ?>" ?>
                            <button type="submit" class="btn bg-primary border-0" style="font-size: 12px;" type="button">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>

    <div class="row gy-4">
        <?php if ($items) { ?>
            <?php foreach ($items as $k => $v) {
                $category = $categories->get($v->category);
            ?>
                <!-- Item-->
                <div class="col-lg-6">
                    <div class="card card-hover card-horizontal border-0 shadow-sm mb-4">
                        <div class="row no-gutters">
                            <div class="col-md-4 seller-product-image">
                                <img src="<?= SITE_URL ?>assets/images/menus/<?= $v->cover ?>" alt="" class="img-fluid" style="height:156px;">
                            </div>
                            <div class="col-md-8">
                                <div class="position-relative py-3 pe-3">
                                    <div class="d-flex align-items-start justify-content-between">
                                        <div class="">
                                            <p class="mb-2 fs-base"><?= $v->title; ?></p>
                                            <div class="fw-bold">
                                                <span class="d-inline-block fs-sm"><?= $category->title; ?></span>
                                                <span class="mx-2">|</span>
                                                <span><?= Helpers::format_currency($v->price); ?></span>
                                            </div>
                                        </div>
                                        <div class="dropdown dropstart top-0 end-0" style="z-index: 5;">
                                            <button class="bg-accent icon-40 rounded-circle shadow-sm" type="button" id="contextMenu1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                    <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                                </svg>
                                            </button>
                                            <ul class="dropdown-menu border-0 dropdown-menu-right my-1" aria-labelledby="contextMenu1">
                                                <li><a class="dropdown-item" href="dashboard/manage-menus/edit/<?= $v->id; ?>">Edit</a></li>
                                                <li><a onclick="return confirm('Are you sure you want to make this product <?= $v->status ? 'unpublished' : 'public'; ?>?')" href="controllers/menus.php?rq=status&id=<?= $v->id; ?>" class="dropdown-item"><?= $v->status ? 'unpublished' : 'public'; ?></a></li>
                                                <li><a onclick="return confirm('Are you sure you want to delete this product?')" href="controllers/menus.php?rq=delete&id=<?= $v->id; ?>" class="dropdown-item text-danger">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-center justify-content-sm-start border-top pt-3 pb-2 mt-3 text-nowrap">
                                        <span class="d-inline-block mr-4 fs-sm <?= $v->status ? 'text-success' : 'text-danger'; ?>"><?= $v->status ? 'Public' : 'Hidden'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="col-md-12">
                <div class="card h-100 py-5">
                    <div class="card-body">
                        <div class="h-100 d-flex align-items-center justify-content-center py-5">
                            <div class="text-center">
                                <i class="fa fa-error fa-3x"></i>
                                <?php if ($searchTerm) { ?>
                                    <h3 class="mt-4 font-weight-bold">No result found for you filter.</h3>
                                <?php } else { ?>
                                    <h3 class="mt-4 font-weight-bold">Product not available?</h3>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if ($paginate && $paginate->total_pages() > 1) { ?>
        <nav class="mt-5 mb-4" aria-label="Page navigation sample">
            <ul class="pagination">
                <li class="page-item <?= $paginate->has_previous_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/menus?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=<?= $paginate->previous_page() ?>">Previous</a></li>
                <?php if ($paginate->total_pages() > 2) { ?>
                    <li class="page-item <?= Input::get('p') && Input::get('p') == 1 ? 'active' : null; ?>"><a class="page-link" href="dashboard/menus?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=1">1</a></li>
                    <li class="page-item <?= Input::get('p') && Input::get('p') == 2 ? 'active' : null; ?>"><a class="page-link" href="dashboard/menus?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=2">2</a></li>
                    <li class="page-item <?= Input::get('p') && Input::get('p') == 3 ? 'active' : null; ?>"><a class="page-link" href="dashboard/menus?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=3">3</a></li>
                <?php } ?>

                <li class="page-item disabled">
                    <a class="page-link"><?= $next . ' of ' . $paginate->total_pages() ?></a>
                </li>

                <?php if ($paginate->total_pages() > 4) { ?>
                    <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 4 ? 'active' : null; ?>"><a class="page-link" href="dashboard/menus?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=4">4</a></li>
                    <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 5 ? 'active' : null; ?>"><a class="page-link" href="dashboard/menus?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=5">5</a></li>
                <?php } ?>
                <li class="page-item <?= $paginate->has_next_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/menus?status=<?= Input::get('status') ? Input::get('status') : 'all' ?>&p=<?= $paginate->next_page() ?>">Next</a></li>
            </ul>
        </nav>
    <?php } ?>
</div>