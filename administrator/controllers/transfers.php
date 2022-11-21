<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$wallets = new General('wallets');
$sellers = new General('sellers');
$seller_banks = new General('seller_banks');
$payouts = new General('payouts');
$paystack = new Paystack();

// print_r($paystack->listBanks()); die;

$backto = Input::get('backto') ? '../' . Input::get('backto') : '../categories';
if (
    $user->isAdmin() &&
    Input::exists('get') &&
    Input::get('rq')
) {
    switch (trim(Input::get('rq'))) {
        case 'paystack':
            $all_payout = $wallets->getAll(0, 'payout_balance', '>');
            foreach($all_payout as $k => $v){
                $seller = $user->getSeller($v->user_id);
                $bank = $seller_banks->get($v->user_id, 'user_id');
                if($bank->p_recipient_code){
                    $res = $paystack->initiateTransfer($bank->p_recipient_code, $bank->payout_balance, "Weekly Payout");
                    if($res->status){
                        Session::flash('success', "Completed Successfully");
                    }
                }else{
                    $res = $paystack->createTransferRecipient($bank->account_name, $bank->account_number, $bank->p_bank_code);
                    if($res->status){
                        $recipient_code = $res->data->recipient_code;
                        $seller_banks->update(array('p_recipient_code' => $recipient_code ), $bank->id);
                        
                        $res = $paystack->initiateTransfer($bank->p_recipient_code, $bank->payout_balance, "Weekly Payout");
                        if($res->status){
                            Session::flash('success', "Completed Successfully");
                        }
                    }
                }
                
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
            case 'paystack':
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
                case 'paystack':
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