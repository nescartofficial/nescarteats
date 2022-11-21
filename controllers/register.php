<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$wallet = new General('wallets');
$profiles = new General('profiles');
$sms_snippets = new General('sms_snippets');
$messages = new General('messages');
$message_snippets = new General('message_snippets');
$notifications = new General('notifications');
$notification_snippets = new General('notification_snippets');

$backto = Input::get('backto') ? '../' . Input::get('backto') : '../sign-up';

if (Input::exists()) {
	Session::put('signup_data', $_POST);
	if (1) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'first_name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50,
			),
			'last_name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50,
			),
			'password' => array(
				'required' => true,
				'min' => 6
			),
			'phone' => array(
				'required' => true,
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
			try {
				$salt = Hash::salt(32);
				$db = DB::getInstance();
				$email_token = Helpers::getUnique(20, 'Aa');
				$phone_otp = Helpers::getUnique(5, 'd');

				// if(!Helpers::validate_phone_number(Input::get('phone'))){
				// 	Session::flash('error', 'Please submit a valid phone number');
				// 	Redirect::to_js($backto);
				// }

				// create user
				$user->create(array(
					'email' => strtolower(Input::get('email')),
					'username' => strtolower(Input::get('email')),
					'first_name' => Input::get('first_name'),
					'last_name' => Input::get('last_name'),
					'password' => Hash::make(Input::get('password'), $salt),
					'vendor' => Input::get('vendor') ? 1 : 0,
					'phone' => Input::get('phone'),
					'phone_otp' => $phone_otp,
					'uid' => Helpers::getUnique(5, 'a'),
					'email_token' => $email_token,
					'salt' => $salt,
					'joined' => date('Y-m-d H:i:s', time()),
					'group' => 1,
				));

				$inserted = $db->lastInsertId();
				if ($inserted) {
					$wallet->create(array(
						'user_id' => $inserted,
						'balance' => 0.0,
					));
				}

				$name = Input::get('first_name') . ' ' . Input::get('last_name');
				$link = $email_token;
				if (Input::get('vendor')) {
					Messages::verifySellerEmail($link, $name, Input::get('email'));

					$message = str_replace(['[otp]'], [$phone_otp], $sms_snippets->get("OTP", 'title')->message);
					Messages::sendSMS(Input::get('phone'), $message);
				} else {
					Messages::newAccount($name, Input::get('email'));
					Messages::verifyEmail($link, $name, Input::get('email'));

					$message = str_replace(['[otp]'], [$phone_otp], $sms_snippets->get("OTP", 'title')->message);
					Messages::sendSMS(Input::get('phone'), $message);

					// send message
					// -- email verification
					$link = SITE_URL . "controllers/profile.php?rq=verify&token={$email_token}";
					$msg_snip = $message_snippets->get("B_EMAIL_VERIFICATION", 'title');
					$message = str_replace(['[name]', '[link]'], [$name, $link], $msg_snip->message);
					Messages::send($message, $msg_snip->subject, Input::get('email'), "Admin", true);

					// -- phone verification
					$msg_snip = $message_snippets->get("B_PHONE_VERIFICATION", 'title');
					$message = str_replace(['[name]', '[otp]'], [$name, $email_token], $msg_snip->message);
					Messages::send($message, $msg_snip->subject, Input::get('email'), "Admin", true);

					$messages->create(array(
						'user_id' => $inserted,
						'subject' => $msg_snip->subject,
						'message' => $msg_snip->message,
					));

					// send notification to admin
					$notifications->create(array(
						'user_id' => 0,
						'snippet_id' => $notification_snippets->get('A_NEW_BUYER', 'title')->id,
					));
				}

				if ($user->login(Input::get('email'), Input::get('password'))) {
					Session::flash('success', 'Registration successfully, confirm your email address.');
					Session::delete('signup_data');
					Redirect::to_js('../dashboard/profile');
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

$backto = Input::get('vendor') ? "../sign-up/" . Input::get('vendor') : $backto;
Redirect::to($backto);
