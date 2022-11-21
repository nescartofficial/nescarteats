<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$categories = new General('categories');
$delivery_fees = new General('delivery_fees');

$backto = Input::get('backto') ? '../' . Input::get('backto') : '../delivery-fees';
if (
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $delivery_fees->get(Input::get('id')) : null;
            if ($found) {
                $delivery_fees->update(array(
                    'status' => !$found->status,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'status':
            $found = Input::get('id') ? $categories->get(Input::get('id')) : null;
            if ($found) {
                $categories->update(array(
                    'status' => !$found->status,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'popular':
            $found = Input::get('id') ? $categories->get(Input::get('id')) : null;
            if ($found) {
                $categories->update(array(
                    'popular' => !$found->popular,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'classified':
            $found = Input::get('id') ? $categories->get(Input::get('id')) : null;
            if ($found) {
                $categories->update(array(
                    'classified' => !$found->classified,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;

        case 'delete':
            $found = Input::get('id') ? $delivery_fees->get(Input::get('id')) : null;
            if ($found) {
                $found->image && $found->image != 'default.jpg' ? Helpers::deleteFile("../../media/images/category/" . $found->image) : null;
                $delivery_fees->remove($found->id);
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
    if (1) { //Token::check(Input::get('token'))) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'add':
                // validate
                $validation = $validate->check($_POST, array(
                    'fee' => array(
                        'required' => true,
                    ),
                    'type' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'edit':
                // validate
                $validation = $validate->check($_POST, array(
                    'fee' => array(
                        'required' => true,
                    ),
                    'type' => array(
                        'required' => true,
                    ),
                    'id' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (trim(Input::get('rq'))) {
                case 'add':
                    try {
                        $delivery_fees->create(array(
                            'fee' => Input::get('fee'),
                            'category_id' => Input::get('category') ? Input::get('category') : null,
                            'country' => Input::get('country') ? Input::get('country') : null,
                            'state' => Input::get('state') ? Input::get('state') : null,
                            'city' => Input::get('city') ? Input::get('city') : null,
                            'type' => Input::get('type'),
                            'status' => Input::get('status') && Input::get('status') == 'public' ? 1 : 0,
                        ), $sfound->id);
                        
                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = Input::get('id') ? $delivery_fees->get(Input::get('id')) : null;
                        if ($found) {
                            $delivery_fees->update(array(
                                'fee' => Input::get('fee'),
                                'category_id' => Input::get('category') ? Input::get('category') : null,
                                'country' => Input::get('country') ? Input::get('country') : null,
                                'state' => Input::get('state') ? Input::get('state') : null,
                                'city' => Input::get('city') ? Input::get('city') : null,
                                'type' => Input::get('type'),
                                'status' => Input::get('status') && Input::get('status') == 'public' ? 1 : 0,
                            ), $found->id);
                            Session::flash('success', 'Updated Successfully');
                        } else {
                            Session::flash('error', 'Failed to add products');
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
