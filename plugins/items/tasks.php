<?php

class ItemsTasks {
	public function add($arg) {
		global $db, $me;
		set_lang_name('items');

		$query = $db->insert('items_tasks', array(
			'full_name' => $arg['full_name'],
			'instruction' => $arg['instruction'],
			'type' => $arg['type'],
			'parametrs' => $arg['parametrs']
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

		$query = $db->update('items_tasks', array(
			'full_name' => $arg['full_name'],
			'instruction' => $arg['instruction'],
			'type' => $arg['type'],
			'parametrs' => $arg['parametrs']
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
		$query = $db->delete('items_tasks');

		if ($query) {			
			$me->add_notification(__('Successfully removed.'), 'success');
		} else {
			$me->add_notification(__('Error removing.'), 'danger');
		}
	}

	public function get($id) {
		global $db;

		if (!is_numeric($id)) return;

		$db->where('id', $id);
		$query = SmartDB::getOne('items_tasks');

		return $query;
	}

	public function get_tasks($num = 10) { 	
		global $db;
		return SmartDB::get('items_tasks', $num);
	}

	//
	// GROUP TASKS
	//

	public function add_group_tasks($arg) {
		global $db, $me;
		set_lang_name('items');

		$query = $db->insert('items_group_tasks', array(
			'full_name' => $arg['full_name'],
			'description' => $arg['description'],
			'list' => json_encode($arg['list'])
		));

		if ($query) {			
			$me->add_notification(__('Successfully added.'), 'success');
		} else {
			$me->add_notification(__('Not added.'), 'danger');
		}
	}

	public function save_group_tasks($arg) {
		global $db, $me;
		set_lang_name('items');

		$db->where('id', $arg['id']);

		$query = $db->update('items_group_tasks', array(
			'full_name' => $arg['full_name'],
			'description' => $arg['description'],
			'list' => $arg['list']
		));

		if ($query) {
			$me->add_notification(__('Successfully saved.'), 'success');
		} else {
			$me->add_notification(__('Error saving.'), 'danger');
		}
	}

	public function delete_group_tasks($arg) {
		global $db, $me;
		set_lang_name('items');

		$db->where('id', $arg['id']);
		$query = $db->delete('items_group_tasks');

		if ($query) {			
			$me->add_notification(__('Successfully removed.'), 'success');
		} else {
			$me->add_notification(__('Error removing.'), 'danger');
		}
	}

	public function get_group_tasks($arg) {
		global $db;

		if (!is_numeric($arg['id'])) return;

		$db->where('id', $arg['id']);
		$query = SmartDB::getOne('items_group_tasks');

		if ($arg['decode_list']) {
			$query['list'] = json_encode($query['list']);
		}

		return $query;
	}

	public function get_groups_tasks($num) {
		global $db;
		return $db->get('items_group_tasks', $num);		
	}

	public function run_task($type, $parametrs) {
		global $me;
		set_lang_name('items');
		$user = $me->config('user');

		switch ($type) {
			case "vk_likes": // http://vk.com/dev/likes.getList
				if (!$user['vk_id']) return false;

				for ($i = 0; true; $i += 1000) {
					$params = array(
						'type'		=> $parametrs['type'],
						'owner_id'	=> $parametrs['owner_id'],
						'item_id'	=> $parametrs['item_id'],
						'filter'	=> $parametrs['filter'],
						'item_id'	=> $parametrs['item_id'],
						'offset'	=> $i,
						'count'		=> 1000,
						'v'			=> 5.34
					);

					$list = File::get_contents('http://api.vk.com/method/likes.getList?'.http_build_query($params));					
					$list = json_decode($list, true);

					if (count($list['response']['items']) == 0) {
						$me->add_notification(_s('ID%n did not "Like" or did not repost.', $user['vk_id']), 'warning');
						return false;
					}

					if (array_search($user['vk_id'], $list['response']['items']) !== false) {
						return true;
					} else {						
						$me->add_notification(_s('ID%n did not "Like" or did not repost.', $user['vk_id']), 'warning');
						return false;
					}		
				}

				break;

			case "vk_groups_is": // http://vk.com/dev/groups.isMember
				if (!$user['vk_id']) return false;

				$params = array(
					'group_id'	=> $parametrs['group_id'],
					'user_id'	=> $user['vk_id'],
				);

				$result = File::get_contents('http://api.vk.com/method/groups.isMember?'.http_build_query($params));
				$result = json_decode($result, true);

				if ($result['response'] == 1) {
					return true;
				} else {						
					$me->add_notification(__('User is not found in the group.'), 'warning');
					return false;
				}

				break;
			
			default:
				return false;
		}
	}

	public function verify_tasks($id) {
		$group_tasks = ItemsTasks::get_group_tasks(array('id' => $id));

		$group_tasks['list'] = json_decode($group_tasks['list'], true);

		if (!$group_tasks['list']) return;

		foreach ($group_tasks['list'] as $task_id) {
			$task = ItemsTasks::get($task_id);
			$result = ItemsTasks::run_task($task['type'], json_decode($task['parametrs'], true));
			if (!$result) return false;
		}

		return true;
	}
}

$me->add_interface('items', 'add_task', array('ItemsTasks', 'add'), 10);
$me->add_interface('items', 'save_task', array('ItemsTasks', 'save'), 10);
$me->add_interface('items', 'delete_task', array('ItemsTasks', 'delete'), 10);
$me->add_interface('items', 'get_task', array('ItemsTasks', 'get'));
$me->add_interface('items', 'get_tasks', array('ItemsTasks', 'get_tasks'));

$me->add_interface('items', 'add_group_tasks', array('ItemsTasks', 'add_group_tasks'), 10);
$me->add_interface('items', 'save_group_tasks', array('ItemsTasks', 'save_group_tasks'), 10);
$me->add_interface('items', 'delete_group_tasks', array('ItemsTasks', 'delete_group_tasks'), 10);
$me->add_interface('items', 'get_group_tasks', array('ItemsTasks', 'get_group_tasks'));
$me->add_interface('items', 'get_groups_tasks', array('ItemsTasks', 'get_groups_tasks'));

?>