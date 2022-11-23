<?php
require_once('../core/init.php');

$user = new User();
$constants = new Constants();
$reviews = new General('reviews');
$menus = new General('menus');
$notifications = new General('notifications');
$notification_snippets = new General('notification_snippets');
$orders = new General('orders');

$pfound = $menus->get(Input::get('product'));
$backto = Input::get('backto') ? '../' . Input::get('backto') : '../';

if (
    Input::exists('get') &&
    Input::get('rq') &&
    $user->isLoggedIn()
) {
    switch (trim(Input::get('rq'))) {
        case 'delete':
            $found = Input::get('id') ? $reviews->get(Input::get('id')) : null;
            if ($found) {
                $reviews->remove($found->id);
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
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        switch (trim(Input::get('rq'))) {
            case 'vendor-review':
                $backto = "../dashboard/vendor-reviews/" . Input::get('vendor_slug');
                $validation = $validate->check($_POST, array(
                    'rating' => array(
                        'required' => true,
                    ),
                    'review' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'order-review':
                $backto = "../dashboard/reviews";
                $validation = $validate->check($_POST, array(
                    'order' => array(
                        'required' => true,
                    ),
                ));
                break;
            case 'review':
                $backto = "../dashboard/reviews";
                $validation = $validate->check($_POST, array(
                    'id' => array(
                        'required' => true,
                    ),
                ));
                break;
        }

        if ($validation->passed()) {
            switch (Input::get("rq")) {
                case 'vendor-review':
                    try {
                        $user_id = $user->data()->id;
                        $review = $reviews->get(Input::get('id'));

                        if ($review) {
                            $reviews->update(array(
                                'rating' => Input::get('rating'),
                                'review' => Input::get('review'),
            					'updated_at' => date('Y-m-d H:i:s', time()),
                            ), $review->id);
                            Session::flash('success', 'Review updated successfully,<br/> Thank you.');
                        } else {
                            $reviews->create(array(
                                'user_id' => $user->data()->id,
                                'vendor_id' => Input::get('vendor_id'),
                                'rating' => Input::get('rating'),
                                'review' => Input::get('review'),
                            ));

                            // $notifications->create(array(
                            //     'user_id' => $product->user_id,
                            //     'subject' => "New Product Review.",
                            //     'message' =>  str_replace(['[product]'], [$found->product], $notification_snippets->get('S_NEW_REVIEW', 'title')->message),
                            // ));

                            Session::flash('success', 'Review saved successfully,<br/> Thank you.');
                        }

                        Session::flash('error', 'Something went wrong!');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to($backto);
                    }
                    break;
                case 'order-review':
                    try {
                        $user_id = $user->data()->id;
                        $order = $orders->get(Input::get('order'));
                        if ($order) {
                            $order_details = json_decode($order->details);
                            foreach ($order_details as $k => $v) {
                                $product = $menus->get($v->id);
                                $product_review = $reviews->getByUser($product->id, 'product_id', $user_id);
                                if ($product_review) {
                                    $reviews->update(array(
                                        'review' => Input::get('review-' . $product->id),
                                    ), $product_review->id);
                                } else {
                                    $reviews->create(array(
                                        'user_id' => $user->data()->id,
                                        'product_id' => $product->id,
                                        'review' => Input::get('review-' . $product->id),
                                    ));

                                    $notifications->create(array(
                                        'user_id' => $product->user_id,
                                        'subject' => "New Product Review.",
                                        'message' =>  str_replace(['[product]'], [$found->product], $notification_snippets->get('S_NEW_REVIEW', 'title')->message),
                                    ));
                                }
                            }

                            Session::flash('success', 'Review seved successfully,<br/> Thank you.');
                            Redirect::to($backto);
                        }

                        Session::flash('error', 'Something went wrong!');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to($backto);
                    }
                    break;
                case 'review':
                    try {
                        $user_id = $user->data()->id;
                        $product_review = $reviews->getByUser(Input::get('id'), 'id', $user_id);

                        if ($product_review) {
                            $product = $menus->get($product_review->product_id);
                            if ($product_review) {
                                $reviews->update(array(
                                    'review' => Input::get('review'),
                                ), $product_review->id);
                                Session::flash('success', 'Review updated successfully,<br/> Thank you.');
                            } else {
                                $reviews->create(array(
                                    'user_id' => $user->data()->id,
                                    'product_id' => $product->id,
                                    'review' => Input::get('review'),
                                ));

                                $notifications->create(array(
                                    'user_id' => $product->user_id,
                                    'subject' => "New Product Review.",
                                    'message' =>  str_replace(['[product]'], [$found->product], $notification_snippets->get('S_NEW_REVIEW', 'title')->message),
                                ));

                                Session::flash('success', 'Review saved successfully,<br/> Thank you.');
                            }
                            Redirect::to($backto);
                        }

                        Session::flash('error', 'Something went wrong!');
                        Redirect::to($backto);
                    } catch (Exception $e) {
                        Session::flash('error', $e->getMessage());
                        Redirect::to($backto);
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
