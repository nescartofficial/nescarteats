<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$ads = new General('ads');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../ads';
if (
    $user->isLoggedIn() &&
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $ads->get(Input::get('id')) : null;
            if ($found) {
                $ads->update(array(
                    'status' => $found->status ? 0 : 1,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;

        case 'delete':
            $found = Input::get('id') ? $ads->get(Input::get('id')) : null;
            if ($found) {
                if ($found->image != 'default.jpg') {
                    $path = "../../media/images/ads/" . $found->image;
                    Helpers::deleteFile($path);
                }

                $ads->remove($found->id);
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
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'add':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                        'unique' => 'ads'
                    ),
                    'position' => array(
                        'required' => true,
                    ),
                    'link' => array(
                        'required' => true,
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
                        'unique' => 'menu_ads'
                    ),
                    'position' => array(
                        'required' => true,
                    ),
                    'link' => array(
                        'required' => true,
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            if (!empty($_FILES) && $_FILES['file']['error'] === 0) {
                if ($validate->checkFiles($_FILES['file'], 'file', 1)->passed()) {
                    $temp = explode(".", $_FILES["file"]["name"]);
                    $fname = str_replace(' ', '-', strtolower(Input::get('title')));
                    $newfilename = $fname . '.' . end($temp);

                    // check path
                    $path = (file_exists("../../media/images/ads/") && is_writeable("../../media/images/ads/")) ? "../../media/images/ads/" : (mkdir("../../media/images/ads/", 0777, true) ? "../../media/images/ads/" : "../../media/");

                    // move and create preview
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $path . $newfilename)) {
                        $image = $newfilename;
                    }

                    $image = isset($image) ? $image : null;
                }
            }
        }

        if ($validation->passed()) {
            switch (trim(Input::get('rq'))) {
                case 'add':
                    try {
                        // create user
                        $ads->create(array(
                            'title' => Input::get('title'),
                            'position' => Input::get('position'),
                            'link' => Input::get('link'),
                            'image' => $image ? $image : 'default.jpg',
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                        ));
                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        if ($image) {
                            $path = "../../media/images/ads/" . $image;
                            Helpers::deleteFile($path);
                        }
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = Input::get('id') ? $ads->get(Input::get('id')) : null;

                        if ($found) {
                            if ($image && $image != $found->image && $found->image != 'default.jpg') {
                                $path = "../../media/images/ads/" . $found->image;
                                Helpers::deleteFile($path);
                            }
                            $ads->update(array(
                                'title' => Input::get('title'),
                                'position' => Input::get('position'),
                                'link' => Input::get('link'),
                                'image' => $image ? $image : $found->image,
                                'status' => Input::get('status') == 'public' ? 1 : 0,
                            ), $found->id);
                            Session::flash('success', 'Updated Successfully');
                        }
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        if ($image) {
                            $path = "../../media/images/ads/" . $image;
                            Helpers::deleteFile($path);
                        }
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
