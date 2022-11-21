<?php
require_once("../core/init.php");
$user = new User();
$coupons = new General('coupons');
$constants = new Constants();

$backto = Input::get('backto') ? "../" . Input::get('backto') : "../coupons";

if (Input::exists('get') && Input::get("rq")) {

    switch (trim(Input::get('rq'))) {
        case 'status':
            //$backto = "../dashboard/home/courses";
            $rep = $coupons->get(Input::get('id'));
            if ($rep) {
                try {
                    $coupons->update(array(
                        'status' => !$found->status,
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
                $rep = $coupons->get(Input::get('id'));
                if ($rep) {
                    $coupons->remove($rep->id);
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
                $backto = "../coupons/add";
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'max' => 120
                    ),
                    'code' => array(
                        'required' => true,
                        'max' => 15
                    ),
                    'percentage' => array(
                        'required' => true,
                    ),
                    'date_duration' => array(
                        'required' => true,
                    ),
                    'time_duration' => array(
                        'required' => true,
                    ),
                    'status' => array(
                        'required' => true,
                    )
                ));
                break;
            case 'edit':
                $backto = "../coupons/edit/".Input::get('id');
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'max' => 120
                    ),
                    'code' => array(
                        'required' => true,
                        'max' => 15
                    ),
                    'percentage' => array(
                        'required' => true,
                    ),
                    'date_duration' => array(
                        'required' => true,
                    ),
                    'time_duration' => array(
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
                        $coupons->create(array(
                            'title' => Input::get('title'),
                            'code' => Input::get('code'),
                            'percentage' => Input::get('percentage'),
                            'date_duration' => Input::get('date_duration'),
                            'time_duration' => Input::get('time_duration'),
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'date_added' => date('Y-m-d H:i:s', time()),
                        ));
                        
                        Session::delete('form_data_faq');
                        Session::flash('success', "Successfully submitted, Thank you");
                        Redirect::to('../coupons');
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to($backto);
                    }
                    break;
                case 'edit':
                    try {
                        $found = $coupons->get(Input::get('id'));
                        if($found){
                            $coupons->update(array(
                                'title' => Input::get('title'),
                                'code' => Input::get('code'),
                                'percentage' => Input::get('percentage'),
                                'date_duration' => Input::get('date_duration'),
                                'time_duration' => Input::get('time_duration'),
                                'status' => Input::get('status') == 'public' ? 1 : 0,
                            ), Input::get("id"));
                            Session::flash('success', "Successfully updated, Thank you");
                            Session::delete('form_data_faq');
                        }else{
                            Session::flash('error', "Coupon not found.");
                        }
                        
                        Redirect::to('../coupons');
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
