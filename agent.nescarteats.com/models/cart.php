<?php
class Cart
{
    private $_db, $_data, $_table = 'menus';

    function __construct()
    {
        $this->_db = DB::getInstance();
    }

    public function find($id)
    {
        $data = $this->_db->get($this->_table, array('id', '=', $id));
        if ($data->count()) {
            $this->_data = $data->first();
            return $this;
        }
        return false;
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert($this->_table, $fields)) {
            throw new Exception('There was a problem creating an account.');
        }
    }

    public function update($fields = array(), $id)
    {
        if (!$this->_db->update($this->_table, $id, $fields)) {
            throw new Exception('There was a problem updating...');
        }
    }

    public function remove($id, $field = 'id')
    {
        $result = $this->_db->delete($this->_table, array($field, '=', $id));
        if ($result) {
            return true;
        }
        return false;
    }

    public function getAll($val = '0', $field = 'id', $check = '>')
    {
        $text = $this->_db->get($this->_table, array($field, $check, $val));
        if ($text) {
            return $text->results();
        }
        return false;
    }

    public function get($val, $field = 'id', $check = '=')
    {
        $text = $this->_db->get($this->_table, array($field, $check, $val));
        if ($text->count()) {
            return $text->first();
        }
        return false;
    }

    public function getAllByUser($value, $field = 'id', $user_id)
    {
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE user_id = ? AND {$field} = ? ", array($user_id, $value))->error()) {
            return $this->_db->results();
        }
        return false;
    }

    public function getByUser($value, $field = 'id', $user_id)
    {
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE user_id = ? AND {$field} = ? ", array($user_id, $value))->error()) {
            return $this->_db->first();
        }
        return false;
    }

    public function getCountByUser($value, $field = 'id', $user_id)
    {
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE user_id = ? AND {$field} = ? ", array($user_id, $value))->error()) {
            return $this->_db->count();
        }
        return false;
    }

    public function getCount($value, $field = 'id', $status = "")
    {
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE {$field} = ? {$status}", array($value))->error()) {
            return $this->_db->count();
        }
        return false;
    }

    public function getPages($per_page, $off_set, $where = null)
    {
        return $this->_db->getPerPage($per_page, $off_set, $this->_table, $where, "ORDER BY id DESC");
    }

    public function data()
    {
        return $this->_data;
    }

    public function add_to_cart($pid = null)
    {
        if ($pid) {
            $item = $this->get($pid);
            $cart = array();

            if ($item) {
                if (Session::exists('cart')) {
                    $cart = Session::get('cart');

                    $in_cart = false;
                    $pos = 0;
                    foreach ($cart as $k => $v) {
                        if($v['vendor'] != $item->vendor_id){
                            return false;    
                        }
                        
                        if ($v['id'] == Input::get('pid')) {
                            $in_cart = true;
                            $pos = $k;
                            break;
                        }
                    }

                    if ($in_cart) {
                        $cart[$pos]['quantity'] += 1;
                        $cart[$pos]['total_amount'] = $this->get_total_amount();
                    } else {
                        array_push($cart, array('id' => $item->id, 'vendor' => $item->vendor_id, 'name' => $item->title, 'amount' => $item->price, 'total_amount' => $item->price, 'quantity' => 1, 'addons' => array(), 'variations' => array(), 'status' => 'pending'));
                    }
                } else {
                    array_push($cart, array('id' => $item->id, 'vendor' => $item->vendor_id, 'name' => $item->title, 'amount' => $item->price, 'total_amount' => $item->price, 'quantity' => 1, 'addons' => array(), 'variations' => array(), 'status' => 'pending'));
                }

                Session::put('cart', $cart);
                return true;
            }
        }
        return false;
    }

    // check if cart has variation
    public function getVendors($menu_id = null)
    {
        $cart = $menu_id ? $this->get_cart($menu_id) : $this->get_cart();
        if (!$menu_id) {
            $vendor_list = array();
            foreach ($cart as $v) {
                if (!in_array($v['vendor'], $vendor_list)) {
                    array_push($vendor_list, $v['vendor']);
                }
            }
            return $vendor_list;
        }

        return $cart ? $cart['vendor'] : null;
    }

    // check if cart has variation
    public function getAddons($menu_id)
    {
        $cart = $this->get_cart($menu_id);
        return $cart && isset($cart['addons']) ? $cart['addons'] : false;
    }

    // check if cart has variation
    public function hasAddon($id, $addon)
    {
        $cart = $this->get_cart($id);
        $addons = $cart ? $cart['addons'] : null;
        if ($addons && count($addons) > 0) {
            foreach ($addons as $k => $v) {
                if ($v['id'] == $addon) { // remove addon
                    return array('id' => $v['id'], 'price' => $v['price'], 'quantity' => $v['quantity'], 'position' => $k);
                }
            }
        }

        return false;
    }

    // Add addon to cart
    public function add_addon($id, $addon, $price = 0, $title = null)
    {
        $cart = $this->get_cart($id);
        if ($cart) {
            $removed = false;
            $addons = $cart['addons'];

            // Check and remove from cart.
            $cart_addon = $this->hasAddon($id, $addon);
            if ($cart_addon) {
                unset($addons[$cart_addon['position']]);
                $removed = true;
            }

            !$removed ? array_push($addons, array('id' => $addon, 'title' => $title, 'price' => $price, 'quantity' => 1)) : $addons;
            $this->update_cart($id, $addons, 'addons'); // update variation
            return true;
        }
        return false;
    }

    // Add addon to cart
    public function addon_quantity($menu_id, $addon, $price = 0, $inc = 'inc')
    {
        $cart = $this->get_cart($menu_id);
        if ($cart && $this->hasAddon($menu_id, $addon)) {
            $addons = $cart['addons'];
            $quantity = 1;
            foreach ($addons as $k => $v) {
                if ($v['id'] == $addon) { // remove addon
                    $quantity = $inc == 'inc' ? $v['quantity'] + 1 : $v['quantity'];
                    $quantity = $inc == 'dec' && $v['quantity'] > 1 ? $v['quantity'] - 1 : $quantity;
                    $addons[$k]['quantity'] = $quantity;
                }
            }
            $this->update_cart($menu_id, $addons, 'addons'); // update variation
            return $quantity;
        }
        return false;
    }

    // check if cart has variation
    public function hasVariation($menu_id, $variation)
    {
        $cart = $this->get_cart($menu_id);
        return $cart && isset($cart['variations']['id']) && $cart['variations']['id'] == $variation ? $cart['variations'] : false;
    }

    // check if cart has variation
    public function getVariations($menu_id)
    {
        $cart = $this->get_cart($menu_id);
        return $cart && isset($cart['variations']) ? $cart['variations'] : false;
    }

    // Add variation to cart
    public function add_variation($id, $variation, $price = 0, $title = null)
    {
        $cart = $this->get_cart($id);
        if ($cart) {
            $this->update_cart($id, array('id' => $variation, 'title' => $title, 'price' => $price), 'variations');
            return true;
        }
        return false;
    }

    public function exists($pid = null, $item = false)
    {

        if (Session::exists('cart')) {
            if ($pid) {
                $cart = Session::get('cart');
                foreach ($cart as $k => $v) {
                    if ($v['id'] == $pid) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
    public function remove_from_cart($pid = null, $item = false)
    {

        if (Session::exists('cart')) {
            if ($pid) {
                $cart = Session::get('cart');
                foreach ($cart as $k => $v) {
                    if ($v['id'] == $pid) {
                        if ($item) {
                            unset($cart[$k]);
                        } else {
                            if ($cart[$k]['quantity'] > 1) {
                                $cart[$k]['quantity'] -= 1;
                                $cart[$k]['total_amount'] = $this->get_total_amount();
                            } else {
                                unset($cart[$k]);
                            }
                        }

                        Session::put('cart', $cart);
                        return true;
                    }
                }
            } else {
                Session::delete('cart');
                return true;
            }
        }

        return false;
    }

    public function first()
    {
        if (Session::exists('cart')) {
            $cart = Session::get('cart');
            return next($cart);
        }
        
        return null;
    }
    
    public function get_cart($pid = null)
    {
        if ($pid) {
            if (Session::exists('cart')) {
                $cart = Session::get('cart');
                foreach ($cart as $k => $v) {
                    if ($v['id'] == $pid) {
                        return $v;
                    }
                }
            }
            return null;
        }

        return Session::exists('cart') ? Session::get('cart') : null;
    }

    public function update_cart($pid = null, $value = 0, $field = 'mp_amount')
    {
        if ($pid) {
            if (Session::exists('cart')) {
                $cart = Session::get('cart');
                foreach ($cart as $k => $v) {
                    if ($v['id'] == $pid) {
                        $cart[$k][$field] = $value;
                    }
                }
                Session::put('cart', $cart);
            } else {
                return null;
            }
        } else {
            return null;
        }

        return Session::exists('cart') ? Session::get('cart') : null;
    }

    public function clear($pid = null)
    {
        return Session::exists('cart') ? Session::delete('cart') : null;
    }

    public function get_cart_json($pid = null)
    {
        if ($pid) {
            if (Session::exists('cart')) {
                $cart = Session::get('cart');

                foreach ($cart as $k => $v) {
                    if ($v['id'] == $pid) {
                        return json_encode($v);
                    }
                }
            }

            return null;
        }

        return Session::exists('cart') ? json_encode(Session::get('cart')) : null;
    }

    public function get_count()
    {
        return Session::exists('cart') ? count(Session::get('cart')) : 0;
    }

    // Get Cart Amount
    public function get_cart_amount($menu_id = null, $vendor_id = null)
    {
        $price = 0;
        $variation_price = 0;
        $addon_price = 0;

        $cart = $this->get_cart();
        if ($cart) {
            foreach ($cart as $k => $menu) {
                if ($vendor_id && $cart[$k]['vendor'] != $vendor_id) {
                    continue;
                }

                if ($menu_id == $cart[$k]['id']) {
                    $addons = $cart[$k]['addons'];
                    if ($addons) {
                        foreach ($addons as $daddon) {
                            $addon_price += $daddon['price'] * $daddon['quantity'];
                        }
                    }

                    $variation_price += $cart[$k]['variations']['price'];

                    $price += $cart[$k]['amount'] * $cart[$k]['quantity'];

                    break;
                } else {
                    $addons = $menu['addons'];
                    if ($addons) {
                        foreach ($addons as $daddon) {
                            $addon_price += $daddon['price'] * $daddon['quantity'];
                        }
                    }

                    $variation_price += $menu['variations'] ? $menu['variations']['price'] : 0;

                    $price += $menu['amount'] * $menu['quantity'];
                }
            }
        }

        $total = $price + $variation_price + $addon_price;
        $all = array('total' => $total, 'price' => $price, 'addon' => $addon_price, 'variation' => $variation_price);
        return $all;
    }

    public function get_amount($menu, $addon = null, $format = true, $coupon_delivery = null, $vendor_id = null)
    {
        $total_amount = 0;
        if (Session::exists('cart')) {
            $cart = Session::get('cart');

            $amount = 0;
            foreach ($cart as $k => $v) {
                if ($v['id'] == $menu) {

                    $variations = $v['variations'] ? $v['variations']['price'] : 0;

                    $addons_amount = 0;
                    $addons = $v['addons'];
                    if ($addons) {
                        foreach ($addons as $av) {
                            if ($addon && $av['id'] == $addon) {
                                $addons_amount += $av['price'] * $av['quantity'];
                                continue;
                            } else {
                                $addons_amount += $av['price'] * $av['quantity'];
                            }
                        }
                    }

                    $amount += ($v['amount'] * $v['quantity']);
                }
            }

            $total_amount += $amount + $variations + $addons_amount;

            if ($coupon_delivery) {
                $total_amount = isset($_SESSION["coupon"]) ? $total_amount - ($total_amount * ($_SESSION["coupon"]->percentage / 100)) : $total_amount;
                $total_amount = isset($_SESSION["delivery_price"]) ? $total_amount + $_SESSION["delivery_price"] : $total_amount;
            }
        } else {
            return null;
        }
        return $format ? Helpers::format_currency($total_amount) : $total_amount;
    }

    public function get_total_amount($format = true, $delivery = null)
    {
        $total_amount = $amount = $addons_amount = $variation_amount = 0;
        if (Session::exists('cart')) {
            $cart = Session::get('cart');
            foreach ($cart as $k => $v) {
                $variation_amount = $v['variations'] ? $v['variations']['price'] : 0;

                $addons_amount = 0;
                $addons = $v['addons'];
                if ($addons) {
                    foreach ($addons as $addon) {
                        $addons_amount += $addon['price'] * $addon['quantity'];
                    }
                }

                $amount += ($v['amount'] * $v['quantity']);
            }
            $total_amount = $amount + $variation_amount + $addons_amount;

            $total_amount = isset($_SESSION["coupon"]) ? $total_amount - ($total_amount * ($_SESSION["coupon"]->percentage / 100)) : $total_amount;
            $total_amount = isset($_SESSION["delivery_price"]) ? $total_amount + $_SESSION["delivery_price"] : $total_amount;
            // $total_amount = $delivery && is_numeric($delivery) ? $total_amount + $delivery : $total_amount;
        } else {
            return null;
        }
        return $format ? Helpers::format_currency($total_amount) : $total_amount;
    }

    public function isEmpty()
    {
        if (Session::exists('cart') && count(Session::get('cart')) > 0) {
            return false;
        }

        return true;
    }
}
