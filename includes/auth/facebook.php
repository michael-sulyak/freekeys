<?php
/*
Name: AuthFacebook
Version: 0.01
Author: expert_m
*/

class AuthFacebook {
	private $link;

	 function __construct() {
	 	$this->link = HOME_URL.'/?do=login&type=facebook';
	 }

	public function run_auth() {
		global $me, $auth, $db;
		set_lang_name('auth', 'include');

		if ($me->config('auth', false) && $_GET['do'] == 'login' && $_GET['type'] == 'facebook') {
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

		$user = $this->get_user($token['access_token']);

		$db->where('facebook_id', $user['id']);
		$query = $db->getOne('users');

		if ($query) {
			$auth->auth_user($query);
		} else {
			$me->add_notification(__('The user with ID not found.'), 'danger');			
		}

	}	

	private function get_token() {
		global $me;
		set_lang_name('auth');

		if ($_GET['do'] != 'login' || $_GET['type'] != 'facebook' || !$_GET['code']) {
			return;
		}

		$params = array(
			'client_id'		=> $me->config('auth_facebook_app_id'),
			'client_secret'	=> $me->config('auth_facebook_app_secret'),
			'code'			=> $_GET['code'],
			'redirect_uri'	=> $this->link
		);

		$data = File::get_contents('https://graph.facebook.com/oauth/access_token?'.http_build_query($params));

		parse_str($data, $token);

		if (!isset($token['access_token'])) {
			$me->add_notification('Unknown error.', 'danger');
			return;
		}

		return $token;
	}

	private function get_user($access_token) {
		global $me;

        $params = array('access_token' => $access_token);

        $user = json_decode(file_get_contents('https://graph.facebook.com/me?'.http_build_query($params)), true);

        if (isset($user['id'])) {
            return $user;
        }
    }

	public function link() {
		global $me;

		$params = array(
			'client_id'		=>  $me->config('auth_facebook_app_id'),
			'scope'			=> 'email',
			'redirect_uri'	=> $this->link,
			'response_type' => 'code'
		);

		return 'https://www.facebook.com/dialog/oauth?'.http_build_query($params);
	}

	private function linking() {
		global $me, $db;
		set_lang_name('auth');

		$token = $this->get_token();
			
		if (!$token) {
			return;
		}

		$userInfo = $this->get_user($token['access_token']);

		$user = $me->config('user');
		$user['facebook_id'] = $userInfo['id'];
		$me->set_config('user', $user);

		$db->where('id', $user['id']);
		$query = $db->update('users', array(
			'facebook_id' => $user['facebook_id']
		));

		if ($query) {
			$me->add_notification(__('Facebook successfully linked.'), 'success');
		} else {
			$me->add_notification(__('Unknown error.'), 'danger');
		}
	}
}

?>