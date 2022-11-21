<?php
class Products
{
	private $_db, $_data,
		$_table_supplier = 'suppliers',
		$_table_categories = 'categories',
		$_table = 'products';

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
		if ($text->count()) {
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

	public function getAllCount($value, $field = 'id', $comp = ">", $limit = 3, $except_id = 0)
	{
		if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE  {$field} {$comp} ? AND id <> {$except_id} LIMIT {$limit}", array($value))->error()) {
			return $this->_db->results();
		}
		return false;
	}

	public function getAllProducts($value, $field = 'id', $check = '>', $cat = null, $status = 1, $limit = null)
	{ // category has to array or object
		$query = null;
		if ($cat) {
			if (is_array($cat) || is_object($cat) && !empty($cat)) {
				foreach ($cat as $k => $v) {
					$query .= " OR category = {$v->id}";
				}
			}
		}
		//return $query;
		$cat = $cat ? $query : null;
		if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE {$field} {$check} ? $cat AND status = ? {$limit}", array($value, $status))->error()) {
			return $this->_db->results();
		}
		return false;
	}
	public function search($search, $category = 22, $country = 160, $state = 48351, $type = 1,  $status = 1)
	{ // category has to array or object

		if ($country || $state) {
			$where = "products.title LIKE '%{$search}%'";
			$where .= $category ? " AND products.category = {$category}" : "";
			$where .= $country ? " AND suppliers.country = {$country}" : "";
			$where .= $state ? " AND suppliers.state = {$state}" : "";

			// $query = "SELECT * FROM products INNER JOIN suppliers ON products.seller_id = suppliers.id WHERE {$where} ";
			// print_r($query); die;

			if (!$this->_db->query("SELECT * FROM products INNER JOIN suppliers ON products.supplier_id = suppliers.id WHERE {$where} ", array($where))->error()) {
				return $this->_db->results();
			}
		} else {
			$cat = $category ? " AND category = " . $category : '';
			if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE title LIKE '%{$search}%' $cat AND status = ? ", array($status))->error()) {
				return $this->_db->results();
			}
		}

		return false;
	}

	public function getAllSpecialProducts($arr = null, $value = 0, $field = "id", $check = "=")
	{ // category has to array or object
		$query = null;
		if ($arr) {
			if (is_array($arr) || is_object($arr) && !empty($arr)) {
				foreach ($arr as $k => $v) {
					$query .= " OR id = {$v}";
				}
			}
		}
		//return $query;
		$cat = $arr ? $query : null;
		// return $cat;
		if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE {$field} {$check} ? $cat ", array($value))->error()) {
			return $this->_db->results();
		}
		return false;
	}

	public function getRelatedProducts($value, $field = 'id', $cat = null, $limit = null)
	{
		$cat = $cat ? " AND category = {$cat}" : null;
		$limit = $limit ? " LIMIT {$limit} " : null;
		if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE {$field} <> ? $cat {$limit}", array($value))->error()) {
			return $this->_db->results();
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

	public function getPages($per_page, $off_set, $where = null)
	{
		return $this->_db->getPerPage($per_page, $off_set, $this->_table, $where, "ORDER BY id DESC");
	}

	public function data()
	{
		return $this->_data;
	}
}
