<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$reviews = new General('reviews');
$orders = new General('orders');
$sellers = new General('sellers');
$products = new General('products');

$uid = $user->data()->id;
$sbid = Input::exists() && Input::get('content') ? Input::get('content') : null;
$searchTerm = $sbid ? "WHERE title LIKE '%{$sbid}%' OR subtitle LIKE '%{$sbid}%' OR text LIKE '%{$sbid}%' AND status = '1'" : "WHERE user_id = {$uid}";

$next = Input::get('p') ? Input::get('p') : 1;
$per_page = 4;
$pagination = new Pagination();
$total_record = $pagination->countAll('reviews', $searchTerm);
$paginate = new Pagination($next, $per_page, $total_record);
$items = $reviews->getPages($per_page, $paginate->offset(), $searchTerm);

Alerts::displayError();
Alerts::displaySuccess();
?>


<div class="container-fluid container-md">
    <div class="row">
        <div class="col-md-12 mb-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reviews</li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0">Reviews</h1>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-12" style="min-height: 580px;">
            <div class="card h-100">
                <div class="card-body">
                    
                    <?php if ($items) { ?>
                        
                        <header class="d-flex mb-4">
                            <img class="mr-3" src="assets/icons/Reviews.svg" alt="mp icon"/> 
                            <div class="">
                                <h5 class="">Your Reviews </h5>
                                <p class="mb-0">List of all your reviews for products you order.</p>
                            </div>
                        </header>
                        
                        <div class="list-group list-group-flush">
                            <?php foreach ($items as $index => $con) {
                                $product = $products->get($con->product_id);
                
                                $status = $con->status ? 'Read' : 'Unread';
                                $status_color = $con->status == 1 ? 'text-green' : 'text-yellow'; ?>
                                
                                    <div class="list-group-item list-group-item-action">
                                        <div class="row d-flex align-items-center justify-content-between">
                                            <div class="col-md-6 d-flex align-items-center">
                                                <img src="media/images/product/<?= $product->cover; ?>" alt="" class="img-fluid mr-3" style="height: 65px; width: 65px; object-fit: cover;">
                                                <div class="w-100 mt-2">
                                                    <h6 class="mb-1"><?= $product->title ?></h6>
                                                    <p class="text-truncate"><?= $con->review ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md"><p class="text-right"><a href="dashboard/review-details?review=<?= $con->id ?>" class="text-site-accent">Manage</a></p></div>
                                        </div>
                                    </div>
                            <?php } ?>
                        </div>
                        
                        
                        <?php if ($paginate && $paginate->total_pages() > 1) { // Pagination ?>
                            <nav class="mt-5 mb-4" aria-label="Page navigation sample">
                                <ul class="pagination">
                                    <li class="page-item <?= $paginate->has_previous_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/reviews?p=<?= $paginate->previous_page() ?>">Previous</a></li>
                                    <?php if ($paginate->total_pages() > 2) { ?>
                                        <li class="page-item <?= Input::get('p') && Input::get('p') == 1 ? 'active' : null; ?>"><a class="page-link" href="dashboard/reviews?p=1">1</a></li>
                                        <li class="page-item <?= Input::get('p') && Input::get('p') == 2 ? 'active' : null; ?>"><a class="page-link" href="dashboard/reviews?p=2">2</a></li>
                                        <li class="page-item <?= Input::get('p') && Input::get('p') == 3 ? 'active' : null; ?>"><a class="page-link" href="dashboard/reviews?p=3">3</a></li>
                                    <?php } ?>
                                    <li class="page-item disabled">
                                      <a class="page-link"><?= $next .' of '. $paginate->total_pages() ?></a>
                                    </li>
                                    <?php if ($paginate->total_pages() > 4) { ?>
                                        <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 4 ? 'active' : null; ?>"><a class="page-link" href="dashboard/reviews?p=4">4</a></li>
                                        <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 5 ? 'active' : null; ?>"><a class="page-link" href="dashboard/reviews?p=5">5</a></li>
                                    <?php } ?>
                                    <li class="page-item <?= $paginate->has_next_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/reviews?p=<?= $paginate->next_page() ?>">Next</a></li>
                                </ul>
                            </nav>
                        <?php } ?>
                    <?php }else{ ?>
                        <div class="h-100 d-flex align-items-center justify-content-center py-5">
                            <div class="text-center">
                                <i class="fa fa-shopping-cart fa-3x"></i>
                                <h3 class="mt-4 font-weight-bold">You have placed no orders yet!</h3>
                                <p class="mb-4">All your orders will be saved here for you to access their state anytime.</p>
                                <a href="categories" class="btn bg-site-primary border-0">Start Shopping</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>