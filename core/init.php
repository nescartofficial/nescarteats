<?php
session_start();

require_once('site_constants.php');

// show all errors for now
error_reporting(E_ALL);

// Using African time zone
date_default_timezone_set('Africa/Lagos');

// global configuration array
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => 'nescarteatsdbserver.mysql.database.azure.com',
		'username' => 'nescarteatsdbadmin',
		'password' => 'aT5d6mITGm@L3KWk%i7K@D',
		'db' => 'oniontab_website_nescarteats'
	),

	'remember' => array(
		'cookie_name' => 'com_nescarteats_hash',
		'cookie_expiry' => (86400 * 180) // 6 months
	),

	'session' => array(
		'session_name' => 'com_nescarteats_user',
		'token_name' => 'com_nescarteats_token'
	)
);

/**
The function below helps you include and autoload a class once and only when required!
 **/
spl_autoload_register(function ($class) {
	require_once(LIB_PATH . DS . strtolower($class) . '.php');
});

//require_once('functions/sanitize.php');
if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
	if ($hashCheck->count()) {
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}
}
