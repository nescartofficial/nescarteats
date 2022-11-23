<?php
require_once('stroyka-head.php');
$user->isLoggedIn() && $user->isAdmin() ? require_once('stroyka-nav.php') : null;

if (Input::exists('get')) {
	if ($user->isLoggedIn() && $user->isAdmin()) {
		!Input::get('page') ? Template::render('dashboard', 'view/admin') : Template::render(Input::get('page'), 'view/admin');
	} else {
		$user->isLoggedIn() ? Template::render('access-denied', 'view') : Template::render('login', 'view');
	}
} else {
	$user->isAdmin() ?
		Template::render('dashboard', 'view/admin') :
		Template::render('login', 'view');
}
$user->isLoggedIn() && $user->isAdmin() ? require_once('stroyka-footer.php') : null;
require_once('stroyka-foot.php');