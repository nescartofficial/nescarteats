<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$slideshows = new General('slideshows');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../slideshows';
if (
    $user->isLoggedIn() &&
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $slideshows->get(Input::get('id')) : null;
            if ($found) {
                $slideshows->update(array(
                    'status' => $found->status ? 0 : 1,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;

        case 'delete':
            $found = Input::get('id') ? $slideshows->get(Input::get('id')) : null;
            if ($found) {
                if ($found->image != 'default.jpg') {
                    $path = "../../assets/images/slideshow/" . $found->image;
                    Helpers::deleteFile($path);
                }

                $slideshows->remove($found->id);
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
                        'unique' => 'slideshows'
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
            // print_r($_FILES); die;
            if (!empty($_FILES) && $_FILES['file']['error'] === 0) {
                if ($validate->checkFiles($_FILES['file'], 'file', 1)->passed()) {
                    
                    $temp = explode(".", $_FILES["file"]["name"]);
                    $fname = str_replace(' ', '-', Input::get('title'));
                    $newfilename = $fname . '.' . end($temp);

                    // check path
                    $path = (file_exists("../../assets/images/slideshow/") && is_writeable("../../assets/images/slideshow/")) ? "../../assets/images/slideshow/" : (mkdir("../../assets/images/slideshow/", 0777, true) ? "../../assets/images/slideshow/" : "../../media/");

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
                        $slideshows->create(array(
                            'title' => Input::get('title'),
                            'link' => Input::get('link'),
                            'image' => $image ? $image : 'default.jpg',
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                        ));
                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        if ($image) {
                            $path = "../../assets/images/slideshow/" . $image;
                            Helpers::deleteFile($path);
                        }
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = Input::get('id') ? $slideshows->get(Input::get('id')) : null;

                        if ($found) {
                            if ($image && $image != $found->image && $found->image != 'default.jpg') {
                                $path = "../../assets/images/slideshow/" . $found->image;
                                Helpers::deleteFile($path);
                            }
                            
                            $slideshows->update(array(
                                'title' => Input::get('title'),
                                'link' => Input::get('link'),
                                'image' => $image ? $image : $found->image,
                                'status' => Input::get('status') == 'public' ? 1 : 0,
                            ), $found->id);
                            Session::flash('success', 'Updated Successfully');
                        }
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        if ($image) {
                            $path = "../../assets/images/slideshow/" . $image;
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
