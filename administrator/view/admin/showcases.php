<?php
// include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$categories = new General('categories');
$ads = new General('ads');
$showcases = new General('showcases');
$items = $showcases->getAll();

$citems = $categories->getAll();
$aitems = $ads->getAll();

$item = Input::get('action') && Input::get('action') == 'edit' && Input::get('sub') && is_numeric(Input::get('sub')) ? $showcases->get(Input::get('sub')) : null;

Alerts::displayError();
Alerts::displaySuccess();
?>
<div id="top" class="sa-app__body">
    <div class="mx-xxl-3 px-4 px-sm-5">
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
                                        <h4 class="card-title">Home Showcases</h4>
                                        <h6 class="card-subtitle"><?= Input::get('action') ? ucwords(Input::get('action')) . ' showcase' : 'List of all showcase' ?></h6>
                                    </div>
                                    <a href="showcases<?= !Input::get('action') ? '/add' : null ?>" class="btn btn-sm bg-accent shadow-sm"><i class="fas <?= Input::get('action') ? 'fa-list' : 'fa-plus' ?> fa-sm text-white-50"></i> <?= Input::get('action') ? 'List' : 'Add' ?></a>
                                </div>
                                <hr>
                                <?php if (Input::get('action')) { // managing category
                                    if (Input::get('action') == 'add' || Input::get('action') == 'edit') { // add / Edit
                                ?>
                                        <div class="col-md-6 mx-auto">
                                            <form action="controllers/showcases.php" method="post">
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" value="<?= $item ? $item->title : null; ?>" id="title" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="category">Category</label>
                                                    <select name="category" id="category" class="custom-select">
                                                        <option value="default">Select Category</option>
                                                        <?php foreach ($citems as $k => $v) { ?>
                                                            <option value="<?= $v->id ?>" <?= $v->id == $item->category ? 'selected' : null; ?>><?= $v->title ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ads">ADS</label>
                                                    <select name="ads[]" id="ads" class="form-control select2" multiple="multiple" data-placeholder="Select a state..." style="width: 100%;" multiple>
                                                        <?php foreach ($aitems as $k => $v) { ?>
                                                            <option value="<?= $v->id ?>"><?= $v->title ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select name="status" id="status" class="custom-select">
                                                        <option value="public" <?= $item->status ? 'selected' : null; ?>>Public</option>
                                                        <option value="hidden" <?= !$item->status ? 'selected' : null; ?>>Hidden</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-4">
                                                    <input type="hidden" name="rq" value="<?= $item ? 'edit' : 'add'; ?>">
                                                    <input type="hidden" name="id" value="<?= $item ? $item->id : null; ?>">
                                                    <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                                                    <button type="submit" class="btn bg-accent"><?= $item ? 'Edit Showcase' : 'Add Showcase'; ?></button>
                                                </div>
                                            </form>
                                        </div>

                                    <?php }
                                } else { ?>

                                    <?php if ($items) { // list of showcases 
                                    ?>
                                        <div class="table-responsive">
                                            <table id="zero_config" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr class="bg-gray">
                                                        <th class="col  font-weight-bold">Title</th>
                                                        <th class="text-center font-weight-bold">Category</th>
                                                        <th class="text-center font-weight-bold">Status</th>
                                                        <th class="text-center font-weight-bold">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($items as $k => $v) {
                                                        $ctitle = $v->category && $categories->get($v->category) ? $categories->get($v->category)->title : null; ?>
                                                        <tr>
                                                            <td class="text-accent font-weight-bold"><?= $v->title; ?></td>
                                                            <td class="text-center"><?= $ctitle ? $ctitle : 'nil'; ?></td>
                                                            <td class="text-center text-accent font-weight-bold"><?= $v->status ? 'public' : 'hidden'; ?></td>
                                                            <td class="text-center">
                                                                <div class="btn-group flex-wrap">
                                                                    <a href="controllers/showcases.php?rq=status&id=<?= $v->id; ?>" class="btn waves-effect waves-light btn-sm bg-accent"><i class="mdi <?= $v->status ? 'mdi-eye' : 'mdi-eye-off'; ?>"></i> status</a>

                                                                    <a href="showcases/edit/<?= $v->id; ?>" class="btn waves-effect waves-light btn-sm bg-main"><i class="mdi mdi-table-edit"></i> edit</a>

                                                                    <a onclick="return confirm('Are you sure you want to delete?')" href="controllers/showcases.php?rq=delete&id=<?= $v->id; ?>" class="btn waves-effect waves-light btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
</div>