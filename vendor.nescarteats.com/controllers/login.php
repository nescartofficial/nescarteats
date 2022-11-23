<?php
require_once('../core/init.php');

$user = new User();
$profiles = new General('profiles');
$constants = new Constants();
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../sign-in';

if (Input::exists()) {
    if (1) { // Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'email' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
                'validemail' => true,
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
        ));
        
        // print_r('here'); die;
        
        if ($validation->passed()) {
            try {
                $_POST['username'] = Input::get('email');
                $us = $user->get(Input::get('email'), 'email');
                // print_r($us); die;
                if($us->account_type == 'vendor'){
                    if (!$user->login(Input::get('email'), Input::get('password'))) {
                        $us ? Session::flash('error', 'Invalid login details.') : Session::flash('error', 'Account not found!');
                    } else {
                        if (!$user->getProfile()) {
                            $profiles->create(array('user_id' => $user->data()->id));
                        }
                        Session::delete('form_data');
                        Redirect::to_js(Input::get('backto') ? $backto : '../dashboard');
                    }
                }else{
                    Session::flash('error', 'Invalid login details.');
                }
                
                Redirect::to_js($backto);
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

Redirect::to_js($backto);
