<?php
require_once("../core/init.php");
$user = new User();
$constants = new Constants();
$products = new Products();
$backto = Input::get('backto') ? Input::get('backto') : '../search';

if (
    Input::exists() &&
    Input::get('rq')
) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'search':
                $validation = $validate->check($_POST, array(
                    'search' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (Input::get("rq")) {
                case 'search':
                    try {
                        $sterm = Input::get('search') ? Input::get('search') : null;
                        $plist = $products->search($sterm);
                        Session::put('search', $plist);
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
