<?php
$user = new User();
$metas = new General('metas');
require_once('head.php');

if ($user->isLoggedIn()) {
	$userview = $user->data()->vendor ? 'view/vendor' : 'view/user';
	$userview = $user->isAdmin() ? 'view/admin' : $userview;

	!$user->data()->vendor ? require_once('user-nav.php') : null;
} else {
	(Input::get('page') && Input::get('page') == 'sign-in' || Input::get('page') == 'sign-up') ? null : require_once('nav.php');
}

if (Input::exists('get')) {
	if ($user->isLoggedIn()) {
		$user->data()->vendor ?
			Template::render('dashboard', $userview) : (Input::get('page') == 'dashboard' ?
				Template::render('dashboard', $userview) :
				Template::render(Input::get('page'), 'view'));
	} else {
	}
	$user->isLoggedIn() && $user->data()->vendor ?
		Template::render('dashboard', $userview) :
		Template::render(Input::get('page'), 'view');
} else {
	$user->isLoggedIn() && $user->data()->vendor ?
		Template::render('dashboard', $userview) :
		Template::render('home', 'view');
}

if ($user->isLoggedIn()) {
	!$user->data()->vendor ? require_once('user-nav.php') : null;
} else {
	(Input::get('page') && Input::get('page') == 'sign-in' || Input::get('page') == 'sign-up') ? null : require_once('footer.php');
}
require_once('foot.php');
