<?php
/*
Name: ModularEngine
URI: http://nextable.ru/
Version: 0.2
Author: expert_m
Author URI: http://nextable.ru/
*/

// Constants
define('ME', true);
define('ME_VERSION', '0.2');

define('HOME_DIR', str_replace('\\', '/', dirname(__FILE__)));
define('PLUGINS_DIR', HOME_DIR.'/plugins');
define('INCLUDES_DIR', HOME_DIR.'/includes');


//define('HOME_URL', 'http://'.$_SERVER['SERVER_NAME'].((dirname($_SERVER['PHP_SELF']) == '/') ? '' : dirname($_SERVER['PHP_SELF'])));
//define('MODULES_URL', HOME_URL.'/modules');

header('Content-Type: text/html; charset=utf-8');
session_start();

class ModularEngine {	
	private $includes = array();
	private $plugins = array();
	private $actions = array();
	private $config = array();
	private $config_const = array();
	private $error_handler;
	private $interface = array();
	private $notifications = array();
	private $config_json = 'config.json';

	function __construct() {
		$this->set_config('start_time', microtime(true));
		$this->set_config('project_name', 'FreeKeys CMS', true);
		$this->set_config('project_version', '1.5.003', true);
		$this->set_config('project_author', 'expert_m', true);

		$this->includes = array("mysql", "locale", "auth", "theme", "helper", "messages");

		$this->add_interface('me', 'update_config', array($this, 'update_config'), 10);
	}

	public function start() {
		global $me;		
		foreach ($this->includes as $name) {
			require_once INCLUDES_DIR."/$name/$name.php";
		}

		foreach ($this->plugins as $name) {
			require_once PLUGINS_DIR."/$name/$name.php";
		}

		$this->add_action('init', array($me, 'process_interface'), 10);
		$this->create_action('init');
		$this->create_action('start');
		// 8 - generation_theme
		// 5 - 
		// 2 - interface
	}

	// Plugins //

	public function add_plugin($name_plugin) {
		array_push($this->plugins, $name_plugin);
	}

	public function set_list_plugins($list) {
		$this->plugins = $list;
	}

	public function get_list_modules() {
		return $this->plugins;
	}

	// Action //

	public function add_action($tag, $function, $priority = 5) { // Max. priority = 10
		if ($priority < 1) $priority = 1;
		if ($priority > 10) $priority = 10;
		if (!isset($this->actions[$tag])) $this->actions[$tag] = array();
		array_push($this->actions[$tag], array('priority' => $priority, 'function' => $function));
	}

	public function get_actions() {
		return $this->actions;
	}

	public function create_action($tag) {
		if (!isset($this->actions[$tag])) return;

		if (count($this->actions[$tag]) >= 2)
			usort($this->actions[$tag], create_function('$a, $b', 'return ($a["priority"] < $b["priority"]) ? -1 : ($a["priority"] == $b["priority"] ? 0 : 1);'));
		
		foreach ($this->actions[$tag] as $item) {
			call_user_func($item['function']);
		}
	}

	// Config //

	public function set_config($name, $value, $const = false) {
		if ($this->config_const[$name] == false) {
			$this->config[$name] = $value;
			if ($const) $config_const[$name] = true;
		}
	}

	public function get_config() {
		return $this->config;		
	}

	public function config($name, $default_value = NULL) {
		if ($this->config[$name] == NULL) {
			return $default_value;
		} else {
			return $this->config[$name];
		}
	}

	public function load_config_json($file = 'config.json') {
		if ($json = file_get_contents($file)) {
			$this->config = array_merge($this->config, json_decode($json, true));

			if ($this->config['list_plugins']) {
				$this->set_list_plugins($this->config['list_plugins']);
			}

			if ($this->config['timezone']) {
				date_default_timezone_set($this->config['timezone']);
			}

			if ($this->config['home_url']) {
				define('HOME_URL', $this->config['home_url']);
				define('PLUGINS_URL', HOME_URL.'/plugins');
				define('INCLUDES_URL', HOME_URL.'/includes');
			}
		}
	}

	public function update_config($arg) {
		$json = json_decode(file_get_contents($this->config_json), true);

		foreach ($json as $key => $value) {
			if (isset($arg[$key])) {
				$json[$key] = $arg[$key];
				$this->config[$key] = $arg[$key];
			}
		}

		file_put_contents($this->config_json, json_encode($json));
	}

	// Interface //

	public function add_interface($category, $name, $func, $level = 0) {
		$this->interface[$category][$name] = array('function' => $func, 'level' => $level);
	}

	public function get_interface($category, $name, $check = true) {
		if ($this->level_check($this->interface[$category][$name]['level'])) {
			return $this->interface[$category][$name]['function'];
		} elseif ($check) {
			$this->fatal_error(
				'Interface was not found.',
				'Fatal error',
				array(
					'category'	=> $category,
					'name'		=> $name,
					'user_level'=> $this->config('level')
				)
			);
		}
	}

	public function process_interface() {
		if (!isset($_POST['interface'])) return;
		foreach($_POST['interface'] as $category => $value) {
			foreach($value as $name => $list) {
				foreach($list as $arg) {
					if (!isset($arg['run'])) continue;
					$this->run_interface($category, $name, $arg);
				}
			}
		}
	}

	public function run_interface($category, $name, $arg = '') {
		if (!isset($this->interface[$category][$name])) return;
		$this->level_check($this->interface[$category][$name]['level'], true);
		$func = $this->interface[$category][$name]['function'];

		if (is_array($func)) {
			if (!method_exists($func[0], $func[1])) {
				return;
			}
		} elseif (!function_exists($func)) {
			return;
		}
		
		return call_user_func($func, $arg);
	}

	// Notification //

	public function add_notification($text, $type = 'info') {
		array_push($this->notifications, array('text' => $text, 'type' => $type));
	}

	public function get_notifications() {
		return $this->notifications;
	}

	// Error //

	public function fatal_error($message = '', $title = '', $args = array()) {
		if ($this->error_handler) {
			call_user_func_array($this->error_handler, array($message, $title, $args));
			return;
		}

		exit(
			"Title: $title<br>\n".
			"Message: $message<br>\n".
			"Args: ".implode(', ', $args)
		);
	}

	public function set_error_handler($func) {
		$this->error_handler = $func;
	}

	// Other //

	public function level_check($level, $exit = false) {
		if ($this->config('level') >= $level) {
			return true;
		} else {
			if ($exit) {
				$this->fatal_error(
					'Insufficient privileges to perform the action.',
					'Permission denied', // 'Access error',
					array(
						'your_level' => $this->config('level', 0),					
						'required_level' => $level,
					)
				);
			}
			
			return false;
		}
	}

	public function notify_admin() {
		
	}

	public function execution_time($n = 2) {
		return round((microtime(true)-$this->config('start_time')), $n);
	}
}

?>