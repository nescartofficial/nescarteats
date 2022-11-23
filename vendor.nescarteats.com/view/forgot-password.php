<?php
$User = new User();
$User->isLoggedIn() ? Redirect::to_js('dashboard') : null;

$token = Input::get('token');
$found = $token ? $user->get($token, 'salt') : null;

$error_message = Alerts::error_message();
?>

<section class="container-fluid sign-in">
    <div class="row min-vh-100">
        <div class="col-lg-6 side-image"></div>
        <div class="col-lg-4 align-self-center mx-auto">
            <div class="card">
                <div class="card-body sm-padding">
                    <?php if ($found) { ?>
                        <div class="text-center mb-4">
                            <img src="assets/logo/Nescart Eats Logo HFC.png" alt="" class="img-fluid logo mb-3">
                            <p class="fs-5 fw-bold">Reset Password?</p>
                            <p>You may set your new password, your account will be enabled with this new password.</p>
                        </div>
                        
                        <form action="controllers/password-reset.php" method="POST">
                            <div class="form-group mb-4">
                                <input name="password" class="form-control form-control-lg" placeholder="Input New Password" type="password">
                            </div> <!-- form-group// -->
                            <div class="form-group mb-4">
                                <input name="confirm_password" class="form-control form-control-lg" placeholder="Confirm New Password" type="password">
                            </div> <!-- form-group// -->
        
                            <div class="form-group mb-5">
                                <input type="hidden" name="token" value="<?= Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                                <input type="hidden" name="rq" value="do-reset">
                                <input type="hidden" name="salt" value="<?= $found->salt ?>">
                                <button type="submit" class="btn btn-primary btn-block"> Reset Password </button>
                            </div> <!-- form-group// -->
                            
                            <p class="text-center"><a href="login" class="text-reset">Back to Login</a></p>
                        </form>
                    
                    <?php } else{ ?>
                        <div class="text-center mb-4">
                            <img src="assets/logo/Nescart Eats Logo HFC.png" alt="" class="img-fluid logo mb-3">
                            <p class="fs-5 fw-bold">Forgot Password?</p>
                            <p>An email with password reset instructions has been sent to your email address, if it exists on our system.</p>
                        </div>
    
                        <?php print_r($error_message); ?>
                        
                        <?php if($error_message){ ?>
                            <p class="text-center">If you didn't receive your password reset email, <a href="forgot-password" class="text-site-accent font-weight-bold mb-4">Try again</a></p>
                        <?php }else{ ?>
                            <form action="controllers/password-reset.php" method="get" class="needs-validation" novalidate>
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email address" required>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" name="rq" value="get-link">
                                        <button type="submit" class="btn bg-primary w-100">Send Instruction</button>
                                    </div>
                                </div>
                            </form>
        
                            <div class="mt-4 text-center">
                                <a href="sign-in" class="mb-3 d-block">Back to Sign In</a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
</section>