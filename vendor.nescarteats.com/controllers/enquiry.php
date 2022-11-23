<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$sellers = new General('suppliers');
$products = new General('products');
$enquiries = new General('enquiries');

if (
    Input::exists('get') &&
    $user->isLoggedIn()
) {
    if (Input::get('rq')) {
        switch (Input::get('rq')) {
            case 'status':
                try {
                    $found = $enquiries->get(Input::get('id'));
                    if ($found) {
                        $enquiries->update(array(
                            'status' => Input::get('status'),
                        ), $found->id);
                    }
                    Session::flash('success', 'Status updated succesfully');
                    Redirect::to('../dashboard/enquiries');
                } catch (Exception $e) {
                    Session::flash('error', $e->getMessage());
                }
                break;
        }
    }

    Redirect::to($backto);
}

if (
    Input::exists() &&
    $user->isLoggedIn()
) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'to' => array(
                'required' => true,
                'validNumber' => true
            ),
            'quantity' => array(
                'required' => true,
                'validNumber' => true
            ),
            'requirement' => array(
                'required' => true,
            ),
            'product' => array(
                'required' => true,
            ),
        ));


        if ($validation->passed()) {
            try {
                $sfound = $sellers->get(Input::get('to'));
                $pfound = $products->get(Input::get('product'));

                if ($sfound && $pfound) {
                    if ($sfound->user_id == $user->data()->id) {
                        Session::flash('error', 'Something went wrong!');
                        Redirect::to('../product/' . $pfound->slug);
                    }

                    $enq = $enquiries->getByUser(Input::get('product'), 'product_id', $user->data()->id);
                    if ($enq && $enq->status == 1) {
                        Session::flash('error', 'You have a pending request on this product.');
                        Redirect::to('../product/' . $pfound->slug);
                    }
                    $enquiries->create(array(
                        'request_id' => Helpers::getUnique(5, 'a'),
                        'user_id' => $user->data()->id,
                        'product_id' => Input::get('product'),
                        'supplier_user_id' => $sfound->user_id,
                        'quantity' => Input::get('quantity'),
                        'deadline' => Input::get('deadline'),
                        'created' => date('Y-m-d H:i:s', time()),
                        'status' => 1
                    ));
                    Messages::send(Input::get('message'), "New Enquiry from Chimmerce.com", $sfound->email, $msg_from = "enquiry@chimmerce.com");
                    Session::flash('success', 'Sent successfully, you will be contacted shortly.');

                    Redirect::to('../dashboard/orders');
                }

                Redirect::to('../');
            } catch (Exception $e) {
                Session::flash('error', $e->getMessage());
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

Redirect::to('../');
