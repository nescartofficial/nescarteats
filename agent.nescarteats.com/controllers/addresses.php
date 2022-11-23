<?php
require_once("../core/init.php");
$user = new User();
$addresses = new General('addresses');
$constants = new Constants();
$backto = Input::get('backto') ? Input::get('backto') : '../dashboard/address';

if (
	$user->isLoggedIn() &&
	Input::exists('get') &&
	Input::get('rq') &&
	Input::get('id')
) {
	switch (trim(Input::get('rq'))) {
		case 'delete':
			$found = Input::get('id') ? $addresses->get(Input::get('id')) : null;
			if ($found) {
				$addresses->remove($found->id);
				Session::flash('success', "Deleted Successfully");
				Redirect::to_js($backto);
			}
			Session::flash('error', "Something went wrong somewhere!");
			Redirect::to_js($backto);
			break;
	}
}

if (
	$user->isLoggedIn() &&
	Input::exists() &&
	Input::get('rq')
) {
	if (Token::check(Input::get('token'))) {
		$validate = new Validate();
		switch (trim(Input::get('rq'))) {
			case 'add':
				$validation = $validate->check($_POST, array(
					'country' => array(
						'required' => true,
					),
					'state' => array(
						'required' => true,
					),
					'city' => array(
						'required' => true,
					),
					'title' => array(
						'required' => true,
					),
					'address' => array(
						'required' => true,
						"min" => 5,
						"max" => 100,
					),
				));
				break;
			case 'edit':
				$validation = $validate->check($_POST, array(
					'id' => array(
						'required' => true,
					),
					'country' => array(
						'required' => true,
					),
					'state' => array(
						'required' => true,
					),
					'city' => array(
						'required' => true,
					),
					'title' => array(
						'required' => true,
					),
					'address' => array(
						'required' => true,
						"min" => 5,
						"max" => 255,
					),
				));
				break;
		}

		if ($validation->passed()) {
			switch (Input::get("rq")) {
				case 'add':
					try {
						$addresses->create(array(
							'user_id' => $user->data()->id,
							'address' => Input::get('address'),
							'title' => Input::get('title'),
							'country' => Input::get('country'),
							'state' => Input::get('state'),
							'city' => Input::get('city'),
						));

						Session::flash('success', 'Address added successful.');
						Redirect::to_js($backto);
					} catch (Exception $e) {
						Session::flash('error', $e->getMessage());
						Redirect::to_js($backto);
					}
					break;
				case 'edit':
					try {
						$found = $addresses->get(Input::get('id'));
						if ($found) {
							$addresses->update(array(
								'address' => Input::get('address'),
								'title' => Input::get('title'),
								'country' => Input::get('country'),
								'state' => Input::get('state'),
								'city' => Input::get('city'),
							), $found->id);
							Session::flash('success', 'Address updated.');
						} else {
							Session::flash('error', 'Address not found!.');
						}

						Redirect::to_js($backto);
					} catch (Exception $e) {
						Session::flash('error', $e->getMessage());
						Redirect::to_js($backto);
					}
					break;
			}
		} else {
			Session::flash('error', $validation->errors());
			Redirect::to_js($backto);
		}
	} else {
		Session::flash('error', $constants::INVALID_REQUEST);
		Redirect::to_js($backto);
	}
} else {
	Session::flash('error', $constants::INVALID_REQUEST);
	Redirect::to_js($backto);
}
