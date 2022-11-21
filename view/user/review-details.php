<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$world = new World();
$reviews = new General('reviews');
$orders = new General('orders');
$products = new General('products');

$seller = $user->getSeller();

$review = Input::get('review') && is_numeric(Input::get('review')) ? $reviews->get(Input::get('review')) : null;
$order = Input::get('order') && is_numeric(Input::get('order')) ? $orders->get(Input::get('order'), 'invoice') : null;
$order_details = $order ? json_decode($order->details) : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<div class="container container--max--xl">
    <div class="mb-5">
        <div class="row g-4 align-items-center">
            <div class="col">
                <span class="small">Review</span>
                <h1 class="h3 m-0"><?= $order ? 'Order #'. $order->invoice.' Reviews' : 'Review' ?></h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-header py-2 d-flex align-items-center justify-content-between">
                    <p class="font-weight-bold my-1 fs-exact-18">Product Review</p>
                </div>
                
                <?php if ($order_details) { ?>
                    <form action="controllers/reviews.php" method="POST">
                        <div class="card-body">
                            <?php
                            $total_amount = 0;
                            foreach ($order_details as $k => $v) {
                                $product = $products->get($v->id);
                                $product_review = $reviews->getByUser($v->id, 'product_id', $user->data()->id);
                            ?>
                                <div class="d-flex mb-4">
                                    <img src="<?= SITE_URL; ?>media/images/product/<?= $product->cover; ?>" class="me-4" height="60" alt="" class="" />
                                    <div class="d-md-flex justify-content-between align-items-center ml-4 w-100">
                                        <div>
                                            <h5 class="mb-0"><?= $product->title; ?></h5>
                                            <p class="mb-0"><?= Helpers::format_currency($product->price); ?>.00</p>
                                        </div>
                                    </div>
                                </div>
                                <textarea name="review-<?= $product->id ?>" class="form-control" placeholder="Leave a review for this product" required><?= $product_review ? $product_review->review : null; ?></textarea>
                                <hr>
                            <?php } ?>
                        </div>
                        
                        <div class="card-footer">
                            <input type="hidden" name="rq" value="order-review">
                            <input type="hidden" name="backto" value="dashboard/reviews">
                            <input type="hidden" name="order" value="<?= $order->id ?>">
                            <input type="hidden" name="token" value="<?= Token::generate(); ?>">
                            <button type="submit" class="btn">Save Review</button>
                        </div>
                    </form>
                <?php } ?>
                <?php if ($review) { ?>
                    <form action="controllers/reviews.php" method="POST">
                        <div class="card-body">
                            <?php
                                $product = $products->get($review->product_id);
                                $product_review = $reviews->getByUser($review->product_id, 'product_id', $user->data()->id);
                            ?>
                                <div class="d-flex mb-4">
                                    <img src="<?= SITE_URL; ?>media/images/product/<?= $product->cover; ?>" class="me-4" height="60" alt="" class="" />
                                    <div class="d-md-flex justify-content-between align-items-center ml-4 w-100">
                                        <div>
                                            <h5 class="mb-0"><?= $product->title; ?></h5>
                                            <p class="mb-0"><?= Helpers::format_currency($product->price); ?>.00</p>
                                        </div>
                                    </div>
                                </div>
                                <textarea name="review" class="form-control" placeholder="Leave a review for this product" required><?= $product_review ? $product_review->review : null; ?></textarea>
                                <hr>
                        </div>
                        
                        <div class="card-footer">
                            <input type="hidden" name="rq" value="review">
                            <input type="hidden" name="backto" value="dashboard/reviews">
                            <input type="hidden" name="id" value="<?= $review->id ?>">
                            <input type="hidden" name="token" value="<?= Token::generate(); ?>">
                            <button type="submit" class="btn">Save Review</button>
                        </div>
                    </form>
                <?php } ?>
                
                
            </div>
        </div>
        
        <div class="col-lg-4 mt-4 mt-md-0">
            
        </div>
    </div>
</div>