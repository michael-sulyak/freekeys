<?

class Message {

	public function add($arg) {
		global $db, $me;
		set_lang_name('auth', 'include');

		if (!is_numeric($arg['user_id'])) {			
			$me->add_notification(__('Unknown sender or recipient.'), 'danger');
			return;
		}

		$user = $me->config('user');

		$query = $db->insert('messages', array(
			'user_id' => $arg['user_id'],
			'sender_id' => isset($arg['sender_id']) ? $arg['sender_id'] : $user['id'],
			'subject' => $arg['subject'],
			'text' => htmlspecialchars($arg['text']),
			'date' => time(),
			'fresh' => true
		));

		if (!$query) {
			$me->add_notification(_s('The message "%s" is not sent.', $arg['subject']), 'danger');
		}

	}

	public function get_messages($arg) {
		global $db;
		
		$db->where('user_id', $user['user_id']);
		$db->orderBy('id', 'desc');
		$query = $db->get('messages', $arg['num']);

		foreach ($query as $key => $value) {
			$query[$key]['text'] = nl2br($value['text']);
		}

		return $query;		
	}
}

function message_init() {
	global $me;
	$me->add_interface('message', 'add', array('Message', 'add'), 1);
	$me->add_interface('message', 'get_messages', array('Message', 'get_messages'), 1);
}

$me->add_action('db_end_init', 'message_init');

?>