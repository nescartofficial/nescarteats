<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$blogs = new General('blogs');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../blog';

if (
    Input::exists('get') &&
    $user->isAdmin() &&
    Input::get('rq')
) {
    switch (trim(Input::get('rq'))) {
        case 'delete':
            $found = Input::get('id') ? $blogs->get(Input::get('id')) : null;
            if ($found) {
                $blogs->remove($found->id);
                Session::flash('success', "Deleted Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'status':
            $found = Input::get('id') ? $blogs->get(Input::get('id')) : null;
            if ($found) {
                $blogs->update(array('status' => $found->status ? 0 : 1), $found->id);
                Session::flash('success', "Set Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'featured':
            $found = Input::get('id') ? $blogs->get(Input::get('id')) : null;
            if ($found) {
                $blogs->update(array('featured' => $found->featured ? 0 : 1), $found->id);
                Session::flash('success', "Set Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
    }

    Redirect::to($backto);
}

if (
    $user->isAdmin() &&
    Input::exists() &&
    Input::get('rq')
) {
    Session::put('form_data', $_POST);

    if (Input::get('token')) {
        $validate = new Validate();
        switch (Input::get('rq')) {
            case 'add':
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'intro' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'post' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'date' => array(
                        'required' => true,
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                    'slug' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'edit':
                Session::delete('form_data');
                // $backto = '../blog/edit/' . Input::get('id');
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'intro' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'post' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'date' => array(
                        'required' => true,
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                    'slug' => array(
                        'required' => true,
                    ),
                    'id' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            if (!empty($_FILES) && $_FILES['featured']['error'] === 0) {
                if ($validate->checkFiles($_FILES['featured'], 'file', 1)->passed()) {
                    $temp = explode(".", $_FILES["featured"]["name"]);
                    $fname = Input::get('slug') . '-' . Helpers::getUnique(2);
                    $newfilename = $fname . '.' . end($temp);

                    // check path
                    $path = (file_exists("../../assets/images/blog/") && is_writeable("../../assets/images/blog/")) ? "../../assets/images/blog/" : (mkdir("../../assets/images/blog/", 0777, true) ? "../../assets/images/blog/" : "../media/");

                    // move and create preview
                    if (move_uploaded_file($_FILES["featured"]["tmp_name"], $path . $newfilename)) {
                        $image = $newfilename;
                    }

                    $featured = isset($image) ? $image : null;
                }
            }
        }

        if ($validation->passed()) {
            switch (Input::get('rq')) {
                case 'add':
                    try {
                        $blogs->create(array(
                            'title' => Input::get('title'),
                            'intro' => Input::get('intro'),
                            'post' => nl2br(Input::get('post')),
                            'cover' => $featured ? $featured : 'default.jpg',
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'featured' => Input::get('featured') == 'public' ? 1 : 0,
                            'slug' => Input::get('slug'),
                            'date' => Input::get('date'),
                        ));
                        Session::delete('form_data');
                        Session::flash('success', "Added Successfully");
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        if (isset($featured)) {
                            Helpers::deleteFile("../../assets/images/blog/" . $featured);
                        }
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = $blogs->get(Input::get('id'));
                        if ($found) {
                            if (isset($featured) && $found->cover != 'default.jpg') {
                                Helpers::deleteFile("../../assets/images/blog/" . $found->cover);
                            }

                            $blogs->update(array(
                                'title' => Input::get('title'),
                                'intro' => Input::get('intro'),
                                'post' => Input::get('post'),
                                'cover' => $featured ? $featured : $found->cover,
                                'status' => Input::get('status') == 'public' ? 1 : 0,
                                'featured' => Input::get('featured') == 'public' ? 1 : 0,
                                'slug' => Input::get('slug'),
                                'date' => Input::get('date'),
                            ), Input::get('id'));

                            Session::delete('form_data');
                            Session::flash('success', "Updated Successfully");
                        } else {
                            if (isset($featured)) {
                                Helpers::deleteFile("../../assets/images/blog/" . $featured);
                            }
                            Session::flash('error', "Something went wrong! disease not found");
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
