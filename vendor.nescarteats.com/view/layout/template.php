<?php
$user = new User();
$metas = new General('metas');
require_once('head.php');

if ($user->isLoggedIn()) {
	$userview = $user->data()->account_type == 'vendor' ? 'view/vendor' : 'view/user';
	$userview = $user->isAdmin() ? 'view/admin' : $userview;

	$user->data()->account_type == 'vendor' ? null : require_once('user-nav.php');
} else {
	(Input::get('page') && Input::get('page') == 'sign-in' || Input::get('page') == 'sign-up' || Input::get('page') == 'forgot-password') ? null : require_once('nav.php');
}
print_r($userview);

if (Input::exists('get')) {
	if ($user->isLoggedIn()) {
		$user->data()->account_type == 'vendor' ?
			Template::render('dashboard', $userview) : (Input::get('page') == 'dashboard' ?
				Template::render('dashboard', $userview) :
				Template::render(Input::get('page'), 'view'));
	} else {
	}
	$user->isLoggedIn() && $user->data()->account_type == 'vendor' ?
		Template::render('dashboard', $userview) :
		Template::render(Input::get('page'), 'view');
} else {
	$user->isLoggedIn() && $user->data()->account_type == 'vendor' ?
		Template::render('dashboard', $userview) :
		Template::render('home', 'view');
}

if ($user->isLoggedIn()) {
	$user->data()->account_type == 'vendor' ? null : require_once('user-nav.php');
} else {
	(Input::get('page') && Input::get('page') == 'sign-in' || Input::get('page') == 'sign-up' || Input::get('page') == 'forgot-password') ? null : require_once('footer.php');
}
require_once('foot.php');
