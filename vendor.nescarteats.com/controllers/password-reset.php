<?php
require_once("../core/init.php");
$user = new User();
$constants = new Constants();
$messages = new General('messages');
$message_snippets = new General('message_snippets');

$backto = Input::get('backto') ? Input::get('backto') : '../forgot-password';

if (
    Input::exists('get') &&
    Input::get('rq')
) {
    switch (trim(Input::get('rq'))) {
        case 'get-link':
            $found = Input::get('email') ? $user->get(Input::get('email'), 'email') : null;
            if ($found) {
                // Messages::resetPassword($found->salt, $found->first_name . ' ' . $found->last_name, $found->email);
                
                // -- Send password reset.
                $name = $found->first_name. ' '.$found->last_name;
                $token = $found->salt;
                
                $url = SITE_URL . "forgot-password?token={$token}";
		        $link = "<a href='{$url}'>{$token}</a>";
                $link .= "<br><br><a class='button' href='{$url}' style='background:#ff1a00;border: none;color: white;padding: 0.8rem 2rem;text-align: center;text-decoration: none;display: inline-block;'>Reset my password</a><br><br>";
                
                $msg_snip = $message_snippets->get("B_CHANGE_PASSWORD", 'title');
                $message = str_replace(['[name]', '[link]'], [$name, $link], $msg_snip->message);
                Messages::send($message, $msg_snip->subject, $found->email, $name, true);  
                
                Session::flash('success_display', "<h5>Password Reset Email Sent</h5> <p>Please check your inbox to find your password reset link</p>");
                Redirect::to_js('../forgot-password');
            }
            
            Session::flash('error_display', "<b>Check email address and try again.</b>");
            Redirect::to_js('../forgot-password');
            break;
    }
}

if (
    Input::exists() &&
    Input::get('rq')
) {
    if (1) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'do-reset':
                $backto = '../forgot-password?token='.Input::get('salt');
                $validation = $validate->check($_POST, array(
                    'password' => array(
                        'required' => true,
                        'min' => 6
                    ),
                    'confirm_password' => array(
                        'required' => true,
                        'min' => 6,
                        'matches' => 'password',
                    ),
                    'salt' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (Input::get("rq")) {
                case 'do-reset':
                    try {
                        $backto = '../forgot-password';
                        $found = $user->get(Input::get('salt'), 'salt');
                        if ($found) {
                            $salt = Hash::salt(32);
                            $db = DB::getInstance();
                            $user->update(array(
                                'password' => Hash::make(Input::get('password'), $salt),
                                'salt' => $salt,
                            ), $found->id);
                            
                            
                            Session::flash('success_display', "<p>Your password have been successsfully change.</p>");
                            // Session::flash('success', 'Password reset successfully');
                            Redirect::to_js('../login');
                        } else {
                            Session::flash('error', 'Something went wrong, please try again');
                        }
                        Redirect::to_js($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to_js($backto);
                    }
                    break;
            }
        } else {
            Session::flash('error', $validation->errors());
            Redirect::to_js($backto);
        }
    } else {
        Session::flash('error', $constants::INVALID_REQUEST);
        Redirect::to_js($backto);
    }
} else {
    Session::flash('error', $constants::INVALID_REQUEST);
    Redirect::to_js($backto);
}

Redirect::to_js($backto);
