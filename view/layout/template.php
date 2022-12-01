<?php
$User = new User();
$metas = new General('metas');
require_once('head.php');

if ($User->isLoggedIn()) {
	$userview = $User->data()->account_type == 'vendor' ? 'view/vendor' : 'view/user';
	$userview = $User->data()->account_type == 'agent' ? 'view/agent' : $userview;
	$userview = $User->isAdmin() ? 'view/admin' : $userview;

	$User->data()->account_type == 'vendor' || $User->data()->account_type == 'agent' ? null : require_once('user-nav.php');
} else {
	(Input::get('page') && Input::get('page') == 'sign-in' || Input::get('page') == 'sign-up' || Input::get('page') == 'forgot-password') ? null : require_once('nav.php');
}

if (Input::exists('get')) {
	if ($User->isLoggedIn()) {
		$User->data()->account_type == 'vendor' || $User->data()->account_type == 'agent' ?
			Template::render('dashboard', $userview) : (Input::get('page') == 'dashboard' ?
				Template::render('dashboard', $userview) :
				Template::render(Input::get('page'), 'view'));
	} else {
	}
	$User->isLoggedIn() && $User->data()->account_type == 'vendor' || $User->data()->account_type == 'agent' ?
		Template::render('dashboard', $userview) :
		Template::render(Input::get('page'), 'view');
} else {
	$User->isLoggedIn() && $User->data()->account_type == 'vendor' || $User->data()->account_type == 'agent' ?
		Template::render('dashboard', $userview) :
		Template::render('home', 'view');
}

if ($User->isLoggedIn()) {
	$User->data()->account_type == 'vendor' || $User->data()->account_type == 'agent' ? null : require_once('user-nav.php');
} else {
	(Input::get('page') && Input::get('page') == 'sign-in' || Input::get('page') == 'sign-up' || Input::get('page') == 'forgot-password') ? null : require_once('footer.php');
}
require_once('foot.php');
