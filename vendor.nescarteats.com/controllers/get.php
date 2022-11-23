<?php
require_once("../core/init.php");
$user = new User();
$car_makes = new General('car_make');
$car_models = new General('car_model');
$categories = new General('categories');
$products = new Products();
$world = isset($world) ? $world : new World();
$orders = new General('orders');
$result = array();

if (Input::exists('get') && Input::get('req')) {
  switch (trim(Input::get('req'))) {
    case 'search':
      $scat = Input::get('category') && is_numeric(Input::get('category')) ? Input::get('category') : null;
      $sterm = Input::get('query') ? Input::get('query') : null;
      $res = $products->search($sterm, $scat);
      $res = array(array('value' => "The Value", "data" => "the data"));
      if ($res) {
        $result = $res;
      } else {
        $result['error'] = 'Data Not found';
      }
      break;
  }
}

if (Input::exists() && Input::get('req')) {
  switch (trim(Input::get('req'))) {
    case 'add-favourite':
      if ($user->isLoggedIn()) {
        $saved_menus = new General('saved_menus');
        $saved_vendors = new General('saved_vendors');

        $type = Input::get('type');
        $id = Input::get('id');
        if ($type && $id) {
          $toggle = false;
          if ($type == 'menu') { // Add to menu Favourite
            $found = $saved_menus->getByUser($id, 'menu_id', $user->data()->id);
            if ($found) {
              $saved_menus->remove($found->id);
            } else {
              $saved_menus->create(array(
                'user_id' => $user->data()->id,
                'menu_id' => $id,
              ));
              $toggle = true;
            }
          } else if ($type == 'vendor') { // add to vendor favourite
            $found = $saved_vendors->getByUser($id, 'vendor_id', $user->data()->id);
            if ($found) {
              $saved_vendors->remove($found->id);
            } else {
              $saved_vendors->create(array(
                'user_id' => $user->data()->id,
                'vendor_id' => $id,
              ));
              $toggle = true;
            }
          }
          $result['success'] = array('type' => $type, 'id' => $id, 'toggle' => $toggle);
        } else {
          $result['error'] = 'Data not found';
        }
      } else {
        $result['error'] = 'No User Account';
      }
      break;
    case 'get-order':
      $seller = $user->getVendor();

      if ($user->isLoggedIn() && $seller) {
        $found = $orders->getWhere("id > 0 AND status = 1 AND (details LIKE '%seller_:_{$seller->id}_%' AND details LIKE '%status_:_pending_%') ");
        if ($found) {
          $result['success'] = array(
            'id' => $found[0]->id,
            'link' => 'dashboard/order-details/view/' . $found[0]->invoice,
            'message' => "A new order have been made, take action",
            'title' => "New Order Alert",
          );
        } else {
          $result['error'] = 'Something went wrong, please try again later.';
        }
      } else {
        $result['error'] = 'Something went wrong, please try again later.';
      }
      break;
    case 'world':
      $type = Input::get('type');
      $append = Input::get('append') ? 1 : 0;
      if ($type) {
        switch ($type) {
          case 'country':
            $res = $world->getStatesByCountryId(Input::get('value'), true);
            break;
          case 'state':
            $res = $world->getCitiesByStateId(Input::get('value'), true);
            break;
          default:
            $res = null;
        }
        $result['success'] = array('type' => $type, 'data' => $res, 'append' => $append);
      } else {
        $result['error'] = 'Data Not found';
      }
      break;
    case 'category':
      $value = Input::get('value');
      if ($value) {

        $res = $categories->getAll(Input::get('value'), 'parent_id', '=');
        $options = '';
        if ($res) {
          foreach ($res as $k => $v) {
            $options .= "<option value='{$v->id}'>{$v->title}</option>";
          }
        }
        $result['success'] = array('value' => $value, 'data' => $res, 'append' => $options);
      } else {
        $result['error'] = 'Data Not found';
      }
      break;
    case 'subcategory':
      $category = Input::get('value');
      if ($category) {
        $subs = $categories->getAll($category, 'parent_id', '=');
        $options = '';
        foreach ($subs as $k => $v) {
          $options .= "<option value='{$v->id}'>{$v->title}</option>";
        }
        $result['success'] = array('success' => true, 'data' => $options, 'append' => $options);
      } else {
        $result['error'] = 'Data Not found';
      }
      break;
    case 'search':
      $scat = Input::get('category') ? Input::get('category') : null;
      $sterm = Input::get('search') ? Input::get('search') : null;
      $scountry = Input::get('country') ? Input::get('country') : null;
      $state = Input::get('state') ? Input::get('state') : null;

      $res = $products->search($sterm, $scat, $scountry, $state);
      // print_r($scat); die;
      if ($res) {
        $data = array();
        foreach ($res as $k => $v) {
          array_push($data, array("value" => $v->title, "data" => $v->slug));
        }
        $result['success'] = $data;
      } else {
        $result['error'] = "Data not found";
      }
      break;
    case 'fund-wallet':
      if ($user->isLoggedIn()) {
        if (Input::get('amount')) {
          $result['success'] = array(
            'req' => 'fund-wallet',
            'type' => 'pay',
            'to' => 'dashboard/wallet',
            'email' => $user->data()->email,
            'phone' => $user->data()->phone,
            'token' => Token::generate(),
            'amount' => Input::get('amount'),
          );
        } else {
          $result['error'] = 'Something went wrong, please try again later.';
        }
      } else {
        $result['error'] = 'Something went wrong, please try again later.';
      }
      break;
    case 'pay':
      if ($user->isLoggedIn()) {
        $result['success'] = array(
          'email' => $user->data()->email,
          'token' => Token::generate(),
          'amount' => 2000,
          'req' => 'pay',
          'to' => 'dashboard',
          'type' => 'pay',
        );
      } else {
        $result['error'] = 'Something went wrong, please try again later.';
      }
      break;
    case 'show-sub-menu':
      if (Input::get('subs')) {
        $subs = json_decode(Input::get('subs'));
        $title = $categories->get($subs[0]->category_id);
        $text = "";
        foreach ($subs as $k => $v) {
          $text .= "<div class='col-md-3 mb-3'>
          <a href='shop/category/{$v->id}'>{$v->title}</a>
        </div>";
        }

        $output = "<div class='col-md-12 mb-3'>
          <h4>{$title->title}</h4>
          <p class='font-weight-bold'>Sub Category</p>
        </div>
          <div class='col-md-3 mb-3'>
            <a href='shop/category/{$title->id}'>All</a>
          </div>
          {$text}";

        $result['success'] = $output;
      } else {
        $result['error'] = 'Something went wrong, please try again later.';
      }
      break;
  }
}

echo json_encode($result);
