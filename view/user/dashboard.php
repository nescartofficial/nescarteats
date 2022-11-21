<?php
require_once("core/init.php");
$user = new User();
$wallet = new General('wallets');
$enquiries = new General('enquiries');
$unread = count($enquiries->getAllByUser(0, 'status', $user->data()->id));
!$user->isLoggedIn() ? Redirect::to('../login') : null;

$views = $user->isVendor() ? 'view/vendor' : 'view/user';


// if ($user->data()->phone_otp) {
//     Template::render('phone-verification', $views);
// }else 
if (!$user->getProfile() && (!Input::get('action') || Input::get('action') != 'profile')) {
     Redirect::to_js('dashboard/profile');
}

// $user->data()->email_token != 'verified' ?
//     Template::render('confirm-email', $views) :
//     Template::render('main', $views);

// if (!$user->data()->phone_otp && Input::get('page') && Input::get('page') == 'dashboard' && Input::get('action')) {
if (Input::get('page') && Input::get('page') == 'dashboard' && Input::get('action')) {
    Template::render(Input::get('action'), $views);
} else {
    // !$user->data()->phone_otp ? Template::render('main', $views) : null;
    Template::render('main', $views);
}
