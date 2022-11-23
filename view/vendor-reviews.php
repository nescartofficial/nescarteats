<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
$pagination = new Pagination();
$vendors = new Vendors();
$reviews = new General('reviews');

$vendor = Input::get('sub') ? $vendors->get(Input::get('sub'), 'slug') : null;
!$vendor ? Redirect::to_js('dashboard') : null;

$total_record = $pagination->countAll('reviews', "WHERE vendor_id = {$vendor->id}");
$review_list = $reviews->getPages($total_record, 0, "WHERE vendor_id = {$vendor->id}", 'ORDER BY date_added DESC');

// $review_list = $reviews->getAll($vendor->id, 'vendor_id', '=');
$review = $reviews->get($user->data()->id, 'user_id');

$form_data = Session::exists('review_fd') ? Session::get('review_fd') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<section class="container-fluid py-5">
    <header class="container mb-6">
        <div class="d-flex align-items-center justify-contnt-between">
            <!-- Back -->
            <a href="#" id="back_button" class="d-flex p-2 border-0 rounded shadow bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22.5 38.381">
                    <path id="chevron-left" d="M30.949,7.767a3.526,3.526,0,0,1,0,5.216l-12.4,12.932,12.4,12.932a3.516,3.516,0,0,1,0,5.207,3.93,3.93,0,0,1-5.344,0c-1.384-1.344-14.9-15.535-14.9-15.535a3.619,3.619,0,0,1,0-5.214S24.221,9.119,25.6,7.769a3.928,3.928,0,0,1,5.344,0Z" transform="translate(-9.6 -6.72)" />
                </svg>

            </a>
            <h4 class="mb-0 mx-auto pr-40">Reviews</h4>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h2><?= $vendor->name; ?></h2>

                <div class="d-flex justify-content-between">
                    <p class="fs-14p mb-0 fw-bold">
                        <i class="fa fa-star"></i> 233 Reviews
                    </p>
                    <p class="fs-14p mb-0 fw-bold">
                        <i class="fa fa-badge"></i> 4.5 Rating
                    </p>
                </div>
            </div>

            <div class="col-12 mb-5">
                <h4>Write a review</h4>
                <p class="fs-16p mb-5">
                    Share your experience to help others.</p>

                <form action="controllers/reviews.php" name="reviews_form" id="reviews_form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="rq" value="vendor-review">
                    <input type="hidden" name="vendor_slug" value="<?= $vendor->slug; ?>">
                    <input type="hidden" name="vendor_id" value="<?= $vendor->id; ?>">
                    <input type="hidden" name="token" value="<?php echo Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                    <input type="hidden" name="id" value="<?= $review->id; ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="mb-3">
                                <select name="rating" class="form-select" required>
                                    <option>- Select Rating -</option>
                                    <option value="1">1 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="5">5 Stars</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <textarea type="text" name="review" value="" id="review" class="form-control" placeholder="Write a review here" required style="min-height: 120px"><?= $review ? $review->review : ($form_data ? $form_data['review'] : null); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn bg-accent"><?= $review ? 'Update' :  'Submit'; ?> Review</button>
                    </div>
                </form>
            </div>

            <div class="col-12">
                <?php Component::render('review', array('data' => $review_list, 'type' => 'list', 'title' => "Reviews from others.")); ?>
            </div>
        </div>
    </div>
</section>