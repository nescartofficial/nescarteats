<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$notifications = new General('notifications');
$notification_snippets = new General('notification_snippets');
$sellers = new General('sellers');
$products = new General('products');

$sbid = Input::exists() && Input::get('content') ? Input::get('content') : null;
$searchTerm = $sbid ? "WHERE title LIKE '%{$sbid}%' OR subtitle LIKE '%{$sbid}%' OR text LIKE '%{$sbid}%' AND status = '1'" : "WHERE user_id = " . $user->data()->id;

$next = Input::get('p') ? Input::get('p') : 1;
$per_page = 6;
$pagination = new Pagination();
$total_record = $pagination->countAll('notifications', $searchTerm);
$paginate = new Pagination($next, $per_page, $total_record);
$items = $notifications->getPages($per_page, $paginate->offset(), $searchTerm);

$order = Input::get('sub') && Input::get('sub') == 'view' && Input::get('sub1') && is_numeric(Input::get('sub1')) ? $notifications->get(Input::get('sub1')) : null;
$order_details = $order ? json_decode($order->details, true) : null;

Alerts::displayError();
Alerts::displaySuccess();
?>


<?php if ($items) { ?>
    <header class="container-lg mt-4 mt-lg-0 mb-5">
        <p class="mb-1">Dashboard | Notifications</p>
        <h4>Notifications</h4>
    </header>
    
    <article class="card border-0 order-item mobile-card-section" style="min-height: 790px;">
        <div class="card-body">
            
            <header class="d-flex mb-4">
                <img class="mr-3" src="assets/icons/Notifications Header.svg" alt="mp icon"/> 
                <div class="">
                    <h5 class="">Your Notifications </h5>
                    <p class="mb-0">List of all your notification.</p>
                </div>
            </header>
            
            <ul class="list-group list-group-flush">
                <?php foreach ($items as $index => $con) {
                    $id = $con->id;
                    $date_added = $con->date_added;
                    $status = $con->status;
                    if($con->snippet_id){
                        $con = $notification_snippets->get($con->snippet_id);
                    } ?>
                    <div class="list-group-item d-flex justify-content-between">
                        <div class="col-12 col-lg-10">
                            <span class="small"><?= date_format(date_create($date_added), 'M d, Y'); ?></span>
                            <p><?= $con->message ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="badge <?= $status ? 'bg-green' : 'bg-yellow' ?> px-2 br-2p"><?= $status ? 'Read' : 'Unread' ?></span>
                                    <a href="controllers/notifications.php?rq=status&id=<?= $id ?>" class="font-weight-bold d-lg-none text-site-accent fs-12p ml-3"><?= $status ? 'Mark as Unread' : 'Mark as Read' ?></a>
                                </div>
                                <a href="controllers/notifications.php?rq=delete&id=<?= $id ?>" class="font-weight-bold d-lg-none text-site-accent fs-12p"><i class="fa fa-trash-alt"></i></a>
                            </div>
                        </div>
                        <div class="col-lg">
                            <a href="controllers/notifications.php?rq=status&id=<?= $id ?>" class="font-weight-bold d-none d-lg-inline-block text-site-accent fs-12p mr-3"><u><?= $status ? 'Mark as Unread' : 'Mark as Read' ?></u></a>
                            <a href="controllers/notifications.php?rq=delete&id=<?= $id ?>" class="font-weight-bold d-none d-lg-inline-block text-site-accent fs-12p"><i class="fa fa-trash-alt"></i></a>
                        </div>
                    </div>
                <?php } ?>
            </ul>
            
            <?php if ($paginate && $paginate->total_pages() > 1) { // Pagination ?>
                <nav class="mt-5 mb-4" aria-label="Page navigation sample">
                    <ul class="pagination">
                        <li class="page-item <?= $paginate->has_previous_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/notifications?p=<?= $paginate->previous_page() ?>">Previous</a></li>
                        <?php if ($paginate->total_pages() > 2) { ?>
                            <li class="page-item <?= Input::get('p') && Input::get('p') == 1 ? 'active' : null; ?>"><a class="page-link" href="dashboard/notifications?p=1">1</a></li>
                            <li class="page-item <?= Input::get('p') && Input::get('p') == 2 ? 'active' : null; ?>"><a class="page-link" href="dashboard/notifications?p=2">2</a></li>
                            <li class="page-item <?= Input::get('p') && Input::get('p') == 3 ? 'active' : null; ?>"><a class="page-link" href="dashboard/notifications?p=3">3</a></li>
                        <?php } ?>
                        <li class="page-item disabled">
                          <a class="page-link"><?= $next .' of '. $paginate->total_pages() ?></a>
                        </li>
                        <?php if ($paginate->total_pages() > 4) { ?>
                            <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 4 ? 'active' : null; ?>"><a class="page-link" href="dashboard/notifications?p=4">4</a></li>
                            <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 5 ? 'active' : null; ?>"><a class="page-link" href="dashboard/notifications?p=5">5</a></li>
                        <?php } ?>
                        <li class="page-item <?= $paginate->has_next_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/notifications?p=<?= $paginate->next_page() ?>">Next</a></li>
                    </ul>
                </nav>
            <?php } ?>
        </div>
    </article>

    
<?php } else { ?>
    <header class="container-lg mt-4 mt-lg-0 mb-5">
        <p class="mb-1">Dashboard | Notifications</p>
        <h4>Notifications</h4>
    </header>
    
    <div class="card border-0" style="min-height: 790px;">
        <div class="card-body d-flex align-items-center justify-content-center">
            <div class="col-md-6 text-center mx-auto py-5">
                <i class="fa fa-bell fa-3x"></i>
                <h4 class="mt-4">You don't have any notification</h4>
                <p>Here you will be able to see all the notifications that we send you. Stay tuned</p>
            </div>
        </div>
    </div>
<?php } ?>