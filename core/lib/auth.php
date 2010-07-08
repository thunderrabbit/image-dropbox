<?php

require_once DB_PATH . '/core/lib/validate.php';

class Auth {
	private $db;
	private $auth_token;

	private function set_auth_cookie()
	{
			setcookie('token', $this->auth_token, time()+DB_AUTH_TIMEOUT,
						'/', DB_URL, false, true);
	}

	public function __construct($db)
	{
		$this->db = $db;
		$this->auth_token = NULL;
	}

	public function check_auth()
	{
		if(isset($_COOKIE['token']) && $_COOKIE['token'] ==
			$_SESSION['auth_token']) {
			$this->auth_token = sha1($_SESSION['auth_salt'] . time());
			$_SESSION['auth_token'] = $this->auth_token;
			$this->set_auth_cookie();
			return true;
		} else {
			$this->logout();
			return false;
		}
	}

	public function login($username, $password)
	{
		$sql = sprintf("SELECT * FROM " . DB_PREFIX . "users WHERE username='%s'",
				$this->db->safe($username));
		$result = $this->db->query($sql);
		$user = $result->fetch_assoc();

		if(sha1($password . $user['salt']) != $user['password'])
			throw new DBException('bad login');

		$this->auth_token = sha1($user['salt'].time());
		$_SESSION['auth_token'] = $this->auth_token;
		$_SESSION['auth_id'] = $user['id'];
		$_SESSION['auth_user'] = $user['username'];
		$_SESSION['auth_salt'] = $user['salt'];
		$_SESSION['auth_email'] = $user['email'];
		$_SESSION['auth_email_hash'] = $user['email_hash'];
		if(isset($user['admin']) && $user['admin'])
			$_SESSION['auth_admin'] = true;

		$this->set_auth_cookie();
	}

	public function logout()
	{
		$this->auth_token = NULL;
		$this->set_auth_cookie();
		unset($_SESSION['auth_id']);
		unset($_SESSION['auth_user']);
		unset($_SESSION['auth_token']);
		unset($_SESSION['auth_salt']);
		unset($_SESSION['auth_email']);
		unset($_SESSION['auth_email_hash']);
		unset($_SESSION['auth_admin']);
	}

	private function valid_username($username)
	{
		return preg_match('/^[a-zA-Z0-9\-_.]+$/', $username);
	}

	public function signup($username, $password1, $password2, $alias, $email)
	{
		if(!is_null($this->auth_token))
			throw new DBException('already logged in');

		$username = $this->db->safe($username);
		$password1 = $this->db->safe($password1);
		$password2 = $this->db->safe($password2);
		$alias = $this->db->safe($alias);
		$email = $this->db->safe($email);

		$sql = sprintf("SELECT username FROM " . DB_PREFIX . "users WHERE username='%s'", 
					$username);

		if($this->db->exists($sql))
			throw new DBException('username already exists');

		if(!$this->valid_username($username))
			throw new DBException('invalid characters in username');

		if($password1 != $password2)
			throw new DBException('passwords do not match');

		if(!validate::email($email))
			throw new DBException('invalid email');

		$salt = sha1($username);
		$password_hash = sha1($password1 . $salt);
		$email_hash = md5($email);
		$sql = sprintf("INSERT INTO " . DB_PREFIX . "users (username,alias,email,email_hash,
						password,salt,joindate) VALUES ('%s','%s','%s','%s',
						'%s','%s',UNIX_TIMESTAMP())", $username, $alias, 
						$email, $email_hash, $password_hash, $salt);

		if(!$this->db->query($sql))
			throw new DBException('failed query when adding user' . $sql);

		$this->login($username,$password1);
	}

	public function authenticated()
	{
		return (!is_null($this->auth_token));
	}

}

?>
