<?php
class World {
	private $_db,
			$_data,
			$_table = array('countries', 'states', 'cities');
	
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	
	public function update($fields = array(), $id, $table){
		if(!$this->_db->update($table, $id, $fields)){
			throw new Exception('There was a problem updating...');
		}
	}
	
	// create new world item
	public function create($fields = array(), $table){
		if(!$this->_db->insert($table, $fields)){
			throw new Exception('There was a problem creating an account.');
		}
	}
	
	public function find($id, $table){
			$data = $this->_db->get($table, array('id', '=', $id));
			if($data->count()){
				$this->_data = $data->first();
				return true;
			}
		
		return false;
	} 
	
	public function getCountryName($id){
		return $this->_db->get($this->_table[0], array('id', '=', $id))->first()->name;
	}

	public function getStateByField($field, $val){
		return $this->_db->get($this->_table[1], array($field, '=', $val))->first();
	}

	public function getCityByField($field, $val){
		return $this->_db->get($this->_table[2], array($field, '=', $val))->first();
	}

	public function getStateName($id){
	    $state = $this->_db->get($this->_table[1], array('id', '=', $id))->first();
	    $state = $state ? $state->name :null;
		return $state;
	}
	
	public function getCityName($id){
		return $this->_db->get($this->_table[2], array('id', '=', $id))->first()->name;
	}
	public function getCountries($opt = false){
		$html = '';
		$list = $this->_db->get($this->_table[0], array('id', '>', 0));
		if($opt && $list->count()){
			foreach($list->results() AS $index => $state){
				$html .= "<option value='{$state->id}'>{$state->name}</option>\n";
			}
			return $html;
		}elseif(!$opt && $list->count()){
			return $list->results();
		}
		// if($list->count()){
		// 	return $list->results();
		// }
		return false;
	}
	public function getStates(){
		$list = $this->_db->get($this->_table[1], array('id', '>', 0));
		if($list->count()){
			return $list->results();
		}
		return false;
	}
	
	public function getStatesByCountryId($country_id, $opt = null){
		$html = '';
		$list = $this->_db->get($this->_table[1], array('country_id', '=', $country_id));
		
		if($opt && $list->count()){
			foreach($list->results() AS $index => $state){
				$html .= "<option value='{$state->id}'>{$state->name}</option>\n";
			}
			return $html;
		}elseif(!$opt && $list->count()){
			return $list->results();
		}
		return false;
	}
	
	public function getCities(){
		$list = $this->_db->get($this->_table[2], array('id', '>', 0));
		if($list->count()){
			return $list->results();
		}
		return false;
	}
	public function getThreeNames($cid, $sid, $ccid = null){
		$country = $this->_db->get($this->_table[0], array('id', '=', $cid))->first()->name;
		$state = $this->_db->get($this->_table[1], array('id', '=', $sid))->first()->name;
		if($ccid){
			$city = $this->_db->get($this->_table[2], array('id', '=', $ccid))->first()->name; 
		}
		
		return array(
			'country' => $country,
			'state' => $state,
			'city' => $ccid? $city: ''
		);
	}
	
	public function getCitiesByStateId($state_id, $opt = null){
		$html = '';
		$list = $this->_db->get($this->_table[2], array('state_id', '=', $state_id));
		
		if($opt && $list->count()){
			foreach($list->results() AS $index => $city){
				$html .= "<option value='{$city->id}'>{$city->name}</option>\n";
			}
			return $html;
		}elseif(!$opt && $list->count()){
			return $list->results();
		}
		return false;
	}
	
	public function getCountryCode($count_id){
		$result = $this->_db->get($this->_table[0], array('id', '=', $count_id));
		if($result->count()){
			return $result->first()->sort_name;
		}
		return '';
	}
	
	public function getAll($table){
		$data = $this->_db->get($table, array('id', '>', 0));
			if($data->count()){
				return $data->results();
			}
		return false;
	}
	
	public function exists(){
		return (!empty($this->_data))? True : False;
	}

	public function data(){
		return $this->_data;
	}
	
}
