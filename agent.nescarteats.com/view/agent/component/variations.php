<?php
$categories = new General('categories');
$cart = new Cart();

$title = $data && isset($data['title']) ? $data['title'] : null;
$variations = $data && isset($data['data']) ? $data['data'] : null;
$type = $data && isset($data['type']) ? $data['type'] : null;
?>

<?php if (!$variations) { ?>
    <div class="repeater d-md-flex">
        <div class="col-lg-3">
            <label for="variations" class="form-label">Variations</label>
        </div>
        <div class="col-lg-9">
            <div data-repeater-list="variations" class="mb-3">
                <div data-repeater-item>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <input type="text" name="title" value="" id="variations" class="form-control form-control-lg" placeholder="Heated in the microwave?" required>
                        </div>

                        <div class="col-lg-4">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary--light border-end-0 fs-12p" id="inputGroup-sizing-sm">₦</span>
                                <input type="text" name="price" value="0" class="border-start-0 form-control" placeholder="Price" />
                            </div>
                        </div>

                        <div class="col">
                            <a href="javascript:;" data-repeater-delete class="btn"><i class="fa fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <a href="javascript:;" data-repeater-create class="text-accent">Add Another Variation</a>
        </div>
    </div>
<?php } else { ?>
    <div class="repeater d-md-flex">
        <div class="col-lg-3">
            <label for="variations" class="form-label">Variations</label>
        </div>
        <div class="col-lg-9">
            <div data-repeater-list="variations" class="mb-3">
                <?php foreach ($variations as $k => $v) { ?>
                    <div data-repeater-item>
                        <div class="row mb-3">
                            <div class="col-lg-6">
                                <input type="text" name="title" value="<?= $v->variation ?>" id="variations" class="form-control form-control-lg" placeholder="Heated in the microwave?" required>
                            </div>

                            <div class="col-lg-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-primary--light border-end-0 fs-12p" id="inputGroup-sizing-sm">₦</span>
                                    <input type="text" name="price" value="0" class="border-start-0 form-control" placeholder="Price" />
                                </div>
                                <input type="hidden" name="id" value="<?= $v->id; ?>">
                            </div>

                            <div class="col">
                                <a href="javascript:;" data-repeater-delete class="btn"><i class="fa fa-trash-alt"></i></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <a href="javascript:;" data-repeater-create class="text-accent">Add Another Variation</a>
        </div>
    </div>
<?php } ?>