<?php

class User {
	public static function ban($time) {
		UserMeta::add(array('key' => 'user_ban', 'value' => time()+$time));
	}

	public static function isBan() {
		$query = UserMeta::get('user_ban');

		foreach ($query as $value) {
			if ($value['meta_value'] >= time()) {
				return $value['meta_value'];
			} else {
				UserMeta::delete($value['id']);
			}
		}
		
		return false;
	}

	public static function ip() {
		return $_SERVER["REMOTE_ADDR"];
	}


/*
	public function ban($arg1, $arg2 = NULL) {
		global $me, $db;
		if ($me->config('auth', false)) {
			$user = $me->config('user');
			$id = $arg2 ? $arg1 : $user['id'];
			$time = $arg2 ? $arg2 : $arg1;
		} else {
			$id = $arg1;
			$time = $arg2;
		}

		$db->where('id', $id);
		$db->update('users', array('last_visit' => time()));
	}	
	*/
}

?>