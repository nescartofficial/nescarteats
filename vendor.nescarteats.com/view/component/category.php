<?php
$categories = new General('categories');
$cart = new Cart();

$title = $data && isset($data['title']) ? $data['title'] : null;
$category = $data ? $data['data'] : null;
$type = $data ? $data['type'] : null;
?>

<?php if ($category) { ?>
    <?php if ($type == 'list') { ?>
        <section class="category-component list">
            <header class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">Categories</h4>
            </header>

            <div class="spacer-seperator-sm"></div>
            <div class="category-slider">
                <?php foreach ($category as $k => $v) { ?>
                    <a href="dashboard/category/<?= $v->slug ?>" class="d-block shadow item mb-4">
                        <section class="shadow bg-white rounded p-2 p-md-3 h-100">
                            <div class="list">
                                <header class="img-hover-zoom mb-2">
                                    <img src="assets/images/category/<?= $v->image ?>" alt="<?= $v->title ?>" class="img-fluid rounded">
                                </header>
                                <div class="body text-center">
                                    <p class="title fs-14p text-truncate mb-0" data-bs-toggle="tooltip" title="<?= $v->title ?>"><?= $v->title ?></p>
                                </div>
                            </div>
                        </section>
                    </a>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
<?php } ?>