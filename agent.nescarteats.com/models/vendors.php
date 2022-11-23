<?php
class Vendors
{
    private $_db, $_data, $_table = 'vendors';

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

    public function update($fields = array(), $id, $key = 'id')
    {
        if (!$this->_db->update($this->_table, $id, $fields, $key)) {
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

    public function join($columns = "*", $on = "enquiries.product_id = products.id", $where = "", $table1 = "enquiries", $table2 = "products", $type = 1)
    {
        $result = $this->_db->join($columns, $on, $where, $table1, $table2, $type);
        if ($result) {
            return $this->_db->results();
        }
        return false;
    }

    // Get Popular Vendor Near You
    public function getVendors($vendor_id = null, $state = null, $city = null, $all = true) // by state
    {
        $state = $state ? " OR state = {$state}" : null;
        $city = $city ? " OR city = {$city}" : null;
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE `status` = 1 AND `is_verified` = 1 {$state} {$city}")->error()) {
            return $all ? $this->_db->results() : $this->_db->first();
        }
        return false;
    }

    // Get All Vendor Near Me
    public function getAllNearMe($state, $city = null) // by state
    {
        $city = $city ? " OR city = {$city}" : null;
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE status = 1 AND is_verified = 1 AND `state` = ? {$city}", array($state))->error()) {
            return $this->_db->results();
        }
        return false;
    }

    // Get All Vendor Near Me
    public function getPopulars($name = null, $state = null, $city = null) // by state
    {
        $name = $name ? " AND name LIKE '%{$name}%'" : null;
        $state = $state ? " OR state = {$state}" : null;
        $city = $city ? " OR city = {$city}" : null;

        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE status = 1 AND is_verified = 1 {$name} {$state} {$city}")->error()) {
            return $this->_db->results();
        }
        return false;
    }
    
    // Get TOP vendors
    public function getTops($state, $city = null, $limit = null) // by state
    {
        // if (!$this->_db->query("SELECT id, menu, COUNT(*) AS total FROM order_details GROUP BY menu {$where} ORDER BY total DESC")->error()) {
        //     return $this->_db->results();
        // }
        $city = $city ? " AND vendors.city = {$city}" : null;
        $limit = $limit ? " LIMIT {$limit}" : null;
        if (!$this->_db->query(
            "SELECT vendors.*, COUNT(*) AS total
            FROM vendors
            INNER JOIN order_details AS d ON d.vendor_id = vendors.id
            WHERE vendors.state = {$state} 
            {$city}
            GROUP BY vendors.id
            ORDER BY total DESC
            {$limit}", array($state))->error()) {
                
            return $this->_db->results();
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

    public function getAllSum($sfield, $value = 0, $field = 'id', $check = '>')
    {
        if (!$this->_db->query("SELECT SUM($sfield) AS sum FROM {$this->_table} WHERE {$field} {$check} ? ", array($value))->error()) {
            return $this->_db->results()[0]->sum;
        }
        return false;
    }

    public function getAllSumWhere($sfield, $where = "id > 0")
    {
        if (!$this->_db->query("SELECT SUM($sfield) AS sum FROM {$this->_table} WHERE {$where} ")->error()) {
            return $this->_db->results()[0]->sum;
        }
        return false;
    }

    public function getAllExcept($value, $field = 'id', $limit = null)
    {
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE {$field} <> ? {$limit}", array($value))->error()) {
            return $this->_db->results();
        }
        return false;
    }

    public function getAllCount($value, $field = 'id', $comp = ">", $limit = 3, $except_id = 0)
    {
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE  {$field} {$comp} ? AND id <> {$except_id} LIMIT {$limit}", array($value))->error()) {
            return $this->_db->results();
        }
        return false;
    }

    public function getLatest($limit = 3)
    {
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE  id > 0 LIMIT ? ORDER by date_added", array($limit))->error()) {
            return $this->_db->results();
        }
        return false;
    }

    public function getDistinct($column = "id", $where = "")
    {
        if (!$this->_db->query("SELECT DISTINCT {$column} FROM {$this->_table} ?", array($where))->error()) {
            return $this->_db->results();
        }
        return false;
    }

    public function getByUser($value, $field = 'id', $user_id, $user_field = 'user_id')
    {
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE {$user_field} = ? AND {$field} = ? ", array($user_id, $value))->error()) {
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
    public function getWhere($where = "id > 0")
    {
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE {$where} ")->error()) {
            return $this->_db->results();
        }
        return false;
    }

    public function getAllByStatus($status = 0, $cancel = null)
    {
        $cancel = !is_null($cancel) ? " AND cancel = {$cancel}" : null;
        if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE status = ? {$cancel} ", array($status))->error()) {
            return $this->_db->results();
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
}
