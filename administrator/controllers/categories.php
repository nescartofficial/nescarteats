<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$categories = new General('categories');
$category_specials = new General('category_specials');

$backto = Input::get('backto') ? '../' . Input::get('backto') : '../categories';
if (
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'special-status':
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
        case 'featured':
            $found = Input::get('id') ? $categories->get(Input::get('id')) : null;
            if ($found) {
                $categories->update(array(
                    'featured' => !$found->featured,
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
        case 'popular':
            $found = Input::get('id') ? $categories->get(Input::get('id')) : null;
            if ($found) {
                $categories->update(array(
                    'popular' => !$found->popular,
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

        case 'delete':
            $found = Input::get('id') ? $categories->get(Input::get('id')) : null;
            if ($found) {
                Helpers::deleteFile("../../assets/images/category/" . $found->image);
                $categories->remove($found->id);
                Session::flash('success', "Deleted Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'delete-special':
            $found = Input::get('id') ? $category_specials->get(Input::get('id')) : null;
            if ($found) {
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
            case 'add-special':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'unique' => 'category_specials',
                    ),
                ));
                break;
            case 'edit-special':
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
            case 'add':
                // validate
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                        'unique' => 'categories'
                    ),
                    'slug' => array(
                        'required' => true,
                        'min' => 2,
                        'unique' => 'categories'
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
            if (!empty($_FILES) && $_FILES['file']['error'] === 0) {
                if ($validate->checkFiles($_FILES['file'], 'file', 1)->passed()) {
                    $temp = explode(".", $_FILES["file"]["name"]);
                    $fname = str_replace(' ', '-', strtolower(Input::get('title')));
                    $newfilename = $fname . '.' . end($temp);

                    // check path
                    $path = (file_exists("../../assets/images/category/") && is_writeable("../../assets/images/category/")) ? "../../assets/images/category/" : (mkdir("../../assets/images/category/", 0777, true) ? "../../assets/images/category/" : "../../media/");

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
                case 'add-special':
                    try {
                        $category_specials->create(array(
                            'title' => Input::get('title'),
                            'categories' => Input::get('categories') ? implode(',', Input::get('categories')) : null,
                            'products' => Input::get('products') ? implode(',', Input::get('products')) : null,
                            'slug' => Helpers::slugify(Input::get('title')),
                        ), $sfound->id);
                        Session::flash('success', 'Updated Successfully');

                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit-special':
                    try {
                        $found = Input::get('id') ? $category_specials->get(Input::get('id')) : null;
                        if ($found) {
                            $category_specials->update(array(
                                'title' => Input::get('title'),
                                'categories' => Input::get('categories') ? implode(',', Input::get('categories')) : null,
                                'products' => Input::get('products') ? implode(',', Input::get('products')) : null,
                            ), $found->id);
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
                        $categories->create(array(
                            'title' => Input::get('title'),
                            'deal' => Input::get('deal'),
                            'image' => $image ? $image : 'default.jpg',
                            'slug' => Input::get('slug'),
                            'percentage' => Input::get('percentage'),
                            'parent_id' => Input::get('category') ? Input::get('category') : 0,
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'classified' => Input::get('classified') == 'public' ? 1 : 0,
                            'date_added' => date("Y-m-d H:i:s", time()),
                        ));
                        Session::flash('success', 'Added Successfully');
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
                        $found = Input::get('id') ? $categories->get(Input::get('id')) : null;
                        if ($found) {
                            if (isset($image) && $image != $found->cover && $found->cover != 'default.jpg') {
                                $path = "../../assets/images/category/" . $found->cover;
                                Helpers::deleteFile($path);
                            }

                            $categories->update(array(
                                'title' => Input::get('title'),
                                'deal' => Input::get('deal'),
                                'percentage' => Input::get('percentage'),
                                'parent_id' => Input::get('category') ? Input::get('category') : $found->parent_id,
                                'classified' => Input::get('classified') == 'public' ? 1 : 0,
                                'image' => $image ? $image : $found->image,
                                'status' => Input::get('status') == 'public' ? 1 : 0,
                                'slug' => Input::get('slug'),
                            ), $found->id);
                            Session::flash('success', 'Updated Successfully');
                        }
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        if (isset($image) && $image) {
                            $path = "../../assets/images/category/" . $found->cover;
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
