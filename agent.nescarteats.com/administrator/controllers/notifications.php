<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$notifications = new General('notifications');
$notification_snippets = new General('notification_snippets');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../notifications';
if (
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $notifications->get(Input::get('id')) : null;
            if ($found) {
                $notifications->update(array('status' => !$found->status), $found->id);
                Session::flash('success', "Set Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'snippet-status':
            $found = Input::get('id') ? $notification_snippets->get(Input::get('id')) : null;
            if ($found) {
                $notification_snippets->update(array('status' => !$found->status), $found->id);
                Session::flash('success', "Set Successfully");
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
        case 'snippet-delete':
            $found = Input::get('id') ? $notification_snippets->get(Input::get('id')) : null;
            if ($found) {
                $notification_snippets->remove($found->id);
                Session::flash('success', "Deleted Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
    }
}

if (
    $user->isAdmin() &&
    Input::exists() &&
    Input::get('rq')
) {
    if (1) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'add-snippet':
                $backto = '../notification-snippets';
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'subject' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'message' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                ));
                break;
            case 'edit-snippet':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'subject' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'message' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (trim(Input::get('rq'))) {
                case 'add-snippet':
                    try {
                        $notification_snippets->create(array(
                            'title' => Input::get('title'),
                            'subject' => Input::get('subject'),
                            'message' => Input::get('message'),
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'slug' => Helpers::slugify(Input::get('title')),
                        ));
                        
                        Session::flash('success', 'Saved!');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;  
                case 'edit-snippet':
                    try {
                        
                        $found = Input::get('id') ? $notification_snippets->get(Input::get('id')) : null;
                        if ($found) {
                            $notification_snippets->update(array(
                                'title' => Input::get('title'),
                                'subject' => Input::get('subject'),
                                'message' => Input::get('message'),
                                'status' => Input::get('status') == 'public' ? 1 : 0,
                                'slug' => Helpers::slugify(Input::get('title')),
                            ), $found->id);
                            Session::flash('success', 'Updated Successfully');
                        } else {
                            Session::flash('error', 'Failed to Update!');
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
