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
		$sql = sprintf("SELECT * FROM users WHERE username='%s'",
				$this->db->safe($username));
		$result = $this->db->query($sql);
		$user = $result->fetch_assoc();

		if(sha1($password . $user['salt']) != $user['password'])
			throw new Exception('bad login');

		$this->auth_token = sha1($user['salt'].time());
		$_SESSION['auth_token'] = $this->auth_token;
		$_SESSION['auth_id'] = $user['id'];
		$_SESSION['auth_user'] = $user['username'];
		$_SESSION['auth_salt'] = $user['salt'];
		$_SESSION['auth_email'] = $user['email'];
		$_SESSION['auth_email_hash'] = $user['email_hash'];
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

		if($this->db->exists($sql))
			throw new Exception('username already exists');

		if(!$this->valid_alias($alias))
			throw new Exception('invalid characters in alias');

		if($password1 != $password2)
			throw new Exception('passwords do not match');

		if(!validate::email($email))
			throw new Exception('invalid email');

		$salt = sha1($username);
		$password_hash = sha1($password1 . $salt);
		$email_hash = md5($email);
		$sql = sprintf("INSERT INTO users (username,alias,email,email_hash,
						password,salt,joindate) VALUES ('%s','%s','%s','%s',
						'%s','%s',UNIX_TIMESTAMP())", $username, $alias, 
						$email, $email_hash, $password_hash, $salt);

		if(!$this->db->query($sql))
			throw new Exception('failed query when adding user' . $sql);

		$this->login($username,$password1);
	}

	public function authenticated()
	{
		return (!is_null($this->auth_token));
	}

}

?>
