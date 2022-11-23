<?php
$user = new User();
$menus = new General('menus');
$menu_list = $menus->getAll($user->data()->id, 'user_id', '=');

$title = $data && isset($data['title']) ? $data['title'] : null;
$addons = $data && isset($data['data']) ? $data['data'] : null;
$form_data = $data && isset($data['form_data']) ? $data['form_data'] : null;
?>

<?php if (!$addons) { ?>
    <div class="repeater d-md-flex">
        <div class="col-lg-3">
            <label for="addons" class="form-label">Addons</label>
        </div>
        <div class="col-lg-9">
            <div data-repeater-list="addons" class="mb-3">
                <div data-repeater-item>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <input type="text" name="title" value="" id="addons" class="form-control form-control-lg" placeholder="Add more Protein?" required>
                        </div>

                        <div class="col-lg-4">
                            <!-- innner repeater -->
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary--light border-end-0 fs-12p" id="inputGroup-sizing-sm">₦</span>
                                <input type="text" name="price" value="0" class="border-start-0 form-control" placeholder="Price" />
                            </div>
                        </div>

                        <div class="col-1">
                            <a href="javascript:;" data-repeater-delete class="btn"><i class="fa fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <a href="javascript:;" data-repeater-create class="text-accent">Add Another Addon</a>
        </div>
    </div>
<?php } else { ?>
    <div class="repeater d-md-flex">
        <div class="col-lg-3">
            <label for="addons" class="form-label">Addons</label>
        </div>
        <div class="col-lg-9">
            <div data-repeater-list="addons" class="mb-3">
                <?php foreach ($addons as $k => $v) { ?>
                    <div data-repeater-item>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <input type="text" name="title" value="<?= $v->addon; ?>" id="addons" class="form-control form-control-lg" placeholder="Add more Protein?" required>
                            </div>

                            <div class="col-lg-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-primary--light border-end-0 fs-12p" id="inputGroup-sizing-sm">₦</span>
                                    <input type="text" name="price" value="0" class="border-start-0 form-control" placeholder="Price" />
                                </div>
                                <input type="hidden" name="id" value="<?= $v->id; ?>">
                            </div>

                            <div class="col-1">
                                <a href="javascript:;" data-repeater-delete class="btn"><i class="fa fa-trash-alt"></i></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <a href="javascript:;" data-repeater-create class="text-accent">Add Another Addon</a>
        </div>
    </div>
<?php } ?>