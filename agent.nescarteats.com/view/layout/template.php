<?php
$user = new User();
$metas = new General('metas');
require_once('head.php');

if ($user->isLoggedIn()) {
	$userview = 'view/agent';
	$user->data()->account_type == 'vendor' || $user->data()->account_type == 'agent' ? null : require_once('user-nav.php');
} else {
// 	(Input::get('page') && Input::get('page') == 'sign-in' || Input::get('page') == 'sign-up' || Input::get('page') == 'forgot-password') ? null : require_once('nav.php');
}

if (Input::exists('get')) {
	if ($user->isLoggedIn()) {
		$user->data()->account_type == 'vendor' || $user->data()->account_type == 'agent' ?
			Template::render('dashboard', $userview) : 
			(Input::get('page') == 'dashboard' ?
				Template::render('dashboard', $userview) :
				Template::render(Input::get('page'), 'view'));
	} else {
	}
	$user->isLoggedIn() && $user->data()->account_type == 'vendor' || $user->data()->account_type == 'agent' ?
		Template::render('dashboard', $userview) :
		Template::render(Input::get('page'), 'view');
} else {
	$user->isLoggedIn() && $user->data()->account_type == 'vendor' || $user->data()->account_type == 'agent' ?
		Template::render('dashboard', $userview) :
		Template::render('sign-in', 'view');
}

if ($user->isLoggedIn()) {
	$user->data()->account_type == 'vendor' || $user->data()->account_type == 'agent' ? null : require_once('user-nav.php');
} else {
// 	(Input::get('page') && Input::get('page') == 'sign-in' || Input::get('page') == 'sign-up' || Input::get('page') == 'forgot-password') ? null : require_once('footer.php');
}
require_once('foot.php');
