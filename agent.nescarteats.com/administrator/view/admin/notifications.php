<?php
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$notifications = new General('notifications');
$notification_snippets = new General('notification_snippets');

$items = $notifications->getAll(0, 'user_id', "=");
$notification_state_change = Input::get('status') ? 'Unread Message' : 'Read Message';

$searchTerm = Input::get('status') ? "WHERE id > 0  AND user_id = 0 AND status = ".Input::get('status') : "WHERE id > 0  AND user_id = 0 AND status = 0";
$pagination = new Pagination();
$total_record = $pagination->countAll('notifications', $searchTerm);
$paginate = new Pagination($next, $per_page, $total_record);
$items = $searchTerm ? $notifications->getPages($total_record, 0, $searchTerm, 'ORDER BY date_added DESC') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>
<div id="top" class="sa-app__body">
    <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
        <div class="container">
            <div class="py-5">
                <div class="row g-4 align-items-center">
                    <div class="col">
                        <nav class="mb-2" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-sa-simple">
                                <li class="breadcrumb-item"><a href="index-2.html">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                            </ol>
                        </nav>
                        <h1 class="h3 m-0"><?= Input::get('status') ? 'Read' : 'Unread' ?> Notifications</h1>
                    </div>
                    <div class="col-auto d-flex">
                        <a href="notifications?status=<?= Input::get('status') ? 0 : 1 ?>" class="btn btn-secondary me-3">Show <?= $notification_state_change ?></a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="p-4"><input type="text" placeholder="Start typing to search" class="form-control form-control--search mx-auto" id="table-search" /></div>
                <div class="sa-divider"></div>
                <table class="sa-datatables-init" data-order="[[ 0, &quot;asc&quot; ]]" data-sa-search-input="#table-search">
                    <thead>
                        <tr>
                            <th class="">Date</th>
                            <th class="min-w-10x">Subject</th>
                            <th class="min-w-15x">Notification</th>
                            <th class="text-center">Status</th>
                            <th class="w-min" data-orderable="false"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $k => $v) {
                                $id = $v->id;
                                $date_added = $v->date_added;
                                $status = $v->status;
                                if($v->snippet_id){
                                    $v = $notification_snippets->get($v->snippet_id);
                                }
                        ?>
                            <tr>
                                <td>
                                    <p class="mb-1 fw-bold"><?= date_format(date_create($date_added), 'Y-m-d'); ?></p>
                                </td>
                                <td>
                                    <p class="mb-1 fw-bold"><?= $v->subject; ?></p>
                                </td>
                                <td>
                                    <p class="mb-1"><?= $v->message; ?></p>
                                </td>
                                <td class="text-center">
                                    <div class="badge badge-sa-primary"><?= $status ? 'Read' : 'Unread'; ?></div>
                                    <a class="dropdown-item text-primary" onclick="return confirm('Are you sure you want to change the state of this item?');" href="controllers/notifications.php?rq=status&id=<?= $id; ?>">change</a>
                                </td>
                                <td>
                                    <div class="dropdown"><button class="btn btn-sa-muted btn-sm" type="button" id="customer-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                            </svg></button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="customer-context-menu-0">
                                            <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/notifications.php?rq=delete&id=<?= $v->id; ?>">Delete</a></li>
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