<?php
// include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$Menus = new Menus();
$pagination = new Pagination();
$Vendors = new General('vendors');
$Categories = new General('categories');


$items = $Menus->getAll();
$title = "All Products";
$items = Input::get('show') && Input::get('show') == 'published' ? $Menus->getAll(1, 'status', '=') : $items;
$title = Input::get('show') && Input::get('show') == 'published' ? "Published Products" : $title;
$items = Input::get('show') && Input::get('show') == 'hidden' ? $Menus->getAll(0, 'status', '=') : $items;
$title = Input::get('show') && Input::get('show') == 'hidden' ? "Hidden Products" : $title;
$items = Input::get('show') && Input::get('show') == 'stock' ? $Menus->getAll(0, 'quantity', '=') : $items;
$title = Input::get('show') && Input::get('show') == 'stock' ? "Products out of Stock" : $title;
$items = Input::get('seller') ? $Menus->getAll(Input::get('seller'), 'seller_id', '=') : $items;
$title = Input::get('seller') ? "Seller Products" : $title;

// Header Counter
$total_count = $pagination->countAll('products', "WHERE id > 0");
$published_count = $pagination->countAll('products', "WHERE id > 0 AND status = 1");
$hidden_count = $pagination->countAll('products', "WHERE id > 0 AND status = 0");
$stock_count = $pagination->countAll('products', "WHERE id > 0 AND quantity = 0");

// Total Earning
$total_cost = $Menus->getAllSumWhere("price", "id > 0");

$item = Input::get('action') && Input::get('action') == 'edit' && Input::get('sub') && is_numeric(Input::get('sub')) ? $Menus->get(Input::get('sub')) : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<div id="top" class="sa-app__body">
    <div class="mx-xxl-3 px-4 px-sm-5">
        <div class="py-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0">Products</h1>
                </div>
                <!-- <div class="col-auto d-flex"><a href="#" class="btn btn-secondary me-3">Import</a><a href="app-product.html" class="btn btn-primary">New product</a></div> -->
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="mx-xxl-3 px-4 px-sm-5">
        <div class="container">
            <div class="row gy-5 mb-6">
                <div class="col-12 col-md-4 d-flex">
                    <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                        <div class="sa-widget-header saw-indicator__header">
                            <div>
                                <h2 class="sa-widget-header__title">Total Cost</h2>
                                <small class="text-muted">* Quantity not taking into account.</small>
                            </div>
                        </div>
                        <div class="saw-indicator__body">
                            <div class="saw-indicator__value"><?= Helpers::format_currency($total_cost); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 d-flex">
                    <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                        <div class="sa-widget-header saw-indicator__header">
                            <h2 class="sa-widget-header__title">Total Products</h2>
                        </div>
                        <div class="saw-indicator__body">
                            <div class="saw-indicator__value"><?= $total_count ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 d-flex">
                    <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                        <div class="sa-widget-header saw-indicator__header">
                            <h2 class="sa-widget-header__title">Published Products</h2>
                            <div class="sa-widget-header__actions">
                                <a href="products?show=published" class="active">view</a>
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
                            <h2 class="sa-widget-header__title">Hidden Products</h2>
                            <div class="sa-widget-header__actions">
                                <a href="products?show=hidden" class="active">view</a>
                            </div>
                        </div>
                        <div class="saw-indicator__body">
                            <div class="saw-indicator__value"><?= $hidden_count ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 d-flex">
                    <div class="card saw-indicator flex-grow-1" data-sa-container-query="{&quot;340&quot;:&quot;saw-indicator--size--lg&quot;}">
                        <div class="sa-widget-header saw-indicator__header">
                            <h2 class="sa-widget-header__title">Out of Stock</h2>
                            <div class="sa-widget-header__actions">
                                <a href="products?show=stock" class="active">view</a>
                            </div>
                        </div>
                        <div class="saw-indicator__body">
                            <div class="saw-indicator__value"><?= $stock_count ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="mx-xxl-3 px-4 px-sm-5 pb-6">
        <div class="container">
            <div class="sa-layout">
                <div class="sa-layout__content">
                    <div class="card">
                        <div class="p-4">
                            <h4 class=""><?= $title ?></h4>
                        </div>


                        <div class="p-4">
                            <div class="row g-4">
                                <div class="col-auto sa-layout__filters-button"><button class="btn btn-sa-muted btn-sa-icon fs-exact-16" data-sa-layout-sidebar-open=""><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor">
                                            <path d="M7,14v-2h9v2H7z M14,7h2v2h-2V7z M12.5,6C12.8,6,13,6.2,13,6.5v3c0,0.3-0.2,0.5-0.5,0.5h-2 C10.2,10,10,9.8,10,9.5v-3C10,6.2,10.2,6,10.5,6H12.5z M7,2h9v2H7V2z M5.5,5h-2C3.2,5,3,4.8,3,4.5v-3C3,1.2,3.2,1,3.5,1h2 C5.8,1,6,1.2,6,1.5v3C6,4.8,5.8,5,5.5,5z M0,2h2v2H0V2z M9,9H0V7h9V9z M2,14H0v-2h2V14z M3.5,11h2C5.8,11,6,11.2,6,11.5v3 C6,14.8,5.8,15,5.5,15h-2C3.2,15,3,14.8,3,14.5v-3C3,11.2,3.2,11,3.5,11z"></path>
                                        </svg></button></div>
                                <div class="col"><input type="text" placeholder="Start typing to search for products" class="form-control form-control--search mx-auto" id="table-search" /></div>
                            </div>
                        </div>
                        <div class="sa-divider"></div>
                        <table class="sa-datatables-init" data-order="[[ 0, &quot;desc&quot; ]]" data-sa-search-input="#table-search">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="min-w-20x">Product</th>
                                    <th>Category</th>
                                    <th>Seller</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th class="w-min" data-orderable="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $k => $v) {
                                    $category = $Categories->get($v->category);
                                    $vendor = $Vendors->get($v->vendor_id);
                                ?>
                                    <tr>
                                        <td><?= date_format(date_create($v->date_added), 'Y-m-d') ?></td>
                                        <td>
                                            <div class="d-flex align-items-center"><a href="<?= SITE_URL ?>product/<?= $v->slug ?>" target="_blank" class="me-4">
                                                    <div class="sa-symbol sa-symbol--shape--rounded sa-symbol--size--lg"><img src="<?= SITE_URL ?>assets/images/menus/<?= $v->cover ?>" width="40" height="40" alt="" style="object-fit:cover;" /></div>
                                                </a>
                                                <div><a href="<?= SITE_URL ?>product/<?= $v->slug ?>" target="_blank" class="text-reset"><?= $v->title; ?></a>
                                                    <div class="sa-meta mt-0">
                                                        <ul class="sa-meta__list">
                                                            <li class="sa-meta__item">ID: <span title="Click to copy product ID" class="st-copy"><?= $v->id; ?></span></li>
                                                            <li class="sa-meta__item">SKU: <span title="Click to copy product SKU" class="st-copy"><?= isset($v->sku) ? $v->sku : null; ?></span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><a href="<?= $category ? "categories/edit/{$category->id}" : "categories"; ?>" class="text-reset"><?= $category ? $category->title : null; ?></a></td>
                                        <td><a href="<?= $vendor ? "sellers/view/{$vendor->id}" : "sellers"; ?>" class="text-reset"><?= $vendor ? $vendor->name : null; ?></a></td>
                                        <td>
                                            <div class="badge badge-sa-success"><?= $v->quantity; ?> In Stock</div>
                                        </td>
                                        <td>
                                            <div class="badge badge-sa-success"><a href="controllers/products.php?rq=status&id=<?= $v->id; ?>" class=""><?= $v->status ? 'public' : 'hidden'; ?></a></div>
                                        </td>
                                        <td>
                                            <div class="sa-price"><span class="sa-price__symbol"></span><span class="sa-price__integer"><?= Helpers::format_currency($v->price); ?></span><span class="sa-price__decimal">.00</span></div>
                                        </td>
                                        <td>
                                            <div class="dropdown"><button class="btn btn-sa-muted btn-sm" type="button" id="product-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                        <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                                    </svg></button>
                                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="product-context-menu-0">
                                                    <li><a class="dropdown-item" href="<?= SITE_URL ?>product/<?= $v->slug ?>" target="_blank">View</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider" />
                                                    </li>
                                                    <li><a onclick="return confirm('Are you sure you want to delete this product?')" href="controllers/products.php?rq=delete&id=<?= $v->id; ?>" class="dropdown-item text-danger">Delete</a></li>
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
    </div>
</div>