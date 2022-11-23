<?php
require_once("../core/init.php");
$User = new User();
$profiles = new General('profiles');
$Wallets = new General('wallets');
$Vendors = new General('vendors');
$constants = new Constants();
$backto = Input::get('backto') ? Input::get('backto') : '../dashboard';

if (
    Input::exists('get') &&
    $User->isLoggedIn()
) {
    if (Input::get('rq')) {
        switch (Input::get('rq')) {
            case 'become-seller':
                try {

                    $User->update(array(
                        'type' => 'seller',
                    ), $User->data()->id);

                    if (!$User->getVendor()) {
                        $Vendors->create(array(
                            'user_id' => $User->data()->id,
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
    $User->isLoggedIn() &&
    Input::exists() &&
    Input::get('rq')
) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'add':
                $backto = '../dashboard';
                $validation = $validate->check($_POST, array(
                    'name' => array(
                        'required' => true,
                    ),
                    'phone' => array(
                        'required' => true,
                        "max" => 16,
                        "unique" => 'vendors',
                    ),
                    'email' => array(
                        'required' => true,
                        "min" => 5,
                        "unique" => 'vendors',
                        'validemail' => true,
                    ),
                    'about' => array(
                        'required' => true,
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
                        "max" => 200,
                    ),
                ));
                break;
            case 'edit':
                $backto = '../dashboard';
                $validation = $validate->check($_POST, array(
                    'phone' => array(
                        'required' => true,
                        "max" => 16,
                    ),
                    'email' => array(
                        'required' => true,
                        "min" => 5,
                        'validemail' => true,
                    ),
                    'name' => array(
                        'required' => true,
                    ),
                    'about' => array(
                        'required' => true,
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
                        "max" => 200,
                    ),
                ));
                break;
            case 'bank':
                $backto = '../dashboard';
                $validation = $validate->check($_POST, array(
                    'phone' => array(
                        'required' => true,
                        "max" => 16,
                    ),
                    'email' => array(
                        'required' => true,
                        "min" => 5,
                        'validemail' => true,
                    ),
                    'name' => array(
                        'required' => true,
                    ),
                    'about' => array(
                        'required' => true,
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
                        "max" => 200,
                    ),
                ));
                break;
        }

        $cover_img = $logo_img = "";
        if ($validation->passed()) {
            if (!empty($_FILES) && $_FILES['cover']['error'] === 0) {
                $upload = new Upload($_FILES['cover']);
                if ($upload->uploaded) {
                    // save uploaded image with a new name
                    $upload->file_overwrite = true;
                    $upload->dir_auto_create = true;
                    $upload->png_compression = 5;
                    $upload->file_new_name_body = Input::get('name') . '-cover-' . $User->data()->uid;
                    $upload->process("../assets/images/vendor/");
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
            if (!empty($_FILES) && $_FILES['logo']['error'] === 0) {
                $upload = new Upload($_FILES['logo']);
                if ($upload->uploaded) {
                    // save uploaded image with a new name
                    $upload->file_overwrite = true;
                    $upload->dir_auto_create = true;
                    $upload->png_compression = 5;
                    $upload->file_new_name_body = Input::get('name') . '-logo-' . $User->data()->uid;
                    $upload->process("../assets/images/vendor/");
                    if ($upload->processed) {
                        $logo_img = $upload->file_dst_name;
                    } else {
                        Session::flash('error',  $upload->error);
                        Redirect::to_js($backto);
                    }
                }
            }
        }

        if ($validation->passed()) {
            switch (Input::get("rq")) {
                case 'add':
                    try {
                        $salt = Hash::salt(32);
        				$db = DB::getInstance();
        				$email_token = Helpers::getUnique(20, 'Aa');
        				$phone_otp = Helpers::getUnique(5, 'd');
        				$uid = Helpers::getUnique(5, 'a');
        				
        				$reffered_by = Session::exists('reffered_by') ? Session::get('reffered_by') : null;
        				$reffered_user = $reffered_by ? $User->get($reffered_by, 'uid') : null;
        
        				// create user
        				$User->create(array(
        					'email' => strtolower(Input::get('email')),
        					'username' => strtolower(Input::get('email')),
        					'first_name' => Input::get('first_name'),
        					'last_name' => Input::get('last_name'),
        					'password' => Hash::make(Input::get('password'), $salt),
        					'vendor' => 1,
        					'account_type' => 'vendor',
        					'phone' => Input::get('phone'),
        					'phone_otp' => $phone_otp,
        					'uid' => $uid,
        					'reffered_by' => $reffered_user ? $reffered_user->uid : null,
        					'managed_by' => Input::get('agent_uid'),
        					'email_token' => $email_token,
        					'salt' => $salt,
        					'joined' => date('Y-m-d H:i:s', time()),
        					'group' => 1,
        				));
        
        				$inserted = $db->lastInsertId();
        				
        				if ($inserted) {
        					$Wallets->create(array(
        						'user_id' => $inserted,
        						'balance' => 0.0,
        					));
        					
        					$Vendors->create(array(
                                'user_id' => $inserted,
                                'name' => Input::get('name'),
                                'about' => Input::get('about'),
                                'email' => Input::get('email'),
                                'phone' => Input::get('phone'),
                                'country' => Input::get('country'),
                                'state' => Input::get('state'),
                                'city' => Input::get('city'),
                                'address' => Input::get('address'),
                                'cover' => $cover_img ? $cover_img : 'cover.jpg',
                                'logo' => $logo_img ? $logo_img : 'logo.png',
                                'opening_time' => Input::get('opening_time'),
                                'closing_time' => Input::get('closing_time'),
                                'delivery_time' => Input::get('delivery_time'),
                                'featured' => 0,
                                'status' => 1,
                                'slug' => Helpers::slugify(Input::get('name')) . '-' . $uid,
                            ));
        				}
                        
                        Session::flash('success', 'Vendor setup successfully.');
                        Redirect::to_js($backto);
                    } catch (Exception $e) {
                        if ($cover_img) {
                            $path = "../assets/images/vendor/" . $cover_img;
                            Helpers::deleteFile($path);
                        }
                        if ($logo_img) {
                            $path = "../assets/images/vendor/" . $logo_img;
                            Helpers::deleteFile($path);
                        }
                        Session::flash('error', $e->getMessage());
                        Redirect::to_js($backto);
                    }
                    break;

                case 'edit':
                    try {
                        $found = $User->getVendor();
                        if ($found) {
                            $Vendors->update(array(
                                'name' => Input::get('name'),
                                'about' => Input::get('about'),
                                'email' => Input::get('email'),
                                'phone' => Input::get('phone'),
                                'country' => Input::get('country'),
                                'state' => Input::get('state'),
                                'city' => Input::get('city'),
                                'address' => Input::get('address'),
                                'cover' => $cover_img ? $cover_img : $found->cover,
                                'logo' => $logo_img ? $logo_img : $found->logo,
                                'opening_time' => Input::get('opening_time'),
                                'closing_time' => Input::get('closing_time'),
                                'delivery_time' => Input::get('delivery_time'),
                                'status' => 1,
                                'slug' => Helpers::slugify(Input::get('name')) . '-' . $User->data()->uid,
                            ), $found->id);
                            Session::flash('success', 'Profile Updated Successfully.');
                        } else {
                            Session::flash('error', 'Something went wrong! unable to find profile.');
                        }
                        Redirect::to_js($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to_js($backto);
                    }
                    break;

                case 'bank':
                    try {
                        $found = $User->getVendor();
                        if ($found) {
                            if ($cover_img && $cover_img != $found->cover && $found->cover != 'cover.jpg') {
                                $path = "../assets/images/vendor/" . $found->cover;
                                Helpers::deleteFile($path);
                            }
                            if ($logo_img && $logo_img != $found->logo && $found->logo != 'logo.png') {
                                $path = "../assets/images/vendor/" . $found->logo;
                                Helpers::deleteFile($path);
                            }

                            $obj = $User->data()->type == 'courier' ? $couriers : $Vendors;
                            $obj->update(array(
                                'name' => Input::get('name'),
                                'about' => Input::get('about'),
                                'email' => Input::get('email'),
                                'phone' => Input::get('phone'),
                                'country' => Input::get('country'),
                                'state' => Input::get('state'),
                                'city' => Input::get('city'),
                                'address' => Input::get('address'),
                                'cover' => $cover_img ? $cover_img : $found->cover,
                                'logo' => $logo_img ? $logo_img : $found->logo,
                                'status' => 1,
                                'slug' => Input::get('slug'),
                            ), $found->id);
                            Session::flash('success', 'Profile Updated Successfully.');
                        } else {
                            Session::flash('error', 'Something went wrong! unable to find profile.');
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