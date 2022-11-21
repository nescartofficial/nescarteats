<?php
require_once('../core/init.php');

$errors = array();

if (Helpers::isXHR() && Input::exists() && isset($_POST['ref'])) {
    $result = array();
    /// Store transaction ID in a variable
    $txid = $_POST['transaction_id'];

    $ch = curl_init();

    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$txid}/verify", // Pass transaction ID for validation
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            // 'Authorization: Bearer FLWSECK-efd967da697e5161a288a516224196a1-X', //Get your Secrete key from flutterwave dashboard.
            'Authorization: Bearer FLWSECK_TEST-ee8ed5a1e9b7d5af1c23a9ed78619114-X', //Get your Secrete key from flutterwave dashboard.
        ),
    ));
    $request = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($request) {
        $result = json_decode($request, true);
    }
    // print_r($result);
    // echo json_encode($result); exit;
    if ($result && $result['status'] === 'success') { //is_array($result) && array_key_exists('data', $result) && array_key_exists('status', $result['data']) && ($result['data']['status'] === 'success')) {
        $db = DB::getInstance();
        $data = json_decode(Input::get('data'), true);

        if (Input::get('req')) {
            switch (Input::get('req')) {
                case "fund-wallet":
                    try {
                        $user = new User();
                        $amount = $result['data']['amount'];
                        $wallet = new General('wallets');
                        $uwallet = $user->getWallet($user->data()->id);
                        if (!$uwallet) {
                            $wallet->create(array(
                                'user_id' => $user->data()->id,
                                'balance' => 0,
                            ));
                            $uwallet = $user->getWallet($user->data()->id);
                        }
                        $amount += $uwallet->balance;

                        $wallet->update(array(
                            'balance' => $amount,
                        ), $uwallet->id);

                        // message
                        if (empty($errors)) {
                            Session::flash("success", "Congratulation! Account funded successfully.<br/>");
                            echo json_encode(array(
                                'success' => true,
                                'to' => 'dashboard/wallet',
                                'message' => "Congratulation! Payment made successfully, your transaction reference is: {$data['ref']}. Thank you."
                            ));
                            exit;
                        }
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }

                    break;
                case "pay":
                    try {
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
                                'platform_amount' => $vendor_amount,
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
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                    break;
            }
        }
    } else {
        $errors[] = "Transaction was unsuccessful";
    }
} else {
    $errors[] = "No transaction reference provided";
}

echo json_encode($errors);
exit;
