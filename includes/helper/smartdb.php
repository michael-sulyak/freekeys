<?php

class SmartDB {
	public static function processed($name, $table) {
		global $me;

		$access_rights = $me->config('db_read_level');
		$result = array();

		foreach ($access_rights[$name] as $key => $value) {
			if ($me->level_check($value)) {
				$result[$key] = stripcslashes($table[$key]);
			} else {
				$result[$key] = '';
			}
		}

		return $result;
	}

	public static function getOne($name) {
		global $db;
		$query = $db->getOne($name);
		return SmartDB::processed($name, $query);
	}

	public static function get($name, $num = '') {
		global $db;

		$result = array();

		if ($num == '') {
			$query = $db->get($name);
		} else {
			$query = $db->get($name, $num);
		}		

		foreach ($query as $key => $value) {
			$result[$key] = SmartDB::processed($name, $value);
		}

		return $result;
	}
}

?>