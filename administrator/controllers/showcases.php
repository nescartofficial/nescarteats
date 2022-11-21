<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$showcases = new General('showcases');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../showcases';

// $ads = $_POST['ads'];
// echo implode(",", $ads);
// print_r($_POST); die;

if (
    $user->isLoggedIn() &&
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $showcases->get(Input::get('id')) : null;
            if ($found) {
                $showcases->update(array(
                    'status' => $found->status ? 0 : 1,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;

        case 'delete':
            $found = Input::get('id') ? $showcases->get(Input::get('id')) : null;
            if ($found) {
                $showcases->remove($found->id);
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
    $user->isAdmin() &&
    Input::exists() &&
    Input::get('rq')
) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'add':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                        'unique' => 'showcases'
                    ),
                    'category' => array(
                        'required' => true,
                        'notDefault' => true,
                        'unique' => 'showcases'
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'edit':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'category' => array(
                        'required' => true,
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (trim(Input::get('rq'))) {
                case 'add':
                    try {
                        $showcases->create(array(
                            'title' => Input::get('title'),
                            'category' => Input::get('category'),
                            'ads' => Input::get('ads') ? implode(",", Input::get('ads')) : null,
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                        ));

                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = Input::get('id') ? $showcases->get(Input::get('id')) : null;
                        if ($found) {
                            if (Input::get('ads') || !empty(Input::get('ads'))) {
                                $ads = implode(",", Input::get('ads'));
                            }
                            $showcases->update(array(
                                'title' => Input::get('title'),
                                'category' => Input::get('category') ? Input::get('category') : $found->category,
                                'ads' => $ads ? $ads : $found->ads,
                                'status' => Input::get('status') == 'public' ? 1 : 0,
                            ), $found->id);
                            Session::flash('success', 'Updated Successfully');
                        }
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
