<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$suppliers = new General('suppliers');

$backto = Input::get('backto') ? '../' . Input::get('backto') : '../suppliers';
if (
    $user->isLoggedIn() &&
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $suppliers->get(Input::get('id')) : null;
            if ($found) {
                $suppliers->update(array(
                    'status' => $found->status ? 0 : 1,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto."/view/".$found->user_id);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'featured':
            $found = Input::get('id') ? $suppliers->get(Input::get('id')) : null;
            if ($found) {
                $suppliers->update(array(
                    'featured' => $found->featured ? 0 : 1,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto."/view/".$found->user_id);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;

        case 'delete':
            $found = Input::get('id') ? $suppliers->get(Input::get('id')) : null;
            if ($found) {
                $suppliers->remove($found->id);
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
            case 'add-special':
                // validate
                $validation = $validate->check($_POST, array(
                    'id' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'edit-special':
                // validate
                $validation = $validate->check($_POST, array(
                    'id' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'add':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                        'unique' => 'suppliers'
                    ),
                    'slug' => array(
                        'required' => true,
                        'min' => 2,
                        'unique' => 'suppliers'
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
                    'slug' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (trim(Input::get('rq'))) {
                case 'add-special':
                    try {
                        $found = Input::get('id') ? $suppliers->get(Input::get('id')) : null;
                        if ($found) {
                            $sfound = $ssuppliers->get($found->id, 'category');
                            $ssuppliers->update(array(
                                'products' => implode(',', Input::get('products')),
                            ), $sfound->id);
                            Session::flash('success', 'Updated Successfully');
                        } else {
                            Session::flash('error', 'Failed to add products');
                        }
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'add':
                    try {
                        // create user
                        $suppliers->create(array(
                            'title' => Input::get('title'),
                            'category_id' => Input::get('category') ? Input::get('category') : null,
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'date_added' => date("Y-m-d H:i:s", time()),
                        ));
                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = Input::get('id') ? $suppliers->get(Input::get('id')) : null;
                        if ($found) {
                            $suppliers->update(array(
                                'title' => Input::get('title'),
                                'slug' => Input::get('slug'),
                                'category_id' => Input::get('category') ? Input::get('category') : $found->category_id,
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
