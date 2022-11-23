<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$vendors = new General('vendors');
$notifications = new General('notifications');
$addresses = new General('addresses');
$menus = new General('menus');
$profiles = new General('profiles');

$backto = Input::get('backto') ? '../' . Input::get('backto') : '../vendors';
if (
    $user->isLoggedIn() &&
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $vendors->get(Input::get('id')) : null;
            if ($found) {
                $vendors->update(array(
                    'status' => $found->status ? 0 : 1,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto . "/view/" . $found->user_id);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'verify':
            $found = Input::get('id') ? $vendors->get(Input::get('id')) : null;
            if ($found) {
                $vendors->update(array(
                    'is_verified' => !$found->is_verified,
                ), $found->id);

                if (!$found->is_verified) {
                    $message = "<p>Congratulation! your business have been activated successfully.</p>";
                    Messages::send($message, "Account Verified", $found->email, $found->name, true);
                }

                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'featured':
            $found = Input::get('id') ? $vendors->get(Input::get('id')) : null;
            if ($found) {
                $list = $menus->getAll($found->id, 'seller_id', '=');
                foreach ($list as $k => $v) {
                    $menus->remove($v->id);
                }

                $vendors->update(array(
                    'featured' => $found->featured ? 0 : 1,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto . "/view/" . $found->user_id);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'delete-user':
            $found = Input::get('id') ? $user->get(Input::get('id')) : null;
            if ($found) {
                $items = $menus->getAll($found->id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $images = explode(',', $v->image);
                        foreach ($images as $ik => $iv) {
                            $path = "../../media/images/product/" . $iv;
                            Helpers::deleteFile($path);
                        }
                        $menus->remove($v->id);
                    }
                }

                $items = $profiles->getAll($found->id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $path = "../../media/images/profile/" . $v->image;
                        Helpers::deleteFile($path);
                        $profiles->remove($v->id);
                    }
                }

                $seller_banks = new General('seller_banks');
                $items = $seller_banks->getAll($found->id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $seller_banks->remove($v->id);
                    }
                }

                $verifications = new General('verifications');
                $items = $verifications->getAll($found->id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $path = "../../media/images/cac/" . $v->cac_file;
                        Helpers::deleteFile($path);
                        $path = "../../media/images/tin/" . $v->tin_file;
                        Helpers::deleteFile($path);
                        $verifications->remove($v->id);
                    }
                }

                $items = $notifications->getAll($found->id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $notifications->remove($v->id);
                    }
                }

                $items = $addresses->getAll($found->id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $addresses->remove($v->id);
                    }
                }

                $reviews = new General('reviews');
                $items = $reviews->getAll($found->id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $reviews->remove($v->id);
                    }
                }

                $wallets = new General('wallets');
                $items = $wallets->get($found->id, 'user_id', '=');
                if ($items) {
                    $wallets->remove($items->id);
                }

                // $vendors->remove($found->seller_id);
                $user->remove($found->id);
                Session::flash('success', "Deleted Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;

        case 'delete':
            $found = Input::get('id') ? $vendors->get(Input::get('id')) : null;
            if ($found) {
                $items = $menus->getAll($found->id, 'seller_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $images = explode(',', $v->image);
                        foreach ($images as $ik => $iv) {
                            $path = "../../media/images/product/" . $iv;
                            Helpers::deleteFile($path);
                        }
                        $menus->remove($v->id);
                    }
                }

                $items = $profiles->getAll($found->user_id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $path = "../../media/images/profile/" . $v->image;
                        Helpers::deleteFile($path);
                        $profiles->remove($v->id);
                    }
                }

                $payout_requests = new General('payout_requests');
                $items = $payout_requests->getAll($found->id, 'seller_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $payout_requests->remove($v->id);
                    }
                }

                $seller_banks = new General('seller_banks');
                $items = $seller_banks->getAll($found->user_id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $seller_banks->remove($v->id);
                    }
                }

                $verifications = new General('verifications');
                $items = $verifications->getAll($found->user_id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $path = "../../media/images/cac/" . $v->cac_file;
                        Helpers::deleteFile($path);
                        $path = "../../media/images/tin/" . $v->tin_file;
                        Helpers::deleteFile($path);
                        $verifications->remove($v->id);
                    }
                }

                $items = $notifications->getAll($found->user_id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $notifications->remove($v->id);
                    }
                }

                $items = $addresses->getAll($found->user_id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $addresses->remove($v->id);
                    }
                }

                $reviews = new General('reviews');
                $items = $reviews->getAll($found->user_id, 'user_id', '=');
                if ($items) {
                    foreach ($items as $k => $v) {
                        $reviews->remove($v->id);
                    }
                }

                $wallets = new General('wallets');
                $items = $wallets->get($found->user_id, 'user_id', '=');
                if ($items) {
                    $wallets->remove($items->id);
                }

                $user->remove($found->user_id);
                $vendors->remove($found->id);
                Session::flash('success', "Deleted Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
    }
}

if (
    $user->isLoggedIn() &&
    $user->isAdmin() &&
    Input::exists() &&
    Input::get('rq')
) {
    if (1) { //Token::check(Input::get('token'))) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'add-special':
                // validate
                $validation = $validate->check($_POST, array(
                    'id' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'edit-special':
                // validate
                $validation = $validate->check($_POST, array(
                    'id' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'add':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                        'unique' => 'suppliers'
                    ),
                    'slug' => array(
                        'required' => true,
                        'min' => 2,
                        'unique' => 'suppliers'
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'edit':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'slug' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (trim(Input::get('rq'))) {
                case 'add-special':
                    try {
                        $found = Input::get('id') ? $vendors->get(Input::get('id')) : null;
                        if ($found) {
                            $sfound = $ssuppliers->get($found->id, 'category');
                            $ssuppliers->update(array(
                                'products' => implode(',', Input::get('products')),
                            ), $sfound->id);
                            Session::flash('success', 'Updated Successfully');
                        } else {
                            Session::flash('error', 'Failed to add products');
                        }
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'add':
                    try {
                        // create user
                        $vendors->create(array(
                            'title' => Input::get('title'),
                            'category_id' => Input::get('category') ? Input::get('category') : null,
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'date_added' => date("Y-m-d H:i:s", time()),
                        ));
                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = Input::get('id') ? $vendors->get(Input::get('id')) : null;
                        if ($found) {
                            $vendors->update(array(
                                'title' => Input::get('title'),
                                'slug' => Input::get('slug'),
                                'category_id' => Input::get('category') ? Input::get('category') : $found->category_id,
                                'status' => Input::get('status') == 'public' ? 1 : 0,
                            ), $found->id);
                            Session::flash('success', 'Updated Successfully');
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
