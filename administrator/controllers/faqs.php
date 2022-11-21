<?php
require_once("../core/init.php");
$user = new User();
$faqs = new General('faqs');
$constants = new Constants();

$backto = Input::get('backto') ? "../" . Input::get('backto') : "../faqs";

if (Input::exists('get') && Input::get("rq")) {

    switch (trim(Input::get('rq'))) {
        case 'status':
            //$backto = "../dashboard/home/courses";
            $rep = $faqs->get(Input::get('id'));
            if ($rep) {
                try {
                    $faqs->update(array(
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
                $rep = $faqs->get(Input::get('id'));
                if ($rep) {
                    $faqs->remove($rep->id);
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
    Session::put('form_data_faq', $_POST);
    if (1) {
        $validate = new Validate();

        switch (trim(Input::get('rq'))) {
            case 'add':
                $backto = "../faqs/add";
                $validation = $validate->check($_POST, array(
                    'question' => array(
                        'required' => true,
                        'max' => 200
                    ),
                    'answer' => array(
                        'required' => true,
                    ),
                    'type' => array(
                        'required' => true,
                    ),
                    'status' => array(
                        'required' => true,
                    )
                ));
                break;
            case 'edit':
                $backto = "../faqs/edit/".Input::get('id');
                $validation = $validate->check($_POST, array(
                    'question' => array(
                        'required' => true,
                        'max' => 200
                    ),
                    'answer' => array(
                        'required' => true,
                    ),
                    'type' => array(
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
                        $faqs->create(array(
                            'question' => Input::get('question'),
                            'answer' => Input::get('answer'),
                            'type' => Input::get('type'),
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'date_added' => date('Y-m-d H:i:s', time()),
                        ));
                        
                        Session::delete('form_data_faq');
                        Session::flash('success', "Successfully submitted, Thank you");
                        Redirect::to('../faqs');
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to($backto);
                    }
                    break;
                case 'edit':
                    try {
                        $faqs->update(array(
                            'question' => Input::get('question'),
                            'answer' => Input::get('answer'),
                            'type' => Input::get('type'),
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                        ), Input::get("id"));
                        
                        Session::delete('form_data_faq');
                        Session::flash('success', "Successfully updated, Thank you");
                        Redirect::to('../faqs');
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
