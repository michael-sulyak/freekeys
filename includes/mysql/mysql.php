<?php
/*
Name: MySQL
Version: 0.02
Author: expert_m
*/

function db_init() {
	global $db, $me;
	require_once 'MysqliDb.php';

	$db = new MysqliDb(
		$me->config('db_host'), 
		$me->config('db_user'), 
		$me->config('db_password'), 
		$me->config('db_name')
	);

	$db->setPrefix($me->config('db_prefix', ''));
	$db->setTrace(true);
	$me->create_action('db_end_init');
}

$me->add_action('init', 'db_init');

function db_query_count() {
	global $db;
	return count($db->trace);
}

function db_variable() {
	global $tpl;
	$tpl->add_function('query_count', 'db_query_count');	
}

$me->add_action('generation_theme', 'db_variable');
$me->load_config_json(INCLUDES_DIR.'/mysql/config.json');

?>