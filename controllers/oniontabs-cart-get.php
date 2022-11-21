<?php
require_once("../core/init.php");
$user = new User();
$cart = new Cart();
$menus = new General('menus');
$Variations = new General('menu_variations');
$Addons = new General('menu_addons');
$wallets = new General('wallets');
$orders = new General('orders');
$referrals = new General('referrals');
$settings = new General('settings');
$delivery_prices = new General('delivery_prices');

$ref_rate = $settings->get('REFERRAL_RATE', 'title')->value;

$result = array();
if (Input::exists() && Input::get('req')) {
    switch (trim(Input::get('req'))) {
        case 'get-count':
            try {
                $count = $cart->get_count(Input::get('pid'));
                if ($count) {
                    $result['success'] = array(
                        'count' => $count,
                    );
                } else {
                    $result['error'] = 'Failed to get cart count';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;
        case 'add-cart':
            try {
                if ($cart->add_to_cart(Input::get('pid'))) {
                    $result['success'] = array(
                        'count' => $cart->get_count(Input::get('pid')),
                        'title' => 'Item added to cart',
                    );
                } else {
                    $result['error'] = 'Failed to add item to cart';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;
        case 'add-variation-item':
            try {
                if (Input::get('menu') && Input::get('variation')) {
                    $variation = $Variations->get(Input::get('variation'));

                    if ($variation && $cart->add_variation(Input::get('menu'), $variation->id, $variation->price, $variation->variation)) {
                        $result['success'] = array(
                            'menu' => json_encode($cart->get_cart(Input::get('menu'))),
                            'message' => 'Variation Set ',
                            'count' => $cart->get_count(Input::get('menu')),
                            'quantity' => $cart->get_cart(Input::get('menu'))['quantity'],
                            'amount' => $cart->get_amount(Input::get('menu')),
                            'total_amount' => $cart->get_total_amount(),
                        );
                    } else {
                        $result['error'] = 'Failed to add item to cart';
                    }
                } else {
                    $result['error'] = 'Something went wrong';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;
        case 'add-addon-item':
            try {
                if (Input::get('menu') && Input::get('addon')) {
                    $addon = $Addons->get(Input::get('addon'));
                    if ($addon && $cart->add_addon(Input::get('menu'), $addon->id, $addon->price, $addon->addon)) {
                        $result['success'] = array(
                            'menu' => json_encode($cart->get_cart(Input::get('menu'))),
                            'message' => 'Addon Set',
                            'count' => $cart->get_count(Input::get('menu')),
                            'quantity' => $cart->get_cart(Input::get('menu'))['quantity'],
                            'amount' => $cart->get_amount(Input::get('menu'), Input::get('addon')),
                            'total_amount' => $cart->get_total_amount(),
                            'added' => $cart->hasAddon(Input::get('menu'), $addon->id),
                        );
                    } else {
                        $result['error'] = 'Failed to add item to cart';
                    }
                } else {
                    $result['error'] = 'Something went wrong';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;
        case 'add-addon-quantity':
            try {
                if (Input::get('menu') && Input::get('addon') && Input::get('type')) {
                    $addon = $Addons->get(Input::get('addon'));

                    if ($addon && $quantity = $cart->addon_quantity(Input::get('menu'), $addon->id, $addon->price, Input::get('type'))) {
                        $result['success'] = array(
                            'menu' => json_encode($cart->get_cart(Input::get('menu'))),
                            'message' => 'Addon Set ',
                            'count' => $cart->get_count(Input::get('menu')),
                            'quantity' => $cart->get_cart(Input::get('menu'))['quantity'],
                            'addon_quantity' => $quantity,
                            'amount' => $cart->get_amount(Input::get('menu'), Input::get('addon')),
                            'total_amount' => $cart->get_total_amount(),
                        );
                    } else {
                        $result['error'] = 'Failed to add item to cart';
                    }
                } else {
                    $result['error'] = 'Something went wrong';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;

        case 'inc-item':
            try {
                if ($cart->add_to_cart(Input::get('pid'))) {
                    $result['success'] = array(
                        'title' => 'Item quantity increased',
                        'quantity' => $cart->get_cart(Input::get('pid'))['quantity'],
                        'amount' => $cart->get_amount(Input::get('pid')),
                        'total_amount' => $cart->get_total_amount(),
                        'count' => $cart->get_count(Input::get('pid')),
                    );
                } else {
                    $result['error'] = 'Failed to increase item quantity';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;

        case 'dec-item':
            try {
                $item = $cart->get_cart(Input::get('pid'));
                if ($item && $item['quantity'] > 1) {
                    if ($cart->remove_from_cart(Input::get('pid'))) {
                        $result['success'] = array(
                            'title' => 'Item quantity reduced',
                            'quantity' => $cart->get_cart(Input::get('pid'))['quantity'],
                            'amount' => $cart->get_amount(Input::get('pid')),
                            'total_amount' => $cart->get_total_amount(),
                            'count' => $cart->get_count(Input::get('pid')),
                        );
                    } else {
                        $result['error'] = 'Failed to decrease item quantity';
                    }
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;

        case 'cart-remove-item':
            try {
                $item = $cart->get_cart(Input::get('pid'));
                // print_r('here'); exit;
                if ($item) {
                    if ($cart->remove_from_cart(Input::get('pid'), true)) {
                        $result['success'] = array(
                            'title' => 'Item quantity reduced',
                            'quantity' => $cart->get_cart(Input::get('pid')) ? $cart->get_cart(Input::get('pid'))['quantity'] : 0,
                            'amount' => $cart->get_total_amount(),
                            'count' => $cart->get_count(Input::get('pid')),
                        );
                    } else {
                        $result['error'] = 'Failed to decrease item quantity';
                    }
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;

        case 'clear-cart':
            try {
                if ($cart->remove_from_cart()) {
                    $result['success'] = array(
                        'title' => 'Cart cleared',
                        'count' => $cart->get_count(Input::get('pid')),
                    );
                } else {
                    $result['error'] = 'Failed to clear Cart, try again';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;

        case 'pay-card':
            try {
                if ($user->isLoggedIn() && !$cart->isEmpty()) {

                    $checkout = Session::exists('checkout') ? Session::get('checkout') : null;
                    $address =  $checkout && $checkout['billing'] ? json_decode($checkout['billing']) : null;
                    $delivery_price =  $checkout && isset($checkout['delivery_price']) ? $checkout['delivery_price'] : null;

                    $result['success'] = array(
                        'email' => $user->data()->email,
                        'phone' => $user->data()->phone,
                        'ref' => Token::generate(),
                        'amount' => $cart->get_total_amount(false, $delivery_price),
                        'type' => 'pay',
                        'req' => 'pay'
                    );
                } else {
                    $result['error'] = 'Something went wrong, please try again later.';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;

        case 'pay-transfer':
            try {
                if ($user->isLoggedIn() && !$cart->isEmpty()) {
                    $result['success'] = array(
                        'email' => $user->data()->email,
                        'phone' => $user->data()->phone,
                        'ref' => Token::generate(),
                        'amount' => $cart->get_total_amount(false),
                        'type' => 'pay',
                        'req' => 'pay'
                    );
                } else {
                    $result['error'] = 'Something went wrong, please try again later.';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;

        case 'pay-ondelivery':
            try {
                if ($user->isLoggedIn() && !$cart->isEmpty()) {
                    if (1) {
                        $user = new User();
                        $cart = new Cart();
                        $orders = new General('orders');
                        $order_details = new General('order_details');
                        $menus = new General('menus');
                        $vendors = new General('vendors');
                        $categories = new General('categories');
                        $notifications = new General('notifications');
                        $notification_snippets = new General('notification_snippets');
                        $sms_snippets = new General('sms_snippets');

                        $checkout = Session::exists('checkout') ? Session::get('checkout') : null;
                        $address =  $checkout && $checkout['billing'] ? json_decode($checkout['billing'])->address : null;
                        $delivery =  $checkout && isset($checkout['delivery']) ? $checkout['delivery'] : null;
                        $delivery_price =  $checkout && isset($checkout['delivery_price']) ? $checkout['delivery_price'] : null;
                        $reffered_by =  $checkout && isset($checkout['reffered_by']) ? $checkout['reffered_by'] : null;

                        $delivery_price = null;
                        if (Session::exists('delivery_price')) {
                            $delivery_price = Session::get('delivery_price');
                            Session::delete('delivery_price');
                        }

                        $cart_items = $cart->get_cart();
                        $cart_amount = $cart->get_cart_amount();

                        $total_amount = $cart->get_total_amount(false, $delivery_price);
                        $order_id = '#' . Helpers::getUnique(6, 'd');
                        $invoice = Helpers::getUnique(6, 'd');

                        // Create Order
                        $orders->create(array(
                            'user_id' => $user->data()->id,
                            'order_id' => $order_id,
                            'invoice' => $invoice,
                            'cart' => json_encode($cart_items),
                            'coupon' => Session::exists('coupon') ? Session::get('coupon')->percentage : null,
                            'delivery_price' => $delivery_price,
                            'delivery_address' => $delivery,
                            'menu_amount' => $cart_amount['price'],
                            'variations_amount' => $cart_amount['variation'],
                            'addons_amount' => $cart_amount['addon'],
                            'total_amount' => $cart->get_total_amount(false),
                            'total_menus' => count($cart_items),
                            'acknowledge_delivery' => 0,
                            'acknowledge_cancel' => 0,
                            'payment_method' => 'Pay On Delivery',
                            'reffered_by' => $reffered_by,
                            'vendors' => json_encode($cart->getVendors()),
                            'status' => 'pending',
                        ));

                        // Implement percentage/commission on product amount and send message
                        $item_tables = "";
                        $vendor_arr = array();
                        foreach ($cart_items as $item) {
                            $menu = $menus->get($item['id']);
                            $vendor = $vendors->get($menu->user_id, 'user_id');
                            $category = $categories->get($menu->category);

                            $total_amount = $cart->get_amount($menu->id, null, false);
                            $vendor_amount = $category && $category->percentage ? $total_amount - ($total_amount * ($category->percentage / 100)) : $total_amount;
                            $platform_amount = $category && $category->percentage ? ($total_amount * ($category->percentage / 100)) : 0;

                            $cart->update_cart($item['id'], $total_amount, 'total_amount');
                            $cart->update_cart($item['id'], $vendor_amount, 'vendor_amount');
                            $cart->update_cart($item['id'], $platform_amount, 'platform_amount');

                            $amount = Helpers::format_currency($item['amount']);
                            $name = $item['name'];
                            $qty = $item['quantity'];
                            $item_tables .= "<tr>
                                <td>{$name}</td>
                                <td>{$amount}</td>
                                <td>{$qty}</td>
                            </tr>";

                            if (!in_array($vendor->id, $vendor_arr)) {
                                array_push($vendor_arr, $vendor->id);

                                // Send Notification
                                $notifications->create(array(
                                    'user_id' => $vendor->id,
                                    'subject' => "Order Placed",
                                    'message' => str_replace(['[invoice]'], [$order_id], $notification_snippets->get('S_ORDER_PLACED', 'title')->message),
                                ));

                                // Send OTP Message
                                $message = str_replace(['otp'], ['none'], $sms_snippets->get("S_ORDER_NEW", 'title')->message);
                                Messages::sendSMS($vendor->phone, $message);

                                // Send Order Message
                                $message = "<p>You have a new order made to your store, please login your account and take neccessary action(s)</p>";
                                $subject = "New Order Notification";
                                Messages::send($message, $subject, $vendor->email, $vendor->name, true);
                            }

                            // Order Details
                            $menu_cart_amount = $cart->get_cart_amount($menu->id, $vendor->id);
                            $total_amount = $menu_cart_amount['total'];
                            $vendor_amount = $category && $category->percentage ? $total_amount - ($total_amount * ($category->percentage / 100)) : $total_amount;
                            $platform_amount = $category && $category->percentage ? ($total_amount * ($category->percentage / 100)) : 0;

                            $order_details->create(array(
                                'user_id' => $user->data()->id,
                                'order_id' => $order_id,
                                'vendor_id' => $vendor->id,
                                'menu' => $menu->id,
                                'variations' => json_encode($cart->getVariations($menu->id)),
                                'addons' => json_encode($cart->getAddons($menu->id)),
                                'quantity' => $item['quantity'],
                                'amount' => $item['amount'],
                                'total_amount' => $total_amount,
                                'vendor_amount' => $vendor_amount,
                                'platform_amount' => $platform_amount,
                                'status' => 'pending',
                            ));
                        }


                        if (!$orders->get($user->data()->id, 'user_id')) {
                            Messages::firstOrder($user->data()->first_name . ' ' . $user->data()->last_name, $user->data()->email);
                        }

                        $message = "<p>You have a new order made, please login your account and take neccessary action(s)</p>";
                        $subject = "New Order Notification";
                        Messages::send($message, $subject, Messages::MAIN_EMAIL, "Admin", true);

                        Messages::orderConfirmation($user->data()->first_name . ' ' . $user->data()->last_name, $user->data()->email, $order_id, date('Y-m-d', time()), $cart->get_total_amount(), $address, $item_tables);

                        $coupon = Session::exists('coupon') ? json_encode(Session::get('coupon')) : null;
                        Session::put('checkout', array_merge(Session::get('checkout'), array('order_id' => $order_id, 'invoice' => $invoice, 'coupon' => $coupon)));
                        $cart->clear();

                        if (empty($errors)) {
                            Session::exists('coupon') ? Session::delete('coupon') : null;
                            Session::exists('delivery_price') ? Session::delete('delivery_price') : null;

                            $notifications->create(array(
                                'user_id' => $user->data()->id,
                                'subject' => "Order Placed",
                                'message' => str_replace(['[invoice]'], [$order_id], $notification_snippets->get('B_ORDER_PLACED', 'title')->message),
                            ));

                            $notifications->create(array(
                                'user_id' => 0,
                                'subject' => "Order Placed",
                                'message' => str_replace(['[invoice]'], [$order_id], $notification_snippets->get('A_ORDER_PLACED', 'title')->message),
                            ));

                            Session::flash("success", "Congratulation! Payment made successfully.<br/>");
                            echo json_encode(array(
                                'success' => true,
                                'to' => 'dashboard/order-complete',
                                'message' => "Congratulation! Payment made successfully. Thank you."
                            ));
                            exit;
                        }
                    } else {
                        $result['error'] = 'Something went wrong';
                    }
                } else {
                    $result['error'] = 'Something went wrong, please try again later.';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;
        case 'pay-wallet':
            try {
                if ($user->isLoggedIn() && !$cart->isEmpty()) {

                    $checkout = Session::exists('checkout') ? Session::get('checkout') : null;
                    $address =  $checkout && $checkout['billing'] ? json_decode($checkout['billing']) : null;
                    $delivery_price =  $checkout && isset($checkout['delivery_price']) ? $checkout['delivery_price'] : null;


                    $uwallet = $user->getWallet($user->data()->id);
                    if ($uwallet->balance >= $cart->get_total_amount(false, $delivery_price)) {
                        $user = new User();
                        $cart = new Cart();
                        $orders = new General('orders');
                        $order_details = new General('order_details');
                        $menus = new General('menus');
                        $vendors = new General('vendors');
                        $categories = new General('categories');
                        $notifications = new General('notifications');
                        $notification_snippets = new General('notification_snippets');
                        $sms_snippets = new General('sms_snippets');

                        $checkout = Session::exists('checkout') ? Session::get('checkout') : null;
                        $address =  $checkout && $checkout['billing'] ? json_decode($checkout['billing'])->address : null;
                        $delivery =  $checkout && isset($checkout['delivery']) ? $checkout['delivery'] : null;
                        $delivery_price =  $checkout && isset($checkout['delivery_price']) ? $checkout['delivery_price'] : null;
                        $reffered_by =  $checkout && isset($checkout['reffered_by']) ? $checkout['reffered_by'] : null;

                        $delivery_price = null;
                        if (Session::exists('delivery_price')) {
                            $delivery_price = Session::get('delivery_price');
                            Session::delete('delivery_price');
                        }

                        $cart_items = $cart->get_cart();
                        $cart_amount = $cart->get_cart_amount();

                        $total_amount = $cart->get_total_amount(false, $delivery_price);
                        $order_id = '#' . Helpers::getUnique(6, 'd');
                        $invoice = Helpers::getUnique(6, 'd');

                        // Create Order
                        $orders->create(array(
                            'user_id' => $user->data()->id,
                            'order_id' => $order_id,
                            'invoice' => $invoice,
                            'cart' => json_encode($cart_items),
                            'coupon' => Session::exists('coupon') ? Session::get('coupon')->percentage : null,
                            'delivery_price' => $delivery_price,
                            'delivery_address' => $delivery,
                            'menu_amount' => $cart_amount['price'],
                            'variations_amount' => $cart_amount['variation'],
                            'addons_amount' => $cart_amount['addon'],
                            'total_amount' => $cart->get_total_amount(false),
                            'total_menus' => count($cart_items),
                            'acknowledge_delivery' => 0,
                            'acknowledge_cancel' => 0,
                            'payment_method' => 'Pay On Delivery',
                            'reffered_by' => $reffered_by,
                            'vendors' => json_encode($cart->getVendors()),
                            'status' => 'pending',
                        ));

                        // Implement percentage/commission on product amount and send message
                        $item_tables = "";
                        $vendor_arr = array();
                        foreach ($cart_items as $item) {
                            $menu = $menus->get($item['id']);
                            $vendor = $vendors->get($menu->user_id, 'user_id');
                            $category = $categories->get($menu->category);

                            $total_amount = $cart->get_amount($menu->id, null, false);
                            $vendor_amount = $category && $category->percentage ? $total_amount - ($total_amount * ($category->percentage / 100)) : $total_amount;
                            $platform_amount = $category && $category->percentage ? ($total_amount * ($category->percentage / 100)) : 0;

                            $cart->update_cart($item['id'], $total_amount, 'total_amount');
                            $cart->update_cart($item['id'], $vendor_amount, 'vendor_amount');
                            $cart->update_cart($item['id'], $platform_amount, 'platform_amount');

                            $amount = Helpers::format_currency($item['amount']);
                            $name = $item['name'];
                            $qty = $item['quantity'];
                            $item_tables .= "<tr>
                                <td>{$name}</td>
                                <td>{$amount}</td>
                                <td>{$qty}</td>
                            </tr>";

                            if (!in_array($vendor->id, $vendor_arr)) {
                                array_push($vendor_arr, $vendor->id);

                                // Send Notification
                                $notifications->create(array(
                                    'user_id' => $vendor->id,
                                    'subject' => "Order Placed",
                                    'message' => str_replace(['[invoice]'], [$order_id], $notification_snippets->get('S_ORDER_PLACED', 'title')->message),
                                ));

                                // Send OTP Message
                                $message = str_replace(['otp'], ['none'], $sms_snippets->get("S_ORDER_NEW", 'title')->message);
                                Messages::sendSMS($vendor->phone, $message);

                                // Send Order Message
                                $message = "<p>You have a new order made to your store, please login your account and take neccessary action(s)</p>";
                                $subject = "New Order Notification";
                                Messages::send($message, $subject, $vendor->email, $vendor->name, true);
                            }

                            // Order Details
                            $menu_cart_amount = $cart->get_cart_amount($menu->id, $vendor->id);
                            $total_amount = $menu_cart_amount['total'];
                            $vendor_amount = $category && $category->percentage ? $total_amount - ($total_amount * ($category->percentage / 100)) : $total_amount;
                            $platform_amount = $category && $category->percentage ? ($total_amount * ($category->percentage / 100)) : 0;

                            $order_details->create(array(
                                'user_id' => $user->data()->id,
                                'order_id' => $order_id,
                                'vendor_id' => $vendor->id,
                                'menu' => $menu->id,
                                'variations' => json_encode($cart->getVariations($menu->id)),
                                'addons' => json_encode($cart->getAddons($menu->id)),
                                'quantity' => $item['quantity'],
                                'amount' => $item['amount'],
                                'total_amount' => $total_amount,
                                'vendor_amount' => $vendor_amount,
                                'platform_amount' => $platform_amount,
                                'status' => 'pending',
                            ));
                        }


                        if (!$orders->get($user->data()->id, 'user_id')) {
                            Messages::firstOrder($user->data()->first_name . ' ' . $user->data()->last_name, $user->data()->email);
                        }

                        $message = "<p>You have a new order made, please login your account and take neccessary action(s)</p>";
                        $subject = "New Order Notification";
                        Messages::send($message, $subject, Messages::MAIN_EMAIL, "Admin", true);

                        Messages::orderConfirmation($user->data()->first_name . ' ' . $user->data()->last_name, $user->data()->email, $order_id, date('Y-m-d', time()), $cart->get_total_amount(), $address, $item_tables);

                        $coupon = Session::exists('coupon') ? json_encode(Session::get('coupon')) : null;
                        Session::put('checkout', array_merge(Session::get('checkout'), array('order_id' => $order_id, 'invoice' => $invoice, 'coupon' => $coupon)));
                        $cart->clear();

                        if (empty($errors)) {
                            Session::exists('coupon') ? Session::delete('coupon') : null;
                            Session::exists('delivery_price') ? Session::delete('delivery_price') : null;

                            $notifications->create(array(
                                'user_id' => $user->data()->id,
                                'subject' => "Order Placed",
                                'message' => str_replace(['[invoice]'], [$order_id], $notification_snippets->get('B_ORDER_PLACED', 'title')->message),
                            ));

                            $notifications->create(array(
                                'user_id' => 0,
                                'subject' => "Order Placed",
                                'message' => str_replace(['[invoice]'], [$order_id], $notification_snippets->get('A_ORDER_PLACED', 'title')->message),
                            ));

                            Session::flash("success", "Congratulation! Payment made successfully.<br/>");
                            echo json_encode(array(
                                'success' => true,
                                'to' => 'dashboard/order-complete',
                                'message' => "Congratulation! Payment made successfully. Thank you."
                            ));
                            exit;
                        }
                    } else {
                        $result['error'] = 'Something went wrong, Insufficent fund in wallet.';
                    }
                } else {
                    $result['error'] = 'Something went wrong, please try again later.';
                }
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;

        case 'pay-cart':
            try {
                $orders->create(array(
                    'details' => $cart->get_cart_json(),
                    'amount' => $cart->get_total_amount(false),
                ));

                $cart->remove_from_cart();
                $result['success'] = array(
                    'title' => 'Item have been ordered',
                );
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            break;

        case 'checkout':
            if (Input::get('action')) {
                switch (Input::get('action')) {
                    case 'add-bill':
                        Session::put('checkout', array('billing' => Input::get('data')));
                        $result['success'] = true;
                        break;
                    case 'add-payment':
                        Session::put('checkout', array_merge(Session::get('checkout'), array('payment' => Input::get('data'))));
                        $method = json_decode(Input::get('data')) ? json_decode(Input::get('data'))->payment : null;

                        $checkout = Session::exists('checkout') ? Session::get('checkout') : null;
                        $address =  $checkout && $checkout['billing'] ? json_decode($checkout['billing']) : null;
                        $delivery_price =  $checkout && isset($checkout['delivery_price']) ? $checkout['delivery_price'] : null;

                        $result['success'] = array(
                            'email' => $user->data()->email,
                            'phone' => $user->getProfile()->phone,
                            'ref' => Token::generate(),
                            'amount' => $cart->get_total_amount(false, $delivery_price),
                            'delivery_price' => $delivery_price,
                            'type' => 'pay',
                            'req' => 'pay',
                            'method' => $method,
                        );
                        break;
                }
            } else {
                $result['error'] = 'Something went wrong, please try again later.';
            }
            break;
    }
}

echo json_encode($result);
