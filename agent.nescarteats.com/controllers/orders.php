<?php
require_once("../core/init.php");
$user = new User();
$constants = new Constants();
$Orders = new Orders();
$OrderDetails = new General('order_details');
$PayoutRequests = new General('payout_requests');
$Notifications = new General('notifications');
$NotificationSnippets = new General('notification_snippets');
$Wallets = new General('wallets');
$Cart = new Cart();
$backto = Input::get('backto') ? Input::get('backto') : '../checkout';

if (
    Input::exists('get') &&
    $user->isLoggedIn()
) {
    if (Input::get('rq')) {
        switch (Input::get('rq')) {
            case 'order-allstatus': // Seller change status of his orders
                $found = Input::get('id') ? $Orders->get(Input::get('id'), 'invoice') : null;
                $vendor = $user->getVendor();
                if ($vendor && $found) {

                    $details = $Orders->getDetails($found->order_id, " AND vendor_id = {$vendor->id} ");
                    foreach ($details as $k => $detail) {
                        $OrderDetails->update(array(
                            'status' => Input::get('status'),
                        ), $detail->id);
                    }

                    if (Input::get('status') == "accepted") {
                        $message = "<p>Order with Invoice NO: <b>{$found->order_id}</b> have been accepted by seller <b>{$vendor->name}</b> and will be ready for pickup.</p>";
                        Messages::send($message, "Accepted Order", Messages::MAIN_EMAIL, "Admin", true);

                        // send notification to admin
                        $Notifications->create(array(
                            'user_id' => 0,
                            'subject' => "Order Accepted",
                            'message' =>  str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('A_ORDER_ACCEPTED', 'title')->message),
                        ));
                    }

                    if (Input::get('status') == "picked") {
                        $message = "<p>Order with Invoice NO: <b>{$found->order_id}</b> have been picked by the logistics from the seller <b>{$vendor->name}</b>.</p>";
                        Messages::send($message, "Order Ready For Delivery", Messages::MAIN_EMAIL, "Admin", true);

                        // send notification to admin
                        $notification = $NotificationSnippets->get('A_ORDER_PICKEDUP', 'title');
                        if($notification){
                            $Notifications->create(array(
                                'user_id' => 0,
                                'subject' => "Order Picked Up",
                                'message' =>  str_replace(['[invoice]'], [$found->order_id], $notification->message),
                            ));
                        }
                    }

                    Session::flash('success', "Action taken successfully");
                    Redirect::to_js("../dashboard/order-details/" . $found->invoice);
                }
                Session::flash('error', "Something went wrong somewhere!");
                Redirect::to_js($backto);
                break;
            case 'request-payout': // Seller request payout of his order
                $vendor = $user->getVendor();
                $found = $PayoutRequests->getByUser(0, 'status', $vendor->id, 'seller_id');
                if ($vendor && !$found) {
                    // Delivered Delivery
                    $searchTerm = "WHERE id > 0 AND status = 3 AND details LIKE '%seller_:_{$vendor->id}_%' ";
                    $pagination = new Pagination();
                    $order_count = $pagination->countAll('orders', $searchTerm);
                    $paginate = new Pagination(1, $order_count, $order_count);
                    $delivered_order = $Orders->getPages($order_count, $paginate->offset(), $searchTerm);

                    // Payout Order
                    $payout_orders = array_filter($delivered_order, function ($order) {
                        $countdown = strtotime("+5 days", strtotime($order->delivery_date));
                        $today = strtotime("today");
                        return $today >= $countdown;
                    });
                    $payment_amount = 0;
                    $order_ids = [];
                    foreach ($payout_orders as $k => $v) {
                        $details = json_decode($v->details);
                        $detail_list = array_filter($details, function ($order) use ($vendor) {
                            return $order->seller == $vendor->id;
                        });

                        foreach ($detail_list as $dk => $dv) {
                            $payment_amount += $dv->amount;
                        }
                        array_push($order_ids, $v->id);
                    }

                    if ($payout_orders) {
                        $PayoutRequests->create(array(
                            'seller_id' => $vendor->id,
                            'amount' => $payment_amount,
                            'orders' => json_encode($order_ids),
                        ));

                        $message = "<p>A payout request have been made by seller <b>{$vendor->name}</b> and requires your immediate attention.</p>";
                        Messages::send($message, "Payout Request From: {$vendor->name} ", Messages::MAIN_EMAIL, "Admin", true);
                    }


                    Session::flash('success', "Action taken successfully");
                    Redirect::to_js("../dashboard/earnings");
                }

                Session::flash('error', "You already have a pending payout request");
                Redirect::to_js($backto);
                break;
            case 'complete-order': // Buyer approve that order have been delivered
                try {
                    $backto = "../dashboard/order-details/" . $found->invoice;
                    $found = Input::get('id') ? $Orders->get(Input::get('id')) : null;
                    if ($user->isLoggedIn() && $found && $found->user_id == $user->data()->id) {
                        $vendor = $user->getVendor();
                        $Orders->update(array(
                            'acknowledge_delivery' => 1,
                        ), $found->id);


                        // send notification to admin
                        $Notifications->create(array(
                            'user_id' => 0,
                            'subject' => "Order Delivered",
                            'message' =>  str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('A_ORDER_ACKNOWLEDGE', 'title')->message),
                        ));

                        $message = "<p>Order with Invoice NO: <b>{$found->order_id}</b> have been acknowledged.</p>";
                        Messages::send($message, "Order Delivered.", Messages::MAIN_EMAIL, "Admin", true);

                        Session::flash('success', "Action taken successfully, please leave a review.");
                        Redirect::to_js("../dashboard/review-details?order=" . $found->invoice);
                    }
                    Session::flash('error', "Something went wrong somewhere!");
                    Redirect::to_js($backto);
                } catch (Exception $e) {
                    Session::flash('error', $e->getMessage());
                }
                break;
            case 'cancel-order': // Buyer approve that order have been delivered
                try {
                    $found = Input::get('id') ? $Orders->get(Input::get('id')) : null;
                    if($found){
                        $backto = "../dashboard/order-details/" . $found->invoice;
                        if ($user->isLoggedIn() && $found && $found->user_id == $user->data()->id) {
                            $Orders->update(array(
                                'is_cancel' => 1,
                                'status' => 'cancelled',
                            ), $found->id);
                            
                            $detail_list = $OrderDetails->getAll($found->order_id, 'order_id', '=');
                            foreach($detail_list as $detail){
                                $OrderDetails->update(array(
                                    'status' => 'cancelled'    
                                ), $detail->id);
                            }
    
                            if($found->payment_method != "Pay On Delivery"){
                                $uwallet = $user->getWallet();
                                $Wallets->update(array(
                                    'balance' => $uwallet->balance + $found->total_amount,
                                ), $uwallet->id);
                            }
    
                            // send notification to admin
                            $notification = $NotificationSnippets->get('U_ORDER_CANCELLED', 'title');
                            if($notification){
                                $Notifications->create(array(
                                    'user_id' => 0,
                                    'subject' => "Order Cancelled",
                                    'message' =>  str_replace(['[invoice]'], [$found->order_id], $NotificationSnippets->get('U_ORDER_CANCELLED', 'title')->message),
                                ));
                            }
                            
                            
    
                            $message = "<p>Order with Invoice NO: <b>{$found->order_id}</b> have been Cancelled.</p>";
                            Messages::send($message, "Order Delivered.", Messages::MAIN_EMAIL, "Admin", true);
    
                            Session::flash('success', "Order cancelled successfully.");
                            Redirect::to_js("../dashboard/order-details/" . $found->invoice);
                        }
                    }
                    Session::flash('error', "Something went wrong somewhere!");
                    Redirect::to_js($backto);
                } catch (Exception $e) {
                    Session::flash('error', $e->getMessage());
                }
                break;
        }
    }

    Redirect::to($backto);
}

// Checking if input exists
if (
    $user->isLoggedIn() &&
    Input::exists() &&
    Input::get('rq')
) {
    if (Input::get('rq')) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
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
                case 'pay':
                    try {

                        $Orders->create(array(
                            'order_id' => Input::get('ref'),
                            'user_id' => $user->data()->id,
                            'details' => $Cart->get_cart_json(),
                            'payment_type' => 1,
                            'total_amount' => $Cart->get_total_amount(),
                            'created' => date('Y-m-d H:i:s', time()),
                            'status' => 1,
                            'cancel' => 0,
                        ));

                        $Cart->clear();
                        Session::flash('success', 'Order made, successfully, Awaiting Confirmation.');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to($backto);
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
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                    }
                    break;
            }
        } else {
            Session::flash('error', $validation->errors());
            Redirect::to($backto);
        }
    } else {
        Session::flash('error', $constants::INVALID_REQUEST);
        Redirect::to($backto);
    }
} else {
    Session::flash('error', $constants::INVALID_REQUEST);
    Redirect::to($backto);
}
