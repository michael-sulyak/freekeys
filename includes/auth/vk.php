<?php
/*
Name: AuthVk
Version: 0.01
Author: expert_m
*/

class AuthVk {
	private $link;

	 function __construct() {
	 	$this->link = HOME_URL.'/?do=login&type=vk';
	 }

	public function run_auth() {
		global $me, $auth, $db;
		set_lang_name('auth', 'include');

		if ($me->config('auth', false) && $_GET['do'] == 'login' && $_GET['type'] == 'vk') {
			$this->linking();
			return;
		}

		if ($me->config('auth', false)) {
			return;
		}

		$token = $this->get_token();

		if (!$token) {
			return;
		}

		$db->where('vk_id', $token['user_id']);
		$query = $db->getOne('users');

		if ($query) {
			$auth->auth_user($query);
		} else {
			$me->add_notification(__('The user with ID not found.'), 'danger');			
		}
	}	

	private function get_token() {
		global $me;
		set_lang_name('auth', 'include');

		if ($_GET['do'] != 'login' || $_GET['type'] != 'vk' || !$_GET['code']) {
			return;
		}

		$params = array(
			'client_id'		=> $me->config('auth_vk_app_id'),
			'client_secret'	=> $me->config('auth_vk_app_secret'),
			'code'			=> $_GET['code'],
			'redirect_uri'	=> $this->link
		);

		$data = File::get_contents('https://oauth.vk.com/access_token?'.http_build_query($params));
		
		$token = json_decode($data, true);

		if (!isset($token['access_token'])) {
			$me->add_notification($token['error_description'], 'danger');
			return;
		}

		return $token;
	}

	public function link() {
		global $me;

		$params = array(
			'client_id'		=> $me->config('auth_vk_app_id'),
			'scope'			=> 'email',			
			'redirect_uri'	=> $this->link,
			'response_type'	=> 'code'
		);

		return 'http://oauth.vk.com/authorize?'.http_build_query($params);
	}

	private function linking() {
		global $me, $db;
		set_lang_name('auth', 'include');

		$token = $this->get_token();
			
		$user = $me->config('user');
		$user['vk_id'] = $token['user_id'];
		$me->set_config('user', $user);

		$db->where('id', $user['id']);
		$query = $db->update('users', array(
			'vk_id' => $user['vk_id']
		));

		if ($query) {
			$me->add_notification(__('VK successfully linked.'), 'success');
		} else {
			$me->add_notification(__('Unknown error.'), 'danger');
		}
	}
}

?>