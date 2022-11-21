<?php
require_once("../core/init.php");
$user = new User();
$addresses = new General('addresses');
$profiles = new General('profiles');
$sellers = new General('sellers');
$couriers = new General('couriers');
$messages = new General('messages');
$message_snippets = new General('message_snippets');
$sms_snippets = new General('sms_snippets');
$constants = new Constants();
$backto = Input::get('backto') ? Input::get('backto') : '../dashboard/profile';

if (
    Input::exists('get') &&
    Input::get('rq')
) {
    switch (trim(Input::get('rq'))) {
        case 'verify':
            $found = Input::get('token') ? $user->get(Input::get('token'), 'email_token') : null;
            if ($found) {
                $user->update(array('email_token' => 'verified'), $found->id);
                Session::flash('success', "Email verified successfully");
                Redirect::to_js('../login');
            }
            // Session::flash('error', "No account found with this email address");
            Redirect::to_js('../login');
            break;
        case 'get-email-verification':
            $found = Input::get('email') ? $user->get(Input::get('email'), 'email') : null;
            if ($found) {
                $name = $user->data()->first_name . ' ' . $user->data()->last_name;
                !$user->data()->vendor ? Messages::verifyEmail($user->data()->email_token, $name, Input::get('email')) : Messages::verifySellerEmail($user->data()->email_token, $name, Input::get('email'));

                Session::flash('success', "Email verification sent successfully");
                Redirect::to_js('../dashboard');
            }
            Session::flash('error', "No account found with this email address");
            Redirect::to_js('../login');
            break;
    }
}

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
                        $sellers->create(array(
                            'user_id' => $user->data()->id,
                        ));
                    }

                    Session::flash('success', 'Congratulation');
                    Redirect::to($backto . '/profile');
                } catch (Exception $e) {
                    Session::flash('error', $e->getMessage());
                }
                break;
            case 'become-courier':
                try {

                    $user->update(array(
                        'type' => 'courier',
                    ), $user->data()->id);

                    if (!$user->getVendor()) {
                        $couriers->create(array(
                            'user_id' => $user->data()->id,
                        ));
                    }

                    Session::flash('success', 'Congratulation');
                    Redirect::to($backto . '/profile');
                } catch (Exception $e) {
                    Session::flash('error', $e->getMessage());
                }
                break;
            case 'resend-otp':
                try {
                    // 	$message = str_replace(['[otp]'], [$user->data()->phone_otp], $sms_snippets->get("OTP", 'title')->message);
                    // 	Messages::sendSMS($user->data()->phone, $message);

                    $name = $user->data()->first_name . ' ' . $user->data()->last_name;
                    $msg_snip = $message_snippets->get("B_PHONE_VERIFICATION", 'title');
                    $message = str_replace(['[name]', '[otp]'], [$name, $user->data()->phone_otp], $msg_snip->message);
                    Messages::send($message, $msg_snip->subject, $user->data()->email, $name, true);

                    $messages->create(array(
                        'user_id' => $user->data()->id,
                        'title' => $msg_snip->subject,
                        'message' => $msg_snip->message,
                    ));

                    Session::flash('success', 'OTP sent successfully!');
                    Redirect::to('../phone-verification');
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
    if (1) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'setup':
                $validation = $validate->check($_POST, array(
                    'first_name' => array(
                        'required' => true,
                        "max" => 50,
                        "min" => 2,
                    ),
                    'last_name' => array(
                        'required' => true,
                        "max" => 50,
                        "min" => 2,
                    ),
                    'phone' => array(
                        'required' => true,
                        "max" => 16,
                    ),
                    'country' => array(
                        'required' => true,
                    ),
                    'state' => array(
                        'required' => true,
                    ),
                    'city' => array(
                        'required' => true,
                    ),
                    'address' => array(
                        'required' => true,
                        "min" => 5,
                        "max" => 255,
                    ),
                ));
                break;
            case 'edit':
                $validation = $validate->check($_POST, array(
                    'id' => array(
                        'required' => true,
                    ),
                    'first_name' => array(
                        'required' => true,
                        "max" => 50,
                        "min" => 2,
                    ),
                    'last_name' => array(
                        'required' => true,
                        "max" => 50,
                        "min" => 2,
                    ),
                    'phone' => array(
                        'required' => true,
                        "max" => 16,
                    ),
                    'country' => array(
                        'required' => true,
                    ),
                    'state' => array(
                        'required' => true,
                    ),
                    'city' => array(
                        'required' => true,
                    ),
                    'address' => array(
                        'required' => true,
                        "min" => 5,
                        "max" => 255,
                    ),
                ));
                break;
            case 'change-password':
                $backto = '../dashboard/change-password';
                $validation = $validate->check($_POST, array(
                    'password' => array(
                        'required' => true,
                        'min' => 6
                    ),
                    'new_password' => array(
                        'required' => true,
                        'min' => 6
                    ),
                    'confirm_password' => array(
                        'required' => true,
                        'min' => 6,
                        'matches' => 'new_password',
                    ),
                ));
                break;
            case 'phone-verification':
                $validation = $validate->check($_POST, array(
                    'otp' => array(
                        'required' => true,
                        'min' => 4
                    ),
                ));
                break;
        }

        // $cover_img = $logo_img = "";
        // if ($validation->passed()) {
        //     if (!empty($_FILES) && $_FILES['cover']['error'] === 0) {
        //         if ($validate->checkFiles($_FILES['cover'], 'file', 1)->passed()) {
        //             // print_r( 'passed'); die;
        //             $temp = explode(".", $_FILES['cover']["name"]);
        //             $fname = Input::get('first_name') . '-' . Input::get('last_name') . '-' . Helpers::getUnique(2);
        //             $newfilename = $fname . '.' . end($temp);
        //             // check path
        //             $path = (file_exists("../assets/images/profile/") && is_writeable("../assets/images/profile/")) ? "../assets/images/profile/" : (mkdir("../assets/images/profile/", 0777, true) ? "../assets/images/profile/" : "../media/");
        //             // move and create preview
        //             if (move_uploaded_file($_FILES['cover']["tmp_name"], $path . $newfilename)) {
        //                 $image = $newfilename;
        //             }
        //             $cover_img = isset($image) ? $image : null;
        //         }
        //     }
        // }

        $cover_img = $logo_img = "";
        if ($validation->passed()) {
            if (!empty($_FILES) && $_FILES['cover']['error'] === 0) {
                $upload = new Upload($_FILES['cover']);
                if ($upload->uploaded) {
                    // save uploaded image with a new name
                    $upload->image_y = 800;
                    $upload->file_overwrite = true;
                    $upload->dir_auto_create = true;
                    $upload->png_compression = 5;
                    $upload->file_new_name_body = Input::get('first_name') . '-' . Input::get('last_name') . '-' . $user->data()->uid;
                    $upload->process("../assets/images/profile/");
                    if ($upload->processed) {
                        $cover_img = $upload->file_dst_name;
                    } else {
                        Session::flash('error',  $upload->error);
                        Redirect::to_js($backto);
                    }
                }
            }
        }

        if ($validation->passed()) {
            switch (Input::get("rq")) {
                case 'setup':
                    try {
                        $backto = '../dashboard/profile';

                        $phone_otp = $user->data()->phone_otp;
                        if ($user->data()->phone != Input::get('phone')) { // send otp to verify phone
                            $phone_otp = Helpers::getUnique(5, 'd');
                            $message = str_replace(['otp'], [$phone_otp], $sms_snippets->get("OTP", 'title')->message);
                            Messages::sendSMS($user->data()->phone, $message);

                            // -- phone verification
                            // $name = $user->data()->first_name. ' '.$user->data()->last_name;
                            // $msg_snip = $message_snippets->get("B_PHONE_VERIFICATION", 'title');
                            // $message = str_replace(['[name]', '[otp]'], [$name, $phone_otp], $msg_snip->message);
                            // Messages::send($message, $msg_snip->subject, $user->data()->email, $name, true);   

                            // $messages->create(array(
                            //     'user_id' => $user->data()->id,
                            //     'title' => $msg_snip->subject,
                            //     'message' => $msg_snip->message,
                            // ));
                        }

                        $user->update(array(
                            'first_name' => Input::get('first_name'),
                            'last_name' => Input::get('last_name'),
                            'phone' => Input::get('phone'),
                            'phone_otp' => $phone_otp,
                        ), $user->data()->id);

                        $profiles->create(array(
                            'user_id' => $user->data()->id,
                            'image' => $cover_img ? $cover_img : 'user-avatar.jpg',
                            'address' => Input::get('address'),
                            'phone' => Input::get('phone'),
                            'country' => Input::get('country'),
                            'state' => Input::get('state'),
                            'city' => Input::get('city'),
                        ));

                        $addresses->create(array(
                            'user_id' => $user->data()->id,
                            'address' => Input::get('address'),
                            'title' => "Home",
                            'country' => Input::get('country'),
                            'state' => Input::get('state'),
                            'city' => Input::get('city'),
                        ));

                        Session::flash('success', 'Profile setup successful.');
                        Redirect::to_js(Session::exists('tocheckto') ? '../checkout' : '../dashboard');
                    } catch (Exception $e) {
                        if ($cover_img) {
                            $path = "../assets/images/profile/" . $cover_img;
                            Helpers::deleteFile($path);
                        }
                        Session::flash('error', $e->getMessage());
                        Redirect::to_js($backto);
                    }
                    break;

                case 'edit':
                    try {
                        $backto = '../dashboard/profile';
                        $found = $profiles->get($user->data()->id, 'user_id');

                        if ($found) {
                            $phone_otp = $user->data()->phone_otp;
                            if ($user->data()->phone != Input::get('phone')) { // send otp to verify phone
                                $phone_otp = Helpers::getUnique(5, 'd');
                                $message = str_replace(['otp'], [$phone_otp], $sms_snippets->get("OTP", 'title')->message);
                                Messages::sendSMS($user->data()->phone, $message);

                                // -- phone verification
                                // $name = $user->data()->first_name. ' '.$user->data()->last_name;
                                // $msg_snip = $message_snippets->get("B_PHONE_VERIFICATION", 'title');
                                // $message = str_replace(['[name]', '[otp]'], [$name, $phone_otp], $msg_snip->message);
                                // Messages::send($message, $msg_snip->subject, $user->data()->email, $name, true);   

                                // $messages->create(array(
                                //     'user_id' => $user->data()->id,
                                //     'title' => $msg_snip->subject,
                                //     'message' => $msg_snip->message,
                                // ));
                            }

                            $user->update(array(
                                'first_name' => Input::get('first_name'),
                                'last_name' => Input::get('last_name'),
                                'phone' => Input::get('phone'),
                                'phone_otp' => $phone_otp,
                            ), $user->data()->id);

                            $found_address = $addresses->getByUser('Default', 'title', $user->data()->id);
                            $data = array(
                                'user_id' => $user->data()->id,
                                'address' => Input::get('address'),
                                'title' => "Default",
                                'country' => Input::get('country'),
                                'state' => Input::get('state'),
                                'city' => Input::get('city'),
                            );
                            $found_address ? $addresses->update($data, $found_address->id) : $addresses->create($data);

                            $profiles->update(array(
                                'image' => $cover_img ? $cover_img : $found->image,
                                'address' => Input::get('address'),
                                'phone' => Input::get('phone'),
                                'country' => Input::get('country'),
                                'state' => Input::get('state'),
                                'city' => Input::get('city'),
                            ), $found->id);
                        }

                        Session::flash('success', 'Profile updated.');
                        Redirect::to_js('../dashboard');
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to_js($backto);
                    }
                    break;

                case 'change-password':
                    try {
                        if (!($user->data()->password == Hash::make(Input::get('password'), $user->data()->salt))) {
                            Session::flash('error', 'Current password does not match');
                            Redirect::to_js($backto);
                        }

                        $salt = Hash::salt(32);
                        $user->update(array(
                            'salt' => $salt,
                            'password' => Hash::make(Input::get('new_password'), $salt),
                        ), $user->data()->id);

                        // $messages->create(array(
                        //     'user_id' => $user->data()->id,
                        //     'title' => $msg_snip->subject,
                        //     'message' => $msg_snip->message,
                        // ));

                        Session::flash('success', "Password Changed Successfully");
                        Redirect::to_js($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;

                case 'phone-verification':
                    try {

                        if (Input::get('otp') == $user->data()->phone_otp) {
                            $user->update(array(
                                'phone_otp' => null,
                            ), $user->data()->id);
                        }

                        Session::flash('success', 'Phone verification successfull');
                        Redirect::to_js('../dashboard/profile');
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
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
