<?php
require_once("../core/init.php");
$User = new User();
$Cart = new Cart();
$Menus = new Menus();

$saved_menus = new General('saved_menus');
$categories = new General('categories');
$world = isset($world) ? $world : new World();
$orders = new General('orders');
$result = array();

// print_r($_POST);
if (Input::exists() && Input::get('rq')) {
    switch (trim(Input::get('rq'))) {
        case 'menu':
            $menu = Input::get('menu') ? $Menus->get(Input::get('menu')) : null;

            if ($menu) {
                $vendor = $Menus->getVendor($menu->vendor_id);
                $vendor_slug = 'vendor/' . $vendor->slug;
                // get Cart
                $cart = $Cart->get_cart($menu->id);

                // Favourite
                $is_saved_menu = $User->isLoggedIn() ? $saved_menus->getByUser($menu->id, 'menu_id', $User->data()->id) : null;

                // set category
                $category = $categories->get($menu->category);
                $title = $menu->title;
                $total_price = $cart ? $Cart->get_amount($menu->id) : Helpers::format_currency($menu->price);
                $price = Helpers::format_currency($menu->price);

                // Ingredients
                // $list = json_decode($menu->ingredients);
                // print_r($list);
                // $ingredients = '';
                // if ($list) {
                //     foreach ($list as $ingredient) {
                //         $ingredients .= "<span class='badge text-bg-secondary'>{$ingredient->value}</span>";
                //     }
                // }

                // Get Images;
                $images = null;
                $menu_images =  explode(',', $menu->image);
                $images = "<img data-lazy='assets/images/menus/{$menu->cover}' src='assets/images/menus/{$menu->cover}' class='' style='height: 102px; width: 102px; border-radius: 5px;' 'data-fancybox='gallery'>";
                // foreach ($menu_images as $image) {
                //     $images .= "<img data-lazy='assets/images/menus/{$image}' src='assets/images/menus/{$image}' class='item-thumb' style='height: 122px; width: 122px;' 'data-fancybox='gallery'>";
                // }

                // Get Variations
                $variation_list = $Menus->getVariations($menu->id, 'menu_id', '=');
                $mvariation = '';
                if ($variation_list) {
                    foreach ($variation_list as $k => $variation) {
                        $active = $Cart->hasVariation($menu->id, $variation->id) ? 'active' : null;
                        $mvariation .= "
                        <div class='col-6 col-lg-4 h-100 '>
                            <input type='radio' class='btn-check menu-variation' name='variation' value='on' data-id='{$variation->id}' data-menu='{$variation->menu_id}' id='variation-{$variation->id}' autocomplete='off'>
                            <label class='w-100 shadow-sm btn btn-outline-danger menu-variation-btn {$active}' for='variation-{$variation->id}'>
                                <small>{$variation->variation}</small>
                            </label>
                        </div>";
                    }
                }

                // Get Addons
                $addon_list = $Menus->getAddons($menu->id, 'menu_id', '=');
                $maddon = '';
                if ($addon_list) {
                    foreach ($addon_list as $k => $addon) {
                        $cart_addon = $Cart->hasAddon($menu->id, $addon->id);
                        $addon_quantity = $cart_addon ? $cart_addon['quantity'] : 1;

                        $active = $cart_addon ? 'active' : null;
                        $show = !$active ? 'd-none' : '';
                        $addon_price = Helpers::format_currency($addon->price);

                        $maddon .= "
                        <div class='col-6 col-lg-4 h-100 '>
                            <input type='checkbox' class='btn-check menu-addon' name='addon' value='on' data-id='{$addon->id}' data-menu='{$addon->menu_id}' id='addon-{$addon->id}' autocomplete='off'>
                            <label class='w-100 shadow-sm btn btn-outline-danger menu-addon-btn {$active}' for='addon-{$addon->id}'>
                                <small>{$addon->addon}</small><br/>
                                <small class='mb-0 mt-2'>{$addon_price}</small>

                                <div class='d-flex align-items-center justify-content-center mt-4 menu-addon-quantity {$show}'>
                                    <button class='rounded-circle fw-bold input-group-text menu-addon--quantity' data-type='dec' data-id='{$addon->id}' data-menu='{$addon->menu_id}' type='button' id='button-minus'> &minus; </button>
                                    <p class='fw-bold px-3 mb-0 addon-quantity-{$addon->id}' id='addon-quantity-{$addon->id}'>{$addon_quantity}</p>
                                    <button class='rounded-circle fw-bold input-group-text menu-addon--quantity' data-type='inc' data-id='{$addon->id}' data-menu='{$addon->menu_id}' type='button' id='button-plus'> &plus; </button>
                                </div>
                            </label>

                        </div>";
                    }
                }

                // Quantity
                $quantity_controller = "
                    <button class='rounded-circle fw-bold input-group-text dec-item' data-pid='{$menu->id}' type='button' id='button-minus'> &minus; </button>
                    <p class='fw-bold px-3 mb-0 item-quantity-{$menu->id}' id='item-quantity-{$menu->id}'>1</p>
                    <button class='rounded-circle fw-bold input-group-text inc-item' data-pid='{$menu->id}' type='button' id='button-plus'> &plus; </button>
                ";

                // Add to cart button
                $cart_text = $cart ? 'Update Cart' : 'Add to Cart';
                $cart_btn = $cart ?
                    "<button type='button' class='btn w-100' data-bs-dismiss='modal'>{$cart_text}</button>" :
                    "<button type='button' class='btn w-100 add-cart' data-pid='{$menu->id}'>{$cart_text}</button>";

                $result['success'] = array(
                    'images' => $images,
                    'title' => $title,
                    'category' => "<a href='find-vendors?category=$category->slug'>$category->title</a>",
                    'price' => $price,
                    'saved_fill' => $is_saved_menu ? '#ef9244' : 'none',
                    'totalamount' => $total_price,
                    'description' => $menu->description,
                    'variations' => $mvariation,
                    'addons' => $maddon,
                    'quantitybtn' => $quantity_controller,
                    'cartbtn' => $cart_btn,
                    'vendor' => $vendor_slug,
                );
            } else {
                $result['error'] = 'Data Not found';
            }
            break;
    }
}

echo json_encode($result);
