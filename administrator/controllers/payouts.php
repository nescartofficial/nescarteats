<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$wallets = new General('wallets');
$sellers = new General('sellers');
$banks = new General('banks');
$seller_banks = new General('seller_banks');
$payouts = new General('payouts');
$notifications = new General('notifications');
$notification_snippets = new General('notification_snippets');
$sms_snippets = new General('sms_snippets');
$paystack = new Paystack();

$backto = Input::get('backto') ? '../' . Input::get('backto') : '../payouts';
if (
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq')
) {
    switch (trim(Input::get('rq'))) {
        case 'single-payout':
            $found = Input::get('id') ? $wallets->get(Input::get('id')) : null;
            if ($found) {
                // Get Seller Account
                $seller = $sellers->get($found->user_id, 'user_id');
                $u_seller = $user->get($found->user_id);
                
                // Pay Seller
                $seller_bank = $seller_banks->get($v->user_id, 'user_id');
                $bank = $banks->get($seller_bank->bank);
                if($seller_bank->p_recipient_code){
                    $res = $paystack->initiateTransfer($seller_bank->p_recipient_code, $seller_bank->payout_balance, "Weekly Payout");
                    if($res->status){
                        // Session::flash('success', "Completed Successfully");
                    }
                }else{
                    $res = $paystack->createTransferRecipient($seller_bank->account_name, $seller_bank->account_number, $bank->code);
                    if($res->status){
                        $recipient_code = $res->data->recipient_code;
                        $seller_banks->update(array('p_recipient_code' => $recipient_code ), $seller_bank->id);
                        
                        $res = $paystack->initiateTransfer($seller_bank->p_recipient_code, $seller_bank->payout_balance, "Weekly Payout");
                        if($res->status){
                            // Session::flash('success', "Completed Successfully");
                        }
                    }
                }
                
                // Update wallet
                $uwallet = $user->getWallet($found->user_id);
                $total_earning = $uwallet->total_earning;
                $current_earning = $uwallet->current_earning;
                $last_earning_date = $uwallet->last_earning_date;
                $payout_balance = $uwallet->payout_balance;
                $total_payout = $uwallet->total_payout;
                
                $wallets->update(array(
                    'current_earning' => $current_earning - $payout_balance,
                    'payout_balance' => 0,
                    'total_payout' => $total_payout + $payout_balance,
                ), $found->id);
                
                // Create Payout
                $payouts->create(array(
                    'seller_id' => $seller->id,
                    'amount' => $payout_balance,
                    'status' => 1,
                ));
                
                // Send NOTIFICATION/SMS to seller
                $notifications->create(array(
                    'user_id' => $u_seller->id,
                    'snippet_id' => $notification_snippets->get('S_PAYOUT_MADE', 'title')->id,
                ));
                
        		$message = str_replace(['amount'], [$payout_balance], $sms_snippets->get("S_ORDER_PAYOUT", 'title')->message);
        		Messages::sendSMS($u_seller->phone, $message);
        		
                Session::flash('success', "Action taken successfully");
                Redirect::to_js($backto);
            }

            Session::flash('error', "Something went wrong somewhere!");
            Redirect::to_js($backto);
            break;
        case 'bulk-payout':
            $all_payout = $wallets->getAll(0, 'payout_balance', '>');
            foreach($all_payout as $k => $v){
                $seller = $user->getSeller($v->user_id);
                $u_seller = $user->get($v->user_id);
                $seller_bank = $seller_banks->get($v->user_id, 'user_id');
                
                if($seller_bank->p_recipient_code){
                    $res = $paystack->initiateTransfer($seller_bank->p_recipient_code, $seller_bank->payout_balance, "Weekly Payout");
                    if($res->status){
                        Session::flash('success', "Completed Successfully");
                    }
                }else{
                    $res = $paystack->createTransferRecipient($seller_bank->account_name, $seller_bank->account_number, $bank->code);
                    if($res->status){
                        $recipient_code = $res->data->recipient_code;
                        $seller_banks->update(array('p_recipient_code' => $recipient_code ), $seller_bank->id);
                        
                        $res = $paystack->initiateTransfer($seller_bank->p_recipient_code, $seller_bank->payout_balance, "Weekly Payout");
                        if($res->status){
                            Session::flash('success', "Completed Successfully");
                        }
                    }
                }
                
                // Update wallet
                $uwallet = $user->getWallet($v->user_id);
                $total_earning = $uwallet->total_earning;
                $current_earning = $uwallet->current_earning;
                $last_earning_date = $uwallet->last_earning_date;
                $payout_balance = $uwallet->payout_balance;
                $total_payout = $uwallet->total_payout;
                
                $wallets->update(array(
                    'current_earning' => $current_earning - $payout_balance,
                    'payout_balance' => 0,
                    'total_payout' => $total_payout + $payout_balance,
                ), $uwallet->id);
                
                // Create Payout
                $payouts->create(array(
                    'seller_id' => $seller->id,
                    'amount' => $payout_balance,
                    'status' => 1,
                ));
                
                // Send NOTIFICATION/SMS to seller
                $notifications->create(array(
                    'user_id' => $u_seller->id,
                    'snippet_id' => $notification_snippets->get('S_PAYOUT_MADE', 'title')->id,
                ));
                
        		$message = str_replace(['amount'], [$payout_balance], $sms_snippets->get("S_ORDER_PAYOUT", 'title')->message);
        		Messages::sendSMS($u_seller->phone, $message);
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
            switch (trim(Input::get('rq'))) {
                case 'add':
                    try {
                        // create user
                        $payouts->create(array(
                            'title' => Input::get('title'),
                            'deal' => Input::get('deal'),
                            'image' => $image ? $image : 'default.jpg',
                            'slug' => Input::get('slug'),
                            'parent_id' => Input::get('category') ? Input::get('category') : null,
                            'status' => Input::get('status') == 'public' ? 1 : 0,
                            'classified' => Input::get('classified') == 'public' ? 1 : 0,
                            'date_added' => date("Y-m-d H:i:s", time()),
                        ));
                        Session::flash('success', 'Added Successfully');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        if (isset($image) && $image) {
                            $path = "../../media/images/category/" . $found->cover;
                            Helpers::deleteFile($path);
                        }
                        Session::flash('error', $e->getMessage());
                    }
                    break;
                case 'edit':
                    try {
                        $found = Input::get('id') ? $payouts->get(Input::get('id')) : null;
                        if ($found) {
                            if (isset($image) && $image != $found->cover && $found->cover != 'default.jpg') {
                                $path = "../../media/images/category/" . $found->cover;
                                Helpers::deleteFile($path);
                            }

                            $payouts->update(array(
                                'title' => Input::get('title'),
                                'deal' => Input::get('deal'),
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
                            $path = "../../media/images/category/" . $found->cover;
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
