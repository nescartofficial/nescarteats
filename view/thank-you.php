<?php
$user = new User();

$message = Session::exists('thank-you') ? Session::get('thank-you') : null;
$message && Session::exists('thank-you') ? Session::delete('thank-you') : null;

!$message ? Redirect::to_js('home') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<?php if ($message) { ?>
    <section class="container-fluid">
        <div class="container spacer-md">
            <div class="row g-4">
                <div class="col-md-6 mx-auto">
                    <h1 class="mb-3">Thank You</h1>
                    <p><?= $message; ?></p>
                </div>
            </div>
        </div>
    </section>
<?php } else { ?>
    <section class="container site-section">
        <div class="row">
            <div class="col-lg-4 mx-auto text-center">
                <img src="assets/icons/Background Dots.svg" alt="Nescart Eats" class="img-fluid icon mb-4">
                <h3 class="mb-4 fw-bold text-black">Thank you for taking this first step in our partnership process.
                </h3>

                <p>Your application has been received and we will contact you shortly to verify your details and do
                    necessary due diligence for a smooth operation</p>
                <p>Don't want wait, contact us here now.</p>

                <div class="col-lg-8 mx-auto">
                    <a href="index.html" class="btn bg-primary w-100 mt-4">Go back Home</a>
                </div>
            </div>
        </div>
    </section>
<?php } ?>