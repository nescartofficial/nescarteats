<?php
$User = new User();
$User->isLoggedIn() ? Redirect::to_js('dashboard') : null;
$backto = Input::get('backto') ? Input::get('backto') : null;

$old = Session::exists('signup_data') ? Session::get('signup_data') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<section class="container-fluid sign-up">
    <div class="row">
        <div class="col-lg-6 side-image"></div>
        <div class="col-lg-4 align-self-center mx-auto">
            <div class="card">
                <div class="card-body sm-padding">
                    <div class="text-center mb-4">
                        <img src="assets/logo/Nescart Eats Logo HFC.png" alt="" class="img-fluid logo mb-3">
                        <p class="fs-5 fw-bold">Create an Account</p>
                    </div>

                    <form action="controllers/register.php" method="post" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" value="<?= $old ? $old['first_name'] : null; ?>" id="first_name" class="form-control" placeholder="" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" value="<?= $old ? $old['last_name'] : null; ?>" id="last_name" class="form-control" placeholder="" required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" value="<?= $old ? $old['email'] : null; ?>" id="email" class="form-control" placeholder="" required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" name="phone" value="<?= $old ? $old['phone'] : null; ?>" id="phone" class="form-control" placeholder="" required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control border-end-0" placeholder="" required>
                                    <span class="bg-primary--light border-start-0 input-group-text">
                                        <i class="far fa-eye togglePassword" data-field="#password" style="cursor: pointer;" ></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <label for="policy" class="form-label"><input type="checkbox" name="policy" id="policy"> By
                                    joining, I agree to the <a href="term-of-use" class="text-accent">Term of
                                        Use</a> and <a href="privacy-policy" class="text-accent">Privacy
                                        Policy</a></label>
                            </div>

                            <div class="col-md-12">
                                <input type="hidden" name="vendor" value="<?= Input::get('action') && Input::get('action') == 'vendor' ? 1 : 0 ?>">
                                <button type="submit" class="btn bg-primary w-100">Sign Up</button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <a href="sign-in" class="mb-3 d-block">Already have an account? Sign In</a>
                        <a href="">Back Home</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>