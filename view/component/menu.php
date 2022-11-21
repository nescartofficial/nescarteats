<?php
$categories = new General('categories');
$vendors = new General('vendors');
$menus = new Menus();
$world = new World();
$cart = new Cart();

$title = $data && isset($data['title']) ? $data['title'] : null;
$meal = $data && isset($data['data']) ? $data['data'] : null;
$type = $data && isset($data['type']) ? $data['type'] : null;
$ids = $data && isset($data['ids']) ? $data['ids'] : null;
?>

<?php if ($meal) { ?>
    <?php if ($type == 'list') { ?>
        <section class="menu-component list">
            <?php if ($title) { ?>
                <header class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="mb-0"><?= $title ?></h4>
                </header>
            <?php } ?>

            <div class="spacer-seperator-sm"></div>
            <div class="row gx-3">
                <?php foreach ($meal as $k => $v) {
                    $v = $ids ? $menus->get($v) : $v;
                    $vendor = $vendors->get($v->vendor_id);
                    $city = $world->getCityName($vendor->city);
                    $images = explode(',', $v->image); ?>
                    <div class="col-6 col-md-4 col-lg-3 mb-4 item">
                        <a href="menu/<?= $v->slug ?>" class="d-block" data-menu='<?= $v->id ?>' data-bs-toggle="modal" data-bs-target="#menuModal">
                            <section class="shadow bg-white rounded p-2 p-md-3 h-100">
                                <div class="list">
                                    <header class="img-hover-zoom mb-2">
                                        <div class="menu-slider--item">
                                            <?php foreach ($images as $ik => $iv) { ?>
                                                <img src="assets/images/menus/<?= $iv  ?>" alt="<?= $v->title ?>" class="img-fluid rounded">
                                            <?php } ?>
                                        </div>
                                    </header>
                                    <div class="body w-100">
                                        <p class="title fs-14p text-truncate mb-1" data-bs-toggle="tooltip" title="<?= $v->title ?>"><?= $v->title ?></p>
                                        <p class="location fs-10p text-muted text-truncate mb-2 mb-lg-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 39.926 63.842">
                                                <path id="location-pin" d="M27.963,3.214A19.928,19.928,0,0,0,8,23.137C8,42.2,27.963,67.056,27.963,67.056S47.926,42.195,47.926,23.137A19.932,19.932,0,0,0,27.963,3.214Zm0,30.948a10.78,10.78,0,1,1,10.775-10.78,10.778,10.778,0,0,1-10.775,10.78Z" transform="translate(-8 -3.214)" fill="#8a8a8a" />
                                            </svg>
                                            <span class="ms-1"><?= $vendor->name; ?>, <?= $city ?></span>
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="fs-14p mb-0 fw-bold"><?= Helpers::format_currency($v->price); ?></p>
                                            <p class="fs-12p mb-0">
                                                <span>4.5</span>
                                                <i class="fa fa-star"></i>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>

    <?php if ($type == 'list-slide') { ?>
        <section class="menu-component list-slide">
            <header class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0"><?= $title ?></h4>
            </header>

            <div class="spacer-seperator-sm"></div>
            <div class="menu-slider">
                <?php foreach ($meal as $k => $v) {
                    $v = $ids ? $menus->get($v) : $v;
                    $vendor = $vendors->get($v->vendor_id);
                    $city = $world->getCityName($vendor->city);
                    $images = explode(',', $v->image); ?>
                    <a href="menu/<?= $v->slug ?>" class="shadow d-block item mb-4" data-menu='<?= $v->id ?>' data-bs-toggle="modal" data-bs-target="#menuModal">
                        <section class="shadow bg-white rounded p-2 p-md-3 h-100">
                            <div class="d-flex align-items-lg-center gap-3 list">
                                <div class="img-hover-zoom"><img src="assets/images/menus/<?= $v->cover  ?>" alt="<?= $v->title ?>" class="img-fluid rounded"></div>

                                <div class="body flex-fill">
                                    <p class="title fs-14p text-truncate mb-1" data-bs-toggle="tooltip" title="<?= $v->title ?>"><?= $v->title ?></p>
                                    <p class="location fs-10p text-muted text-truncate mb-2 mb-lg-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 39.926 63.842">
                                            <path id="location-pin" d="M27.963,3.214A19.928,19.928,0,0,0,8,23.137C8,42.2,27.963,67.056,27.963,67.056S47.926,42.195,47.926,23.137A19.932,19.932,0,0,0,27.963,3.214Zm0,30.948a10.78,10.78,0,1,1,10.775-10.78,10.778,10.778,0,0,1-10.775,10.78Z" transform="translate(-8 -3.214)" fill="#8a8a8a" />
                                        </svg>
                                        <span class="ms-1"><?= $vendor->name; ?>, <?= $city ?></span>
                                    </p>
                                    <div class="w-100 d-flex align-items-center justify-content-between">
                                        <p class="fs-14p mb-0 fw-bold"><?= Helpers::format_currency($v->price); ?></p>
                                        <p class="fs-12p mb-0">
                                            <span>4.5</span>
                                            <i class="fa fa-star"></i>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </a>
                <?php } ?>
            </div>
        </section>
    <?php } ?>



    <!-- The Modal -->
    <div class="modal" id="menuModal">
        <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
            <div class="modal-content">
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="text-end mb-1">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="row justify-content-between gx-3 mb-4">
                        <div class="col-lg-3 mb-3 mb-lg-0">
                            <div class="images enu-slider--image"></div>
                        </div>
                        <div class="col-10 col-lg-7">
                            <p class="title fw-bold fs-20p mb-0"></p>
                            <p class="category fs-14p mb-1"></p>
                            <p class="price fw-bold mb-0"></p>
                        </div>
                        <div class="col col-lg">
                            <!-- Save -->
                            <a href="#" class="d-flex p-2 border-0 rounded shadow bg-white add-favourite" data-type="menu" data-id="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 67.494 57.106">
                                    <path id="Favourite_Outline" data-name="Favourite Outline" d="M58.692,8.92a16.3,16.3,0,0,0-21.647,0l-4.058,3.725L28.925,8.92a16.293,16.293,0,0,0-21.642,0,14.879,14.879,0,0,0,0,22.331l25.7,23.591,25.7-23.591a14.886,14.886,0,0,0,0-22.331Z" transform="translate(0.761 -1.807)" fill="none" stroke="#ef9244" stroke-width="6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="fw-bold fs-18p mb-1">Description</p>
                        <div class="description"></div>
                    </div>

                    <div class="mb-4">
                        <p class="fw-bold mb-1">Variation</p>
                        <div class="row gy-3 variations"></div>
                    </div>

                    <div class="quantity d-flex align-items-center justify-content-between mb-4">
                        <p class="fw-bold mb-0">Quantity</p>
                        <div class="product-quantity-controller">
                            <div class="input-group mb-3 input-spinner d-flex align-items-center quantitybtn">

                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="fw-bold mb-1">Addons</p>
                        <div class="row gy-3 addons"></div>
                    </div>

                    <div class="mb-4">
                        <p class="mb-0 fw-bold">Total Amount: <span class="cart-menu-amount text-accent"></span></p>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-outline-accent vendor-slug"><i class="fa fa-store"></i></a>
                        <div class="cart-container flex-fill"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>