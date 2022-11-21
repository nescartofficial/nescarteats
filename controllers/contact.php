<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$contacts = new General('contacts');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../contact';

if (Input::exists()) {
    Session::put('form_data', $_POST);
    if (Input::get('token')) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'email' => array(
                'required' => true,
                'min' => 5,
                'max' => 100,
                'validemail' => true,
            ),
            'country' => array(
                'required' => true,
            ),
            'phone' => array(
                'required' => true,
                'min' => 6,
                'max' => 16,
            ),
            'first_name' => array(
                'required' => true,
                'min' => 2,
                'max' => 80
            ),
            'last_name' => array(
                'required' => true,
                'min' => 2,
                'max' => 80
            ),
            'reason' => array(
                'required' => true,
                'min' => 2,
                'max' => 80
            ),
            'organization' => array(
                'required' => true,
                'min' => 2,
                'max' => 80
            ),
            'message' => array(
                'required' => true,
                'min' => 4,
                'max' => 250
            ),
        ));


        if ($validation->passed()) {
            try {
                $contacts->create(array(
                    'reason' => Input::get('reason'),
                    'first_name' => Input::get('first_name'),
                    'last_name' => Input::get('last_name'),
                    'country' => Input::get('country'),
                    'email' => Input::get('email'),
                    'phone' => Input::get('phone'),
                    'organization' => Input::get('organization'),
                    'message' => Input::get('message'),
                ));
                Session::delete('form_data');
                Session::flash('thank-you', "Your message have been sent successfully, I will respond to you shortly. Thank you.");
                Redirect::to_js('../thank-you?contact=1');
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
