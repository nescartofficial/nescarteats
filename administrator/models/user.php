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
	
	public function login($username = null, $password = null, $remember = false)
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
	// add new Admin User
	public function newAdminUser()
	{
		if (Input::exists()) {
			if (Token::check(Input::get('token'))) {
				//echo "I have been ran <br />";
				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'username' => array(
						'required' => true,
						'min' => 2,
						'max' => 20,
						'unique' => 'users'
					),
					'password' => array(
						'required' => true,
						'min' => 6
					),
					'password_again' => array(
						'required' => true,
						'matches' => 'password'
					),
					'first_name' => array(
						'required' => true,
						'min' => 2,
						'max' => 25
					),
					'last_name' => array(
						'required' => true,
						'min' => 2,
						'max' => 50
					),
					'email' => array(
						'required' => true,
						'min' => 2,
						'max' => 50,
						'validemail' => true,
						'unique' => 'users'
					)
				));


				if ($validation->passed()) {
					//$user = new User();
					$salt = Hash::salt(32);
					try {

						$this->create(array(
							'username' => Input::get('username'),
							'password' => Hash::make(Input::get('password'), $salt),
							'email' => Input::get('email'),
							'salt' => $salt,
							'name' => Input::get('first_name') . ' ' . Input::get('last_name'),
							'joined' => date('Y-m-d H:i:s'),
							'group' => Input::get('permission')

						));
						Session::flash('success', 'You have been registered and can now log in!');
						//Redirect::to('index.php');
					} catch (Exception $e) {
						Session::flash('error', $e->getMessage());
					}
					// Session::flash('success', 'You Registered Successfully');
					// header('Location: index.php');
				} else {
					Session::flash('error', $validation->errors());
				}
			}
		}
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

					$login = $this->login(Input::get('username'), Input::get('password'), $remember);
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

	public function getSeller($id = null)
	{
		$id = $this->_isLoggedIn && !$id ? $this->data()->id : $id;
		$result = $this->_db->get('sellers', array('user_id', '=', $id));
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
