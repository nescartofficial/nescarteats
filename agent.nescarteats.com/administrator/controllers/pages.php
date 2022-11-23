<?php
require_once("../core/init.php");
$user = new User();
$pages = new General('pages');
$constants = new Constants();

$backto = Input::get('backto') ? "../" . Input::get('backto') : "../pages";

if (Input::exists('get') && Input::get("rq")) {

    switch (trim(Input::get('rq'))) {
        case 'status':
            //$backto = "../dashboard/home/courses";
            $rep = $pages->get(Input::get('id'));
            if ($rep) {
                try {
                    $pages->update(array(
                        'status' => $found->status ? 0 : 1,
                    ), $rep->id);
                    Session::flash('success', "Status changed, Thank you");
                    Redirect::to($backto);
                } catch (Exception $e) {
                    Session::flash('error', $e->getMessage());
                    Redirect::to($backto);
                }
            }
            break;
        case 'delete':
            //$backto = "../dashboard/home/list-blog";
            try {
                $rep = $pages->get(Input::get('id'));
                if ($rep) {
                    $pages->remove($rep->id);
                    Session::flash('success', "Removed successfully.");
                    Redirect::to($backto);
                } else {
                    Session::flash('error', "Error! Data not found!");
                }
            } catch (Exception $e) {
                Session::flash('error', $e->getMessage());
                Redirect::to($backto);
            }
            break;
    }

    Redirect::to($backto);
}
// Checking if input exists
if (
    $user->isLoggedIn() &&
    Input::exists() &&
    Input::get("rq")
) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();

        switch (trim(Input::get('rq'))) {
            case 'add':
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'max' => 200
                    ),
                    'content' => array(
                        'required' => true,
                    ),
                    'status' => array(
                        'required' => true,
                    )
                ));
                break;
            case 'edit':
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'max' => 200
                    ),
                    'content' => array(
                        'required' => true,
                    ),
                    'status' => array(
                        'required' => true,
                    )
                ));
                break;
        }

        if ($validation->passed()) {
            switch (Input::get("rq")) {
                case 'add':
                    try {
                        $pages->create(array(
                            'title' => Input::get('title'),
                            'content' => Input::get('content'),
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                        ));
                        Session::flash('success', "Successfully submitted, Thank you");
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to($backto);
                    }
                    break;
                case 'edit':
                    try {
                        $pages->update(array(
                            'title' => Input::get('title'),
                            'content' => Input::get('content'),
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                        ), Input::get("id"));
                        Session::flash('success', "Successfully updated, Thank you");
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
