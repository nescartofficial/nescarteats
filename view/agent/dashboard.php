<?php
require_once("core/init.php");
$user = new User();
$wallet = new General('wallets');
$enquiries = new General('enquiries');
$unread = count($enquiries->getAllByUser(0, 'status', $user->data()->id));
!$user->isLoggedIn() ? Redirect::to('../login') : null;

$views = 'view/agent';

// if ($user->data()->phone_otp) {
//     Template::render('phone-verification', $views);
// } else 
// if ($user->data()->email_token != 'verified') {
//     Template::render('confirm-email', $views);
// }

if (!Input::get('action') || Input::get('action') != 'profile') {
    !$user->getProfile() ? Redirect::to_js('dashboard/profile') : null;
}

if (!Input::get('action') || Input::get('action') != 'store') {
    $user->isCompleteProfile() && !$user->isCompleteStoreProfile() && !$user->isCompleteVerification() ? Redirect::to_js('dashboard/store') : null;
}

// if (!$user->data()->phone_otp && $user->data()->email_token == 'verified') {
// if ($user->data()->email_token == 'verified') {
    Template::render('nav', $views . '/layout');
    Template::render('main', $views);
    Template::render('footer', $views . '/layout');
// }
