<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'message' => array(
                'required' => true,
                'min' => 2,
            ),
            'fullname' => array(
                'required' => true,
                'min' => 2,
            ),
            'email' => array(
                'required' => true,
                'min' => 2,
                'validemail' => true,
            ),
            'phone' => array(
                'required' => true,
                'min' => 9,
                'max' => 16,
                'validNumber' => true
            ),
        ));


        if ($validation->passed()) {
            try {

                $msg = "Email : " . Input::get('email') . " Phone : " . Input::get('phone') . "\n\n" . Input::get('message');
                if (Messages::sendText(Messages::ADMIN_EMAIL, "Contact Message", $msg))
                    Session::flash('success', 'Sent successfully, you will be contacted shortly.');
                else
                    Session::flash('error', 'Failed to send message please try again. Thank you');

                Redirect::to('../contact-us');
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

Redirect::to('../contact-us');
