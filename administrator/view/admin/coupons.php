<?php
// include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;

$coupons = new General('coupons');
$items = $coupons->getAll();

$item = Input::get('action') && Input::get('action') == 'edit' && Input::get('sub') && is_numeric(Input::get('sub')) ? $coupons->get(Input::get('sub')) : null;
$form_data = Session::exists('form_data_coupons') ? Session::get('form_data_coupons') : null;

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
                                    <li class="breadcrumb-item"><a href="coupons">Coupons</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Coupons</li>
                                </ol>
                            </nav>
                            <h1 class="h3 m-0"><?= $item ? 'Edit' : 'Add' ?> Coupons</h1>
                        </div>
                        <div class="col-auto d-flex">
                            <button type="submit" form="page_form" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
                <form action="controllers/coupons.php" method="post" enctype="multipart/form-data" name="page_form" id="page_form">
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
                                            <div class="col-lg-6 mb-4">
                                                <label for="title" class="form-label">Title</label>
                                                <input type="text" name="title" value="<?= $item ? $item->title : ($form_data ? $form_data['title'] : null); ?>" id="title" class="form-control">
                                            </div>
                                            <div class="col-lg-6 mb-4">
                                                <label for="form-category/code" class="form-label">Code</label>
                                                <input type="text" name="code" value="<?= $item ? $item->code : ($form_data ? $form_data['code'] : 'MPC-'.Helpers::getUnique(6, 'A')); ?>" id="form-category/code" class="form-control">
                                            </div>
                                            <div class="col-lg-6 mb-4">
                                                <label for="percentage" class="form-label">Percentage</label>
                                                <input type="number" value="<?= $item ? $item->percentage : ($form_data ? $form_data['percentage'] : null); ?>" min="0" name="percentage" id="percentage" class="form-control">
                                            </div>
                                            <div class="col-lg-3 mb-4">
                                                <label for="date_duration" class="form-label">Date</label>
                                                <input type="date" name="date_duration" value="<?= $item ? $item->date_duration : ($form_data ? $form_data['date_duration'] : null); ?>" id="date_duration" class="form-control">
                                            </div>
                                            <div class="col-lg-3 mb-4">
                                                <label for="time_duration" class="form-label">Time</label>
                                                <input type="time" name="time_duration" value="<?= $item ? $item->time_duration : ($form_data ? $form_data['time_duration'] : null); ?>" id="time_duration" class="form-control">
                                            </div>
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
        <div class="py-5">
            <div class="row g-4 align-items-center">
                <div class="col">
                    <nav class="mb-2" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-sa-simple">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Coupons</li>
                        </ol>
                    </nav>
                    <h1 class="h3 m-0">Coupons</h1>
                </div>
                <div class="col-auto d-flex"><a href="coupons/add" class="btn btn-primary">New</a></div>
            </div>
        </div>
        <div class="card">
            <div class="p-4"><input type="text" placeholder="Start typing to search" class="form-control form-control--search mx-auto" id="table-search" /></div>
            <div class="sa-divider"></div>
            <table class="sa-datatables-init" data-sa-search-input="#table-search">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th class="min-w-15x">Code</th>
                        <th>Percentage</th>
                        <th>Duration</th>
                        <th>Visibility</th>
                        <th class="w-min" data-orderable="false"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($items) { ?>
                        <?php foreach ($items as $index => $v) { ?>
                            <tr>
                                <td><?= $v->title; ?></td>
                                <td><?= $v->code; ?></td>
                                <td><?= $v->percentage; ?>%</td>
                                <td><?= date_format(date_create($v->date), 'Y-m-d'). ' @'.date_format(date_create($v->time), 'H:ia'); ?></td>
                                <td>
                                    <a href="controllers/coupons.php?rq=status&id=<?= $v->id; ?>">
                                        <?= $v->status ? '<div class="badge badge-sa-success">Visible</div>' : '<div class="badge badge-sa-danger">hidden</div>'; ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="dropdown"><button class="btn btn-sa-muted btn-sm" type="button" id="category-context-menu-0" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More"><svg xmlns="http://www.w3.org/2000/svg" width="3" height="13" fill="currentColor">
                                                <path d="M1.5,8C0.7,8,0,7.3,0,6.5S0.7,5,1.5,5S3,5.7,3,6.5S2.3,8,1.5,8z M1.5,3C0.7,3,0,2.3,0,1.5S0.7,0,1.5,0 S3,0.7,3,1.5S2.3,3,1.5,3z M1.5,10C2.3,10,3,10.7,3,11.5S2.3,13,1.5,13S0,12.3,0,11.5S0.7,10,1.5,10z"></path>
                                            </svg></button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="category-context-menu-0">
                                            <li><a class="dropdown-item" href="coupons/edit/<?= $v->id; ?>">Edit</a></li>
                                            <li>
                                                <hr class="dropdown-divider" />
                                            </li>
                                            <li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete this item?');" href="controllers/coupons.php?rq=delete&id=<?= $v->id; ?>">Delete</a></li>
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