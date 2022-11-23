<?php
$user = new User();
$menus = new General('menus');
$menu_list = $menus->getAll($user->data()->id, 'user_id', '=');

$title = $data && isset($data['title']) ? $data['title'] : null;
$serving_option = $data && isset($data['data']) ? $data['data'] : null;
$form_data = $data && isset($data['form_data']) ? $data['form_data'] : null;
?>

<?php if (!$serving_option) { ?>
    <div class="d-md-flex">
        <div class="col-lg-3">
            <label for="serving_option" class="form-label">Serving Options</label>
        </div>
        <div class="col-lg-9">
            <div data-repeater-list="serving_option" class="mb-3">
                <div data-repeater-item>
                    <div class="row mb-3">
                        <div class="col-lg-5">
                            <input type="text" name="title" value="" id="serving_option" class="form-control form-control-lg" placeholder="Add more Protein?">
                        </div>

                        <div class="col-lg-6">
                            <!-- innner repeater -->
                            <div class="inner-repeater">
                                <div data-repeater-list="list">
                                    <div data-repeater-item class="d-flex gap-2 mb-2">
                                        <select name="option[]" id="option" class="form-control form-select form-select-lg select2" multiple>
                                            <?php if ($menu_list) { ?>
                                                <?php foreach ($menu_list as $k => $v) { ?>
                                                    <option value="<?= $v->id; ?>" <?= ($form_data ? $form_data['serving_option'] : null); ?>><?= $v->title; ?></option>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <option value="">No menus available</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-1">
                            <a href="javascript:;" data-repeater-delete class="text-primary"><i class="fa fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="javascript:;" data-repeater-create class="text-accent">Add Another Option</a>
        </div>
    </div>
<?php } else { ?>
    <div class="d-md-flex">
        <div class="col-lg-3">
            <label for="serving_option" class="form-label">Serving Options</label>
        </div>
        <div class="col-lg-9">
            <div data-repeater-list="serving_option" class="mb-3">
                <?php foreach ($serving_option as $k => $v) {
                    $list = $v->list; ?>
                    <div data-repeater-item>
                        <div class="row mb-3">
                            <div class="col-lg-5">
                                <input type="text" name="title" value="<?= $v->title; ?>" id="serving_option" class="form-control form-control-lg" placeholder="Add more Protein?">
                            </div>

                            <div class="col-lg-6">
                                <!-- innner repeater -->
                                <div class="inner-repeater">
                                    <div data-repeater-list="list">
                                        <?php foreach ($list as $lk => $lv) { ?>
                                            <div data-repeater-item class="d-flex gap-2 mb-2">
                                                <select name="option[]" id="option" class="form-control form-select form-select-lg select2" multiple>
                                                    <?php if ($menu_list) { ?>
                                                        <?php foreach ($menu_list as $mk => $mv) { ?>
                                                            <?php foreach ($lv->option as $ok => $ov) {
                                                                $menu = $menus->get($ov);
                                                                $selected = $menu->id == $mv->id ? true : null; ?>
                                                            <?php } ?>
                                                            <option value="<?= $mv->id; ?>" <?= $selected ? 'selected' : ($form_data ? $form_data['serving_option'] : null); ?>><?= $mv->title; ?></option>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <option value="">No menus available</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-1">
                                <a href="javascript:;" data-repeater-delete class="text-primary"><i class="fa fa-trash-alt"></i></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <a href="javascript:;" data-repeater-create class="text-accent">Add Another Option</a>
        </div>
    </div>
<?php } ?>