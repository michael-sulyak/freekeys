<?php

class Log {
	public function add($text, $tag = 'unknown') {
		global $db;

		$data = array(
			'date'	=> time(),
			'tag'	=> $tag,
			'text'	=> $text,
			'ip'	=> $_SERVER["REMOTE_ADDR"]
		);

		$db->insert('logs', $data);
	}

	public function get($key, $where = '') {
		global $db;
		$db->where('tag', $tag);
		if ($where) $db->where($where);
		return $db->get('logs');
	}

	public function getCount($tag, $arg = array()) {
		global $db;

		if ($arg['last_time']) {
			$db->where('date', time()-$arg['last_time'], ">=");
		}

		if ($arg['ip']) {
			$db->where('ip', $arg['ip']);
		}

		$db->where('tag', $tag);

		$results = $db->get('logs');
		return $db->getValue('logs', 'count(*)');
	}
}

?>