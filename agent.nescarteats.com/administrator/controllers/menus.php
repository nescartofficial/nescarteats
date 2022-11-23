<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$Menus = new Menus();
$Categories = new General('categories');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../menus';

if (
    $user->isLoggedIn() &&
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $Menus->get(Input::get('id')) : null;
            if ($found) {
                $Menus->update(array(
                    'status' => $found->status ? 0 : 1,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'approve':
            $found = Input::get('id') ? $Menus->get(Input::get('id')) : null;
            if ($found) {
                $Menus->update(array(
                    'approved' => $found->approve ? 0 : 1,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'delete':
            $found = Input::get('id') ? $Menus->get(Input::get('id')) : null;
            if ($found) {
                if ($found->image != 'default.jpg') {
                    $images = $found->image;
                    $img = str_replace('"', "", json_decode(json_encode(explode(',', $images))));
                    $images = (str_replace('[', "", str_replace(']', "", $img)));

                    $path = '';
                    for ($i = 0; $i < count($images); $i++) {
                        $path = "../../assets/images/menus/" . $images[$i];
                        $rpath = "../../assets/images/resized/products/" . $images[$i];
                        Helpers::deleteFile($path);
                        Helpers::deleteFile($rpath);
                    }
                }

                if ($found->cover != 'default.jpg') {
                    $path = "../../assets/images/menus/" . $found->cover;
                    $rpath = "../../assets/images/resized/products/" . $found->cover;
                    Helpers::deleteFile($path);
                    Helpers::deleteFile($rpath);
                }

                $Menus->remove($found->id);
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
                        'unique' => 'products'
                    ),
                    'category' => array(
                        'required' => true,
                        'notDefault' => true,
                    ),
                    'amount' => array(
                        'required' => true,
                    ),
                    'description' => array(
                        'required' => true,
                    ),
                    'quantity' => array(
                        'required' => true,
                        'positiveint' => true,
                    ),
                    'slug' => array(
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
                    'category' => array(
                        'required' => true,
                        'notDefault' => true,
                    ),
                    'amount' => array(
                        'required' => true,
                    ),
                    'description' => array(
                        'required' => true,
                    ),
                    'quantity' => array(
                        'required' => true,
                        'positiveint' => true,
                    ),
                    'slug' => array(
                        'required' => true,
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        // if validation is passed
        if ($validation->passed()) {
            if (!empty($_FILES) && $_FILES['cover']['error']['0'] === 0) {

                if ($validate->checkFiles($_FILES['cover'], 'file', 1)->passed()) {
                    //print_r( 'passed'); die;
                    foreach ($_FILES['cover']['name'] as $index => $files) {
                        $temp = explode(".", $_FILES["cover"]["name"][$index]);
                        $fname = Helpers::getUnique(5);
                        $newfilename = $fname . '.' . end($temp);

                        // check path
                        $path = (file_exists("../../assets/images/menus/") && is_writeable("../../assets/images/menus/")) ? "../../assets/images/menus/" : (mkdir("../../assets/images/menus/", 0777, true) ? "../../assets/images/menus/" : "../../assets/");
                        // preview path
                        $prevPath = (file_exists("../../assets/images/resized/products/") && is_writeable("../../assets/images/resized/products/")) ? "../../assets/images/resized/products/" : (mkdir("../../assets/images/resized/products/", 0777, true) ? "../../assets/images/resized/products/" : "../../assets/");

                        // move and create preview
                        if (move_uploaded_file($_FILES["cover"]["tmp_name"][$index], $path . $newfilename) && $validate->imagePreviewSize($path . $newfilename, $prevPath, $fname, 160, 260)) {
                            // && $validate->imagePreviewSize($path.$newfilename, $prevPath, $fname, 400, 400)
                            $image = $newfilename;
                        }
                    }

                    $cover = isset($image) ? $image : null;
                }
            }
        }

        $Menus_img = "";
        if ($validation->passed()) {
            if (!empty($_FILES) && $_FILES['file']['error']['0'] === 0) {
                if ($validate->checkFiles($_FILES['file'], 'file', 1)->passed()) {
                    //print_r( 'passed'); die;
                    foreach ($_FILES['file']['name'] as $index => $files) {
                        $temp = explode(".", $_FILES["file"]["name"][$index]);
                        $fname = Helpers::getUnique(5);
                        $newfilename = $fname . '.' . end($temp);

                        // check path
                        $path = (file_exists("../../assets/images/menus/") && is_writeable("../../assets/images/menus/")) ? "../../assets/images/menus/" : (mkdir("../../assets/images/menus/", 0777, true) ? "../../assets/images/menus/" : "../../assets/");
                        // preview path
                        $prevPath = (file_exists("../../assets/images/resized/products/") && is_writeable("../../assets/images/resized/products/")) ? "../../assets/images/resized/products/" : (mkdir("../../assets/images/resized/products/", 0777, true) ? "../../assets/images/resized/products/" : "../../assets/");

                        // move and create preview
                        if (move_uploaded_file($_FILES["file"]["tmp_name"][$index], $path . $newfilename) && $validate->imagePreviewSize($path . $newfilename, $prevPath, $fname, 220, 300)) {
                            // && $validate->imagePreviewSize($path.$newfilename, $prevPath, $fname, 400, 400)
                            $image = $newfilename;
                            $Menus_img .= $newfilename . ',';
                        }
                    }
                    $image = isset($image) ? $image : null;
                }
            }
        }

        // print_r(rtrim($Menus_img, ',')); die;
        $Menus_img = $Menus_img ? rtrim($Menus_img, ',') : null; //json_encode(explode(',', rtrim($Menus_img, ','))) : null;

        if ($validation->passed()) {
            switch (trim(Input::get('rq'))) {
                case 'add':
                    try {
                        $found_cat = $Categories->get(Input::get('category'));
                        if (!$found_cat) {
                            Session::flash('error', 'Something went wrong, try again');
                            Redirect::to($backto);
                        }

                        $Menus->create(array(
                            'category' => Input::get('category'),
                            'price' => Input::get('amount'),
                            'slashed_price' => Input::get('slash') ? Input::get('slash') : null,
                            'description' => Input::get('description'),
                            'quantity' => Input::get('quantity'),
                            'title' => ucfirst(Input::get('title')),
                            'cover' => $cover ? $cover : 'default.jpg',
                            'image' => $Menus_img ? $Menus_img : 'default.jpg',
                            'slug' => Input::get('slug'),
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'date_added' => date("Y-m-d H:i:s", time()),
                        ));

                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        if (isset($cover) && $cover) {
                            $path = "../../assets/images/menus/" . $found->cover;
                            Helpers::deleteFile($path);
                        }
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found_cat = $Categories->get(Input::get('category'));
                        if (!$found_cat) {
                            Session::flash('error', 'Something went wrong, category not found!');
                            Redirect::to($backto);
                        }


                        $found = Input::get('id') ? $Menus->get(Input::get('id')) : null;
                        if ($found) {

                            if ($Menus_img && $Menus_img != $found->image && $found->image != 'default.jpg') {
                                $images = $found->image;
                                $img = str_replace('"', "", json_decode(json_encode(explode(',', $images))));
                                $images = (str_replace('[', "", str_replace(']', "", $img)));

                                $path = '';
                                for ($i = 0; $i < count($images); $i++) {
                                    $path = "../../assets/images/menus/" . $images[$i];
                                    $rpath = "../../assets/images/resized/products/" . $images[$i];
                                    Helpers::deleteFile($path);
                                    Helpers::deleteFile($rpath);
                                }
                            }

                            if (isset($cover) && $cover != $found->cover && $found->cover != 'default.jpg') {
                                $path = "../../assets/images/products/" . $found->cover;
                                $rpath = "../../assets/images/resized/products/" . $found->cover;
                                Helpers::deleteFile($path);
                                Helpers::deleteFile($rpath);
                            }

                            $Menus->update(array(
                                'category' => Input::get('category'),
                                'price' => Input::get('amount'),
                                'slashed_price' => Input::get('slash') ? Input::get('slash') : $found->slashed_price,
                                'description' => Input::get('description'),
                                'quantity' => Input::get('quantity'),
                                'title' => ucfirst(Input::get('title')),
                                'cover' => isset($cover) ? $cover : $found->cover,
                                'image' => $Menus_img ? $Menus_img : $found->image,
                                'slug' => Input::get('slug'),
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
