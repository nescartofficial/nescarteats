<?php
class Cart
{
    private $_db, $_data, $_table = 'products';

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
                    foreach ($cart as $k => $v) {
                        if ($v['id'] == Input::get('pid')) {
                            $in_cart = true;
                            $pos = $k;
                            break;
                        }
                    }

                    if ($in_cart) {
                        $cart[$pos]['quantity'] += 1;
                    } else {
                        array_push($cart, array('id' => Input::get('pid'), 'name' => $item->title, 'amount' => $item->price, 'quantity' => 1));
                    }
                } else {
                    array_push($cart, array('id' => Input::get('pid'), 'name' => $item->title, 'amount' => $item->price, 'quantity' => 1));
                }

                Session::put('cart', $cart);
                return true;
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

    public function get_total_amount($format = true, $delivery = null)
    {
        $amount = !is_null($delivery) && is_numeric($delivery) ? $delivery : 0;
        if (Session::exists('cart')) {
            $cart = Session::get('cart');
            foreach ($cart as $k => $v) {
                $amount += $v['amount'] * $v['quantity'];
            }
        } else {
            return null;
        }
        return $format ? Helpers::format_currency($amount) : $amount;
    }

    public function get_nav_dropdown()
    {
        $amount = 0;
        if (Session::exists('cart')) {
            $cart = Session::get('cart');
            foreach ($cart as $k => $v) {
                $amount += $v['amount'] * $v['quantity'];
            }
        } else {
            return null;
        }
        return Helpers::format_currency($amount);
    }

    public function isEmpty()
    {
        if (Session::exists('cart') && count(Session::get('cart')) > 0) {
            return false;
        }

        return true;
    }
}
