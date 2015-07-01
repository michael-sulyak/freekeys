<?php
/*
Name: Locale
Version: 0.01
Author: expert_m
*/

putenv('LANG='.$me->config('lang', 'en_EN'));
setlocale(LC_ALL, $me->config('lang'));
bindtextdomain('lang', LOCALE_DIR);
bind_textdomain_codeset('lang', 'UTF8');
textdomain('lang');

function _t($text) {
	return __($text, 'tpl');
}

function __($text, $name = '') {
	if ($name != NULL) set_lang_name($name);
	return _($text);
}

function _n($msgid1, $msgid2, $n) {
	return ngettext($msgid1, $msgid2, $n);
}

function _s() {
	$args = func_get_args();
	$args[0] = _($args[0]);
	return call_user_func_array('sprintf', $args);
}

function set_lang_name($name, $type = 'module') {
	static $last_name = '';
	if ($name == $last_name) return;

	if ($name == 'tpl') {
		bindtextdomain('lang', THEME_DIR."/locale");
	} elseif ($type == 'module') {
		bindtextdomain('lang', PLUGINS_DIR."/$name/locale");
	} elseif ($type == 'include') {
		bindtextdomain('lang', INCLUDES_DIR."/$name/locale");
	}

	$last_name = $name;
}

function units($arg) {
    $number = $arg[0] % 100;
    if ($number >= 11 && $number <= 19) {
        $ending = __($arg[3]);
    } else {
        $i = $number % 10;
        switch ($i) {
            case (1): $ending = __($arg[1]); break;
            case (2):
            case (3):
            case (4): $ending = __($arg[2]); break;
            default: $ending = __($arg[3]);
        }
    }

    return $ending;
}

$me->add_interface('locale', 'units', 'units');

function locale_variables() {
	global $tpl;
	
	$tpl->add_function('_', '_t');
	$tpl->add_filter('_t', 't');

	$tpl->add_function('__');
	$tpl->add_function('_n');
	$tpl->add_function('_s');
	$tpl->add_function('set_lang_name');
}

$me->add_action('generation_theme', 'locale_variables');

?>