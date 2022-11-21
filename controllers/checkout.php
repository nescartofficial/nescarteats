<?php
require_once("../core/init.php");
$user = new User();
$constants = new Constants();
$profiles = new General('profiles');
$delivery_fees = new General('delivery_fees');
$addresses = new General('addresses');
$orders = new General('orders');
$menus = new General('menus');
$cart = new Cart();
$pickups = new General('pickup_points');
$backto = Input::get('backto') ? Input::get('backto') : '../checkout';

if (
    Input::exists('get') &&
    $user->isLoggedIn()
) {
    if (Input::get('rq')) {
        switch (Input::get('rq')) {
            case 'change':
                try {
                    switch (Input::get('action')) {
                        case 'address':
                            $checkout = Session::get('checkout');
                            $checkout['billing'] = null;
                            Session::put('checkout', $checkout);
                            break;
                        case 'delivery':
                            $checkout = Session::get('checkout');
                            $checkout['delivery'] = null;
                            Session::put('checkout', $checkout);
                            break;
                    }

                    // Session::flash('success', 'Item have been ordered');
                    Redirect::to_js($backto);
                } catch (Exception $e) {
                    Session::flash('error', $e->getMessage());
                }
                break;
            case 'pay':
                try {

                    $orders->create(array(
                        'order_id' => Token::generate(),
                        'user_id' => $user->data()->id,
                        'details' => $cart->get_cart_json(),
                        'payment_type' => 2,
                        'total_amount' => $cart->get_total_amount(),
                        'created' => date('Y-m-d H:i:s', time()),
                        'status' => 1,
                        'cancel' => 0,
                    ));

                    $cart->clear();

                    $cart->remove_from_cart();
                    Session::flash('success', 'Item have been ordered');
                    Redirect::to_js($backto . '/paid/transfer');
                } catch (Exception $e) {
                    Session::flash('error', $e->getMessage());
                }
                break;
        }
    }

    Redirect::to_js($backto);
}
// print_r($_POST); die;
// Checking if input exists
if (
    $user->isLoggedIn() &&
    Input::exists() &&
    Input::get('rq')
) {
    if (1) {
        $validate = new Validate();

        switch (trim(Input::get('rq'))) {
            case 'address':
                $validation = $validate->check($_POST, array(
                    'address_id' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'delivery':
                $validation = $validate->check($_POST, array(
                    'options' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'pay':
                $validation = $validate->check($_POST, array(
                    'order' => array(
                        'required' => true,
                    ),
                    'pickup' => array(
                        'required' => true,
                    ),
                    'payment' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (Input::get("rq")) {
                case 'address':
                    try {

                        // Address                        
                        $found = $addresses->get(Input::get('address_id'));
                        $data = array(
                            "address_id" => $found->id,
                            "title" => $found->title,
                            "country" => $found->country,
                            "state" => $found->state,
                            "city" => $found->city,
                            "address" => $found->address,
                        );

                        // Delivery
                        $delivery_price = 0;
                        // $cart_items = $cart->get_cart();
                        // if ($cart_items) {
                        //     foreach ($cart_items as $k => $v) {
                        //         $menu = $menus->get($v['id']);
                        //         if ($menu) {
                        //             $delivery_fee = $delivery_fees->get($menu->category, 'category') ? $delivery_fees->get($menu->category, 'category_id') : $delivery_fees->get($menu->parent_category, 'category_id');
                        //             $delivery_price += $delivery_fee ? $delivery_fee->fee : 0;
                        //         }
                        //     }
                        // }

                        $location_fee = $delivery_fees->get($user->getProfile()->city, 'city');
                        $delivery_price +=  $location_fee ? $location_fee->fee : 0;

                        $delivery_method = Input::get('delivery_method') ? Input::get('delivery_method') : 'delivery';
                        $pickup_locate = $delivery_method == 'pickup' ? $pickups->get(Input::get('pickup_location')) : null;
                        $pickup_locate = $pickup_locate ? $pickup_locate->title : 'nil';

                        $delivery_price ? Session::put('delivery_price', $delivery_price) : null;
                        Session::put('checkout', array('billing' => json_encode($data), 'delivery' => $delivery_method, 'pickup_location' => $pickup_locate, 'delivery_price' => $delivery_price, 'ref' => Input::get('ref') ? Input::get('ref') : 'NONE'));

                        Redirect::to_js($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to_js($backto);
                    }
                    break;
                case 'delivery':
                    try {
                        $price = 0;
                        $cart_items = $cart->get_cart();
                        if ($cart_items) {
                            foreach ($cart_items as $k => $v) {
                                $menu = $menus->get($v->id);
                                if ($menu) {
                                    $delivery_fee = $delivery_fees->get($menu->category, 'category') ? $delivery_fees->get($menu->category, 'category_id') : $delivery_fees->get($menu->parent_category, 'category_id');
                                    $price += $delivery_fee ? $delivery_fee->fee : 0;
                                }
                            }
                        }
                        $location_fee = $delivery_fees->get($user->getProfile()->city, 'city');
                        $price +=  $location_fee ? $location_fee->fee : 0;

                        $pickup_locate = Input::get('options') == 'pickup' ? $pickups->get(Input::get('pickup_location')) : null;
                        $pickup_locate = $pickup_locate ? $pickup_locate->title : 'nil';
                        Session::put('checkout', array_merge(Session::get('checkout'), array('delivery' => Input::get('options'), 'pickup_location' => $pickup_locate, 'delivery_price' => $price)));
                        Redirect::to_js($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to_js($backto);
                    }
                    break;
                case 'pay':
                    try {

                        $orders->create(array(
                            'order_id' => Input::get('ref'),
                            'user_id' => $user->data()->id,
                            'details' => $cart->get_cart_json(),
                            'payment_type' => 1,
                            'total_amount' => $cart->get_total_amount(),
                            'created' => date('Y-m-d H:i:s', time()),
                            'status' => 1,
                            'cancel' => 0,
                        ));

                        $cart->clear();
                        Session::flash('success', 'Order made, successfully, Awaiting Confirmation.');
                        Redirect::to_js($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to_js($backto);
                    }
                    break;
                case 'edit':
                    try {
                        $ads->update(array(
                            'title' => Input::get('title'),
                            'location' => Input::get('location'),
                            'date' => Input::get('date'),
                            'category' => Input::get('category'),
                            'image' => 'image',
                            'status' => Input::get('status'),
                        ), Input::get('id'));

                        Session::flash('success', "Couse Updated Successfully");
                        Redirect::to_js($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
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
