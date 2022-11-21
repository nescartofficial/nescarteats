<?php
$user = new User();

Alerts::displayError();
Alerts::displaySuccess();
?>

<div class="min-vh-100 p-0 p-sm-6 d-flex align-items-stretch">
    <div class="card w-25x flex-grow-1 flex-sm-grow-0 m-sm-auto">
        <div class="card-body p-sm-5 m-sm-3 flex-grow-0">
            <h1 class="mb-0 fs-3">Confirm email address</h1>
            <div class="alert alert-success alert-sa-has-icon mt-4 mb-4" role="alert">
                <div class="alert-sa-icon"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg></div>
                <div class="alert-sa-content">A confirmation email was sent to the
                    <strong><?= $user->data()->email ?></strong>.</div>
            </div>
            <p class="pt-2">Before proceeding, we must verify the authenticity of your inbox.</p>
            <p>Check the mailbox! After receiving the email, click on the link provided to confirm the email
                address.</p>
            <p>If you can't find the confirmation email, <a href="controllers/profile.php?rq=get-email-verification&email=<?= $user->data()->email; ?>" class="text-primary">click here to get another one</a>.</p>
            <p class="mb-0 sa-text--sm">Back to <a href="controllers/logout.php">Sign In</a> page.</p>
        </div>
    </div>
</div><!-- scripts -->