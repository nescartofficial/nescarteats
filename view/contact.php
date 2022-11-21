<?php
$world = new World();
$form_data = Session::exists('form_data') ? Session::get('form_data') : null;
$countries = $world->getCountries();
?>

<section class="site-section">
    <div class="container spacer-lg">
        <div class="row justify-content-between mb-4">
            <div class="col-lg-6 order-1 order-lg-0">
                <h2 class="fs-title" data-aos="fade-left" data-aos-duration="1800">Leave Me a Message and I will Respond</h2>
                <p class="mb-1">E-mail: <a href="mailto:info@nescarteats.com">info@nescarteats.com</a></p>
                <p>Tel. nr.: <a href="tel:+4553803202">+4553803202</a></p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <form action="controllers/contact.php" method="POST" class="needs-validation" novalidate>
                    <div class="row gy-4">
                        <div class="col-lg-5">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <input type="text" name="first_name" value="<?= $form_data ? $form_data['first_name'] : null; ?>" class="form-control form-control-lg" placeholder="First Name *" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <input type="text" name="last_name" value="<?= $form_data ? $form_data['last_name'] : null; ?>" class="form-control form-control-lg" placeholder="Last Name *" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <input type="email" name="email" value="<?= $form_data ? $form_data['email'] : null; ?>" class="form-control form-control-lg" placeholder="Email *" required>
                            </div>
                            <div class="mb-4">
                                <input type="tel" name="phone" value="<?= $form_data ? $form_data['phone'] : null; ?>" class="form-control form-control-lg" placeholder="Phone Number *" required>
                            </div>

                        </div>
                        <div class="col-lg-5">
                            <div class="mb-4">
                                <textarea name="message" class="form-control form-control-lg" placeholder="Message Details *" style="min-height: 165px" required><?= $form_data ? $form_data['message'] : null; ?></textarea>
                            </div>
                            <div class="text-md-end">
                                <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                                <button type="submit" class="btn">Submit <i class="fa fa-arrow-circle-right ms-3"></i></a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>