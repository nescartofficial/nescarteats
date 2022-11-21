<?php
require_once("../core/init.php");
$user = new User();
$world = isset($world) ? $world : new World();
$orders = new General('orders');
$menus = new Menus();
$result = array();

if (Input::exists() && Input::get('req')) {
  switch (trim(Input::get('req'))) {
    case 'menus':
        $type = Input::get('type');
        $append = Input::get('append') ? 1 : 0;
        if (Input::get('value') && $type) {
            switch ($type) {
                case 'country':
                    $res = $world->getStatesByCountryId(Input::get('value'), true);
                    break;
                case 'state':
                    $res = $menus->getAllNearMe(Input::get('value'), null, true);
                    // $res = $menus->getAllNearMe(2650, null, true);
                    break;
                default:
                    $res = null;
            }
            $result['success'] = array('type' => $type, 'data' => $res, 'append' => $append);
        } else {
            $result['error'] = 'Data Not found';
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
    case 'get-order':
      if ($user->isLoggedIn() && $user->isAdmin()) {
        $found = $orders->getAllByStatus(1);
        if ($found) {
          $result['success'] = array(
            'id' => $found[0]->id,
            'link' => 'orders/view/' . $found[0]->order_id,
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
  }
}

echo json_encode($result);
