<?php
$user = new User();
!$user->isLoggedIn() ? Redirect::to('../login') : null;

$backto = null;

// Alerts::displayError();
// Alerts::displaySuccess();
?>

<div class="container site-section">
  <div class="row">

    <div class="col-lg-10 mx-auto">
      <div class="card">
        <div class="card-body">
          <div class="row mx-0 align-items-center">
            <div class="col-md-6 border-end-md p-2 p-sm-5">
              <h2 class="h3 mb-4 mb-sm-5">Hey <?= $user->data()->first_name ?>!<br>Welcome back.</h2>
              <img class="d-block mx-auto img-fluid" src="assets/images/Enter OTP.svg" width="344" alt="Illustartion">
            </div>

            <div class="col-md-6 px-2 pt-4 pb-4 px-sm-5 pb-sm-5 pt-md-5">
              <h5>Phone Verification</h5>
              <p class="mb-4">
                Enter the OTP code sent to <b><?= $user->data()->phone ?></b> to verify your phone number</p>

              <form action="controllers/profile.php" method="POST" class="needs-validation" novalidate>
                <div class="mb-4">
                  <label class="form-label mb-2" for="otp">OTP</label>
                  <input class="form-control form-control-lg" type="number" min="0" name="otp" id="otp" placeholder="Input your OTP" required>
                  <div class="invalid-feedback">
                    Please provide OTP sent to your phone.
                  </div>
                </div>
                <div class="mt-4">
                  <input type="hidden" name="rq" value="phone-verification">
                  <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                  <input type="hidden" name="backto" value="<?= $backto ? $backto : null; ?>">
                  <button class="btn bg-accent  btn-lg w-100" type="submit">Verify Phone</button>
                </div>
              </form>

              <div class="mt-4 mt-sm-5 text-center">Haven't received code? <a href="controllers/profile.php?rq=resend-otp" class="text-site-accent">Resend OTP</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>