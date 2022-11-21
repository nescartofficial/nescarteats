<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$notifications = new General('notifications');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../dashboard/notifications';
if (
    Input::exists('get') &&
    Input::get('rq')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $notifications->get(Input::get('id')) : null;
            if ($found) {
                $notifications->update(array('status' => !$found->status), $found->id);
                Session::flash('success', "Updated Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'delete':
            $found = Input::get('id') ? $notifications->get(Input::get('id')) : null;
            if ($found) {
                $notifications->remove($found->id);
                Session::flash('success', "Deleted Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
    }
}

if (Input::exists()) {

    if (Token::check(Input::get('token'))) {
        //echo "I have been ran <br />";
        $validate = new Validate();

        // validate
        $validation = $validate->check($_POST, array(
            'title' => array(
                'required' => true,
                'min' => 2,
                'unique' => 'services'
            ),
        ));


        if ($validation->passed()) {
            try {
                // create user
                $notifications->create(array(
                    'title' => Input::get('title'),
                ));
                Session::flash('success', 'Added Successfully');
                Redirect::to($backto);
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
