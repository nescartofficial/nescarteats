<?php
class User
{
	private $_db,
		$_data,
		$_sessionName,
		$_cookieName,
		$_isLoggedIn,
		$_table = 'users';


	public function __construct($user = '')
	{
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if (!$user) {
			if (Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);
				if ($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					//logout process........
				}
			}
		} else {
			$this->find($user);
		}
	}

	public function update($fields = array(), $id = null)
	{
		if (!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}
		if (!$this->_db->update('users', $id, $fields)) {
			throw new Exception('There was a problem updating...');
		}
	}

	public function create($fields = array())
	{
		if (!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}

	public function find($user = null)
	{
		if ($user) {

			$field = (is_numeric($user)) ? 'id' : 'username';
			$field = Helpers::isEmail($user) ? 'email' : $field;

			$data = $this->_db->get('users', array($field, '=', $user));
			if ($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function remove($id, $field = 'id')
	{
		$result = $this->_db->delete($this->_table, array($field, '=', $id));
		if ($result) {
			return true;
		}
		return false;
	}

	public function login($username = null, $password = null, $remember = true)
	{
		if (!$username && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
		} else {
			$user = $this->find($username);
			if ($user) {
				if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
					Session::put($this->_sessionName, $this->data()->id);
					if ($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));
						if (!$hashCheck->count()) {
							$this->_db->insert('users_session', array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
						} else {
							$hash = $hashCheck->first()->hash;
						}
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}
					return true;
				}
			}
		}
		return false;
	}

	public function hasPermission($key)
	{
		$group = $this->_db->get('groups', array('id', '=', $this->data()->group));
		if ($group->count()) {
			$permissions = json_decode($group->first()->permissions, True);
			if ($permissions[$key] == True) {
				return True;
			}
		}
		return False;
	}


	public function loginUser()
	{
		if (Input::exists()) {
			if (Token::check(Input::get('token'))) {

				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'username' => array('required' => true),
					'password' => array('required' => true)
				));

				if ($validation->passed()) {
					$remember = (Input::get('remember') === 'on') ? True : false;

					$login = $this->login(strtolower(Input::get('username')), Input::get('password'), $remember);
					if ($login) {
						return true;
						//echo "<script>window.location.reload(true);</script>";
					} else {
						Session::flash('error', 'Sorry, Logging in  failed');
					}
				} else {
					Session::flash('error', $validation->errors());
				}
			}
		}
		return false;
	}

	public function loginAdmin()
	{
		if (Input::exists()) {
			if (Token::check(Input::get('token'))) {

				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'username' => array('required' => true),
					'password' => array('required' => true)
				));

				if ($validation->passed()) {
					$remember = (Input::get('remember') === 'on') ? True : false;

					$login = $this->login(Input::get('username'), Input::get('password'), $remember);
					if ($login) {
						return true;
					} else {
						Session::flash('error', 'Sorry, Logging in  failed');
					}
				} else {
					Session::flash('error', $validation->errors());
				}
			}
		}
	}

	public function exists()
	{
		return (!empty($this->_data)) ? True : False;
	}

	public function logOut()
	{
		$this->_db->delete('users_session', array('user_id', '=', $this->data()->id));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	public function data()
	{
		return $this->_data;
	}

	public function isLoggedIn()
	{
		return $this->_isLoggedIn;
	}

	public function isAdmin()
	{
		return $this->_isLoggedIn && $this->data()->group > 1 ? true : false;
	}

	public function isVendor()
	{
		return $this->_isLoggedIn && $this->data()->vendor ? true : false;
	}

	public function getProfile($id = null)
	{
		$id = $this->_isLoggedIn && !$id ? $this->data()->id : $id;
		$result = $this->_db->get('profiles', array('user_id', '=', $id));
		if ($result && $result->count()) {
			return $result->first();
		}
		return false;
	}

	public function getWallet($id = null)
	{
		$id = $this->_isLoggedIn && !$id ? $this->data()->id : $id;
		$result = $this->_db->get('wallets', array('user_id', '=', $id));
		if ($result && $result->count()) {
			return $result->first();
		}
		return false;
	}

	public function getVendor($id = null)
	{
		$id = $this->_isLoggedIn && !$id ? $this->data()->id : $id;
		$result = $this->_db->get('vendors', array('user_id', '=', $id));
		if ($result && $result->count()) {
			return $result->first();
		}
		return false;
	}
	
	public function getSeller($id = null)
	{
		$id = $this->_isLoggedIn && !$id ? $this->data()->id : $id;
		$result = $this->_db->get('vendors', array('user_id', '=', $id));
		if ($result && $result->count()) {
			return $result->first();
		}
		return false;
	}

	public function getVerification($id = null)
	{
		$id = $this->_isLoggedIn && !$id ? $this->data()->id : $id;
		$result = $this->_db->get('verifications', array('user_id', '=', $id));
		if ($result && $result->count()) {
			return $result->first();
		}
		return false;
	}

	public function getBank($id = null)
	{
		$id = $this->_isLoggedIn && !$id ? $this->data()->id : $id;
		$result = $this->_db->get('seller_banks', array('user_id', '=', $id));
		if ($result && $result->count()) {
			return $result->first();
		}
		return false;
	}

	public function getSupplier($id = null)
	{
		$id = $this->_isLoggedIn && !$id ? $this->data()->id : $id;
		$result = $this->_db->get('suppliers', array('user_id', '=', $id));
		if ($result && $result->count()) {
			return $result->first();
		}
		return false;
	}

	public function getType($is_type = null)
	{
		if ($this->_isLoggedIn) {
			$res = $is_type ? ($is_type == $this->data()->type ? true : false) : $this->data()->type;
			return $res;
		}
		return false;
	}

	public function isCompleteProfile()
	{

		if (!$this->_isLoggedIn) {
			return false;
		}
		$id = $this->data()->id;
		$tbl = 'profiles';

		$result = $this->_db->get('' . $tbl, array('user_id', '=', $id));
		if ($result && $result->count()) {
			return true;
		}

		return false;
	}

	public function isCompleteStoreProfile()
	{

		if (!$this->_isLoggedIn) {
			return false;
		}

		$id = $this->data()->id;

		$tbl = $this->data()->vendor ? 'vendors' : 'profiles';

		$result = $this->_db->get('' . $tbl, array('user_id', '=', $id));
		if ($result && $result->count()) {
			return true;
		}

		return false;
	}

	public function isCompleteVerification()
	{

		if (!$this->_isLoggedIn) {
			return false;
		}

		$id = $this->data()->id;

		$tbl = 'verifications';
		$result = $this->_db->get('' . $tbl, array('user_id', '=', $id));
		if ($result && $result->count()) {
			return true;
		}

		return false;
	}

	public function getUser($id)
	{
		$result = $this->_db->get($this->_table, array('id', '=', $id));
		if ($result->count()) {
			return $result->first();
		}
	}

	public function getUsers()
	{
		$text = $this->_db->get($this->_table, array('signup_as', "", 'admin'));
		if ($text) {
			return $text->results();
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

	public function getCount($val = 0, $field = 'id', $check = '>')
	{
		$where = "WHERE {$field} {$check} '{$val}'";
		$count = $this->_db->countAll($this->_table, $where);
		return $count ? $count[0] : null;
	}

	public function getPages($per_page, $off_set, $where = null)
	{
		return $this->_db->getPerPage($per_page, $off_set, $this->_table, $where, "ORDER BY id DESC");
	}
}
