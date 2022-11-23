<?php
require_once('../core/init.php');
$user = new User();
if ($user->isLoggedIn()) {
	$user->logOut();
}

Redirect::to('../sign-in');
