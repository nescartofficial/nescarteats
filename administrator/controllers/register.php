<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$wallet = new General('wallets');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../';

if (Input::exists()) {

	Session::put('reg_data', $_POST);

	if (Token::check(Input::get('token'))) {
		//echo "I have been ran <br />";
		$validate = new Validate();

		// validate
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' => true,
				'min' => 4,
				'unique' => 'users'
			),
			'password' => array(
				'required' => true,
				'min' => 6
			),
			'password_again' => array(
				'required' => true,
				'matches' => 'password'
			),
			'fullname' => array(
				'required' => true,
				'min' => 2,
			),
			'email' => array(
				'required' => true,
				'min' => 2,
				'max' => 50,
				'validemail' => true,
				'unique' => 'users'
			),
		));


		if ($validation->passed()) {
			$salt = Hash::salt(32);
			$db = DB::getInstance();
			try {
				// create user
				$user->create(array(
					'username' => Input::get('username'),
					'password' => Hash::make(Input::get('password'), $salt),
					'email' => Input::get('email'),
					'salt' => $salt,
					'fullname' => Input::get('fullname'),
					'state' => Input::get('state'),
					'lga' => Input::get('state-lga'),
					'joined' => date('Y-m-d H:i:s', time()),
					'group' => 1,
				));

				if ($user->login(Input::get('username'), Input::get('password'))) {
					Session::flash('success', 'Registration successfully, you are now signed in.');
					Redirect::to($backto);
				}
			} catch (Exception $e) {
				Session::flash('error', $e->getMessage());
			}
		} else {
			Session::flash('error', $validation->errors());
		}
	} else {
		Session::flash('error', $constants->getText('INVALID_TOKEN'));
	}
} else {
	Session::flash('error', $constants->getText('INVALID_ACTION'));
}

Redirect::to($backto);
