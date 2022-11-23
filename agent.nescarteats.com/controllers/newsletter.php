<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$newsletters = new General('newsletters');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../';

if (Input::exists()) {
    Session::put('form_data', $_POST);
    if (1) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'name' => array(
                'required' => true,
                'min' => 2,
            ),
            'email' => array(
                'min' => 4,
                'max' => 120,
                'required' => true,
                'validemail' => true,
                'unique' => 'newsletters',
            ),
        ));


        if ($validation->passed()) {
            try {
                $newsletters->create(array(
                    'name' => Input::get('name'),
                    'email' => Input::get('email'),
                ));

                Session::put('thank-you', 'Newsletter save successfully.');
                Redirect::to('../thank-you');
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