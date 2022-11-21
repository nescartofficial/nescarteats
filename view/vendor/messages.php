<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$messages = new General('messages');
$message_snippets = new General('message_snippets');
$sellers = new General('sellers');
$products = new General('products');

$sbid = Input::exists() && Input::get('content') ? Input::get('content') : null;
$searchTerm = $sbid ? "WHERE title LIKE '%{$sbid}%' OR subtitle LIKE '%{$sbid}%' OR text LIKE '%{$sbid}%' AND status = '1'" : "WHERE user_id = " . $user->data()->id;

$next = isset($_GET['sub1']) ? trim($_GET['sub1']) : 1;
$per_page = 6;
$pagination = new Pagination();
$total_record = $pagination->countAll('messages', $searchTerm);
$paginate = new Pagination($next, $per_page, $total_record);
$items = $messages->getPages($per_page, $paginate->offset(), $searchTerm);

$item = Input::get('sub') && Input::get('sub') == 'read' && Input::get('sub1') && is_numeric(Input::get('sub1')) ? $messages->get(Input::get('sub1')) : null;
if($item && !$item->status){
    $messages->update(array('status' => 1), $item->id);
}

Alerts::displayError();
Alerts::displaySuccess();
?>


<?php if ($items || $item) { ?>
    <header class="container-lg mt-4 mt-lg-0 mb-5">
        <p class="mb-1">Dashboard | Messages</p>
        <h4>Messages</h4>
    </header>
    
    <?php if($item){ 
        
        $id = $item->id;
        $date_added = $item->date_added;
        $status = $item->status;
        if($item->snippet_id){
            $item = $message_snippets->get($item->snippet_id);
        }
    ?>
        <article class="card border-0 order-item mobile-card-section" style="min-height: 790px;">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <p>Sent on: <?= date_format(date_create($date_added), 'M d, Y'); ?></p>
                    
                    <div>
                        <a href="controllers/messages.php?rq=status&id=<?= $id ?>" class="text-site-accent"><?= $status ? 'Mark as Unread' : 'Mark as Read' ?></a>
                        <a onclick="return confirm('Are you sure?')" href="controllers/messages.php?rq=delete&id=<?= $id ?>" class="text-site-accent"><i class="fa fa-trash-alt"></i></a>
                    </div>
                </div>
                
                <h4 class="mb-4"><?= $item->subject; ?></h4>
                
                <div>
                    <?= $item->message; ?>    
                </div>
            </div>
        </article>
    <?php }else{ ?>
        <article class="card border-0 order-item mobile-card-section" style="min-height: 790px;">
            <div class="card-body">
                
                <header class="d-flex mb-4">
                    <img class="mr-3" src="assets/icons/Messages Header.svg" alt="mp icon"/> 
                    <div class="">
                        <h5 class="">Your Messages </h5>
                        <p class="mb-0">List of all messages sent to you.</p>
                    </div>
                </header>
                
                <ul class="list-group list-group-flush">
                    <?php foreach ($items as $index => $con) {
                        $id = $con->id;
                        $date_added = $con->date_added;
                        $status = $con->status;
                        if($con->snippet_id){
                            $con = $message_snippets->get($con->snippet_id);
                        } ?>
                        <div class="list-group-item d-flex justify-content-between">
                            <div class="col-md-10">
                                <span class="small"><?= date_format(date_create($date_added), 'M d, Y'); ?></span>
                                <h5><?= $con->subject ?></h5>
                                <div class="mt-3">
                                    <a href="dashboard/messages/read/<?= $id; ?>" class="mr-3 text-site-accent">Read Message</a>
                                    <span class="badge br-2 px-2 <?= $status ? 'bg-green' : 'bg-yellow' ?>"><?= $status ? 'Read' : 'Unread' ?></span>
                                </div>
                            </div>
                            <div class="d-none d-lg-block">
                                <a href="controllers/messages.php?rq=status&id=<?= $id ?>" class="text-site-accent mr-3"><?= $status ? 'Mark as Unread' : 'Mark as Read' ?></a>
                                <a onclick="return confirm('Are you sure?')" href="controllers/messages.php?rq=delete&id=<?= $id ?>" class="text-site-accent"><i class="fa fa-trash-alt"></i></a>
                            </div>
                        </div>
                    <?php } ?>
                </ul>
                
                <?php if ($paginate && $paginate->total_pages() > 1) { // Pagination ?>
                    <nav class="mt-5 mb-4" aria-label="Page navigation sample">
                        <ul class="pagination">
                            <li class="page-item <?= $paginate->has_previous_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/messages?p=<?= $paginate->previous_page() ?>">Previous</a></li>
                            <?php if ($paginate->total_pages() > 2) { ?>
                                <li class="page-item <?= Input::get('p') && Input::get('p') == 1 ? 'active' : null; ?>"><a class="page-link" href="dashboard/messages?p=1">1</a></li>
                                <li class="page-item <?= Input::get('p') && Input::get('p') == 2 ? 'active' : null; ?>"><a class="page-link" href="dashboard/messages?p=2">2</a></li>
                                <li class="page-item <?= Input::get('p') && Input::get('p') == 3 ? 'active' : null; ?>"><a class="page-link" href="dashboard/messages?p=3">3</a></li>
                            <?php } ?>
                            <li class="page-item disabled">
                              <a class="page-link"><?= $next .' of '. $paginate->total_pages() ?></a>
                            </li>
                            <?php if ($paginate->total_pages() > 4) { ?>
                                <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 4 ? 'active' : null; ?>"><a class="page-link" href="dashboard/messages?p=4">4</a></li>
                                <li class="d-none d-md-inline-block page-item <?= Input::get('p') && Input::get('p') == 5 ? 'active' : null; ?>"><a class="page-link" href="dashboard/messages?p=5">5</a></li>
                            <?php } ?>
                            <li class="page-item <?= $paginate->has_next_page() ? null : 'disabled'; ?>"><a class="page-link" href="dashboard/messages?p=<?= $paginate->next_page() ?>">Next</a></li>
                        </ul>
                    </nav>
                <?php } ?>
            </div>
        </article>
    <?php } ?>

    
<?php } else { ?>
    <header class="container-lg mt-4 mt-lg-0 mb-5">
        <p class="mb-1">Dashboard | Messages</p>
        <h4>Messages</h4>
    </header>
    
    <div class="card border-0" style="min-height: 790px;">
        <div class="card-body d-flex align-items-center justify-content-center">
            <div class="col-md-6 text-center mx-auto py-5">
                <i class="fa fa-bell fa-3x"></i>
                <h4 class="mt-4">You don't have any Messages</h4>
                <p>Here you will be able to see all the message that we send you. Stay tuned</p>
            </div>
        </div>
    </div>
<?php } ?>