<?php
require_once('../core/init.php');

$User = new User();
$constants = new Constants();
$Orders = new Orders();
$OrderDetails = new General('order_details');
$Wallets = new General('wallets');
$Vendors = new General('vendors');
$Notifications = new General('notifications');
$NotificationSnippets = new General('notification_snippets');
$SmsSnippets = new General('sms_snippets');
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../orders';
if (
    $User->isLoggedIn() &&
    $User->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq') &&
    Input::get('id')
) {
    switch (trim(Input::get('rq'))) {
        case 'status':
            $found = Input::get('id') ? $Orders->get(Input::get('id')) : null;
            if ($found) {
                $us = $User->get($found->user_id);

                // if (!Input::get('val') || Input::get('val') == 0) {
                //     $Notifications->create(array(
                //         'user_id' => $found->user_id,
                //         'subject' => "Order rejected",
                //         'text' => "We are so sorry your order have been rejected and your money have been refunded to you wallet. If you have any issue please contact/chat support. Thank you, Nescart.",
                //         'status' => 0,
                //         'date_added' => date("Y-m-d H:i:s", time()),
                //     ));
                //     if($found->payment_type < 3){
                //         $uwallet = $User->getUserWallet($found->user_id);
                //         $Wallets->update(array(
                //             'balance' => $uwallet->balance + $found->total_amount
                //         ), $uwallet->id);
                //     }
                // }

                if (Input::get('status') == 'completed') { // Order delivered -- completed
                    $vendor_arr = array();
                    $details = $Orders->getDetails($found->order_id);

                    foreach ($details as $k => $v) {
                        $vendor = $Vendors->get($v->vendor_id);
                        $vendor_user = $User->get($vendor->user_id);

                        $uwallet = $User->getWallet($vendor->user_id);
                        if ($uwallet) {
                            $total_amount = $v->platform_amount;
                            $Wallets->update(array(
                                'total_earning' => $uwallet->total_earning + ($total_amount),
                                'current_earning' => $uwallet->current_earning + ($total_amount),
                                'last_earning_date' => date('Y-m-d H:i:s', time()),
                            ), $uwallet->id);
                        }

                        if (!in_array($vendor_user->id, $vendor_arr)) {
                            array_push($vendor_arr, $vendor_user->id);

                            // Send NOTIFICATION/SMS to seller
                            $Notifications->create(array(
                                'user_id' => $vendor_user->id,
                                'subject' => "Order Delivered",
                                'message' =>  str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('B_ORDER_DELIVERED', 'title')->message),
                            ));
                            $Notifications->create(array(
                                'user_id' => $vendor_user->id,
                                'subject' => "Order Delivered",
                                'message' =>  str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('S_EARNING_UPDATED', 'title')->message),
                            ));

                            $message = str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('S_EARNING_UPDATED', 'title')->message);
                            Messages::sendSMS($vendor_user->phone, $message);
                        }

                        // Order details - 
                        $OrderDetails->update(array(
                            'status' => 'completed',
                            'updated_at' => date('Y-m-d H:i:s', time()),
                        ), $v->id);
                    }

                    $Orders->update(array(
                        'status' => Input::get('status'),
                        'acknowledge_delivery' => 1,
                        'delivery_date' => date('Y-m-d H:i:s', time()),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ), $found->id);

                    // Send NOTIFICATION/SMS to buyer
                    $Notifications->create(array(
                        'user_id' => $us->id,
                        'subject' => "Order Delivered",
                        'message' =>  str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('B_ORDER_DELIVERED', 'title')->message),
                    ));
                    // Send NOTIFICATION/SMS to admin
                    $Notifications->create(array(
                        'user_id' => 0,
                        'subject' => "Order Delivered",
                        'message' =>  str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('A_ORDER_DELIVERED', 'title')->message),
                    ));

                    $message = str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('B_ORDER_DELIVERED', 'title')->message);
                    Messages::sendSMS($us->phone, $message);
                } else {
                    $Orders->update(array(
                        'status' => Input::get('status'),
                    ), $found->id);
                }

                if (Input::get('status') == 'picked') { // picked up and ready for delivery
                    $vendor_arr = array();
                    $details = $Orders->getDetails($found->order_id);

                    foreach ($details as $k => $v) {
                        // print_r($v);
                        $vendor = $Vendors->get($v->vendor_id);
                        $vendor_user = $User->get($vendor->user_id);

                        if (!in_array($vendor_user->id, $vendor_arr)) {
                            array_push($vendor_arr, $vendor_user->id);
                            // Send NOTIFICATION/SMS to seller
                            $Notifications->create(array(
                                'user_id' => $vendor_user->id,
                                'subject' => "Order Picked Up",
                                'message' =>  str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('S_ORDER_AWAITING_DELIVERY', 'title')->message),
                            ));
                        }

                        $OrderDetails->update(array(
                            'status' => 'picked',
                            'updated_at' => date('Y-m-d H:i:s', time()),
                        ), $v->id);
                    }

                    $Notifications->create(array(
                        'user_id' => $found->user_id,
                        'subject' => "Order Picked Up",
                        'message' =>  str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('B_ORDER_AWAITING_DELIVERY', 'title')->message),
                    ));

                    $message = "<p>Order with invoice NO: {$found->invoice} have been ready for delivery</p>";
                    Messages::send($message, "Order {$found->invoice} ready for delivery", $us->email, $us->first_name . ' ' . $us->last_name, true);

                    // send to seller aswell
                }

                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;

        case 'delete':
            $found = Input::get('id') ? $Orders->get(Input::get('id')) : null;
            if ($found) {
                $Orders->remove($found->id);
                Session::flash('success', "Deleted Successfully");
                Redirect::to_js($backto);
            }
            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
    }
}

if (
    $User->isLoggedIn() &&
    $User->isAdmin() &&
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
                        'unique' => 'menu_orders'
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
                        'unique' => 'menu_orders'
                    ),
                    'status' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (trim(Input::get('rq'))) {
                case 'add':
                    try {
                        // create user
                        $Orders->create(array(
                            'title' => Input::get('title'),
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'date_added' => date("Y-m-d H:i:s", time()),
                        ));
                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = Input::get('id') ? $Orders->get(Input::get('id')) : null;
                        if ($found) {
                            $Orders->update(array(
                                'title' => Input::get('title'),
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
