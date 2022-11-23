<?php
require_once('../core/init.php');

$User = new User();
$constants = new Constants();
$Wallets = new General('wallets');
$Agents = new General('agents');
$categories = new General('categories');
$category_specials = new General('category_specials');

$backto = Input::get('backto') ? '../' . Input::get('backto') : '../dashboard/agents';
if (
    $User->isLoggedIn() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'verify':
            $found = Input::get('id') ? $Agents->get(Input::get('id')) : null;
            if ($found) {
                $us = $User->get($found->user_id);
                
                $Agents->update(array(
                    'is_verified' => !$found->is_verified,
                ), $found->id);

                if (!$found->is_verified) {
                    $message = "<p>Congratulation! your account have been activated successfully.</p>";
                    Messages::send($message, "Account Activated", $found->email, $us->first_name. ' ' .$us->last_name, true);
                }

                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'status':
            $found = Input::get('id') ? $Agents->get(Input::get('id')) : null;
            if ($found) {
                $Agents->update(array(
                    'status' => !$found->status,
                ), $found->id);
                
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;

        case 'delete':
            $found = Input::get('id') ? $Agents->get(Input::get('id')) : null;
            if ($found) {
                $Agents->remove($found->id);
                
                Session::flash('success', "Deleted Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
    }
}

if (
    $User->isLoggedIn() &&
    Input::exists() &&
    Input::get('rq')
) {
    if (1) { //Token::check(Input::get('token'))) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'add':
                // validate
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
                break;
            case 'edit':
                // validate
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
        			),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (trim(Input::get('rq'))) {
                case 'add':
                    try {
                        $salt = Hash::salt(32);
        				$db = DB::getInstance();
        				$email_token = Helpers::getUnique(20, 'Aa');
        				$phone_otp = Helpers::getUnique(5, 'd');
        				
        				$reffered_by = Session::exists('reffered_by') ? Session::get('reffered_by') : null;
        				$reffered_user = $reffered_by ? $User->get($reffered_by, 'uid') : null;
        
        				// create user
        				$User->create(array(
        					'email' => strtolower(Input::get('email')),
        					'username' => strtolower(Input::get('email')),
        					'first_name' => Input::get('first_name'),
        					'last_name' => Input::get('last_name'),
        					'password' => Hash::make(Input::get('password'), $salt),
        					'vendor' => 0,
        					'account_type' => 'agent',
        					'phone' => Input::get('phone'),
        					'phone_otp' => $phone_otp,
        					'uid' => Helpers::getUnique(5, 'a'),
        					'reffered_by' => $reffered_user ? $reffered_user->uid : null,
        					'managed_by' => $User->data()->uid,
        					'email_token' => $email_token,
        					'salt' => $salt,
        					'joined' => date('Y-m-d H:i:s', time()),
        					'group' => 1,
        				));
        
        				$inserted = $db->lastInsertId();
        				if ($inserted) {
        					$Wallets->create(array(
        						'user_id' => $inserted,
        						'balance' => 0.0,
        					));
        					
        					$Agents->create(array(
        						'user_id' => $inserted,
            					'country' => Input::get('country'),
            					'state' => Input::get('state'),
            					'city' => Input::get('city'),
            					'type' => 'default',
        					));
        				}
        				
                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $salt = Hash::salt(32);
        				$db = DB::getInstance();
        				$email_token = Helpers::getUnique(20, 'Aa');
        				$phone_otp = Helpers::getUnique(5, 'd');
        				
        				$reffered_by = Session::exists('reffered_by') ? Session::get('reffered_by') : 'asek';
        				$reffered_user = $User->get($reffered_by, 'uid');
        
        				// create user
        				$User->create(array(
        					'email' => strtolower(Input::get('email')),
        					'username' => strtolower(Input::get('email')),
        					'first_name' => Input::get('first_name'),
        					'last_name' => Input::get('last_name'),
        					'password' => Hash::make(Input::get('password'), $salt),
        					'vendor' => Input::get('vendor') ? 1 : 0,
        					'phone' => Input::get('phone'),
        					'phone_otp' => $phone_otp,
        					'uid' => Helpers::getUnique(5, 'a'),
        					'reffered_by' => $reffered_user ? $reffered_user->uid : null,
        					'email_token' => $email_token,
        					'salt' => $salt,
        					'joined' => date('Y-m-d H:i:s', time()),
        					'group' => 1,
        				));
        
        				$inserted = $db->lastInsertId();
        				if ($inserted) {
        					$Wallets->create(array(
        						'user_id' => $inserted,
        						'balance' => 0.0,
        					));
        					
        					$Agents->create(array(
        						'user_id' => $inserted,
            					'country' => Input::get('country'),
            					'state' => Input::get('state'),
            					'city' => Input::get('city'),
            					'type' => 'head',
        					));
        				}
        				
                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
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
