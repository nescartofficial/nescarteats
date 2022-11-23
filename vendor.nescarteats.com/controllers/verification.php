<?php
require_once("../core/init.php");
$user = new User();
$profiles = new General('profiles');
$vendors = new General('vendors');
$verifications = new General('verifications');
$user_banks = new General('user_banks');
$notifications = new General('notifications');
$notification_snippets = new General('notification_snippets');
$paystack = new Paystack();
$constants = new Constants();

$backto = Input::get('backto') ? Input::get('backto') : '../dashboard';

if (
    Input::exists('get') &&
    $user->isLoggedIn()
) {
    if (Input::get('rq')) {
        switch (Input::get('rq')) {
            case 'become-seller':
                try {

                    $user->update(array(
                        'type' => 'seller',
                    ), $user->data()->id);

                    if (!$user->getVendor()) {
                        $vendors->create(array(
                            'user_id' => $user->data()->id,
                        ));
                    }

                    Session::flash('success', 'Congratulation');
                    Redirect::to($backto . '/profile');
                } catch (Exception $e) {
                    Session::flash('error', $e->getMessage());
                }
                break;
        }
    }

    Redirect::to($backto);
}

if (
    $user->isLoggedIn() &&
    Input::exists() &&
    Input::get('rq')
) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'setup':
                $backto = '../dashboard';
                $validation = $validate->check($_POST, array(
                    'cac' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'edit':
                $backto = '../dashboard';
                $validation = $validate->check($_POST, array(
                    'cac' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'bank':
                $backto = '../dashboard';
                $validation = $validate->check($_POST, array(
                    'bank' => array(
                        'required' => true,
                    ),
                    'account_number' => array(
                        'required' => true,
                    ),
                    'account_name' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        $cac_file = $tin_file = "";
        if ($validation->passed()) {
            if (!empty($_FILES) && $_FILES['cac_file']['error'] === 0) {
                if ($validate->checkFiles($_FILES['cac_file'], 'file', 1)->passed()) {
                    // print_r( 'passed'); die;
                    $temp = explode(".", $_FILES['cac_file']["name"]);
                    $fname = Helpers::getUnique(5, 'Aa') . '-cover';
                    $newfilename = $fname . '.' . end($temp);

                    // check path
                    $path = (file_exists("../media/images/cac/") && is_writeable("../media/images/cac/")) ? "../media/images/cac/" : (mkdir("../media/images/cac/", 0777, true) ? "../media/images/cac/" : "../media/");
                    // move and create preview
                    if (move_uploaded_file($_FILES['cac_file']["tmp_name"], $path . $newfilename)) {
                        $image = $newfilename;
                    }
                    $cac_file = isset($image) ? $image : null;
                }
            }

            if (!empty($_FILES) && $_FILES['tin_file']['error'] === 0) {
                if ($validate->checkFiles($_FILES['tin_file'], 'file', 1)->passed()) {
                    //print_r( 'passed'); die;
                    $temp = explode(".", $_FILES['tin_file']["name"]);
                    $fname = Helpers::getUnique(5, 'Aa') . '-logo';
                    $newfilename = $fname . '.' . end($temp);
                    // check path
                    $path = (file_exists("../media/images/tin/") && is_writeable("../media/images/tin/")) ? "../media/images/tin/" : (mkdir("../media/images/tin/", 0777, true) ? "../media/images/tin/" : "../media/");
                    // move and create preview
                    if (move_uploaded_file($_FILES['tin_file']["tmp_name"], $path . $newfilename)) {
                        $image = $newfilename;
                    }
                    $tin_file = isset($image) ? $image : null;
                }
            }
        }

        if ($validation->passed()) {
            switch (Input::get("rq")) {
                case 'setup':
                    try {
                        // print_r('here'); die;
                        $verifications->create(array(
                            'seller_id' => $user->getVendor()->id,
                            'user_id' => $user->data()->id,
                            'cac' => Input::get('cac'),
                            'tin' => Input::get('tin'),
                            'cac_file' => $cac_file,
                            'tin_file' => $tin_file,
                        ));

                        // send admin notification
                        $notifications->create(array(
                            'user_id' => 0,
                            'snippet_id' => $notification_snippets->get('ORDER_DELIVERED', 'title')->id,
                        ));

                        Session::flash('success', 'Verification setup successful.');
                        Redirect::to_js($backto);
                    } catch (Exception $e) {
                        if ($cac_file) {
                            $path = "../media/images/cac/" . $cac_file;
                            Helpers::deleteFile($path);
                        }
                        if ($tin_file) {
                            $path = "../media/images/tin/" . $tin_file;
                            Helpers::deleteFile($path);
                        }
                        Session::flash('error', $e->getMessage());
                        Redirect::to_js($backto);
                    }
                    break;
                case 'edit':
                    try {
                        $found = $user->getVerification();
                        $bfound = $user->getBank();
                        if ($found && $bfound) {
                            if ($cac_file && $cac_file != $found->cac_file && $found->cac_file != 'cac_file.jpg') {
                                $path = "../media/images/cac/" . $found->cac_file;
                                Helpers::deleteFile($path);
                            }

                            if ($tin_file && $tin_file != $found->tin_file && $found->tin_file != 'tin_file.png') {
                                $path = "../media/images/tin/" . $found->tin_file;
                                Helpers::deleteFile($path);
                            }

                            $verifications->update(array(
                                'seller_id' => $user->getVendor()->id,
                                'user_id' => $user->data()->id,
                                'cac' => Input::get('cac'),
                                'tin' => Input::get('tin'),
                                'cac_file' => $cac_file ? $cac_file : $found->cac_file,
                                'tin_file' => $tin_file ? $tin_file : $found->tin_file,
                            ), $found->id);
                            Session::flash('success', 'Verification Updated Successfully.');
                        } else {
                            Session::flash('error', 'Something went wrong!');
                        }

                        Redirect::to_js($backto);
                    } catch (Exception $e) {
                        if ($cac_file) {
                            $path = "../media/images/cac/" . $cac_file;
                            Helpers::deleteFile($path);
                        }
                        if ($tin_file) {
                            $path = "../media/images/tin/" . $tin_file;
                            Helpers::deleteFile($path);
                        }
                        Session::flash('error', $e->getMessage());
                        Redirect::to_js($backto);
                    }
                    break;
                case 'bank':
                    try {
                        $found = $user->getBank();
                        if ($found) {
                            $user_banks->update(array(
                                'bank' => Input::get('bank'),
                                'account_name' => Input::get('account_name'),
                                'account_number' => Input::get('account_number'),
                            ), $found->id);
                        } else {
                            $user_banks->create(array(
                                'user_id' => $user->data()->id,
                                'bank' => Input::get('bank'),
                                'account_name' => Input::get('account_name'),
                                'account_number' => Input::get('account_number'),
                            ));
                        }

                        Session::flash('success', 'Bank Payout Method Updated Successfully.');
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
