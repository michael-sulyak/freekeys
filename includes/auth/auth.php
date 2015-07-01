<?php
/*
Name: Auth
Version: 0.01
Author: expert_m
*/

class Auth {
	private $time_online;

	function __construct() {
		global $me;
		$this->time_online = $me->config('auth_online', 60*60);
	}

	public function run_auth() {
		global $me;

		$me->set_config('auth', false);
		$me->set_config('level', 0);
		set_lang_name('auth', 'include');

		if ($date = User::isBan()) {
			$me->add_notification(_s('Account or IP banned until %s.', date('H:i:s, d.m.Y', $date)), 'danger');
			$me->set_config('level', -1);
			return;
		}

		if (isset($_COOKIE['user_id'])) {
			$user = $this->checking_id($_COOKIE['user_id'], $_COOKIE['password']);

			if ($user && (time() - $user['last_visit']) < $this->time_online) {
				setcookie('user_id', $_COOKIE['user_id'], (time() + $this->time_online), '/');
				setcookie('password', $_COOKIE['password'], (time() + $this->time_online), '/');
				$me->set_config('auth', true);
				$me->set_config('user', $user);
				$me->set_config('level', $user['user_level']);		

				$this->update_last_visit($user['id']);
			} else {
				setcookie('user_id', '', 0, '/');
				setcookie('password', '', 0, '/');
			}

			return;
		}

		if (isset($_SESSION['user_id'])) {
			$user = $this->checking_id($_SESSION['user_id'], $_SESSION['password']);
			if ($user && time() - $user['last_visit'] < $this->time_online) {
				$me->set_config('auth', true);		
				$me->set_config('user', $user);
				$me->set_config('level', $user['user_level']);	
				
				$this->update_last_visit($user['id']);
			} else {
				$_SESSION['user_id'] = NULL;
				$_SESSION['password'] = NULL;
			}
		}
	}

	public function auth_user($user) {
		global $me;
		setcookie('user_id', $user['id'], (time() + $this->time_online), '/');
		setcookie('password', $user['password'], (time() + $this->time_online), '/');
		$_COOKIE['user_id'] = $user['id'];
		$_COOKIE['password'] = $user['password'];

		$me->set_config('auth', true);
		$me->set_config('user', $user);
		$me->set_config('level', $user['user_level']);		

		$this->update_last_visit($user['id']);
	}

	public function checking_id($id, $password) {		
		global $db;

		$db->where('id', $id);
		$db->where('password', $password);
		$query = $db->get('users');
		if ($query != NULL) return $query[0];
	}

	public function checking_login($login, $password) {
		global $db;

		$db->where('login', $login);
		$db->where('password', $password);
		$query = $db->get('users');
		if ($query != NULL) return $query[0];
	}

	public function update_last_visit($id) {
		global $db;

		$db->where('id', $id);
		$db->update('users', array('last_visit' => time()));
	}

	public function login($arg) {
		global $me;
		set_lang_name('auth', 'include');
		
		if (strlen($arg['login']) == 0 || strlen($arg['password']) == 0) {
			$me->add_notification(__('Login or password is not entered.'), 'danger');
			return;
		}

		$count = Log::getCount('login_error', array('last_time' => 1200, 'ip' => User::ip()));
		if ($count > $me->config('auth_attempts', 3)) {
			$me->add_notification(_s('Account or IP banned until %s.', date('H:i:s, d.m.Y', time()+1200)), 'danger');
			User::ban(1200);
			return;
		}

		$user = $this->checking_login($arg['login'], md5($arg['password']));
		if (!$user) {
			$me->add_notification(__('Login or password is incorrect.'), 'danger');
			Log::add(_s('Attempting to enter the account "%s"', $arg['login']), 'login_error');
			return;
		}

		if (isset($arg['remember'])) {
			setcookie('user_id', $user['id'], (time() + $this->time_online), '/');
			setcookie('password', $user['password'], (time() + $this->time_online), '/');
		} else {
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['password'] = $user['password'];
		}

		$me->set_config('auth', true);
		$me->set_config('user', $user);
		$me->set_config('level', $user['user_level']);	
		$this->update_last_visit($user['id']);

		$me->add_notification(__('Login be successful.'), 'success');
	}

	public function logout() {
		global $me;
		$_SESSION['user_id'] = NULL;
		$_COOKIE['user_id'] = NULL;
		setcookie('user_id', '', 0, '/');
		setcookie('password', '', 0, '/');
		$me->set_config('user', array());
		$me->set_config('auth', false);
	}

	public function register($arg) {
		global $tpl, $me, $db;
		set_lang_name('auth', 'include');

		if (!captcha($arg['captcha_id'], $arg['captcha'])) {
			$me->add_notification(__('CAPTCHA entered incorrectly.'), 'danger');
			return;
		}

		if (strlen($arg['login']) == 0 || strlen($arg['password']) == 0) {
			$me->add_notification(__('Login or password is not entered.'), 'danger');
			return;
		}

		if (strcmp($arg['password'], $arg['password2']) != 0) {
			$me->add_notification(__('Passwords do not match.'), 'danger');
			return;
		}

		if (!$arg['email'] || !filter_var($arg['email'], FILTER_VALIDATE_EMAIL)) {
			$me->add_notification(__('Email address is considered valid.'), 'danger');
			return;
		}

		$db->where('login', $arg['login']);
		$query = $db->get('users');

		if ($query) {
			$me->add_notification(__('This login is already registered.'), 'danger');
			return;
		}

		$user = array(
			'date' => time(),
			'login' => $arg['login'],
			'password' => md5($arg['password']),
			'email' => $arg['email'],
			'last_visit' => time(),
			'user_level' => 1,
			'logged_ip' => $_SERVER["REMOTE_ADDR"]
		);

		$me->set_config('user', $user);
		$me->set_config('arg', $arg);

		$me->create_action('auth_register');

		$query = $db->insert('users', $me->config('user'));

		if ($query) {	
			$me->add_notification(__('Registration was successful.'), 'success');
		} else {			
			$me->add_notification(__('Unknown error.'), 'danger');
		}
	}

	public function save_profile($arg) {
		global $db, $me;
		set_lang_name('auth', 'include');
		$user = $me->config('user');

		if (!$me->config('auth', false)) {
			$me->add_notification(__('You are not logged in.'), 'danger');
			return;
		}

		if ($arg['password'] && strcmp($arg['password'], $arg['password2']) != 0) {
			$me->add_notification(__('Passwords do not match.'), 'danger');
			return;
		} 

		if (!$arg['email'] || !filter_var($arg['email'], FILTER_VALIDATE_EMAIL)) {
			$me->add_notification(__('Email address is considered valid.'), 'danger');
			return;
		}

		if ($arg['password']) {
			$user['password'] = md5($arg['password']);
		}

		$user['email'] = $arg['email'];
		$user['steam_trade_url'] = $arg['steam_trade_url'];
		$me->set_config('user', $user);

		$db->where('id', $user['id']);
		$query = $db->update('users', array(
			'password' => $user['password'],
			'email' => $user['email'],
			'steam_trade_url' => $user['steam_trade_url']
		));

		if ($query) {	
			$me->add_notification(__('Profile changed successfully.'), 'success');
			setcookie('user_id', $user['id'], (time() + $this->time_online), '/');
			setcookie('password', $user['password'], (time() + $this->time_online), '/');
		} else {			
			$me->add_notification(__('Unknown error.'), 'danger');
		}
	}
}

function auth_init() {
	global $auth, $me;
	$auth = new Auth();
	$auth->run_auth();

	if ($me->config('auth_vk', false)) {
		require_once 'vk.php';
		$auth_vk = new AuthVk();
		$auth_vk->run_auth();

		$me->add_interface('auth', 'link_vk', array($auth_vk, 'link'));
	}

	if ($me->config('auth_facebook', false)) {
		require_once 'facebook.php';
		$auth_facebook = new AuthFacebook();
		$auth_facebook->run_auth();

		$me->add_interface('auth', 'link_facebook', array($auth_facebook, 'link'));
	}

	$me->add_interface('auth', 'login', array($auth, 'login'));
	$me->add_interface('auth', 'logout', array($auth, 'logout'));
	$me->add_interface('auth', 'register', array($auth, 'register'));
	$me->add_interface('auth', 'save_profile', array($auth, 'save_profile'), 1);
	$me->create_action('auth_end_init');
}

$me->add_action('db_end_init', 'auth_init');

?>