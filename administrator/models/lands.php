<?php
class Lands
{
	private $_db, $_data, $_table = 'lands';

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

	public function getLatest($limit = '6')
	{
		if (!$this->_db->query("SELECT * FROM {$this->_table} ORDER BY date_added DESC LIMIT {$limit}")->error()) {
			return $this->_db->results();
		}
		return false;
	}

	public function getAllRequests($status = 'pending')
	{
		if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE taker_id > 0 AND status = ? ", array($status))->error()) {
			return $this->_db->results();
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

	public function getHousesFieldCount($field, $state, $lga, $desc)
	{
		if (!$this->_db->query("SELECT * FROM {$this->_table} WHERE `{$field}` > 0 AND `state` = ? AND `lga` = ? AND  `description` = ? AND `status` <> 'completed' ", array($state, $lga, $desc))->error()) {
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
}
