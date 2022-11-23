<?php
$user = new User();
$categories = new General('categories');
$Menus = new Menus();
$vendors = new Vendors();
$world = new World();
$cart = new Cart();

$title = $data && isset($data['title']) ? $data['title'] : null;
$vendor = $data ? $data['data'] : null;
$type = $data ? $data['type'] : null;
?>

<?php if ($vendor) { ?>
    <?php if ($type == 'list') { ?>
        <section class="vendor-component list">
            <?php if ($title) { ?>
                <header class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="mb-0"><?= $title ?></h4>
                </header>
            <?php } ?>

            <div class="spacer-seperator-sm"></div>
            <div class="default-slider">
                <?php foreach ($vendor as $k => $v) {
                    $url =  "vendor/{$v->slug}";
                    $city = $world->getCityName($v->city); 
                    $menu_items = $Menus->getAllCount($v->id, 'vendor_id', '=', 5);
                ?>
                    <a href="<?= $url; ?>" class="shadow d-block item mb-4">
                        <section class="shadow bg-white rounded p-2 p-md-3 h-100">
                            <div class="list">
                                <header class="img-hover-zoom mb-2">
                                    <style>
                                        .menu-slider--item .flickity-page-dots {
                                            bottom: 10px;
                                        }
                                        .menu-slider--item.is-fullscreen img {
                                          height: 100%;
                                        }
                                    </style>
                                    <div class="menu-slider--item" data-flickity='{"pageDots": false, "autoPlay": 4500, "lazyLoad": true, "prevNextButtons": false, "fullscreen": true}'>
                                        <?php if($menu_items){ ?>
                                            <?php foreach($menu_items as $menu){ ?>
                                                <img src="assets/images/menus/nescart-menu-lazyload.png" data-flickity-lazyload="assets/images/menus/<?= $menu->cover ?>" alt="<?= $v->name ?>" alt="<?= $v->name ?>" class="img-fluid rounded">
                                            <?php } ?>
                                        <?php }else{ ?>
                                            <img src="assets/images/vendor/<?= $v->logo  ?>" alt="<?= $v->name ?>" class="img-fluid rounded">
                                        <?php } ?>
                                    </div>
                                </header>

                                <div class="body text-center w-100">
                                    <p class="title fs-14p text-truncate mb-0" data-bs-toggle="tooltip" title="<?= $v->name ?>"><?= $v->name ?></p>
                                </div>
                            </div>
                        </section>
                    </a>
                <?php } ?>
            </div>
        </section>
    <?php } ?>

    <?php if ($type == 'single') { ?>
        <section class="vendor-component single">
            <header class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0"><?= $title ?></h4>
            </header>

            <div class="spacer-seperator-sm"></div>
            <div class="row">
                <?php foreach ($vendor as $k => $v) {
                    $url = "vendor/{$v->slug}";
                    $city = $world->getStateName($v->city);
                    $menu_items = $Menus->getAllCount($v->id, 'vendor_id', '=', 5);
                ?>
                    <div class="col-12 col-md-4 col-lg-3 item mb-4">
                        <a href="<?= $url ?>" class="d-block">
                            <section class="shadow bg-white rounded p-2 p-md-3 h-100">
                                <header class="img-hover-zoom mb-3">
                                    <style>
                                        .menu-slider--item .flickity-page-dots {
                                            bottom: 10px;
                                        }
                                        .menu-slider--item.is-fullscreen img {
                                          height: 100%;
                                        }
                                    </style>
                                    <div class="menu-slider--item" data-flickity='{"pageDots": false, "autoPlay": 4500, "lazyLoad": true, "prevNextButtons": false, "fullscreen": true}'>
                                        <?php if($menu_items){ ?>
                                            <?php foreach($menu_items as $menu){ ?>
                                                <img src="assets/images/menus/nescart-menu-lazyload.png" data-flickity-lazyload="assets/images/menus/<?= $menu->cover ?>" alt="<?= $v->name ?>" alt="<?= $v->name ?>" class="img-fluid rounded">
                                            <?php } ?>
                                        <?php }else{ ?>
                                            <img src="assets/images/vendor/<?= $v->logo  ?>" alt="<?= $v->name ?>" class="img-fluid rounded">
                                        <?php } ?>
                                    </div>
                                </header>

                                <div class="body">
                                    <p class="title fs-18p text-truncate mb-1" data-bs-toggle="tooltip" title="<?= $v->name ?>"><?= $v->name ?></p>
                                    <p class="location fs-14p text-muted text-truncate mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 39.926 63.842">
                                            <path id="location-pin" d="M27.963,3.214A19.928,19.928,0,0,0,8,23.137C8,42.2,27.963,67.056,27.963,67.056S47.926,42.195,47.926,23.137A19.932,19.932,0,0,0,27.963,3.214Zm0,30.948a10.78,10.78,0,1,1,10.775-10.78,10.778,10.778,0,0,1-10.775,10.78Z" transform="translate(-8 -3.214)" fill="#8a8a8a" />
                                        </svg>
                                        <span class="ms-1"><?= $v->address; ?>, <?= $city ?></span>
                                    </p>
                                    <div class="w-100 d-flex align-items-center justify-content-between">
                                        <p class="fs-14p mb-0">
                                            <span>4.7</span>
                                            <i class="fa fa-star"></i>
                                        </p>
                                        <p class="fs-14p mb-0 text-muted">
                                            30-45min
                                        </p>
                                        <p class="fs-14p mb-0 text-muted fw-bold">Open</p>
                                    </div>
                                </div>
                            </section>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
<?php } ?>