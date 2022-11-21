<?php
// include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;

$pages = new General('pages');
$items = $pages->getAll();

$item = Input::get('action') && Input::get('action') == 'edit' && Input::get('sub') && is_numeric(Input::get('sub')) ? $pages->get(Input::get('sub')) : null;

Alerts::displayError();
Alerts::displaySuccess();
?>
<div id="top" class="sa-app__body px-2 px-lg-4">
    <div class="py-5">
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
                                    <h4 class="card-title">Pages</h4>
                                    <h6 class="card-subtitle"><?= Input::get('action') ? ucwords(Input::get('action')) . ' pages' : 'List of all pages' ?></h6>
                                </div>
                                <a href="pages<?= !Input::get('action') ? '/add' : null ?>" class="btn btn-sm bg-accent shadow-sm"><i class="fas <?= Input::get('action') ? 'fa-list' : 'fa-plus' ?> fa-sm text-white-50"></i> <?= Input::get('action') ? 'List' : 'Add' ?></a>
                            </div>
                            <hr>
                            <?php if (Input::get('action')) { // managing category
                                if (Input::get('action') == 'add' || Input::get('action') == 'edit') { // add / Edit
                            ?>
                                    <div class="col-md-6 mx-auto">
                                        <form class="" action="controllers/pages.php" enctype="multipart/form-data" method="post">
                                            <div class="form-row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" id="title" value="<?= ($item) ? $item->title : null; ?>" class="form-control">
                                                    </select>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label for="content">Content</label>
                                                    <textarea name="content" id="content" class="form-control summernote"><?= ($item) ? $item->content : null; ?></textarea>
                                                </div>


                                                <div class="col-md-12 form-group">
                                                    <label for="status">Status</label>
                                                    <select name="status" id="status" class="custom-select">
                                                        <option value="public">Public</option>
                                                        <option value="hidden">Hidden</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group mt-4">
                                                <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                                                <input type="hidden" name="rq" value="<?= $item ? 'edit' : 'add'; ?>">
                                                <input type="hidden" name="id" value="<?= $item ? $item->id : null; ?>">
                                                <button type="submit" class="btn bg-accent"><?= $item ? 'Edit' : 'Add'; ?></button>
                                            </div>

                                        </form>
                                    </div>

                                <?php }
                            } else { ?>

                                <?php if ($items) { // list of pages 
                                ?>
                                    <div class="table-responsive">
                                        <table id="zero_config" class="table table-striped table-bordered">
                                            <thead class="bg-gray">
                                                <tr>
                                                    <th class="col-md-8 font-weight-bold">Title</th>
                                                    <th class="text-center font-weight-bold">Status</th>
                                                    <th class="text-center font-weight-bold">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($items as $k => $v) { ?>
                                                    <tr>
                                                        <td class="text-accent font-weight-bold"><?= $v->title; ?></td>
                                                        <td class="text-center text-accent font-weight-bold"><?= $v->status ? '<i class="fa fa-circle text-success"></i>' : '<i class="fa fa-circle text-danger"></i>'; ?></td>
                                                        <td class="text-center">
                                                            <div class="btn-group flex-wrap">
                                                                <a href="controllers/pages.php?rq=status&id=<?= $v->id; ?>" class="btn waves-effect waves-light btn-sm bg-accent"><i class="mdi <?= $con->status ? 'mdi-eye' : 'mdi-eye-off'; ?>"></i> status</a>
                                                                <a href="pages/edit/<?= $v->id; ?>" class="btn btn-sm bg-main"><i class='fa fa-edit'></i> Edit</a>
                                                                <a href="controllers/pages.php?rq=delete&id=<?= $v->id; ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
    </div>
</div>