<?php
class Categories
{
	private $_db,
		$_data,
		$_table = 'categories';



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

	public function getAllCategory($value = '0', $field = 'id', $status = 1, $sub = false, $check = '>')
	{
		$withsub = $sub ? " AND parent_id IS NOT NULL OR parent_id <> 0 " : " AND parent_id IS NULL OR parent_id = 0  ";
		if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE {$field} {$check} ? {$withsub} AND status = ? ", array($value, $status))->error()) {
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

	public function getAllCount($value, $field = 'id', $comp = "=", $limit = 3, $except_id = 0)
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
	
	public function getPages($per_page, $off_set, $where = null)
	{
		return $this->_db->getPerPage($per_page, $off_set, $this->_table, $where, "ORDER BY id DESC");
	}

	public function data()
	{
		return $this->_data;
	}
}
