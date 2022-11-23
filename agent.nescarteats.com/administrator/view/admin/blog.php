<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$categories = new General('categories');
$blogs = new General('blogs');

$items = $blogs->getAll();
$item = Input::get('action') && Input::get('action') == 'edit' && Input::get('sub') && is_numeric(Input::get('sub')) ? $blogs->get(Input::get('sub')) : null;

$category_list = $categories->getAll();
$category_item = Input::get('action') && Input::get('action') == 'edit' && Input::get('sub1') && is_numeric(Input::get('sub1')) ? $categories->get(Input::get('sub1')) : null;

$form_data = Session::exists('form_data') ? Session::get('form_data') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>
<div id="top" class="sa-app__body">
    <div class="mx-sm-2 px-2 px-sm-3 px-xxl-4 pb-6">
        <div class="container">
            <div class="row mt-4">
                <?php if (Input::get('action')) { ?>
                    <div class="col-lg-12">
                        <div class="card shadow mb-4 border-0">
                            <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between bg-site-primary">
                                <p class="m-0 fw-bold "><?= Input::get('action') && Input::get('action') == 'edit' ? 'Edit' : 'Add'; ?> Blog</p>
                                <?php if (Input::get('action') && Input::get('action') == 'edit') { ?>
                                    <a href="faqs" class="btn btn-primary btn-sm px-3">Back </a>
                                <?php } ?>
                            </div>
                            <div class="card-body">
                                <form action="controllers/blogs.php" method="post" enctype="multipart/form-data">
                                    <div class="form-floating mb-4">
                                        <input name="title" value="<?= $item ? $item->title : ($form_data ? $form_data['title'] : null) ?>" id="name" class="form-control slugit" data-slugit-target="#blog_slug" data-slugit-event="keyup" placeholder="">
                                        <label for="title">Title</label>
                                    </div>
            
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-floating mb-4">
                                                <textarea name="intro" id="intro" class="form-control" placeholder="" style="height: 120px;"><?= $item ? $item->intro : ($form_data ? $form_data['intro'] : null) ?></textarea>
                                                <label for="intro">Intro</label>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="form-floating mb-4">
                                        <textarea name="post" id="post" class="form-control sa-quill-control" placeholder="" style="height: 120px;"><?= $item ? $item->post : ($form_data ? $form_data['post'] : null) ?></textarea>
                                    </div>
            
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-4">
                                                <input type="date" name="date" value="<?= $item ? $item->date : ($form_data ? $form_data['date'] : date('Y-m-d', time())) ?>" id="date" class="form-control" placeholder="">
                                                <label for="date">Post Date</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-file mb-4">
                                                <input type="file" name="featured" id="featured" class="form-control form-control-lg" placeholder="">
                                            </div>
                                        </div>
            
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-4">
                                                <select name="featured" id="featured" class="form-control" placeholder="">
                                                    <option value="hidden" <?= $item && !$item->featured ? 'selected'  : null ?>>No</option>
                                                    <option value="public" <?= $item && $item->featured ? 'selected'  : null ?>>Yes</option>
                                                </select>
                                                <label for="featured">Featured?</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-4">
                                                <select name="status" id="status" class="form-control" placeholder="">
                                                    <option value="public" <?= $item && $item->status ? 'selected'  : null ?>>Public</option>
                                                    <option value="hidden" <?= $item && !$item->status ? 'selected'  : null ?>>Hidden</option>
                                                </select>
                                                <label for="status">Status</label>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="">
                                        <input type="hidden" name="rq" value="<?= $item ? 'edit' : 'add'; ?>">
                                        <input type="hidden" name="id" value="<?= $item ? $item->id : null; ?>">
                                        <input type="hidden" name="slug" id="blog_slug" value="<?= $item ? $item->slug : ($form_data ? $form_data['slug'] : null); ?>">
                                        <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                                        <button type="submit" class="btn btn-primary w-100"><?= $item ? "Edit" : "Add"; ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-lg-12">
                        <div class="card shadow mb-4 border-0">
                            <div class="card-header py-2 d-flex flex-row align-items-center justify-content-between bg-site-primary">
                                <p class="m-0 font-weight-bold ">Blog list</p>
                                <a href="blog/add" class="btn btn-primary">Add</a>
                            </div>
                            <div class="card-body">
                                <?php if ($items) { ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped data" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="col-2">Date</th>
                                                    <th class="col-4">Title</th>
                                                    <th class="col-2">Featured</th>
                                                    <th class="col-2">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
            
                                                <?php $count = 0;
                                                foreach ($items as $index => $con) {
                                                    $count++;
                                                ?>
                                                    <tr>
                                                        <td class=""><?= $con->date; ?></td>
                                                        <td class=""><?= $con->title; ?></td>
                                                        <td class=""><?= $con->featured ? 'Yes' : 'No'; ?></td>
                                                        <td class=""><?= $con->status ? 'public' : 'hidden'; ?></td>
                                                        <td class="text-center">
                                                            <div class="btn-group">
                                                                <a href="blog/edit/<?= $con->id; ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                                                <a onclick="return confirm('Deleting this item, are you sure?');" href="controllers/blogs.php?rq=delete&id=<?= $con->id; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
            
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } else {
                                    echo '<p>No item found.</p>';
                                } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>