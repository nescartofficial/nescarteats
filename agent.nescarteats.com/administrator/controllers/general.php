<?php
require_once("../core/init.php");
$user = new User();
$pages = new General('pages');
$constants = new Constants();

$backto = Input::get('backto') ? "../" . Input::get('backto') : "../general";

// Checking if input exists
if (
    $user->isLoggedIn() &&
    Input::exists() &&
    Input::get("rq")
) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();

        switch (trim(Input::get('rq'))) {
            case 'update':
                $validation = $validate->check($_POST, array(
                    'rq' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (Input::get("rq")) {
                case 'update':
                    try {
                        
                        $found = $constants->get("BALANCE_UPDATE_DURATION");
                        $constants->update(array(
                            'content' => Input::get('balance_update'),
                        ), $found->id);
                        
                        $found = $constants->get("PAYOUT_DATE");
                        $constants->update(array(
                            'content' => Input::get('payout_date'),
                        ), $found->id);
                        
                        $found = $constants->get("FACEBOOK");
                        $constants->update(array(
                            'content' => Input::get('facebook'),
                        ), $found->id);
                        
                        $found = $constants->get("TWITTER");
                        $constants->update(array(
                            'content' => Input::get('twitter'),
                        ), $found->id);
                        
                        $found = $constants->get("INSTAGRAM");
                        $constants->update(array(
                            'content' => Input::get('instagram'),
                        ), $found->id);
                        
                        $found = $constants->get("WHATSAPP");
                        $constants->update(array(
                            'content' => Input::get('whatsapp'),
                        ), $found->id);
                        
                        Session::flash('success', "Successfully submitted, Thank you");
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to($backto);
                    }
                    break;
            }
        } else {
            Session::put('error', $validation->errors());
            Redirect::to($backto);
        }
    } else {
        Session::flash('error', $constants::INVALID_TOKEN);
        Redirect::to($backto);
    }
} else {
    Session::flash('error', $constants::INVALID_REQUEST);
    Redirect::to($backto);
}
