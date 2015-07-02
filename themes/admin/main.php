<?php

$tpl->add_function('ref', $me->get_interface('items', 'ref'));	
$tpl->add_function('get_item', $me->get_interface('items', 'get'));
$tpl->add_function('get_items', $me->get_interface('items', 'get_items'));
$tpl->add_function('get_items_category', $me->get_interface('items', 'get_category'));
$tpl->add_function('get_items_categories', $me->get_interface('items', 'get_categories'));
$tpl->add_function('get_items_key', $me->get_interface('items', 'get_key'));
$tpl->add_function('get_items_keys', $me->get_interface('items', 'get_keys'));
$tpl->add_function('get_sum_keys', $me->get_interface('items', 'get_sum_keys'));

$tpl->add_function('link_vk', $me->get_interface('auth', 'link_vk'));
$tpl->add_function('link_fb', $me->get_interface('auth', 'link_facebook'));
$tpl->add_function('shortener', $me->get_interface('items', 'shortener'));

$user = $me->config('user');

if (!$me->config('auth', false) || !$me->level_check(5)) {	
	$me->set_config('theme_entry_point', 'login.twig');
} elseif ($_GET['do'] == 'items' && $_GET['action']) {
	switch ($_GET['action']) {
		case 'add_item':
		case 'edit_item':
		case 'list_items':			
			$me->set_config('theme_entry_point', 'items.twig');
			break;

		case 'add_category':
		case 'edit_category':
		case 'list_categories':			
			$me->set_config('theme_entry_point', 'categories.twig');
			break;

		case 'edit_key':
			$key = $me->run_interface('items', 'get_key', array('id' => $_GET['id']));
			if (!$key) {
				$me->set_config('theme_entry_point', '404.twig');
				break;
			}
			$tpl->add_variable('key', $key);	
		case 'add_key':
		case 'list_keys':
			$me->set_config('theme_entry_point', 'keys.twig');
			break;

		case 'edit_task':
			$task = $me->run_interface('items', 'get_task', $_GET['id']);
			if (!$task) {
				$me->set_config('theme_entry_point', '404.twig');
				break;
			}
			$tpl->add_variable('task', $task);	
			$me->set_config('theme_entry_point', 'tasks.twig');
			break;

		case 'list_tasks':
			$tasks = $me->run_interface('items', 'get_tasks', 50);
			if (!$tasks) {
				$me->set_config('theme_entry_point', '404.twig');
				break;
			}
			$tpl->add_variable('tasks', $tasks);	
			$me->set_config('theme_entry_point', 'tasks.twig');
			break;

		case 'list_group_tasks':
			$tasks = $me->run_interface('items', 'get_groups_tasks', 50);
			if (!$tasks) {
				$me->set_config('theme_entry_point', '404.twig');
				break;
			}
			$tpl->add_variable('tasks', $tasks);	
			$me->set_config('theme_entry_point', 'tasks.twig');
			break;

		case 'edit_group_tasks':
			$group_tasks = $me->run_interface('items', 'get_group_tasks', array('id' => $_GET['id']));
			if (!$group_tasks) {
				$me->set_config('theme_entry_point', '404.twig');
				break;
			}
			$tpl->add_variable('group_tasks', $group_tasks);	
			$me->set_config('theme_entry_point', 'tasks.twig');
			break;

		case 'add_task':
		case 'add_group_tasks':	
			$me->set_config('theme_entry_point', 'tasks.twig');
			break;

		case 'config':	
			$me->set_config('theme_entry_point', 'config.twig');
			break;
		
		default:
			$me->set_config('theme_entry_point', '404.twig');
			break;
	}
} else {
	$tpl->add_variable('config', $me->get_config());	
	$me->set_config('theme_entry_point', 'config.twig');
}

?>