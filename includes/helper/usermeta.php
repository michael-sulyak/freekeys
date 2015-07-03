<?php

class UserMeta {	
	public static function add($arg) {
		global $db, $me;

		$user = $me->config('user');

		$data = array(
			'user_id'	=> $arg['user_id'] ? $arg['user_id'] : ($user['id'] ? $user['id'] : 0),
			'meta_key'	=> $arg['key'],
			'meta_value'=> $arg['value'],
			'user_ip'	=> $arg['user_ip'] ? $arg['user_ip'] : $_SERVER["REMOTE_ADDR"]
		);

		$db->insert('usermeta', $data);
	}

	public static function get($key, $id = '', $ip = '') {
		global $db, $me;

		$db->where('meta_key', $key);

		if (!$id && !$ip) {
			if ($me->config('auth', false)) {
				$user = $me->config('user');
				$db->where('user_id', $user['id']);
			} else {
				$db->where('user_ip', $_SERVER["REMOTE_ADDR"]);				
			}
		} else {
			if ($id) $db->where('user_id', $id);
			if ($ip) $db->where('user_ip', $ip);
		}

		return $db->get('usermeta');
	}

	public static function delete($id) {
		global $db;		
		$db->where('id', $id);
		$db->delete('usermeta');
	}
}

?>