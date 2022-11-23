<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$categories = new General('categories');
$category_specials = new General('category_specials');
$category_special_datas = new General('category_special_datas');

$backto = Input::get('backto') ? '../' . Input::get('backto') : '../specialcats';
if (
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $category_specials->get(Input::get('id')) : null;
            if ($found) {
                $category_specials->update(array(
                    'status' => !$found->status,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'status':
            $found = Input::get('id') ? $categories->get(Input::get('id')) : null;
            if ($found) {
                $categories->update(array(
                    'status' => !$found->status,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'classified':
            $found = Input::get('id') ? $categories->get(Input::get('id')) : null;
            if ($found) {
                $categories->update(array(
                    'classified' => !$found->classified,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;

        case 'data-delete':
            $found = Input::get('id') ? $category_special_datas->get(Input::get('id')) : null;
            if ($found) {
                $category_special_datas->remove($found->id);
                Session::flash('success', "Deleted Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
            
        case 'delete':
            $found = Input::get('id') ? $category_specials->get(Input::get('id')) : null;
            if ($found) {
                $found->image && $found->image != 'default.jpg' ? Helpers::deleteFile("../../assets/images/category/" . $found->image) : null;
                $category_specials->remove($found->id);
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
            case 'add':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'unique' => 'category_specials',
                    ),
                ));
                break;
            case 'edit':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                    ),
                    'id' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'add-data':
                // validate
                $validation = $validate->check($_POST, array(
                    'country' => array(
                        'required' => true,
                    ),
                    'state' => array(
                        'required' => true,
                    ),
                    'category_special_id' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'edit-data':
                // validate
                $validation = $validate->check($_POST, array(
                    'country' => array(
                        'required' => true,
                    ),
                    'state' => array(
                        'required' => true,
                    ),
                    'category_special_id' => array(
                        'required' => true,
                    ),
                    'id' => array(
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
                    $path = (file_exists("../../assets/images/category/") && is_writeable("../../assets/images/category/")) ? "../../assets/images/category/" : (mkdir("../../assets/images/category/", 0777, true) ? "../../assets/images/category/" : "../../assets/");

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
                        $category_specials->create(array(
                            'title' => Input::get('title'),
                            'type' => Input::get('type'),
                            'description' => Input::get('description'),
                            'image' => $image ? $image : 'default.jpg',
                            'slug' => Helpers::slugify(Input::get('title')),
                        ), $sfound->id);
                        
                        Session::flash('success', 'Updated Successfully');

                        Redirect::to($backto);
                    } catch (Exception $e) {
                        if (isset($image) && $image) {
                            $path = "../../assets/images/category/" . $found->cover;
                            Helpers::deleteFile($path);
                        }
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = Input::get('id') ? $category_specials->get(Input::get('id')) : null;
                        if ($found) {
                            if (isset($image) && $image != $found->cover && $found->cover != 'default.jpg') {
                                $path = "../../assets/images/category/" . $found->cover;
                                Helpers::deleteFile($path);
                            }
                            $category_specials->update(array(
                                'title' => Input::get('title'),
                                'description' => Input::get('description'),
                                'image' => $image ? $image : $found->image,
                                'slug' => Helpers::slugify(Input::get('title')),
                            ), $found->id);
                            Session::flash('success', 'Updated Successfully');
                        } else {
                            if (isset($image) && $image) {
                                $path = "../../assets/images/category/" . $found->cover;
                                Helpers::deleteFile($path);
                            }
                            Session::flash('error', 'Failed to add products');
                        }
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'add-data':
                    try {
                        
                        $category_special_datas->create(array(
                            'category_special_id' => Input::get('category_special_id'), 
                            'country' => Input::get('country'), 
                            'state' => Input::get('state'), 
                            'menus' => Input::get('menus') ? implode(',', Input::get('menus')) : null,
                            'categories' => Input::get('categories') ? implode(',', Input::get('categories')) : null,
                            'vendors' => Input::get('vendors') ? implode(',', Input::get('vendors')) : null,
                            'status' => Input::get('status') ? 1 : 0
                        ));
                        
                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit-data':
                    try {
                        $found = Input::get('id') ? $category_special_datas->get(Input::get('id')) : null;
                        if ($found) {
                            $category_special_datas->update(array(
                                'category_special_id' => Input::get('category_special_id'), 
                                'country' => Input::get('country'), 
                                'state' => Input::get('state'), 
                                'menus' => Input::get('menus') ? implode(',', Input::get('menus')) : null,
                                'categories' => Input::get('categories') ? implode(',', Input::get('categories')) : null,
                                'vendors' => Input::get('vendors') ? implode(',', Input::get('vendors')) : null,
                                'status' => Input::get('status') ? 1 : 0
                            ), $found->id);
                            Session::flash('success', 'Updated Successfully');
                            Redirect::to($backto);
                        } else {
                            Session::flash('error', 'Failed to add products');
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
