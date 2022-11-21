<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$Variations = new General('menu_variations');
$Addons = new General('menu_addons');
$categories = new General('categories');
$menus = new General('menus');
$sellers = new General('sellers');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../dashboard/menus';

// print_r(($_POST['variations']));
// die;

if (
    $user->isLoggedIn() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $menus->get(Input::get('id')) : null;
            if ($found) {
                $menus->update(array(
                    'status' => $found->status ? 0 : 1,
                ), $found->id);
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'delete':
            $found = Input::get('id') ? $menus->get(Input::get('id')) : null;
            if ($found) {
                if ($found->image != 'default.jpg') {
                    $images = explode(',', $found->image);
                    foreach ($images as $k => $v) {
                        Helpers::deleteFile("../assets/images/menus/" . $v);
                    }
                }

                // Remove Addons
                $list = $Addons->getAll($found->id, 'menu_id', '=');
                foreach ($list as $addon) {
                    $Addons->remove($addon->id);
                }

                // Remove Addons
                $list = $Variations->getAll($found->id, 'menu_id', '=');
                foreach ($list as $variation) {
                    $Variations->remove($variation->id);
                }

                $menus->remove($found->id);
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
    Input::exists() &&
    Input::get('rq')
) {
    Session::put('form-data', $_POST);
    if (1) { //Token::check(Input::get('token'))) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'add':
                // validate
                $backto .= "/dashboard/manage-menus?category=" . Input::get('category');
                $_POST['slug'] .= '-' . $user->data()->uid;
                $validation = $validate->check($_POST, array(
                    'title' => array(
                        'required' => true,
                        'min' => 2,
                    ),
                    'category' => array(
                        'required' => true,
                    ),
                    'price' => array(
                        'required' => true,
                    ),
                    'description' => array(
                        'required' => true,
                    ),
                    'ingredients' => array(
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
                    ),
                    'price' => array(
                        'required' => true,
                    ),
                    'description' => array(
                        'required' => true,
                    ),
                    'ingredients' => array(
                        'required' => true,
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        // // if validation is passed
        // $cover = null;
        // $menus_img = null;
        // if ($validation->passed()) {
        //     if (!empty($_FILES) && $_FILES['file']['error']['0'] === 0) {
        //         $multiple_image = $validate->getMultipleFiles('file');

        //         $count = 0;
        //         foreach ($multiple_image as $file) {
        //             ++$count;

        //             $upload = new Upload($file);
        //             if ($upload->uploaded) {
        //                 // save uploaded image with a new name
        //                 $upload->file_overwrite = true;
        //                 $upload->dir_auto_create = true;
        //                 $upload->png_compression = 5;
        //                 $upload->file_new_name_body = Helpers::slugify(Input::get('title'));
        //                 $upload->file_name_body_add = '-' . $user->data()->uid . '-' . Helpers::getUnique(2, 'a');
        //                 $upload->process("../assets/images/menus/");

        //                 if ($upload->processed) {
        //                     $menus_img .= $upload->file_dst_name . ',';

        //                     if ($count < 2) {
        //                         $cover = $upload->file_dst_name;
        //                     }
        //                 } else {
        //                     Session::flash('error',  $upload->error);
        //                     Redirect::to_js($backto);
        //                 }
        //             }
        //         }
        //     }
        // }

        $menus_img = $menus_img ? rtrim($menus_img, ',') : null;
        if ($validation->passed()) {
            switch (trim(Input::get('rq'))) {
                case 'add':
                    try {
                        $db = DB::getInstance();
                        $category = $categories->get(Input::get('category'));


                        // Media
                        $images = Session::exists('menu_images') ? Session::get('menu_images') : null;
                        $cover_image = Session::exists('cover_position') ? $images[Session::get('cover_position')] : $images[0];

                        // Generate SKU
                        $vendor = $user->getVendor();
                        $vendor_slug = explode("-", $vendor->slug);
                        $vendor_slug_sku = count($vendor_slug) > 0 ? $vendor_slug[0][0] . $vendor_slug[1][0] : $vendor_slug[0][0] . $vendor_slug[0][1];
                        $sku = strtoupper($vendor_slug_sku) . $product_pre . $category->parent_id . date('d'); // Seller Name, Product UID, Parent_category, day.

                        $menus->create(array(
                            'vendor_id' => $user->getVendor()->id,
                            'user_id' => $user->data()->id,
                            'category' => $category->id,
                            'title' => ucfirst(trim(Input::get('title'))),
                            'price' => Input::get('price'),
                            'discount_price' => Input::get('discount_price'),
                            'apply_discount' => Input::get('apply_discount') ? 1 : 0,
                            'description' => nl2br(Input::get('description')),
                            'ingredients' => json_encode(Input::get('ingredients')),
                            'special' => Input::get('special') ? Input::get('special') : 0,
                            'cover' => $cover_image,
                            'image' => $images ? implode(',', $images) : $found->image,
                            'slug' => Helpers::slugify(Input::get('title')) . '-' . $user->getVendor()->id,
                            'featured' => 0,
                            'sku' => $sku,
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'date_added' => date("Y-m-d H:i:s", time()),
                        ));

                        $menu_id = $db->lastInsertId();

                        // Add menu Variation
                        $menu_variations = Input::get('variations')  ? Input::get('variations') : null;
                        if ($menu_variations) {
                            foreach ($menu_variations as $variation) {
                                $Variations->create(array(
                                    'vendor_id' => $user->getVendor()->id,
                                    'menu_id' => $menu_id,
                                    'variation' => $variation['title'],
                                    'price' => $variation['price'] ? $variation['price'] : 0,
                                    'updated_at' => date('Y-m-d H:i:s', time()),
                                ));
                            }
                        }

                        // Add menu addons
                        $menu_addons = Input::get('addons')  ? Input::get('addons') : null;
                        if ($menu_addons) {
                            foreach ($menu_addons as $addon) {
                                $Addons->create(array(
                                    'vendor_id' => $user->getVendor()->id,
                                    'menu_id' => $menu_id,
                                    'addon' => $addon['title'],
                                    'price' => $addon['price'] ? $addon['price'] : 0,
                                    'updated_at' => date('Y-m-d H:i:s', time()),
                                ));
                            }
                        }

                        // Send Message
                        $seller = $user->getVendor();
                        $product_name = ucfirst(Input::get('title'));
                        $product_amount = Helpers::format_currency(Input::get('price'));

                        $message = "<p>A new product have been added by {$seller->name} and awaiting your approval.</p>";
                        $message .= "<p>Product Name: {$product_name}</p>";
                        $message .= "<p>Amount: {$product_amount}</p>";

                        $subject = "New Product Notification";
                        $name = "Admin";

                        Messages::send($message, $subject, null, $name, true);


                        Session::exists('menu_images') ? Session::delete('menu_images') : null;
                        Session::flash('success', 'Added Successfully');
                        Session::delete('form-data');
                        Redirect::to("../dashboard/menus");
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = Input::get('id') ? $menus->get(Input::get('id')) : null;
                        if ($found) {
                            $category = $categories->get(Input::get('category'));

                            // Media
                            $images = Session::exists('menu_images') ? Session::get('menu_images') : null;
                            $cover_image = $images ? (Session::exists('cover_position') ? $images[Session::get('cover_position')] : $images[0]) : (Input::get('cover_image') ? Input::get('cover_image') : $found->cover);

                            // SKu
                            $sku = $found->sku;
                            if (!$sku) {
                                $vendor = $user->getVendor();
                                $vendor_slug = explode("-", $vendor->slug);
                                $vendor_slug_sku = count($vendor_slug) > 0 ? $vendor_slug[0][0] . $vendor_slug[1][0] : $vendor_slug[0][0] . $vendor_slug[0][1];
                                $sku = strtoupper($vendor_slug_sku) . $product_pre . $category->parent_id . date('d'); // Seller Name, Product UID, Parent_category, day.
                            }

                            $menus->update(array(
                                'category' => $category->id,
                                'title' => ucfirst(trim(Input::get('title'))),
                                'price' => Input::get('price'),
                                'discount_price' => Input::get('discount_price'),
                                'apply_discount' => Input::get('apply_discount') ? 1 : 0,
                                'description' => nl2br(Input::get('description')),
                                'ingredients' => json_encode(Input::get('ingredients')),
                                'special' => Input::get('special') ? Input::get('special') : 0,
                                'cover' => $cover_image,
                                'image' => $images ? implode(',', $images) : $found->image,
                                'slug' => Helpers::slugify(Input::get('title')) . '' . Helpers::getUnique(2, 'ad'),
                                'featured' => 0,
                                'status' => Input::get('status') == 'public' ? 1 : 0,
                            ), $found->id);

                            // Add menu Variation
                            $menu_variations = Input::get('variations')  ? Input::get('variations') : null;
                            if ($menu_variations) {
                                foreach ($menu_variations as $variation) {
                                    $data = array(
                                        'vendor_id' => $user->getVendor()->id,
                                        'menu_id' => $found->id,
                                        'variation' => $variation['title'],
                                        'price' => $variation['price'],
                                        'updated_at' => date('Y-m-d H:i:s', time()),
                                    );
                                    $vfound = $Variations->get($variation['id']);
                                    $vfound ? $Variations->update($data, $variation['id']) : $Variations->create($data);
                                }
                            }

                            // Add menu addons
                            $menu_addons = Input::get('addons')  ? Input::get('addons') : null;
                            if ($menu_addons) {
                                foreach ($menu_addons as $addon) {
                                    $data = array(
                                        'vendor_id' => $user->getVendor()->id,
                                        'menu_id' => $found->id,
                                        'addon' => $addon['title'],
                                        'price' => $addon['price'],
                                        'updated_at' => date('Y-m-d H:i:s', time()),
                                    );
                                    $vfound = $Addons->get($addon['id']);
                                    $vfound ? $Addons->update($data, $addon['id']) : $Addons->create($data);
                                }
                            }

                            Session::exists('menu_images') ? Session::delete('menu_images') : null;
                            Session::flash('success', 'Updated Successfully');
                            Session::delete('form-data');
                            Redirect::to("../dashboard/menus");
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
