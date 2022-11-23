<?php
$categories = new General('categories');
$cart = new Cart();

$title = $data && isset($data['title']) ? $data['title'] : null;
$serving_with = $data && isset($data['data']) ? $data['data'] : null;
$type = $data && isset($data['type']) ? $data['type'] : null;
?>

<?php if (!$serving_with) { ?>
    <div class="d-md-flex">
        <div class="col-lg-3">
            <label for="serving_with" class="form-label">Serving With</label>
        </div>
        <div class="col-lg-9">
            <div data-repeater-list="serving_with" class="mb-3">
                <div data-repeater-item>
                    <div class="row mb-3">
                        <div class="col-lg-5">
                            <input type="text" name="title" value="" id="serving_with" class="form-control form-control-lg" placeholder="Heated in the microwave?">
                        </div>

                        <div class="col-lg-6">
                            <!-- innner repeater -->
                            <div class="inner-repeater">
                                <div data-repeater-list="list">
                                    <div data-repeater-item class="d-flex gap-2 mb-2">
                                        <input type="text" name="option" value="" class="form-control" placeholder="Yes" />
                                        <button data-repeater-delete type="button" class="btn btn-link"><i class="fa fa-trash"></i></button>
                                    </div>
                                    <div data-repeater-item class="d-flex gap-2 mb-2">
                                        <input type="text" name="option" value="" class="form-control" placeholder="No" />
                                        <button data-repeater-delete type="button" class="btn btn-link"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                                <button data-repeater-create type="button" class="btn">Add</button>
                            </div>
                        </div>

                        <div class="col-1">
                            <a href="javascript:;" data-repeater-delete class="text-primary"><i class="fa fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="javascript:;" data-repeater-create class="text-accent">Add Another Serving With Option</a>
        </div>
    </div>
<?php } else { ?>
    <div class="d-md-flex">
        <div class="col-lg-3">
            <label for="serving_with" class="form-label">Serving With</label>
        </div>
        <div class="col-lg-9">
            <div data-repeater-list="serving_with" class="mb-3">
                <?php foreach ($serving_with as $k => $v) {
                    $list = $v->list; ?>
                    <div data-repeater-item>
                        <div class="row mb-3">
                            <div class="col-lg-5">
                                <input type="text" name="title" value="<?= $v->title ?>" id="serving_with" class="form-control form-control-lg" placeholder="Heated in the microwave?">
                            </div>

                            <div class="col-lg-6">
                                <!-- innner repeater -->
                                <div class="inner-repeater">
                                    <div data-repeater-list="list">
                                        <?php foreach ($list as $lk => $lv) { ?>
                                            <div data-repeater-item class="d-flex gap-2 mb-2">
                                                <input type="text" name="option" value="<?= $lv->option ?>" class="form-control" placeholder="Yes" />
                                                <button data-repeater-delete type="button" class="btn btn-link"><i class="fa fa-trash"></i></button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <button data-repeater-create type="button" class="btn">Add</button>
                                </div>
                            </div>

                            <div class="col-1">
                                <a href="javascript:;" data-repeater-delete class="text-primary"><i class="fa fa-trash-alt"></i></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <a href="javascript:;" data-repeater-create class="text-accent">Add Another Serving With Option</a>
        </div>
    </div>
<?php } ?>