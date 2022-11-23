<?php
$User = new User();
$User->isLoggedIn() ? Redirect::to_js('dashboard') : null;

$current_page = Input::get('action') ? Input::get('page') . '/' . Input::get('action') : (Input::get('page') ? Input::get('page') : null);
$backto = Input::get('backto') ? Input::get('backto') : $current_page;

Alerts::displayError();
Alerts::displaySuccess();
?>

<section class="container-fluid sign-in">
    <div class="row min-vh-100">
        <div class="col-lg-6 side-image"></div>
        <div class="col-lg-4 align-self-center mx-auto">
            <div class="card">
                <div class="card-body sm-padding">
                    <div class="text-center mb-4">
                        <img src="assets/logo/Nescart Eats Logo HFC.png" alt="" class="img-fluid logo mb-3">
                        <p class="fs-5 fw-bold">Sign into your Account</p>
                    </div>

                    <form action="controllers/login.php" method="post" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email address" required>
                            </div>
                            <div class="col-lg-12 mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="password" class="form-label">Password</label>
                                    <label class="form-label"><a href="forgot-password">Forgot Password?</a></label>
                                </div>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control border-end-0" placeholder="Enter your password" required>
                                    <span class="bg-primary--light border-start-0 input-group-text">
                                        <i class="far fa-eye togglePassword" data-field="#password" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="backto" value="<?= $backto; ?>">
                                <button type="submit" class="btn bg-primary w-100">Sign In</button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <a href="javascript:;" class="mb-3 d-block">Don't have an account? Sign Up</a>
                        <a href="">Back Home</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>