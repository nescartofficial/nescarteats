<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$messagings = new General('messagings');
$messages = new General('messages');
$message_snippets = new General('message_snippets');
$notifications = new General('notifications');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../messagings';
if (
    $user->isLoggedIn() &&
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'delete':
            $found = Input::get('id') ? $messages->get(Input::get('id')) : null;
            if ($found) {
                $messages->remove($found->id);
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
    Input::get('type')
) {
    if (1) {
        $validate = new Validate();
        switch (trim(Input::get('type'))) {
            case 'messaging':
                // validate
                $validation = $validate->check($_POST, array(
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
            case 'notification':
                // validate
                $validation = $validate->check($_POST, array(
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
            case 'notification-messaging':
                // validate
                $validation = $validate->check($_POST, array(
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
            case 'add-snippet':
                $backto = '../message-snippets';
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
                $backto = '../message-snippets';
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
            switch (trim(Input::get('type'))) {
                case 'messaging':
                    try {
                        $emails = explode(',', Input::get('emails'));
                        $email_list = array();
                        
                        if($emails){
                            foreach($emails as $k => $v){
                                $us = $user->get($v, 'email');
                                if(!$us){ continue; }
                                
                                array_push($email_list, $us->email); // Add to email list
                                Messages::send(Input::get('message'), Input::get('subject'), $v, $us->first_name. ' '. $us->last_name, true);
                                $messages->create(array(
                                    'user_id' => $us->id,
                                    'subject' => Input::get('subject'),
                                    'message' => Input::get('message'),
                                ));
                            }
                            $includes = Input::get('include') && Input::get('include') == 'all' ? $user->getAll() : null;
                            $includes = Input::get('include') && Input::get('include') == 'seller' ? $user->getAll('seller', 'type', '=') : $includes;
                            $includes = Input::get('include') && Input::get('include') == 'buyer' ? $user->getAll('buyer', 'type', '=') : $includes;

                            if($includes){
                                foreach($includes as $k => $v){
                                    array_push($email_list, $v->email); // Add to email list
                                    Messages::send(Input::get('message'), Input::get('subject'), $v, $us->first_name. ' '. $us->last_name, true);
                                    $messages->create(array(
                                        'user_id' => $v->id,
                                        'subject' => Input::get('subject'),
                                        'message' => Input::get('message'),
                                    ));
                                }
                            }
                            
                            $messagings->create(array(
                                'subject' => Input::get('subject'),
                                'message' => Input::get('message'),
                                'type' => "Messaging",
                                'sent_to' => json_encode($email_list),
                            ));
                            Session::flash('success', 'Email Sent Successfully');
                        }else{
                            Session::flash('success', 'No email found!');
                        }
                        
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;  
                case 'notification':
                    try {
                        $emails = explode(',', Input::get('emails'));
                        $email_list = array();
                        
                        if($emails){
                            foreach($emails as $k => $v){
                                $us = $user->get($v, 'email');
                                if(!$us){ continue; }
                                
                                array_push($email_list, $us->email); // Add to email list
                                $notifications->create(array(
                                    'user_id' => $us->id,
                                    'subject' => Input::get('subject'),
                                    'message' => Input::get('message'),
                                ));
                            }
                            $includes = Input::get('include') && Input::get('include') == 'all' ? $user->getAll() : null;
                            $includes = Input::get('include') && Input::get('include') == 'seller' ? $user->getAll('seller', 'type', '=') : $includes;
                            $includes = Input::get('include') && Input::get('include') == 'buyer' ? $user->getAll('buyer', 'type', '=') : $includes;
                            
                            if($includes){
                                foreach($includes as $k => $v){
                                    array_push($email_list, $v->email); // Add to email list
                                    $notifications->create(array(
                                        'user_id' => $v->id,
                                        'subject' => Input::get('subject'),
                                        'message' => Input::get('message'),
                                    ));
                                }
                            }
                            
                            $messagings->create(array(
                                'subject' => Input::get('subject'),
                                'message' => Input::get('message'),
                                'type' => "Messaging",
                                'sent_to' => json_encode($email_list),
                            ));
                            Session::flash('success', 'Email Sent Successfully');
                        }else{
                            Session::flash('success', 'No email found!');
                        }
                        
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;  
                case 'notification-messaging':
                    try {
                        $emails = explode(',', Input::get('emails'));
                        $email_list = array();
                        
                        if($emails){
                            foreach($emails as $k => $v){
                                $us = $user->get($v, 'email');
                                if(!$us){ continue; }
                                
                                array_push($email_list, $v->email); // Add to email list
                                Messages::send(Input::get('message'), Input::get('subject'), $v, $us->first_name. ' '. $us->last_name, true);                                
                                $messages->create(array(
                                    'user_id' => $v->id,
                                    'subject' => Input::get('subject'),
                                    'message' => Input::get('message'),
                                ));
                                $notifications->create(array(
                                    'user_id' => $v->id,
                                    'subject' => Input::get('subject'),
                                    'message' => Input::get('message'),
                                ));
                            }
                            
                            $includes = Input::get('include') && Input::get('include') == 'all' ? $user->getAll() : null;
                            $includes = Input::get('include') && Input::get('include') == 'seller' ? $user->getAll('seller', 'type', '=') : $includes;
                            $includes = Input::get('include') && Input::get('include') == 'buyer' ? $user->getAll('buyer', 'type', '=') : $includes;
                            
                            if($includes){
                                foreach($includes as $k => $v){
                                    array_push($email_list, $v->email); // Add to email list
                                    Messages::send(Input::get('message'), Input::get('subject'), $v, $us->first_name. ' '. $us->last_name, true);
                                    $messages->create(array(
                                        'user_id' => $v->id,
                                        'subject' => Input::get('subject'),
                                        'message' => Input::get('message'),
                                    ));
                                    $notifications->create(array(
                                        'user_id' => $v->id,
                                        'subject' => Input::get('subject'),
                                        'message' => Input::get('message'),
                                    ));
                                }
                            }
                            
                            $messagings->create(array(
                                'subject' => Input::get('subject'),
                                'message' => Input::get('message'),
                                'type' => "Messaging",
                                'sent_to' => json_encode($email_list),
                            ));
                            Session::flash('success', 'Email Sent Successfully');
                            Redirect::to($backto);
                        }else{
                            Session::flash('success', 'No email found!');
                        }
                        
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'add-snippet':
                    try {
                        $message_snippets->create(array(
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
                        
                        $found = Input::get('id') ? $message_snippets->get(Input::get('id')) : null;
                        if ($found) {
                            $message_snippets->update(array(
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
