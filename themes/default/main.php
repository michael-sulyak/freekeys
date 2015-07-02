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

switch ($_GET['do']) {
	case '':
		$me->set_config('theme_entry_point', 'index.twig');
		break;

	case 'items':

		switch ($_GET['action']) {
			case '':
				$tpl->add_variable('item_sort', array(
					'column_name' => $_POST['column_name'] ? $_POST['column_name'] : 'views',
					'order_by' => $_POST['order_by'] ? $_POST['order_by'] : 'desc'
				));

				$me->set_config('theme_entry_point', 'items.twig');
				break;

			case 'view':
				if (isset($_POST['key'])) {					
					$result = $me->run_interface('items', 'take_key', array('name' => $_GET['name']));

					if (!$result) {
						$me->set_config('theme_entry_point', '404.twig');
						break;
					}

					$subject = 'Вы получили "'.$result['item']['full_name'].'"!';

					$text =
						"Ключ: {$result['key']['key']}\n\n".
						"Дополнительная информация: {$result['key']['help']}\n\n".
						"Инструкция: {$result['key']['instruction']}"
					;

					$me->run_interface('message', 'add', array(
						'user_id' => $user['id'],
						'sender_id' => 0,
						'subject' => $subject,
						'text' => $text
					));

					Mail::send(array('subject' => $subject, 'message' => $text));

					$tpl->add_variable('item', $result['item']);
					$tpl->add_variable('key', $result['key']);
					$me->set_config('theme_entry_point', 'key.twig');
					break;
				}

				$item = $me->run_interface('items', 'get', array('name' => $_GET['name'], 'view' => true));
				if (!$item['full_name']) $tpl->redirect('/404/');
				$tpl->add_variable('item', $item);
				$me->set_config('theme_entry_point', 'item.twig');
				break;

			case 'tasks':
				$item = $me->run_interface('items', 'get', array('name' => $_GET['name']));
				if (!$item || !$item['group_tasks_id']) $tpl->redirect('/404/');

				$group_tasks = $me->run_interface('items', 'get_group_tasks', array('id' => $item['group_tasks_id']));
				if (!$group_tasks) $tpl->redirect('/404/');
				$group_tasks['list'] = json_decode($group_tasks['list']);
				
				for ($i = 0; $i < count($group_tasks['list']); $i++) { 
					$group_tasks['list'][$i] = $me->run_interface('items', 'get_task', $group_tasks['list'][$i]);					
				}

				$tpl->add_variable('item', $item);
				$tpl->add_variable('group_tasks', $group_tasks);
				$me->set_config('theme_entry_point', 'tasks.twig');
				break;

			case 'ref':
				$me->run_interface('items', 'ref', $_GET['id']);
				$me->set_config('theme_entry_point', 'ref.twig');
				break;
			
			default:
				$me->set_config('theme_entry_point', '404.twig');
				break;
		}

		break;

	case 'register':
		if ($me->config('auth')) {
			$tpl->redirect('/profile/');
		} else {
			$me->set_config('theme_entry_point', 'register.twig');
		}
		break;

	case 'profile':
		if (!$me->config('auth')) {
			$tpl->redirect('/register/');
		}

		switch ($_GET['action']) {
			case '':
			case 'view':
				$me->set_config('theme_entry_point', 'profile.twig');
				break;

			case 'edit':
				$me->set_config('theme_entry_point', 'edit_profile.twig');
				break;
			
			default:
				$tpl->redirect('/404/');
				break;
		}

		break;

	case 'exit':
		$me->run_interface('auth', 'logout');		
		$tpl->redirect();
		break;

	case 'login':
		$me->set_config('theme_entry_point', 'index.twig');
		break;

	case '404':
		$me->set_config('theme_entry_point', '404.twig');
		break;

	case 'messages':
		if (!$me->config('auth')) {			
			$me->set_config('theme_entry_point', '404.twig');
			break;
		}

		$messages = $me->run_interface('message', 'get_messages', array('user_id' => $user['id'], 'num' => 5));

		$tpl->add_variable('messages', $messages);
		$me->set_config('theme_entry_point', 'messages.twig');
		break;

	default:
		$tpl->redirect('/404/');
		break;
}

if ($_GET['do'] == '') {
	$tpl->add_variable('index', true);
} else {
	$tpl->add_variable('index', false);
}

?>