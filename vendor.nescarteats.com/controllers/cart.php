<?php
require_once("../core/init.php");
$user = new User();
$constants = new Constants();
$products = new General('products');
$coupons = new General('coupons');
$cart = new Cart();
$backto = Input::get('backto') ? Input::get('backto') : '../';

if (Input::exists('get')) {
  if (Input::get('rq')) {
    switch (Input::get('rq')) {
      case 'clear':
        try {
          if ($cart->remove_from_cart()) {
            Session::flash("success", "Cart Cleared");
          }
        } catch (Exception $e) {
          Session::flash('error', $e->getMessage());
        }
        break;
      case 'inc-item';
        try {
          if (Input::get('pid')) {
            $cart->add_to_cart(Input::get('pid'));
          }
        } catch (Exception $e) {
          Session::flash('error', $e->getMessage());
        }
        break;
      case 'dec-item';
        try {

          if (Input::get('pid')) {
            $item = $cart->get_cart(Input::get('pid'));
            if ($item && $item['quantity'] > 1){
              $cart->remove_from_cart(Input::get('pid'));
            }
          }

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
  Input::exists() &&
  Input::get('rq')
) {
  if (Input::get('rq')) {
    $validate = new Validate();
    switch (trim(Input::get('rq'))) {
      case 'add':
        $validation = $validate->check($_POST, array(
          'pid' => array(
            'required' => true,
          ),
        ));
        break;
      case 'edit':
        $validation = $validate->check($_POST, array(
          'title' => array(
            'required' => true,
          ),
          'location' => array(
            'required' => true,
          ),
          'date' => array(
            'required' => true,
          ),
          'category' => array(
            'required' => true,
          ),
        ));
        break;
      case 'coupon':
        $validation = $validate->check($_POST, array(
          'code' => array(
            'required' => true,
          ),
        ));
        break;
    }

    if ($validation->passed()) {
      switch (Input::get("rq")) {
        case 'add':
          try {
            if ($cart->add_to_cart(Input::get('pid'))) {
              Session::flash('success', 'Item added to cart');
            } else {
              Session::flash('error', 'Failed to add item to cart');
            }
            Redirect::to($backto);
          } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
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
        case 'coupon':
          try {
            $coupon = $coupons->get(Input::get('code'), 'code');
            
            $today = date("Y-m-d H:i:s");
            $expire = $coupon->date_duration.' ' .$coupon->time_duration; //from database
            $today_time = strtotime($today);
            $expire_time = strtotime($expire);
            $is_correct_duration = $expire_time > $today_time;
            
            if($coupon && $coupon->status && $is_correct_duration){
              Session::put('coupon', $coupon);
              Session::flash('success', "Coupon Applied");
            }else{
              Session::flash('success', "Invalid or Expired Coupon Code");   
            }
            Redirect::to($backto);
          } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
          }
          break;
      }
    }
  } else {
    Session::flash('error', $constants::INVALID_REQUEST);
    Redirect::to($backto);
  }
} else {
  Session::flash('error', $constants::INVALID_REQUEST);
  Redirect::to($backto);
}
