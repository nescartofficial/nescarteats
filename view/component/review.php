<?php
$user = new User();
$reviews = new General('reviews');
$cart = new Cart();

$title = $data && isset($data['title']) ? $data['title'] : null;
$review = $data ? $data['data'] : null;
$type = $data ? $data['type'] : null;
?>

<?php if ($review) { ?>
    <?php if ($type == 'list') { ?>
        <section class="review-component list">
            <?php if ($title) { ?>
                <header class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="mb-0"><?= $title ?></h4>
                </header>
            <?php } ?>

            <div class="spacer-seperator-sm"></div>

            <?php foreach ($review as $k => $v) {
                $vendor = $user->getVendor($v->vendor_id);
                $us = $user->get($v->user_id);
                $profile = $user->getProfile($v->user_id);

                $diff = date_diff(date_create($v->date_added), date_create());
                $days = $diff->format("%a days"); ?>
                <div class="item mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <?php if ($profile->image) { ?>
                            <img src="assets/images/profile/<?= $profile->image ?>" alt="">
                        <?php } else { ?>
                            <div class="img-replace"><?= $us->first_name[0] ?></div>
                        <?php } ?>
                        <p class="fw-bold mb-0 ms-2 fs-14p flex-fill"><?= $us->first_name . ' ' . $us->last_name; ?></p>
                        <p class="fs-12p mb-0">4.0 <i class="fa fa-star"></i></p>
                    </div>
                    <div class="body">
                        <p class="fs-16p text-truncate mb-2"><?= $v->review ?></p>
                        <p class="fw-bold fs-14p mb-0"><?= $days; ?></p>
                    </div>
                </div>
            <?php } ?>

        </section>
    <?php } ?>

    <?php if ($type == 'full') { ?>
        <section class="book">
            <div class="full">
                <header class="row align-items-center">
                    <div class="col-lg  mb-4 mb-md-0">
                        <img src="assets/images/book/<?= $book->cover ?>" alt="<?= $book->title ?>" class="img-fluid">
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