<?php
/*
Name: Theme
Version: 0.03
Author: expert_m
*/

define('THEMES_DIR', HOME_DIR.'/themes');
define('THEMES_URL', HOME_URL.'/themes'); 

define('CACHE_DIR', HOME_DIR.'/cache');
define('CACHE_URL', HOME_URL.'/cache');

define('THEME_DIR', THEMES_DIR.'/'.$me->config('theme_name', 'default'));
define('THEME_URL', THEMES_URL.'/'.$me->config('theme_name', 'default'));

require_once 'Twig/Autoloader.php';
Twig_Autoloader::register(true);

class Theme
{
	private $twig;
	private $values;
	private $list_values = array();
	private $menu = array();

	function __construct() {
		global $me;

		$loader = new Twig_Loader_Filesystem(THEME_DIR);
		$loader->addPath(THEME_DIR, 'tpl');
		$this->twig = new Twig_Environment($loader, array(
		    'cache' => CACHE_DIR,
		    'strict_variables' => true,
		    'autoescape' => false,    
		    'auto_reload' => true,
		));

		$this->add_variable('url_base', HOME_URL);
		$this->add_variable('url_theme', THEME_URL);
		$this->add_function('execution_time', array($me, 'execution_time'));
		$this->add_function('level_check', array($me, 'level_check'));
		$this->add_function('interface', array($me, 'run_interface'));
		
		// Standard functions PHP
		$this->add_function('in_array');
		$this->add_function('is_array');
		$this->add_function('array_key_exists');		
		$this->add_function('print_r');	

		$me->set_error_handler(array($this, 'error_handler'));
	}

	public function generate() {
		global $me;
		$me->create_action('generation_theme');

		usort($this->menu, create_function('$a, $b', 'return ($a["priority"] < $b["priority"]) ? -1 : ($a["priority"] == $b["priority"] ? 0 : 1);'));
		if ($_GET['do'] == '') $_GET['do'] = $this->menu[0]['name'];
		$me->create_action($_GET['do']);
		$this->add_variable('menu', $this->menu);

		if (file_exists(THEME_DIR.'/main.php')) {
			global $tpl;
			require_once THEME_DIR.'/main.php';
		}

		echo $this->full_render($me->config('theme_entry_point', 'main.twig'));
	}

	public function render($theme, $array = array()) {
		return $this->twig->render($theme, $array);
	}

	public function full_render($theme) {		
		return $this->render($theme, $this->get_values());
	}

	public function add_function($name, $func = '') {
		$this->twig->addFunction($name, new Twig_Function_Function($func ? $func : $name));
	}

	public function add_filter($func, $name = '') {
		$this->twig->addFilter($name, new Twig_Filter_Function($func ? $func : $name));
	}

	public function add_menu($text, $icon, $link, $priority = 5, $name = '', $parent = 'base') {
		array_push($this->menu, array('text' => $text, 'icon' => $icon, 'link' => $link, 'priority' => $priority, 'name' => $name, 'parent' => $parent));
	}	

	public function add_variable($tag, $value) {
		$this->list_values[$tag] = $value;
	}

	public function get_variable($name) {
		return $this->list_values[$name];
	}

	public function get_values() {
		global $me;

		$this->add_variable('get', $_GET);
		$this->add_variable('notifications', $me->get_notifications());

		$this->values = array_merge($this->list_values, $me->get_config());

		return $this->values;
	}

	public function error_handler($message, $title, $args) {
		exit($this->twig->render('error.twig', array('message' => $message, 'title' => $title, 'args' => $args)));		
	}

	public function redirect($url = '') {
		header('Location: '.HOME_URL.$url); 
	}
}

global $tpl;
$tpl = new Theme();

// Actions
$me->add_action('start', array($tpl, 'generate'), 8);

?>