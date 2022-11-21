<?php
class Pagination
{
	private $_db;
	public $current_page;
	public $per_page;
	public $total_count;

	public function __construct($page = null, $per_page = null, $total_count = null)
	{
		if ($page && $per_page && $total_count) {
			$this->current_page = (int)$page;
			$this->per_page = (int)$per_page;
			$this->total_count = (int)$total_count;
		}
		$this->_db = DB::getInstance();
	}

	public function offset()
	{
		// Assuming 20 items per page:
		// page 1 has an offset of 0    (1-1) * 20
		// page 2 has an offset of 20   (2-1) * 20
		//   in other words, page 2 starts with item 21
		return ($this->current_page - 1) * $this->per_page;
	}

	public function total_pages()
	{
		return ($this->per_page > 0) ? ceil($this->total_count / $this->per_page) : 0;
	}

	public function countAll($table, $id = null)
	{
		if ($id) {
			$total_record = $this->_db->countAll($table, $id);
			foreach ($total_record as $bj => $pro) {
				foreach ($pro as $val) {
					return $val;
				}
			}
		} else {
			$total_record = $this->_db->countAll($table);
			foreach ($total_record as $bj => $pro) {
				foreach ($pro as $val) {
					return $val;
				}
			}
		}
	}
	public function selectSum($table, $tcolumn, $id = null)
	{
		$val = 0;
		if ($id) {
			$totaling = " WHERE `user_id` = {$id}";
		} else {
			$totaling = null;
		}
		$sql = "SELECT SUM({$tcolumn}) FROM " . $table . $totaling;
		$result = (array)$this->_db->query($sql)->first();
		if (count($result)) {
			foreach ($result as $value) {
				$val = ($val + $value);
			}
		}
		return $val;
	}

	public function previous_page()
	{
		return $this->current_page - 1;
	}

	public function next_page()
	{
		return $this->current_page + 1;
	}

	public function has_previous_page()
	{
		return $this->previous_page() >= 1 ? true : false;
	}

	public function has_next_page()
	{
		return $this->next_page() <= $this->total_pages() ? true : false;
	}
}
