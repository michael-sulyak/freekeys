<?php
/*
Name: Items
Version: 0.01
Author: expert_m
*/

require_once 'categories.php';
require_once 'keys.php';
require_once 'tasks.php';

class Items {
	public function add($data) {
		global $db, $me;
		set_lang_name('items');

		$query = $db->insert('items', array(
			'full_name' => $data['full_name'],			
			'name' => $data['name'],
			'date' => time(),
			'image' => $data['image'],
			'description' => $data['description'],
			'short_description' => $data['short_description'],
			'category_id' => $data['category_id'],
			'cost' => $data['cost'],
			'group_tasks_id' => $data['group_tasks_id']
		));

		if ($query) {			
			$me->add_notification(__('Successfully added.'), 'success');
		} else {
			$me->add_notification(__('Not added.'), 'danger');
		}
	}

	public function save($data) {
		global $db, $me;		
		set_lang_name('items');

		$db->where('id', $data['id']);

		$query = $db->update('items', array(
			'full_name' => $data['full_name'],			
			'name' => $data['name'],
			'image' => $data['image'],
			'description' => $data['description'],
			'short_description' => $data['short_description'],
			'category_id' => $data['category_id'],
			'cost' => $data['cost'],
			'group_tasks_id' => $data['group_tasks_id']
		));

		if ($query) {
			$me->add_notification(__('Successfully saved.'), 'success');
		} else {
			$me->add_notification(__('Error saving.'), 'danger');
		}
	}

	public function delete($data) {
		global $db, $me;		
		set_lang_name('items');

		$db->where('id', $data['id']);
		$query = $db->delete('items');

		if ($query) {			
			$me->add_notification(__('Successfully removed.'), 'success');
		} else {
			$me->add_notification(__('Error removing.'), 'danger');
		}
	}

	public function get($arg) {
		global $db;
		
		if (is_numeric($arg['id'])) {
			$db->where('id', $arg['id']);
		} elseif ($arg['name']) {
			$db->where('name', $arg['name']);
		} else {
			return;
		}

		$query = $db->getOne('items');	
		if (!$query) return;
		if (!$query['name']) $query['name'] = '';
		if (!$query['full_name']) $query['full_name'] = '';
		if (!$query['category_id']) $query['category_id'] = '';
		if (!$query['short_description']) $query['short_description'] = '';
		if (!$query['description']) $query['description'] = '';
		if (!$query['list_jobs_id']) $query['list_jobs_id'] = '';
		if (!$query['group_tasks_id']) $query['group_tasks_id'] = '';

		if ($arg['view']) {
			if (is_numeric($arg['id'])) {
				$db->where('id', $arg['id']);
			} elseif ($arg['name']) {
				$db->where('name', $arg['name']);
			} else {
				return;
			}

			$db->update('items', array(
				'views' => $db->inc()
			));
		}

		return $query;
	}

	public function get_items($arg) { 	
		global $db;

		if (is_array($arg)) {
			$num = $arg['num'];
			$category = $arg['category'];
			$column_name = $arg['column_name'] ? $arg['column_name'] : 'id';
			$order_by = $arg['order_by'] ? $arg['order_by'] : 'desc';
		} else {
			$num = $arg;
			$category = NULL;
			$column_name = 'id';
			$order_by = 'desc';
		}

		if ($category) {
			$db->where('category_id', $category);
		}

		if ((strnatcasecmp($order_by, 'asc') || strnatcasecmp($order_by, 'desc') &&
			($column_name == 'id' || $column_name == 'full_name' ||
			$column_name == 'cost' || $column_name == 'views')))
			$db->orderBy($column_name, $order_by);
		return $db->get('items', $num);
	}

	//
	// REF
	//

	public function ref($id) {
		global $me, $db;		
		set_lang_name('items');

		if (!is_numeric($id)) {
			$me->add_notification(__('Invalid ID.'), 'warning');
			return;
		}

		if ($me->config('auth')) {
			$user = $me->config('user');
			if ($user['id'] == $id) return;
		}

		$db->where('ip', $_SERVER["REMOTE_ADDR"]);
		$db->where('user_id', $id);
		$query = $db->get('items_referrals');
		if ($query) return;

		$query = $db->insert('items_referrals', array(
			'date' => time(),	
			'ip' => $_SERVER["REMOTE_ADDR"],	
			'user_id' => $id
		));

		if (!$query) {
			$me->add_notification(__('Unknown error.'), 'warning');
			return;
		}

		$db->where('date', time()+60*60, '>=');
		$db->where('user_id', $id);
		$count = $db->getValue('items_referrals', 'count(*)');

		if ($count > $me->config('items_ref_limit', 10)) {
			return;
		}

		if ($me->config('items_ref_cost', 0) > 0) {
			$db->where('id', $id);
			$query = $db->update('users', array(
				'points' => $db->inc($me->config('items_ref_cost', 0))
			));

			if (!$query) {
				$me->add_notification(__('Unknown error.'), 'warning');
				return;
			}
		}

		$me->add_notification(__('Your transition counted.'), 'success');
	}

	public function shortener($url, $site = 'clck.ru') {
		switch ($site) {
			case 'clck.ru':
				$html = file_get_contents("https://clck.ru/?url=" . $url);
				preg_match_all('#<input id="input" name="url" .* tabindex="1" value="(.+?)" />#is', $html, $text);
				return $text[1][0];
			
			default:
				return $url;
		}
	}
}

$me->add_interface('items', 'add', array('Items', 'add'), 10);
$me->add_interface('items', 'save', array('Items', 'save'), 10);
$me->add_interface('items', 'delete', array('Items', 'delete'), 10);
$me->add_interface('items', 'get', array('Items', 'get'));
$me->add_interface('items', 'get_items', array('Items', 'get_items'));
$me->add_interface('items', 'ref', array('Items', 'ref'));
$me->add_interface('items', 'shortener', array('Items', 'shortener'));

function items_register() {
	global $me;

	if ($me->config('items_reg_points', 0) == 0)
		return;

	$user = $me->config('user');
	$user['points'] = $me->config('items_reg_points');
	$me->set_config('user', $user);
}

$me->add_action('auth_register', 'items_register');

function items_end_register() {
	global $me, $db;

	if ($me->config('items_ref_reg_cost', 0) == 0) {
		return;
	}

	$db->where('ip', User::IP());
	$db->orderBy('id', 'asc');
	$query = $db->getOne('items_referrals');

	if (!$query) {
		return;
	}

	$db->where('id', $query['user_id']);
	$db->update('users', array('points' => $db->inc($me->config('items_ref_reg_cost'))));
}

$me->add_action('auth_end_register', 'items_end_register');

?>