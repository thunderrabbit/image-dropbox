<?php

require_once DB_PATH . '/core/lib/validate.php';

class Auth {
	private $db;
	private $auth_token;

	public function __construct($db)
	{
		$this->db = $db;
		$this->auth_token = NULL;
	}

	public function check_auth()
	{
		if(isset($_COOKIE['token'] && $_COOKIE['token'] ==
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

	public function set_auth_cookie()
	{
			setcookie('token', $this->auth_token, time()+DB_AUTH_TIMEOUT,
						'/', DB_URL, false, true);
	}

	public function login($username, $password)
	{
		$sql = sprintf("SELECT * FROM users WHERE username='%s'",
				$this->db->safe($username));
		$result = $this->db->query($sql);
		$user = $result->fetch_assoc();
		if(sha1($password . $user['salt']) == $user['password']) {
			$this->auth_token = sha1($user['salt'].time());
			$_SESSION['auth_token'] = $auth_token;
		    $_SESSION['auth_id'] = $user['id'];
			$_SESSION['auth_user'] = $user['username'];
			$_SESSION['auth_salt'] = $user['salt'];
			$_SESSION['auth_email'] = $user['email'];
			$_SESSION['auth_email_hash'] = $user['email_hash'];
			$this->set_auth_cookie();
		} else {
			throw new Exception('bad login');
		}

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
	}

	private function valid_alias($alias)
	{
		return preg_match('/^[a-zA-Z0-9\-_.]+$/', $alias);
	}

	public function signup($username, $password1, $password2, $alias, $email)
	{
		if(!is_null($this->auth_token))
			throw new Exception('already logged in');
		$username = $this->db->safe($username);
		$password1 = $this->db->safe($password1);
		$password2 = $this->db->safe($password2);
		$alias = $this->db->safe($alias);
		$email = $this->db->safe($email);

		$sql = sprintf("SELECT username FROM users WHERE username='%s'", 
					$username);

		if(!$this->db->exists($sql)) {
			if($this->valid_alias($alias)) {
				if($password1 == $password2) {
					$salt = sha1($username);
					$password_hash = sha1($password . $salt);
					if(validate::email($email)) {
						$email_hash = md5($email);
						$sql = sprintf("INSERT INTO users (username,alias,email,
							email_hash,password,salt,joindate) VALUES ('%s',
							'%s,'%s','%s','%s','%s',UNIX_TIMESTAMP())",
							$username, $alias, $email, $email_hash,
							$password_hash, $salt);
						if(!$this->db->query($sql))
							throw new Exception('failed query when adding user');
						$this->login($username,$password1);
					} else {
						throw new Exception('invalid email');
					}
				} else {
					throw new Exception('passwords do not match');
				}
			} else {
				throw new Exception('invalid characters in alias');
			}
		} else {
			throw new Exception('username already exists');
		}
	}

	public function authenticated()
	{
		return (!is_null($this->auth_token));
	}

}
