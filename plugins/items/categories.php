<?php

class ItemsCategories {	
	public function add($arg) {
		global $db, $me;
		set_lang_name('items');
		
		$query = $db->insert('items_categories', array(
			'full_name' => $arg['full_name'],			
			'name' => $arg['name'],
			'date' => time(),
			'description' => $arg['description']
		));

		if ($query) {			
			$me->add_notification(__('Successfully added.'), 'success');
		} else {
			$me->add_notification(__('Not added.'), 'danger');
		}
	}

	public function save($arg) {
		global $db, $tpl;
		set_lang_name('items');

		$db->where('id', $arg['id']);

		$query = $db->update('items_categories', array(
			'full_name' => $arg['full_name'],			
			'name' => $arg['name'],
			'description' => $arg['description']
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
		$query = $db->delete('items_categories');

		if ($query) {			
			$me->add_notification(__('Successfully removed.'), 'success');
		} else {
			$me->add_notification(__('Error removing.'), 'danger');
		}
	}

	public function get($id) { 	
		global $db;

		if ($id == NULL) return;

		if (is_numeric($id)) {
			$db->where('id', $id);
		} else {
			$db->where('name', $id);
		}
		
		$query = $db->getOne('items_categories');
		if (!$query) return;
		if (!$query['name']) $query['name'] = '';
		if (!$query['full_name']) $query['full_name'] = '';
		if (!$query['description']) $query['description'] = '';

		return $query;
	}

	public function get_categories() { 	
		global $db;
		$db->orderBy('full_name', 'asc');
		return $db->get('items_categories');
	}
}

$me->add_interface('items', 'add_category', array('ItemsCategories', 'add'), 10);
$me->add_interface('items', 'save_category', array('ItemsCategories', 'save'), 10);
$me->add_interface('items', 'delete_category', array('ItemsCategories', 'delete'), 10);
$me->add_interface('items', 'get_category', array('ItemsCategories', 'get'));
$me->add_interface('items', 'get_categories', array('ItemsCategories', 'get_categories'));

?>