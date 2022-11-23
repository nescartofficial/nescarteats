<?php
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$world = new World();

$items = $user->getAll(1, 'type', "=");

$notif = Input::get('action') && Input::get('action') == 'notification' && Input::get('sub') && is_numeric(Input::get('sub')) ? $user->get(Input::get('sub')) : null;
$vuser = Input::get('action') && Input::get('action') == 'view' && Input::get('sub') && is_numeric(Input::get('sub')) ? $user->get(Input::get('sub')) : null;

Alerts::displayError();
Alerts::displaySuccess();
?>


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
                            <h4 class="card-title">Suppliers</h4>
                            <h6 class="card-subtitle"><?= Input::get('action') ? ucwords(Input::get('action')) : 'List of all category' ?></h6>
                        </div>
                        <a href="notifications<?= !Input::get('action') ? '/add' : null ?>" class="btn btn-sm bg-accent shadow-sm"><i class="fas <?= Input::get('action') ? 'fa-list' : 'fa-plus' ?> fa-sm text-white-50"></i> <?= Input::get('action') ? 'List' : 'Add' ?></a>
                    </div>
                    <hr>
                    <?php if (Input::get('action')) { ?>

                        <?php if (Input::get('action') == 'view' && $vuser) {
                            $profile = $user->getSupplier($vuser->id, 'user_id'); ?>
                            <div class="col-md-8 mx-auto">
                                <div class="card-header py-2 bg-dark d-flex justify-content-between align-items-center">
                                    <p class="mb-0 font-weight-bold text-white"><?= $vuser->first_name . ' ' . $vuser->last_name ?></p>
                                    <div class="btn-group">
                                        <a class="mr-3 text-accent text-white" href="controllers/suppliers.php?rq=status&id=<?= $profile->id; ?>" class="btn bg-site-accent"><?= $profile->status ? '<i class="fa fa-eye"></i> public' : '<i class="fa fa-eye-slash"></i> hidden'; ?></a>
                                        <a class="text-white text-accent" href="controllers/suppliers.php?rq=featured&id=<?= $profile->id; ?>" class="btn bg-site-accent"><?= $profile->featured ? '<i class="fa fa-box-open"></i> featured' : '<i class="fa fa-box"></i> unfeatured'; ?></a>
                                    </div>
                                </div>
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <p class="font-weight-bold mb-0">Store</p>
                                        <p class="mb-1"><?= $profile->name ?></p>
                                        <p class=""><?= $profile->about ?></p>

                                        <p class="font-weight-bold mb-0">Contact</p>
                                        <p class="mb-1"><?= $profile->email ?></p>
                                        <p class=""><?= $profile->phone ?></p>

                                        <p class="font-weight-bold mb-0">Location/Address</p>
                                        <p class="mb-1"><?= $world->getCountryName($profile->country) ?></p>
                                        <p class="mb-1"><?= $world->getStateName($profile->state) ?>, <?= $world->getCityName($profile->city); ?></p>
                                        <p><?= $profile->address ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (Input::get('action') == 'notification' && $notif) { ?>
                            <div class="col-md-6 mx-auto">
                                <form action="controllers/notifications.php" method="post">
                                    <div class="form-group">
                                        <label for="user">Selected user/s</label>
                                        <input type="text" name="user-name" value="<?= $notif ? $notif->last_name . ', ' . $notif->first_name : null; ?>" id="user" class="form-control" disabled />
                                    </div>
                                    <div class="form-group">
                                        <label for="Subject">Subject</label>
                                        <input type="text" name="subject" id="subject" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea type="text" name="message" id="message" class="summernote form-control"></textarea>
                                    </div>
                                    <div class="form-group mt-4">
                                        <input type="hidden" name="rq" value="add">
                                        <input type="hidden" name="backto" value="suppliers">
                                        <input type="hidden" name="user" value="<?= $notif ? $notif->id : null; ?>">
                                        <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                                        <button type="submit" class="btn bg-accent">Add</button>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>

                    <?php } else { ?>

                        <?php if ($items) { ?>
                            <div class="table-responsive">
                                <table id="zero_config" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $k => $v) {
                                            $name = $v->last_name . ', ' . $v->first_name;
                                            if ($v->group > 1) {
                                                continue;
                                            } ?>
                                            <tr>
                                                <td><?= $name; ?></td>
                                                <td><?= $v->email; ?></td>
                                                <td class="text-center">
                                                    <div class="btn-group flex-wrap">
                                                        <a href="suppliers/view/<?= $v->id ?>" class="btn waves-effect waves-light btn-sm bg-accent"><i class="fa fa-user"></i> view</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php } else { ?>
                            <p class="font-weight-bold">No user available</p>
                        <?php } ?>

                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
</div>