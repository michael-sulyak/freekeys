<?php

class ItemsKeys {
	public function add($data) {
		global $db, $me;
		set_lang_name('items');
		
		$query = $db->insert('items_keys', array(
			'key' => $data['key'],
			'date' => time(),
			'help' => $data['help'],
			'instruction' => $data['instruction'],
			'inform' => $data['inform'],
			'item_id' => $data['item_id'],
			'number' => $data['number']
		));

		if ($query) {			
			$me->add_notification(__('Successfully added.'), 'success');
		} else {
			$me->add_notification(__('Not added.'), 'danger');
		}
	}

	public function save($arg) {
		global $db, $me;
		set_lang_name('items');

		$db->where('id', $arg['id']);

		$query = $db->update('items_keys', array(
			'key' => $arg['key'],
			'help' => $arg['help'],
			'instruction' => $arg['instruction'],
			'inform' => $arg['inform'],
			'item_id' => $arg['item_id'],
			'number' => $arg['number']
		));

		if ($query) {
			$me->add_notification(__('Successfully saved.'), 'success');
		} else {
			$me->add_notification(__('Error saving.'), 'danger');
		}
	}

	public function delete($arg) {
		global $db, $me;
		set_lang_name('items');

		$db->where('id', $arg['id']);
		$query = $db->delete('items_keys');

		if ($query) {			
			$me->add_notification(__('Successfully removed.'), 'success');
		} else {
			$me->add_notification(__('Error removing.'), 'danger');
		}
	}

	public function get($arg) { 	
		global $db, $me;

		if (isset($arg['id'])) {
			$db->where('id', $arg['id']);
		} elseif (isset($arg['item_id'])) {
			$db->where('item_id', $arg['item_id']);
		} else {
			return;
		}

		$query = $db->getOne('items_keys');	
		if (!$query) return;
		if (!$query['key']) $query['key'] = '';
		if (!$query['help']) $query['help'] = '';
		if (!$query['instruction']) $query['instruction'] = '';
		if (!$query['inform']) $query['inform'] = '';
		if (!$query['item_id']) $query['item_id'] = '';

		return $query;
	}

	public function get_keys($num = 10) { 	
		global $db;
		return $db->get('items_keys', $num);
	}

	public function get_sum_keys($id) {
		global $db;
		$db->where('item_id', $id);
		$query = $db->getOne('items_keys', 'sum(number), count(*) as sum');
		return $query['sum(number)'] ? $query['sum(number)'] : 0;
	}
	
	public function take_key($item_id) {
		global $db, $me;
		set_lang_name('items');

		// Item
		$user = $me->config('user');
		$item = Items::get($item_id);

		if (!$item) {
			$me->add_notification(__('Item not found.'), 'danger');
			return;
		} elseif (!$me->config('auth', false) || $item['cost'] > $user['points']) {
			$me->add_notification(__('Not enough points.'), 'danger');
			return;
		} elseif ($item['group_tasks_id']) {
			$result = ItemsTasks::verify_tasks($item['group_tasks_id']);
			if (!$result) {				
				$me->add_notification(__('Did not do all tasks.'), 'danger');
				return;
			}
		}

		// Key
		$key = ItemsKeys::get(array('item_id' => $item['id']));

		if (!$key || $key['number'] <= 0) {
			$me->add_notification(__('Key not found.'), 'danger');
			return;
		}

		// Update
		$user['points'] = $user['points'] - $item['cost'];
		$me->set_config('user', $user);

		$db->where('id', $user['id']);
		$query = $db->update('users', array(
			'points' => $user['points']
		));

		if (!$query) {
			$me->add_notification(__('Unknown error.'), 'danger');
			return;
		}

		$db->where('id', $key['id']);
		$query1 = $db->update('items_keys', array(
			'number' => $db->dec()
		));

		$db->where('id', $item['id']);
		$query2 = $db->update('items', array(
			'distributed' => $db->inc()
		));

		if (!$query1 || !$query2) {
			$me->add_notification(__('Unknown error.'), 'danger');
			return;
		}

		if ($key['inform']) {
			Message::add(array(
				'user_id'	=> 0,
				'sender_id'	=> 0,
				'subject'	=> 'Key',
				'text'		=> "User ID: ".$user['id']."\n\n".
							   "Item:\n".print_r($item, true)."\n\n".
							   "Key:\n".print_r($key, true)
			));
		}

		// Result
		$result = array();
		$result['item'] = $item;
		$result['key'] = $key;

		return $result;
	}
}

$me->add_interface('items', 'add_key', array('ItemsKeys', 'add'), 10);
$me->add_interface('items', 'save_key', array('ItemsKeys', 'save'), 10);
$me->add_interface('items', 'delete_key', array('ItemsKeys', 'delete'), 10);
$me->add_interface('items', 'get_key', array('ItemsKeys', 'get'));
$me->add_interface('items', 'get_keys', array('ItemsKeys', 'get_keys'));
$me->add_interface('items', 'get_sum_keys', array('ItemsKeys', 'get_sum_keys'));
$me->add_interface('items', 'take_key', array('ItemsKeys', 'take_key'), 1);

?>