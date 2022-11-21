<?php
$user = new User();
$categories = new General('categories');
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
                    $city = $world->getCityName($v->city); ?>
                    <a href="<?= $url; ?>" class="shadow d-block item mb-4">
                        <section class="shadow bg-white rounded p-2 p-md-3 h-100">
                            <div class="list">
                                <header class="img-hover-zoom mb-2">
                                    <img src="assets/images/vendor/<?= $v->logo  ?>" alt="<?= $v->name ?>" class="img-fluid rounded">
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
                    $city = $world->getStateName($v->city); ?>
                    <div class="col-12 col-md-4 col-lg-3 item mb-4">
                        <a href="<?= $url ?>" class="d-block">
                            <section class="shadow bg-white rounded p-2 p-md-3 h-100">
                                <header class="img-hover-zoom mb-3">
                                    <img src="assets/images/vendor/<?= $v->logo  ?>" alt="<?= $v->name ?>" class="img-fluid rounded">
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


    <?php if ($type == 'full') { ?>
        <section class="book">
            <div class="full">
                <header class="row align-items-center">
                    <div class="col-lg  mb-4 mb-md-0">
                        <img src="assets/images/book/<?= $book->logo ?>" alt="<?= $book->title ?>" class="img-fluid">
                    </div>
                    <div class="col-lg-6">
                        <h4 class="title mb-" data-bs-toggle="tooltip" title="<?= $book->title ?>"><?= $book->title ?></h4>
                        <p class="mb-">By: <a href="books?author=<?= $book->author ?>"><?= $book->author ?></a></p>

                        <div class="meta d-flex flex-wrap align-items-center">
                            <div class="d-flex align-items-center mb-3 mb-md-0">
                                <div class="ratings">
                                    <i class="fs-small lni lni-star-filled"></i>
                                    <i class="fs-small lni lni-star-filled"></i>
                                    <i class="fs-small lni lni-star-filled"></i>
                                    <i class="fs-small lni lni-star"></i>
                                    <i class="fs-small lni lni-star"></i>
                                </div>
                                <p class="ms-2 mb-0 fs-small review-count">(12)</p>
                            </div>
                            <span class="mx-2 mb-3 mb-md-0">|</span>
                            <span class="fs-small mb-3 mb-md-0"><?= $categories->get($book->category)->title ?></span>
                            <span class="mx-2 mb-3 mb-md-0">|</span>
                            <span class="fs-small mb-3 mb-md-0">80k Downloads </span>
                        </div>
                    </div>
                    <div class="col">
                        <h4 class="fw-bold"><?= Helpers::format_currency($book->price) ?></h4>
                    </div>
                    <div class="col">
                        <?php if ($book->status && $book->price) { ?>
                            <?php if (!$book_in_cart) { ?>
                                <button class="btn bg-site-accent text-site-dark rounded add-cart" data-pid="<?= $book->id ?>"><?= $cart->get_cart($book->id) ? '<i class="fa fa-check-circle text-site-accent"></i> Added' : '<i class="fa fa-cart-plus"></i> Add to cart'; ?></button>
                            <?php } ?>
                            <div class="<?= !$book_in_cart ? 'd-none' : null; ?> cart-control">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center justify-content-center me-5">
                                        <a href="controllers/cart.php?rq=dec-item&pid=<?= $book->id ?>&backto=../cart" class="btn btn-sm dec-item" data-pid="<?= $book->id ?>"><i class="lni lni-minus"></i></a>
                                        <span class="fw-bold px-3" id="item-quantity-<?= $book->id ?>"><?= $book_in_cart['quantity']; ?></span>
                                        <a href="controllers/cart.php?rq=inc-item&pid=<?= $book->id ?>&backto=../cart" class="btn btn-sm inc-item" data-pid="<?= $book->id ?>"><i class="lni lni-plus"></i></a>
                                    </div>

                                    <p class="text-center item-remove mb-0">
                                        <a href="controllers/cart.php?rq=remove-from-cart&pid=<?= $book->id ?>&backto=../cart" class="icon text-danger cart-remove-item" data-pid="<?= $book->id ?>"><i class="lni lni-trash-can"></i></a>
                                    </p>
                                </div>
                            </div>

                        <?php } else { ?>
                            <i class="text-accent">Not Available</i>
                        <?php } ?>
                    </div>
                </header>
                <hr class="spacer-seperator-sm">
                <div class="body row">
                    <div class="col-lg-7  mb-4 mb-md-0">
                        <h5>Overview</h5>
                        <?= $book->description ?>
                    </div>
                    <div class="col-lg-5">
                        <h5 class="mb-4">Additional Information</h5>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>
<?php } ?>