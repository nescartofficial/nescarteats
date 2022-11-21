<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$backto = '../';

if (Input::exists()) {
    if (1) { //Token::check(Input::get('token'))) {

        $validate = new Validate();

        // validate
        $validation = $validate->check($_POST, array(
            'email' => array(
                'required' => true,
                'min' => 4,
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
        ));


        if ($validation->passed()) {
            try {
                if ($user->login(Input::get('email'), Input::get('password'))) {
                    Redirect::to_js($backto . 'dashboard');
                }
                Session::flash('error', 'Something went wrong, try again!');
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
